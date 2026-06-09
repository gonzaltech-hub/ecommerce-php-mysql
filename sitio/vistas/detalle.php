<?php
// 1. CAPTURA DEL ID
$id = $_GET['id'] ?? null;

// Si intentan entrar a esta página sin un ID, los mando de vuelta al catálogo.
if (!$id) {
  header('Location: index.php?seccion=productos');
  exit;
}

// 2. BUSCO EL PRODUCTO
// Le pido a la clase Producto que me traiga los datos de este ID específico.
$producto = Producto::porId($id);
?>
<section class="detalle-producto">
  <?php
  // Puede pasar que alguien escriba un ID falso en la URL (ej: id=9999).
  // Si Producto::porId devuelve algo, muestro. Si no, muestro error.
  if ($producto):
  ?>

    <div class="detalle-img">
      <img src="img/productos/<?= $producto->getImg() ?>" alt="<?= $producto->getNombre() ?>">
    </div>

    <div class="detalle-texto">
      <h2><?= $producto->getNombre() ?></h2>
      <!-- Estrellas y opiniones solo para decoración -->
      <div class="estrellas">
        <p>4.0</p>
        <div>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="fas fa-star"></i>
          <i class="far fa-star"></i>
        </div>
        <p>(104 opiniones)</p>
      </div>

      <p class="descripcion"><?= $producto->getDescripcion() ?></p>

      <div class="precio-comprar">
        <p class="precio">$<?= number_format($producto->getPrecio(), 2, ',', '.'); ?></p>

        <?php if (!$esAdmin): ?>

          <?php if ($producto->getStock() > 0): ?>
            <form action="acciones/carrito-agregar.php" method="get" class="form-comprar">
              <input type="hidden" name="id" value="<?= $producto->getProductoId() ?>">

              <div class="cantidad-wrapper">
                <label for="cantidad">Cantidad:</label>
                <input type="number"
                  name="cantidad"
                  id="cantidad"
                  value="1"
                  min="1"
                  max="<?= $producto->getStock() ?>">
                <small>(Disponibles: <?= $producto->getStock() ?>)</small>
              </div>

              <button type="submit" class="button">Agregar al Carrito</button>
            </form>

          <?php else: ?>
            <div class="sin-stock-msg">
              <p>Producto Agotado</p>
            </div>
          <?php endif; ?>

        <?php else: ?>
          <div class="detalle-admin">
            <p><strong>Modo Administrador</strong></p>
            <p>Stock actual: <strong><?= $producto->getStock() ?></strong> unidades</p>
            <a href="admin/index.php?seccion=producto-editar&id=<?= $producto->getProductoId() ?>">Editar este producto</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

  <?php else: ?>

    <div class="producto-no-encontrado">
      <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="box-centered">
      <h2>Producto no encontrado</h2>
      <p>Lo sentimos, el producto que buscas no existe o fue eliminado.</p>
      <a href="index.php?seccion=productos" class="btn">Volver a la Tienda</a>
    </div>

  <?php endif; ?>
</section>