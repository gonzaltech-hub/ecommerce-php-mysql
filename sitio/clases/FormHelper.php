<?php

class FormHelper
{

    // RECUPERAR DATOS
    // Este método lo llamo al principio de mis formularios (como en el Login o crear producto).
    // Revisa si hay errores guardados en la sesión de un intento anterior.
    public static function recuperarStickyForm(): array
    {
        // Busco si hay errores o datos viejos en la sesión.
        // Si no hay nada, uso un array vacío [] para que no falle.
        $errores = $_SESSION['errores'] ?? [];
        $dataVieja = $_SESSION['data-vieja'] ?? [];

        // Una vez que ya recuperé los datos para mostrarlos, los borro de la sesión.
        // ¿Por qué? Porque si el usuario recarga la página (F5), no quiero que los errores sigan apareciendo eternamente.
        // Son mensajes de "un solo uso".
        unset($_SESSION['errores'], $_SESSION['data-vieja']);

        // Devuelvo el paquete listo para usar en el HTML.
        return [
            'errores' => $errores,
            'dataVieja' => $dataVieja
        ];
    }

    // GUARDAR DATOS (Se usa en la ACCIÓN/CONTROLADOR)
    // Este método lo llamo cuando la validación falla (ej: contraseña muy corta).
    // Guardo los errores y lo que el usuario escribió en la sesión antes de redirigirlo.
    public static function guardarStickyForm(array $errores, array $data): void
    {
        $_SESSION['errores'] = $errores;
        $_SESSION['data-vieja'] = $data;
    }
}
