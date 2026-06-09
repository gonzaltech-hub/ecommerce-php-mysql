<?php
$id = $_GET['id'] ?? null;
$usuario = Usuario::porId($id);

if (!$usuario) {
  header("Location: index.php?seccion=404");
  exit;
}

// Protección del SuperAdmin
if ($usuario->getRol() == 1) {
  Alerta::add_alerta('error', 'No se puede eliminar al SuperAdministrador.');
  header("Location: index.php?seccion=usuarios");
  exit;
}
?>

<div class="confirmacion">
  <h1>Confirmación Requerida</h1>
  <p>
    ¿Estás seguro de que deseas eliminar al usuario <strong><?= $usuario->getUsername() ?></strong>?
  </p>
  <p>Rol: <strong><?= $usuario->getNombreRol() ?></strong></p>

  <form action="acciones/usuario-eliminar.php?id=<?= $usuario->getId(); ?>" method="post">
    <button type="submit" class="button button-eliminar">Eliminar Definitivamente</button>
  </form>

  <a href="index.php?seccion=usuarios" class="btn mt-20">Cancelar</a>
</div>