<?php
$usuarios = Usuario::todos();
?>

<section class="productos" id="productos">
  <div class="productos-titulo">
    <h2>Gestión de Usuarios</h2>
    <p class="subtitulo">Panel exclusivo para SuperAdministrador</p>
    <a href="index.php?seccion=usuario-nuevo" class="crear-producto">Crear Nuevo Usuario</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th class="ocultar-mobile-id">ID</th>
        <th>Username</th>
        <th class="ocultar-mobile-email">Email</th>
        <th>Rol</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $usuario) : ?>
        <tr>
          <td class="ocultar-mobile-id"><?= $usuario->getId(); ?></td>
          <td><?= $usuario->getUsername(); ?></td>
          <td class="ocultar-mobile-email"><?= $usuario->getEmail(); ?></td>
          <td><?= $usuario->getNombreRol(); ?></td>
          <td class="acciones">
            <a href="index.php?seccion=usuario-editar&id=<?= $usuario->getId(); ?>" class="button button-editar">Editar</a>

            <?php if ($usuario->getRol() != 1): ?>
              <a href="index.php?seccion=usuario-eliminar&id=<?= $usuario->getId(); ?>" class="button button-eliminar">Eliminar</a>
            <?php else: ?>
              <span>(Protegido)</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>