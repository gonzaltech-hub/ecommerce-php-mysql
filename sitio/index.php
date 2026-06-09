<?php
// 1. CARGA INICIAL
// Uso el Autoloader
// Para cargar las clases automáticamente cuando las necesito.
// Inicia la sesión (session_start) para todo el sitio.
require_once "funciones/autoload.php";

// Le pido a la clase que haga el cálculo del carrito (para el badge del carrito para mejor UX)
$cantidadCarrito = Carrito::cantidad_articulos();

// 2. RUTEO (WHITELIST)
// Defino una lista blanca de rutas permitidas y aprovecho para definir el titulo de cada página acá.
$rutas = [
  "home" => ["titulo" => "Inicio"],
  "alumno" => ["titulo" => "Datos Alumno"],
  "productos" => ["titulo" => "Nuestros Productos"],
  "detalle" => ["titulo" => "Detalles"],
  "contacto" => ["titulo" => "Contacto"],
  "carrito" => ["titulo" => "Mi Carrito"],

  // Rutas protegidas (requieren login)
  "mis-compras" => ["titulo" => "Historial de Compras", "requiereAutenticacion" => true],
  "finalizar-compra" => ["titulo" => "Resumen de Compra", "requiereAutenticacion" => true],

  // Rutas de Feedback
  "contacto-enviado" => ["titulo" => "Mensaje Enviado"],
  "newsletter" => ["titulo" => "Suscripción Exitosa"],
  "404" => ["titulo" => "Página no Encontrada"],
];

// Capturo la sección de la URL. Si no viene nada, cargo 'home' por defecto.
$vista = $_GET["seccion"] ?? "home";

// Si la vista no existe en la lista blanca, le muestro la 404.
if (!array_key_exists($vista, $rutas)) {
  $vista = "404";
}

// Obtengo la configuración de la vista actual.
$rutaConfig = $rutas[$vista];


// Verifico si la ruta requiere autenticación y si el usuario no está logueado.
if (($rutaConfig["requiereAutenticacion"] ?? false) && !Autenticacion::estaAutenticado()) {

  // Si intenta entrar sin permiso, muestro una alerta y lo mando al login.
  Alerta::add_alerta('info', 'Debes iniciar sesión para acceder a esta sección.');
  header('Location: admin/index.php?seccion=iniciar-sesion');
  exit;
}

// Variables helper
$estaAutenticado = Autenticacion::estaAutenticado();
$esAdmin = $estaAutenticado && Autenticacion::esAdmin();
$usuarioNombre = $_SESSION['loggedIn']['username'] ?? 'Usuario';

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Paso el título de las rutas -->
  <title><?= $rutaConfig["titulo"]; ?> | GonzalTech</title>
  <link rel="stylesheet" href="css/styles.css" />
  <!-- Iconos de font awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
  <link rel="shortcut icon" href="img/icono/icono.png" type="image/x-icon" />
</head>

<body>
  <header>
    <nav>
      <div class="logo">
        <a href="index.php?seccion=home"><img src="img/logo/logo.png" alt="GonzalTech" /></a>
      </div>

      <!-- Uso 'active' para que el usuario sepa en que sección esta. (UX mas que nada) -->
      <div class="menu">
        <ul>
          <li><a href="index.php?seccion=home" class="<?= $vista === 'home' ? 'active' : '' ?>">Inicio</a></li>
          <li><a href="index.php?seccion=productos" class="<?= $vista === 'productos' ? 'active' : '' ?>">Tienda</a></li>
          <li><a href="index.php?seccion=contacto" class="<?= $vista === 'contacto' ? 'active' : '' ?>">Contacto</a></li>
          <li><a href="index.php?seccion=alumno" class="<?= $vista === 'alumno' ? 'active' : '' ?>">Alumno</a></li>

          <!-- Si esta logeado y es administrador le muestro el 'Panel Admin' -->
          <?php if ($estaAutenticado): ?>
            <?php if ($esAdmin): ?>
              <li>
                <a href="admin/index.php?seccion=dashboard" class="gestion-productos">
                  <b><i class="fas fa-cogs"></i> Panel Admin</b>
                </a>
              </li>
            <?php else: ?>
              <!-- Si esta logeado y es usuario le muestro la vista 'Mis Compras' -->
              <li><a href="index.php?seccion=mis-compras" class="<?= $vista === 'mis-compras' ? 'active' : '' ?>">Mis Compras</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </div>

      <!-- Saludo + nombre de usuario solo si esta logeado -->
      <div class="nav-actions">
        <?php if ($estaAutenticado): ?>
          <span class="user-saludo">Hola, <?= $usuarioNombre ?></span>

          <form action="admin/acciones/logout.php" method="post">
            <button type="submit" class="btn-login-nav logout">Salir</button>
          </form>
        <?php else: ?>
          <a href="admin/index.php?seccion=iniciar-sesion" class="btn-login-nav">Ingresar</a>
        <?php endif; ?>

        <!-- Solo al usuario le aparecerá el carrito -->
        <?php if (!$esAdmin): ?>
          <div class="carrito">
            <a href="index.php?seccion=carrito">
              <i class="fas fa-shopping-cart"></i>
              <?php if ($cantidadCarrito > 0): ?>
                <span class="badge-cantidad"><?= $cantidadCarrito ?></span>
              <?php endif; ?>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </nav>
  </header>

  <main>
    <?= Alerta::get_alertas(); ?>

    <?php
    // Cargo el archivo PHP que corresponde a la sección elegida.
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
        <a href="index.php?seccion=home"><img src="img/logo/logo.png" alt="Logo de GonzalTech"></a>
      </div>
      <div class="footer-info">
        <h3>¡Contáctenos!</h3>
        <p>Teléfono: +54 11 1234-5678</p>
        <p>Dirección: Capital Federal 1234</p>
        <p>Email: gonzaltech@gmail.com</p>
      </div>
      <div class="footer-info">
        <h3>Nuestros horarios</h3>
        <p>Palermo: L-V. 9:00 AM a 17:30 PM.</p>
        <p>Belgrano Local: L-V. 10:00 AM a 19:00 PM.</p>
        <p>Sábados: 10:00 AM a 14:30 PM.</p>
      </div>
      <div class="footer-social">
        <h3>¡Síguenos en las redes!</h3>
        <ul>
          <li><a href="#"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#"><i class="fa-brands fa-x-twitter"></i></a></li>
          <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
          <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
        </ul>
      </div>
    </div>
  </footer>
</body>

</html>