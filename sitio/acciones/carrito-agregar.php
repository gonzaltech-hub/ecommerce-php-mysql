<?php
require_once "../funciones/autoload.php";
// 1. CAPTURA DE DATOS
// Acá recupero la información que viene por la URL.
// Necesito saber qué producto es ($id) y cuántos quiere ($cantidad).
// Si no manda cantidad, asumo que quiere 1 unidad por defecto.
$id = $_GET['id'] ?? FALSE;
$cantidad = $_GET['cantidad'] ?? 1;

if ($id) {
    // 2. DELEGACIÓN
    // Si tengo un ID válido, le paso la responsabilidad a mi clase 'Carrito'.
    // Uso (int) para asegurarme de pasarle números limpios
    Carrito::agregar((int)$id, (int)$cantidad);

    // 3. FEEDBACK
    Alerta::add_alerta('success', "¡Producto agregado al carrito exitosamente!");
}

// 4. REDIRECCIÓN
header('Location: ../index.php?seccion=carrito');
