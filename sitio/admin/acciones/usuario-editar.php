<?php
require_once "../../funciones/autoload.php";

// Solo el SuperAdmin (Rol 1) puede editar a otros usuarios.
Autenticacion::verify();

// CAPTURA DE DATOS
$id = $_GET['id'];
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'] ?? null;
$rol_fk = $_POST['rol_fk'];

// VALIDACIÓN
// Prepara el array para la validación
$datosValidar = [
    'username' => $username,
    'email' => $email,
    'rol_fk' => $rol_fk
];

// Valido datos básicos (sin password, porque es opcional en edición)
$errores = Usuario::validarDatos($datosValidar, true);

// REDIRECCIÓN
if (count($errores) > 0) {
    Alerta::add_alerta('warning', "Datos incorrectos. Por favor revisalos.");

    $_SESSION['errores'] = $errores;
    $_SESSION['data-vieja'] = $_POST;

    // Devuelvo al formulario con el ID para que no pierda la referencia
    header('Location: ../index.php?seccion=usuario-editar&id=' . $id);
    exit;
}

try {
    $usuarioModel = new Usuario();

    // ACTUALIZACIÓN DE DATOS BÁSICOS
    $usuarioModel->editar($id, [
        'username' => $username,
        'email' => $email,
        'rol_fk' => $rol_fk
    ]);

    // LÓGICA CONDICIONAL DE PASSWORD
    // Si el campo NO está vacío, significa que el Admin quiere cambiar la clave.
    if (!empty($password)) {
        // Hasheamos la clave nueva
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Llamamos a un método específico para actualizar solo la clave
        $usuarioModel->editarPassword($id, $passwordHash);
    }

    Alerta::add_alerta('success', "Usuario actualizado con éxito.");
    header('Location: ../index.php?seccion=usuarios');
    exit;
} catch (Exception $e) {
    // Si el email nuevo ya existe en otro usuario
    Alerta::add_alerta('error', "Error al actualizar: El email ya podría estar en uso.");

    $_SESSION['data-vieja'] = $_POST;
    header('Location: ../index.php?seccion=usuario-editar&id=' . $id);
}
