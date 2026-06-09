<?php
// 1. INICIO DE SESIÓN GLOBAL
// Arranco la sesión acá. Como este archivo es lo primero que se carga (en el index), me aseguro de que $_SESSION esté disponible en todo el sitio.
session_start();

function autoloadClasses($nombreClase)
{
    // 2. RUTA ABSOLUTA
    // __DIR__ me dice dónde estoy ahora (carpeta 'funciones').
    // Con "/../clases/" para que vuuelva una carpeta atrás y entre en 'clases'.
    // Y le pego el nombre de la clase + .php (Ej: Producto.php).
    $archivoClase = __DIR__ . "/../clases/$nombreClase.php";

    // 3. CARGA SEGURA
    // Verifico que el archivo realmente exista antes de intentar cargarlo.
    // Si existe, uso require_once para importarlo.
    if (file_exists($archivoClase)) {
        require_once $archivoClase;
    } else {
        // Si no lo encuentra, corto todo y aviso el error.
        die("No se pudo cargar la clase: $nombreClase");
    }
}

// 4. REGISTRO EN SPL
spl_autoload_register('autoloadClasses');
