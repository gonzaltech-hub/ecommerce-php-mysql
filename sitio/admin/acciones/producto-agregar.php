<?php
require_once "../../funciones/autoload.php";

// 1. SEGURIDAD
// Verifico que sea Administrador. Si un usuario normal intenta mandar un POST a este archivo, lo mando al login.
Autenticacion::verify();

// 2. CAPTURA Y LIMPIEZA DE DATOS
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$descripcion = $_POST['descripcion'];
$estado_publicacion = $_POST['estado_publicacion_fk'];

// Capturo la categoría que viene del Select
$categoria_fk = $_POST['categoria_fk'];

$imagen = $_FILES['img'];

// Captura de Checkboxes (Etiquetas)
// Uso '??' para asegurar que siempre tenga al menos un array vacío.
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

// tmp_name es la ruta temporal donde PHP guarda el archivo al recibirlo. Si está vacío, no se subió nada.
if (empty($imagen['tmp_name'])) {
    $errores['imagen'] = "Error: Debes subir una imagen";
} else if (!in_array($imagen['type'], ['image/jpeg', 'image/png'])) {
    $errores['imagen'] = "Error: El archivo subido no es una imagen válida (solo JPG o PNG)";
}

// 4. MANEJO DE ERRORES
if (count($errores) > 0) {
    Alerta::add_alerta('warning', "Hay errores en el formulario. Por favor revísalos.");

    // Guardo los errores en sesión para mostrarlos en la vista.
    $_SESSION['errores'] = $errores;
    // [UX] Guardo los datos que el admin escribió para que no tenga que tipear todo de nuevo.
    $_SESSION['data-vieja'] = $_POST;

    header('Location: ../index.php?seccion=producto-nuevo');
    exit;
}

try {
    $nombreImagen = "";

    // 5. SUBIDA DE ARCHIVO
    // Uso mi clase estática Imagen para mover el archivo y renombrarlo
    if (!empty($imagen['tmp_name'])) {
        $nombreImagen = Imagen::subirImagen("../../img/productos", $imagen);
    }

    // 6. GUARDADO EN BASE DE DATOS
    // Instancio el modelo Producto y llamo al método crear.
    // Le paso todo el array de datos, INCLUYENDO las etiquetas para que gestione la tabla pivot.
    (new Producto)->crear([
        'nombre' => $nombre,
        'precio' => $precio,
        'descripcion' => $descripcion,
        'img' => $nombreImagen,
        'estado_publicacion_fk' => $estado_publicacion,
        'categoria_fk' => $categoria_fk,
        'stock' => $stock,
        'etiquetas' => $etiquetas
    ]);

    Alerta::add_alerta('success', "Producto creado con éxito.");

    header('Location: ../index.php?seccion=productos');
    exit;
} catch (Exception $e) {
    // Si falla algo (ej: base de datos caída), capturo el error para que no explote la pantalla
    Alerta::add_alerta('error', "Error al crear el producto: ");

    $_SESSION['data-vieja'] = $_POST;
    header('Location: ../index.php?seccion=producto-nuevo');
}
