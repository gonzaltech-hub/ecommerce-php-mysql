<?php
require_once "../../funciones/autoload.php";

// Verifico que quien ejecuta la acción sea el "Dueño" (SuperAdmin).
Autenticacion::verify();

$id = $_GET['id'] ?? null;

try {

    $usuario = Usuario::porId($id);

    if ($usuario) {

        // Verifico que el usuario a eliminar NO sea un SuperAdmin.
        // Esto evita que por error borre mi propia cuenta o la del dueño
        if ($usuario->getRol() == 1) {
            Alerta::add_alerta('error', "Operación rechazada: No se puede eliminar al SuperAdministrador.");
            header('Location: ../index.php?seccion=usuarios');
            exit;
        }

        // Si pasó los filtros, procedo al DELETE físico.
        (new Usuario)->eliminar($id);

        Alerta::add_alerta('success', "Usuario eliminado correctamente.");
    } else {
        Alerta::add_alerta('warning', "El usuario que intentas eliminar no existe.");
    }
} catch (Exception $e) {
    // Si el usuario compró algo en el pasado, su ID está en la tabla 'compra'.
    // MySQL bloquea el borrado.
    Alerta::add_alerta('error', "No se puede eliminar el usuario porque tiene historial de compras.");
}

header('Location: ../index.php?seccion=usuarios');
exit;
