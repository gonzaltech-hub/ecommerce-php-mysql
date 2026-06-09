<?php
$id = $_GET['id'] ?? null;
$producto = Producto::porId($id);

if (!$producto) {
  header("Location: index.php?seccion=404");
  exit;
}

$estadoPublicacion = EstadoPublicacion::lista_completa();
$categorias = Categoria::lista_completa();
$etiquetas = Etiqueta::lista_completa();

// Sticky form
$sticky = FormHelper::recuperarStickyForm();
$errores = $sticky['errores'];
$dataVieja = $sticky['dataVieja'];

if (isset($dataVieja['etiquetas'])) {
  $etiquetasSeleccionadas = $dataVieja['etiquetas'];
} else {
  $etiquetasSeleccionadas = $producto->getEtiquetasIds();
}
?>

<section id="producto-nuevo">
  <h1>Editar un Producto</h1>

  <form action="acciones/producto-editar.php?id=<?= $producto->getProductoId() ?>" method="post" enctype="multipart/form-data">

    <div class="form-fila">
      <label for="nombre" class="form-label">Titulo</label>
      <input type="text" id="nombre" name="nombre" class="form-control"
        value="<?= ($dataVieja['nombre'] ?? $producto->getNombre()) ?>">
      <?php if (isset($errores['titulo'])) : ?>
        <div class="error"><?= $errores['titulo']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="precio" class="form-label">Precio</label>
      <input
        type="number"
        step="0.01"
        min="0"
        id="precio"
        name="precio"
        class="form-control"
        value="<?= ($dataVieja['precio'] ?? $producto->getPrecio()) ?>"
        required>
      <?php if (isset($errores['precio'])) : ?>
        <div class="error"><?= $errores['precio']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="stock" class="form-label">Stock Actual</label>
      <input type="number" id="stock" name="stock" class="form-control"
        value="<?= ($dataVieja['stock'] ?? $producto->getStock()) ?>">
      <?php if (isset($errores['stock'])) : ?>
        <div class="error"><?= $errores['stock']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="descripcion" class="form-label">Descripcion</label>
      <textarea id="descripcion" name="descripcion" class="form-control"><?= ($dataVieja['descripcion'] ?? $producto->getDescripcion()) ?></textarea>
      <?php if (isset($errores['descripcion'])) : ?>
        <div class="error"><?= $errores['descripcion']; ?></div>
      <?php endif; ?>
    </div>

    <?php if ($producto->getImg()) : ?>
      <div class="form-fila">
        <p>Imagen Actual</p>
        <img src="<?= '../img/productos/' . $producto->getImg() ?>" alt="Imagen actual del producto">
      </div>
    <?php endif; ?>

    <div class="form-fila">
      <label for="img" class="form-label">Imagen (Opcional - Dejar vacío para conservar la actual)</label>
      <input type="file" id="img" name="img" class="form-control">
      <?php if (isset($errores['imagen'])) : ?>
        <div class="error"><?= $errores['imagen']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="categoria_fk" class="form-label">Categoría</label>
      <select id="categoria_fk" name="categoria_fk" class="form-control">
        <option value="">Seleccione una opción...</option>
        <?php foreach ($categorias as $cat) : ?>
          <option
            value="<?= $cat->getId(); ?>"
            <?= $cat->getId() == ($dataVieja['categoria_fk'] ?? $producto->getCategoriaFk()) ? "selected" : null ?>>
            <?= $cat->getNombre(); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <?php if (isset($errores['categoria_fk'])) : ?>
        <div class="error"><?= $errores['categoria_fk']; ?></div>
      <?php endif; ?>
    </div>

    <div class="form-fila">
      <label for="estado_publicacion_fk" class="form-label">Estado de Publicacion</label>
      <select type="text" id="estado_publicacion_fk" name="estado_publicacion_fk" class="form-control">
        <?php foreach ($estadoPublicacion as $estado) : ?>
          <option
            value="<?= $estado->getEstadoPublicacionId(); ?>"
            <?= $estado->getEstadoPublicacionId() == ($dataVieja['estado_publicacion_fk'] ?? $producto->getEstadoPublicacionFk()) ? "selected" : null ?>>
            <?= $estado->getNombre(); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-fila">
      <label class="form-label">Etiquetas (Opcional)</label>
      <div class="checkbox-container">
        <?php if (empty($etiquetas)): ?>
          <p>No hay etiquetas cargadas.</p>
        <?php else: ?>
          <?php foreach ($etiquetas as $etiqueta) : ?>
            <div class="form-check">
              <input
                type="checkbox"
                class="form-check-input"
                name="etiquetas[]"
                value="<?= $etiqueta->getId(); ?>"
                id="tag_<?= $etiqueta->getId(); ?>"
                <?= in_array($etiqueta->getId(), $etiquetasSeleccionadas) ? 'checked' : ''; ?>>
              <label for="tag_<?= $etiqueta->getId(); ?>" class="form-check-label">
                <?= $etiqueta->getNombre(); ?>
              </label>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <button type="submit" class="button">Actualizar</button>
  </form>
</section>