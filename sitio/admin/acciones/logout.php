<?php
require_once "../../funciones/autoload.php";

// Llamo al método estático de mi clase Autenticacion.
// No hago el session_destroy() acá directamente porque prefiero que la clase se encargue de toda la lógica de manejo de sesiones.
Autenticacion::log_out();

// Una vez cerrada la sesión, lo mando de vuelta a la pantalla de login.
header('Location: ../index.php?seccion=iniciar-sesion');
exit;
