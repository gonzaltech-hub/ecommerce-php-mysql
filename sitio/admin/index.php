<?php
// 1. CARGA INICIAL
// Incluimos el autoloader subiendo un nivel (../) porque estamos dentro de la carpeta 'admin'.
require_once "../funciones/autoload.php";

// 2. CONFIGURACIÓN DE RUTAS (ADMIN)
// Defino qué puede hacer un administrador en este panel.
// A diferencia del sitio público, aca agrego banderas de seguridad extra: 'requiereAdmin' y 'requiereSuperAdmin'.
$rutas = [
  // Login
  "iniciar-sesion" => ["titulo" => "Ingresar al Panel"],

  // Rutas de Admin (Admin y SuperAdmin)
  "dashboard" => ["titulo" => "Tablero", "requiereAutenticacion" => true, "requiereAdmin" => true],
  "productos" => ["titulo" => "Administracion de Productos", "requiereAutenticacion" => true, "requiereAdmin" => true],
  "producto-nuevo" => ["titulo" => "Publicar un nuevo Producto", "requiereAutenticacion" => true, "requiereAdmin" => true],
  "producto-eliminar" => ["titulo" => "Eliminar un Producto", "requiereAutenticacion" => true, "requiereAdmin" => true],
  "producto-editar" => ["titulo" => "Editar un Producto", "requiereAutenticacion" => true, "requiereAdmin" => true],

  // Rutas exclusivas para SuperAdmin (Rol 1)
  // El Admin normal (Rol 2) no podrá entrar aca.
  "usuarios" => ["titulo" => "Gestion de Usuarios", "requiereAutenticacion" => true, "requiereAdmin" => true, "requiereSuperAdmin" => true],
  "usuario-nuevo" => ["titulo" => "Crear Nuevo Usuario", "requiereAutenticacion" => true, "requiereAdmin" => true, "requiereSuperAdmin" => true],
  "usuario-editar" => ["titulo" => "Editar Usuario", "requiereAutenticacion" => true, "requiereAdmin" => true, "requiereSuperAdmin" => true],
  "usuario-eliminar" => ["titulo" => "Eliminar Usuario", "requiereAutenticacion" => true, "requiereAdmin" => true, "requiereSuperAdmin" => true],

  "404" => ["titulo" => "Página no Encontrada"],
];

// Capturo la vista solicitada. Si no pide nada, muestro el login por defecto.
$vista = $_GET["seccion"] ?? "iniciar-sesion";

// Validación de existencia de ruta
if (!array_key_exists($vista, $rutas)) {
  $vista = "404";
}

$rutaConfig = $rutas[$vista];

// 3. SEGURIDAD

if (($rutaConfig["requiereAutenticacion"] ?? false) && !Autenticacion::estaAutenticado()) {
  Alerta::add_alerta('info', 'Debes iniciar sesión para acceder al panel.');
  header('Location: index.php?seccion=iniciar-sesion');
  exit;
}

// Cliente normal (Rol 3) no tiene permisos para entrar al panel cambiando la URL por ej.
if (($rutaConfig["requiereAdmin"] ?? false) && !Autenticacion::esAdmin()) {
  Alerta::add_alerta('error', 'Acceso denegado. No tienes permisos de administrador.');
  header('Location: ../index.php?seccion=home');
  exit;
}

// Admin normal (Rol 2) no tiene permisos para acceder a la seccion.
if (($rutaConfig["requiereSuperAdmin"] ?? false) && !Autenticacion::esSuperAdmin()) {
  Alerta::add_alerta('error', 'Acceso denegado. Solo el Dueño puede acceder a esta sección.');
  header('Location: index.php?seccion=dashboard');
  exit;
}

// Helper para HTML
$usuarioNombre = $_SESSION['loggedIn']['username'] ?? 'Admin';

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $rutaConfig["titulo"]; ?> | GonzalTech</title>
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
  <link rel="shortcut icon" href="../img/icono/icono.png" type="image/x-icon" />
</head>

<body>
  <header>
    <nav>
      <div class="logo">
        <a href="../index.php?seccion=home"><img src="../img/logo/logo.png" alt="GonzalTech" /></a>
      </div>

      <div class="menu">
        <ul>
          <li><a href="../index.php?seccion=home">Ver GonzalTech</a></li>


          <!-- Solo muestro opciones si está logueado y es admin -->
          <?php if (Autenticacion::estaAutenticado() && Autenticacion::esAdmin()): ?>
            <li><a href="index.php?seccion=dashboard" class="<?= $vista === 'dashboard' ? 'active' : '' ?>">Administración</a></li>
            <li><a href="index.php?seccion=productos" class="<?= $vista === 'productos' ? 'active' : '' ?>">Productos</a></li>

            <!-- Si es SuperAdmin, le muestro la pestaña extra de Usuarios -->
            <?php if (Autenticacion::esSuperAdmin()): ?>
              <li><a href="index.php?seccion=usuarios" class="<?= $vista === 'usuarios' ? 'active' : '' ?>">Usuarios</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>

      <div class="nav-actions">
        <?php if (Autenticacion::estaAutenticado()) : ?>
          <span class="user-saludo">Hola, <?= $usuarioNombre ?></span>

          <form action="acciones/logout.php" method="post" class="display-inline">
            <button type="submit" class="btn-login-nav logout">Salir</button>
          </form>
        <?php else: ?>
          <?php if ($vista !== 'iniciar-sesion'): ?>
            <a href="index.php?seccion=iniciar-sesion" class="btn-login-nav">Ingresar</a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <main>
    <?= Alerta::get_alertas(); ?>

    <?php
    $archivoVista = "vistas/$vista.php";

    if (file_exists($archivoVista)) {
      require $archivoVista;
    } else {
      echo "<p>Error: No se encuentra la vista solicitada ($vista).</p>";
    }
    ?>

  </main>

  <footer>
    <div class="footer">
      <div class="footer-logo">
        <a href="../index.php?seccion=home"><img src="../img/logo/logo.png" alt="Logo de GonzalTech"></a>
      </div>
      <div class="footer-info">
        <h3>Soporte Técnico</h3>
        <p>Teléfono Interno: +54 11 1234-5678</p>
        <p>Email: admin@gonzaltech.com</p>
      </div>
      <div class="footer-info">
        <p>&copy; 2025 | Panel de Control</p>
      </div>
      <div class="footer-social">
      </div>
    </div>
  </footer>
</body>

</html>