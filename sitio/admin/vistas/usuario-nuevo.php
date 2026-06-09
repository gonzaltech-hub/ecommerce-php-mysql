<?php
$roles = [
  1 => 'SuperAdmin',
  2 => 'Administrador',
  3 => 'Usuario'
];

$sticky = FormHelper::recuperarStickyForm();
$errores = $sticky['errores'];
$dataVieja = $sticky['dataVieja'];
?>

<section id="usuario-nuevo">
  <h1>Crear Nuevo Usuario</h1>

  <form action="acciones/usuario-crear.php" method="post">

    <div class="form-fila">
      <label for="username" class="form-label">Nombre de Usuario</label>
      <input type="text" id="username" name="username" class="form-control"
        value="<?= $dataVieja['username'] ?? ''; ?>" required>
      <?php if (isset($errores['username'])) : ?>
        <div class="error"><?= $errores['username']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="email" class="form-label">Email</label>
      <input type="email" id="email" name="email" class="form-control"
        value="<?= $dataVieja['email'] ?? ''; ?>" required>
      <?php if (isset($errores['email'])) : ?>
        <div class="error"><?= $errores['email']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="password" class="form-label">Contraseña</label>
      <input type="password" id="password" name="password" class="form-control" required>
      <p class="nota-input">Mínimo 6 caracteres.</p>
      <?php if (isset($errores['password'])) : ?>
        <div class="error"><?= $errores['password']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="rol_fk" class="form-label">Rol</label>
      <select id="rol_fk" name="rol_fk" class="form-control">
        <?php foreach ($roles as $id => $nombre) : ?>
          <option value="<?= $id; ?>" <?= (isset($dataVieja['rol_fk']) && $dataVieja['rol_fk'] == $id) ? 'selected' : ''; ?>>
            <?= $nombre; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <button type="submit" class="button">Crear Usuario</button>
  </form>
</section>