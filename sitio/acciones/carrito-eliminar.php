<?php
require_once "../funciones/autoload.php";

// 1. CAPTURA DEL ID
// Recupero el ID del producto que viene en la URL.
// Si no viene ningún ID, asumo que es FALSE.
$id = $_GET['id'] ?? FALSE;

if ($id) {
    // 2. DELEGACIÓN
    // Uso (int) por seguridad, para asegurarme de que sea un número.
    Carrito::eliminar((int)$id);

    // 3. FEEDBACK
    // Genero una alerta verde para confirmar que se borró correctamente.
    Alerta::add_alerta('success', "Producto eliminado del carrito.");
}

// 4. REDIRECCIÓN
header('Location: ../index.php?seccion=carrito');
