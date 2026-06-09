<?php
$productos = Producto::todos();
?>

<section class="productos" id="productos">
  <div class="productos-titulo">
    <h2>Administracion de Productos</h2>
    <a href="index.php?seccion=producto-nuevo" class="crear-producto">Publicar un Nuevo Producto</a>
  </div>

  <table class="table">
    <thead>
      <tr>
        <th class="ocultar-mobile-id">ID</th>
        <th>Nombre</th>
        <th>Precio</th>
        <th class="ocultar-mobile-desc">Descripcion</th>
        <th class="ocultar-mobile-img">Imagen</th>
        <th>Estado de Publicacion</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($productos as $producto) : ?>
        <tr>
          <td class="ocultar-mobile-id"><?= $producto->getProductoId(); ?></td>
          <td><?= $producto->getNombre(); ?></td>
          <td>$<?= number_format($producto->getPrecio(), 2, ',', '.'); ?></td>
          <td class="ocultar-mobile-desc"><?= $producto->getDescripcion(); ?></td>
          <td class="ocultar-mobile-img"><img src="../img/productos/<?= $producto->getImg(); ?>" alt="<?= $producto->getNombre(); ?>" /></td>
          <td><?= $producto->getEstadoPublicacion()->getNombre(); ?></td>
          <td class="acciones">
            <a href="index.php?seccion=producto-editar&id=<?= $producto->getProductoId(); ?>" class="button button-editar">Editar</a>
            <a href="index.php?seccion=producto-eliminar&id=<?= $producto->getProductoId(); ?>" class="button button-eliminar">Eliminar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>