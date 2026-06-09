<?php
require_once "../../funciones/autoload.php";

// A diferencia de los productos (que los carga un Admin), los usuarios solo los puede crear el SuperAdmin.
// Si alguien con Rol 2 (Admin) intenta entrar acá, lo echo.
Autenticacion::verify();

// CAPTURA DE DATOS
$username = $_POST['username'];
$email    = $_POST['email'];
$password = $_POST['password'];
$rol_fk   = $_POST['rol_fk']; // 1, 2 o 3

// VALIDACIÓN
// Armo el array como lo espera mi clase Usuario
$datosValidar = [
    'username' => $username,
    'email'    => $email,
    'password' => $password,
    'rol_fk'   => $rol_fk
];

// Llamo a validarDatos
// El segundo parámetro es 'false' porque NO es edición ( la password es obligatoria).
$errores = Usuario::validarDatos($datosValidar, false);

// RETORNO POR ERRORES
if (count($errores) > 0) {
    $_SESSION['errores'] = $errores;
    // Guardo los datos viejos para no obligar a escribir todo de nuevo.
    $_SESSION['data-vieja'] = $_POST;
    header('Location: ../index.php?seccion=usuario-nuevo');
    exit;
}

try {
    // HASHEO DE CONTRASEÑA
    // Nunca guardo la contraseña plana.
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // INSERTAR EN BASE DE DATOS
    (new Usuario)->crear([
        'username' => $username,
        'email'    => $email,
        'password' => $passwordHash, // Guardo el hash, NO la password original
        'rol_fk'   => $rol_fk
    ]);

    Alerta::add_alerta('success', "Usuario creado correctamente.");
    header('Location: ../index.php?seccion=usuarios');
    exit;
} catch (Exception $e) {
    // Generalmente entra acá si el EMAIL ya existe en la base de datos
    Alerta::add_alerta('error', "Error al crear usuario. Posiblemente el email ya esté registrado.");
    $_SESSION['data-vieja'] = $_POST;
    header('Location: ../index.php?seccion=usuario-nuevo');
    exit;
}
