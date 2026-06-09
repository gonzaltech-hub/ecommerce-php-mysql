<?php
require_once "../../funciones/autoload.php";

// SEGURIDAD
Autenticacion::verify();

// Verifico que realmente vengan del formulario (POST) y no escribiendo la URL (GET).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?seccion=productos');
    exit;
}

// CAPTURA DEL ID
$id = $_GET['id'] ?? null;

try {
    // Antes de eliminar el registro de la base de datos, necesito traer el objeto.
    // Porque necesito saber cómo se llama la foto asociada para poder borrarla del disco.
    // Si borro primero el registro, pierdo el nombre de la imagen para siempre.
    $producto = Producto::porId($id);

    if ($producto) {

        // ELIMINACIÓN EN BASE DE DATOS
        // Primero elimino el registro SQL.
        // Si esto falla (ej: el producto está comprado), saltará al CATCH y no borrará la imagen.
        (new Producto)->eliminar($id);

        // LIMPIEZA DE ARCHIVOS
        // Ahora que ya no está en la base, borro la imagen física del servidor para no dejar basura.
        if (!empty($producto->getImg())) {
            Imagen::borrarImagen("../../img/productos/" . $producto->getImg());
        }

        // Feedback
        Alerta::add_alerta('success', "Producto eliminado correctamente.");
    } else {
        Alerta::add_alerta('warning', "El producto que intentas eliminar no existe.");
    }
} catch (Exception $e) {
    Alerta::add_alerta('error', "No se puede eliminar el producto porque está asociado a una compra histórica.");
}

header('Location: ../index.php?seccion=productos');
exit;
