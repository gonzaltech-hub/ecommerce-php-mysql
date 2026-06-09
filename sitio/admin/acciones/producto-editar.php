<?php
require_once "../../funciones/autoload.php";

// 1. SEGURIDAD
// Verifico que sea Admin. Si no, lo saco.
Autenticacion::verify();

// 2. CAPTURA DEL ID
// Necesito saber CUÁL producto estoy modificando.
$id = $_GET['id'];

// Captura de datos del formulario (POST)
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$descripcion = $_POST['descripcion'];
$estado_publicacion = $_POST['estado_publicacion_fk'];
$categoria_fk = $_POST['categoria_fk'];

$imagen = $_FILES['img'];

// Etiquetas (Pivot)
// Si el admin desmarca TODOS los checkboxes, el navegador NO envía la variable $_POST['etiquetas'].
// Si no uso el '?? []', PHP tiraría error.
// Al asignarle un array vacío, mi Modelo sabrá que tiene que borrar todas las relaciones viejas en la DB.
$etiquetas = $_POST['etiquetas'] ?? [];

// 3. VALIDACIÓN
$datosValidar = [
    'nombre' => $nombre,
    'precio' => $precio,
    'stock' => $stock,
    'descripcion' => $descripcion,
    'categoria_fk' => $categoria_fk
];

$errores = Producto::validarDatos($datosValidar);

// NOTA: En editar NO valido imagen obligatoria, porque es opcional cambiarla.
// Pero si subieron una, valido que sea formato correcto.
if (!empty($imagen['tmp_name']) && !in_array($imagen['type'], ['image/jpeg', 'image/png'])) {
    $errores['imagen'] = "Error: El archivo subido no es una imagen válida (solo JPG o PNG)";
}

// REDIRECCIÓN POR ERRORES
if (count($errores) > 0) {
    Alerta::add_alerta('warning', "Datos incorrectos. Por favor revísalos.");

    $_SESSION['errores'] = $errores;
    $_SESSION['data-vieja'] = $_POST;

    header('Location: ../index.php?seccion=producto-editar&id=' . $id);
    exit;
}

try {
    // RECUPERAR DATOS ACTUALES
    // Antes de guardar, necesito traer el producto original de la base de datos.
    // Principalmente para saber cómo se llamaba la imagen vieja
    $producto = Producto::porId($id);

    // Validación extra por si el ID no existe
    if (!$producto) {
        Alerta::add_alerta('error', "El producto que intentas editar no existe.");
        header('Location: ../index.php?seccion=productos');
        exit;
    }

    // LÓGICA DE IMAGEN
    $nombreImagen = null;

    if (!empty($imagen['tmp_name'])) {
        // Si el admin subió una imagen nueva.

        // Borro la imagen vieja del disco para no ocupar espacio.
        if (!empty($producto->getImg())) {
            Imagen::borrarImagen("../../img/productos/" . $producto->getImg());
        }

        // Subo la nueva imagen y guardo el nombre nuevo.
        $nombreImagen = Imagen::subirImagen("../../img/productos", $imagen);
    } else {
        // Si el admin NO tocó la imagen.
        // Mantengo el nombre de la imagen que ya tenía el producto.
        $nombreImagen = $producto->getImg();
    }

    // ACTUALIZACIÓN EN DB
    // Llamo al método editar del modelo
    (new Producto)->editar($id, [
        'nombre' => $nombre,
        'precio' => $precio,
        'descripcion' => $descripcion,
        'img' => $nombreImagen,
        'estado_publicacion_fk' => $estado_publicacion,
        'categoria_fk' => $categoria_fk,
        'stock' => $stock,
        'etiquetas' => $etiquetas
    ]);

    Alerta::add_alerta('success', "Producto editado con éxito.");

    header('Location: ../index.php?seccion=productos');
    exit;
} catch (Exception $e) {
    Alerta::add_alerta('error', "Error al editar el producto: ");

    $_SESSION['data-vieja'] = $_POST;
    // Si falla, vuelvo al formulario sin olvidar el ID
    header('Location: ../index.php?seccion=producto-editar&id=' . $id);
}
