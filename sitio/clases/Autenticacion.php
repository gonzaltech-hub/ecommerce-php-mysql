<?php

class Autenticacion
{

    // INICIAR SESIÓN
    // Este método recibe el email y la contraseña que el usuario escribió en el formulario.
    public static function log_in(string $email, string $password): mixed
    {
        // 1. Busco si existe un usuario con ese email en la base de datos.
        $datosUsuario = Usuario::usuario_x_email($email);

        if ($datosUsuario) {
            // 2. VERIFICACIÓN DE CONTRASEÑA
            // No comparo el texto directo (if $password == $bd_pass).
            // Uso password_verify() porque las claves están hasheadas.
            // Esta función compara el texto plano ingresado con el hash seguro de la BD.
            if (password_verify($password, $datosUsuario->getPassword())) {

                // Si la clave es correcta, guardo los datos importantes en $_SESSION.
                $datosLogin['username'] = $datosUsuario->getUsername();
                $datosLogin['id'] = $datosUsuario->getId();
                $datosLogin['rol'] = $datosUsuario->getRol();

                $_SESSION['loggedIn'] = $datosLogin;

                // Devuelvo el rol para saber si lo mando al Panel de Admin o al Home.
                return $datosLogin['rol'];
            } else {
                // Contraseña mal ingresada.
                Alerta::add_alerta('error', "La contraseña ingresada es incorrecta.");
                return FALSE;
            }
        } else {
            // El email no existe.
            Alerta::add_alerta('warning', "El usuario ingresado no se encontró.");
            return NULL;
        }
    }

    // CERRAR SESIÓN
    // Simplemente borra la variable de sesión y destruye todo rastro del usuario en el servidor.
    public static function log_out()
    {
        if (isset($_SESSION['loggedIn'])) {
            unset($_SESSION['loggedIn']);
        };
        session_destroy();
    }


    // Este método es el que protege las rutas. Lo llamo al principio de los archivos de administración.
    // El parámetro $admin me dice si la página requiere permisos especiales o si basta con ser un usuario normal.
    public static function verify($admin = TRUE): bool
    {
        // 1. Primero chequeo si está logueado
        if (isset($_SESSION['loggedIn'])) {

            // 2. Si la página pide ser Admin, verifico el Rol
            if ($admin) {
                // Roles: 1 (SuperAdmin) y 2 (Admin) tienen permiso
                if (in_array($_SESSION['loggedIn']['rol'], [1, 2])) {
                    return TRUE;
                } else {
                    // Si es usuario normal (Rol 3) intentando entrar al admin, lo saco
                    Alerta::add_alerta('error', "No tienes permisos para acceder a esta sección.");
                    header('Location: ../index.php?seccion=home');
                    exit;
                }
            } else {
                // Si no requiere admin, con estar logueado alcanza (ej: finalizar compra)
                return TRUE;
            }
        } else {
            // Si no está logueado, lo mando al login
            Alerta::add_alerta('info', "Debes iniciar sesión para acceder.");
            header('Location: ../admin/index.php?seccion=iniciar-sesion');
            exit;
        }
    }

    // HELPERS VISUALES
    // Estos métodos devuelven TRUE o FALSE.
    // Los uso en las vistas (HTML) para mostrar u ocultar botones (ej: mostrar el botón de "Panel" solo si es admin).
    public static function estaAutenticado(): bool
    {
        return isset($_SESSION['loggedIn']);
    }

    public static function esAdmin(): bool
    {
        // Verifica si es Rol 1 o 2.
        return isset($_SESSION['loggedIn']) && in_array($_SESSION['loggedIn']['rol'], [1, 2]);
    }

    // Verifica si es Rol 1 (El dueño).
    public static function esSuperAdmin(): bool
    {
        return isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']['rol'] == 1;
    }
}
