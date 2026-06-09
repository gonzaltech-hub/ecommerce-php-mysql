<?php
// 1. CAPTURA DE FILTROS
// Obtengo los parámetros de la URL. Si no existen, quedan en null.
$filtro = $_GET['texto'] ?? null;
$etiqueta_activa = $_GET['etiqueta'] ?? null;
$categoria_activa = $_GET['categoria'] ?? null;

// Le pido al Modelo que busque los productos y calcule el título correcto.
$resultadoFiltros = Producto::obtenerConFiltros($filtro, $etiqueta_activa, $categoria_activa);

// 3. DESEMPAQUETO LOS RESULTADOS
$productos = $resultadoFiltros['productos'];
$titulo = $resultadoFiltros['titulo'];

// 4. CARGA DE RECURSOS PARA LA VISTA
$categorias = Categoria::lista_completa();
$etiquetas = Etiqueta::lista_completa();
$iconos = Producto::obtenerIconosEtiquetas();
$colores_badges = Producto::obtenerColoresBadges();
?>

<section class="productos" id="productos">
    <div class="productos-titulo">
        <h2><?= $titulo ?></h2>
        <p class="subtitulo">Productos para potenciar tu vida diaria</p>

        <div class="buscador">
            <form action="index.php" method="get">
                <input type="hidden" name="seccion" value="productos">
                <input type="text" name="texto" placeholder="¿Qué estás buscando hoy?" required>
                <button type="submit"><i class="fa-solid fa-search"></i> Buscar</button>
            </form>
        </div>

        <div class="filtros mt-20">
            <h3>Por Categoría:</h3>

            <a href="index.php?seccion=productos"
                class="btn-filtro <?= !$filtro && !$etiqueta_activa && !$categoria_activa ? 'active' : '' ?>">
                Todos
            </a>

            <?php foreach ($categorias as $cat): ?>
                <a href="index.php?seccion=productos&categoria=<?= $cat->getId() ?>"
                    class="btn-filtro <?= $categoria_activa == $cat->getId() ? 'active' : '' ?>">
                    <?= $cat->getNombre() ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="filtros">
            <h3>Estado:</h3>
            <?php foreach ($etiquetas as $tag): ?>
                <?php
                $idTag = $tag->getId();
                $icono = $iconos[$idTag] ?? 'fa-tag';
                $claseActive = ($etiqueta_activa == $idTag) ? 'active' : '';
                ?>
                <a href="index.php?seccion=productos&etiqueta=<?= $idTag ?>" class="btn-filtro <?= $claseActive ?>">
                    <i class="fa-solid <?= $icono ?>"></i> <?= $tag->getNombre() ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="productos-items">
        <?php if (empty($productos)): ?>
            <div class="box-centered">
                <p class="error">No se encontraron productos con ese criterio.</p>
                <a href="index.php?seccion=productos" class="btn mt-20">Ver todo el catálogo</a>
            </div>
        <?php else: ?>
            <ul>
                <?php foreach ($productos as $producto): ?>
                    <li class="item">

                        <?php if (!empty($producto->getEtiquetas())): ?>
                            <div class="badge-etiquetas">
                                <?php foreach ($producto->getEtiquetas() as $etiqueta): ?>
                                    <?php
                                    $claseColor = $colores_badges[$etiqueta->getNombre()] ?? 'badge-default';
                                    ?>
                                    <span class="badge <?= $claseColor ?>">
                                        <?= $etiqueta->getNombre() ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <a href="index.php?seccion=detalle&id=<?= $producto->getProductoId(); ?>">
                            <img src="img/productos/<?= $producto->getImg(); ?>" alt="<?= $producto->getNombre(); ?>" />
                            <h3><?= $producto->getNombre(); ?></h3>
                        </a>

                        <div class="acciones-producto">
                            <p class="precio">$<?= number_format($producto->getPrecio(), 2, ',', '.'); ?></p>

                            <?php if (!$esAdmin): ?>

                                <?php if ($producto->getStock() > 0): ?>
                                    <a href="acciones/carrito-agregar.php?id=<?= $producto->getProductoId() ?>&cantidad=1" class="btn-carrito">
                                        Agregar al Carrito
                                    </a>
                                <?php else: ?>
                                    <span class="btn-agotado">Sin Stock</span>
                                <?php endif; ?>

                            <?php else: ?>
                                <div class="vista-admin">
                                    <span class="btn-admin">Vista Admin</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>