<?php
// 1. CARGA DE CLASES
// Subo dos niveles para llegar a la raíz y cargar el Autoload.
require_once "../../funciones/autoload.php";

// 2. RECEPCIÓN DE DATOS
// Capturo lo que el usuario escribió en el formulario.
$email = $_POST['email'];
$password = $_POST['password'];

// 3. INTENTO DE LOGIN
// Le paso $email y $password coincidiendo con la definición en Autenticacion.php
$login = Autenticacion::log_in($email, $password);

if ($login) {
    // 4. REDIRECCIÓN
    // Si el rol es 1 (SuperAdmin) o 2 (Admin)...
    if (in_array($login, [1, 2])) {
        // ...lo mando al Panel de Control.
        header('Location: ../index.php?seccion=dashboard');
    } else {
        // Si es rol 3 (Usuario/Cliente)...
        // ...lo mando al Home público.
        header('Location: ../../index.php?seccion=home');
    }
} else {
    // 5. LOGIN FALLIDO
    // Si la contraseña estaba mal o el usuario no existe, lo devuelvo al login.
    header('Location: ../index.php?seccion=iniciar-sesion');
}
