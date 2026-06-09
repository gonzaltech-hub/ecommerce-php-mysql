<?php
require_once "../funciones/autoload.php";

// 1. CAPTURA DEL POST
// Recibo todo lo que me manda el formulario del carrito.
// Acá llega un array donde la clave es el ID del producto y el valor es la nueva cantidad que puso el usuario
$postData = $_POST;

// 2. VERIFICACIÓN DE DATOS
// Antes de hacer nada, me aseguro de que realmente me hayan mandado cantidades.
// Si el array 'cantidad' está vacío, no tiene sentido intentar actualizar nada
if (!empty($postData['cantidad'])) {

    // 3. DELEGACIÓN
    // Le paso el array de cantidades directamente a mi clase Carrito.
    Carrito::actualizar_cantidades($postData['cantidad']);

    // 4. FEEDBACK
    Alerta::add_alerta('success', "¡Cantidades actualizadas exitosamente!");
}

// 5. REDIRECCIÓN
header('Location: ../index.php?seccion=carrito');
