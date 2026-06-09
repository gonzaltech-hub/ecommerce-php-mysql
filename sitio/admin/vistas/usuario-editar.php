<?php
$id = $_GET['id'] ?? null;
$usuario = Usuario::porId($id);

if (!$usuario) {
  header("Location: index.php?seccion=404");
  exit;
}

$roles = [
  1 => 'SuperAdmin',
  2 => 'Administrador',
  3 => 'Usuario'
];

$sticky = FormHelper::recuperarStickyForm();
$errores = $sticky['errores'];
$dataVieja = $sticky['dataVieja'];
?>

<section id="producto-nuevo">
  <h1>Editar Usuario</h1>

  <form action="acciones/usuario-editar.php?id=<?= $usuario->getId() ?>" method="post">

    <div class="form-fila">
      <label for="username" class="form-label">Nombre de Usuario</label>
      <input type="text" id="username" name="username" class="form-control"
        value="<?= ($dataVieja['username'] ?? $usuario->getUsername()) ?>">
      <?php if (isset($errores['username'])) : ?>
        <div class="error"><?= $errores['username']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control"
        value="<?= ($dataVieja['email'] ?? $usuario->getEmail()) ?>">
      <?php if (isset($errores['email'])) : ?>
        <div class="error"><?= $errores['email']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="password" class="form-label">Contraseña (Opcional)</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
      <small>Escribe en el campo si la quieres cambiar.</small>
      <?php if (isset($errores['password'])) : ?>
        <div class="error"><?= $errores['password']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="rol_fk" class="form-label">Rol</label>
      <select id="rol_fk" name="rol_fk" class="form-control">
        <?php foreach ($roles as $id => $nombre) : ?>
          <option
            value="<?= $id; ?>"
            <?= $id == ($dataVieja['rol_fk'] ?? $usuario->getRol()) ? "selected" : null ?>>
            <?= $nombre; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="button">Actualizar Usuario</button>
  </form>
</section>