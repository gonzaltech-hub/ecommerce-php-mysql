<?php
require_once "../funciones/autoload.php";

// 1. LÓGICA
// Llamo directamente al método estático 'vaciar' de la clase Carrito.
// No necesito pasarle ningún parámetro porque la orden es simple: borrar toda la sesión del carrito.
Carrito::vaciar();

// 2. FEEDBACK
Alerta::add_alerta('success', 'Se han eliminado todos los productos del carrito.');

// 3. REDIRECCIÓN
header('Location: ../index.php?seccion=carrito');
