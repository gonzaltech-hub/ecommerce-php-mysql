<?php
$id = $_GET['id'] ?? null;
$producto = Producto::porId($id);

if (!$producto) {
  header("Location: index.php?seccion=404");
  exit;
}
?>

<div class="confirmacion">
  <h1>Confirmacion Requerida</h1>
  <p>
    ¿Estás seguro de que deseas eliminar el producto <strong><?= $producto->getNombre() ?></strong>?
  </p>

  <form action="acciones/producto-eliminar.php?id=<?= $producto->getProductoId(); ?>" method="post">
    <button type="submit" class="button button-eliminar">Eliminar Definitivamente</button>
  </form>
</div>

<section class="detalle-producto">
  <div class="detalle-img">
    <img src="../img/productos/<?= $producto->getImg() ?>" alt="<?= $producto->getNombre() ?>">
  </div>
  <div class="detalle-texto">
    <h2><?= $producto->getNombre() ?></h2>
  </div>
</section>