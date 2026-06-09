<?php
// 1. OBTENCIÓN DE DATOS
// Uso el método especial que creamos en la clase Carrito.
// No uso "get_carrito", uso "obtenerItemsConStock".
// Porque necesito saber el stock maxino real de la base de datos para limitar el input de cantidad.
$items = Carrito::obtenerItemsConStock();
$total = Carrito::precio_total();
?>
<section class="contenedor-carrito">
    <h2>Tu carrito de compras</h2>

    <?php if (empty($items)): ?>
        <p class="alerta">
            No tienes productos en el carrito.
            <a href="index.php?seccion=productos" class="btn">Ir a la Tienda</a>
        </p>
    <?php else: ?>
        <form action="acciones/carrito-actualizar.php" method="post">
            <table class="tabla-carrito">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $id => $item): ?>
                        <tr>
                            <td class="align-middle">
                                <div class="flex-center">
                                    <img src="img/productos/<?= $item['portada'] ?>" alt="<?= $item['titulo'] ?>" width="60">
                                    <div>
                                        <strong><?= $item['titulo']; ?></strong>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">$<?= number_format($item['precio'], 2, ',', '.'); ?></td>

                            <td class="align-middle">
                                <div>
                                    <input
                                        type="number"
                                        class="form-control input-cantidad"
                                        value="<?= $item['cantidad'] ?>"
                                        name="cantidad[<?= $id ?>]"
                                        min="1"
                                        max="<?= $item['stock_maximo'] ?>">

                                    <small> (Max: <?= $item['stock_maximo'] ?>)</small>
                                </div>
                            </td>

                            <td class="align-middle">
                                $<?= number_format($item['precio'] * $item['cantidad'], 2, ',', '.'); ?>
                            </td>

                            <td class="align-middle">
                                <a href="acciones/carrito-eliminar.php?id=<?= $id; ?>" class="btn-eliminar" title="Eliminar del carrito">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="carrito-acciones flex-between mt-20">
                <div class="carrito-opciones">
                    <button type="submit" class="btn-carrito btn-act-carro">
                        <i class="fas fa-sync"></i> Actualizar Cantidades
                    </button>

                    <a href="acciones/carrito-vaciar.php" class="btn-carrito btn-vaciar-carro">
                        <i class="fas fa-trash"></i> Vaciar Carrito</a>

                    <a href="index.php?seccion=productos" class="btn-carrito">
                        <i class="fa-solid fa-angles-left"></i> Seguir Comprando</a>
                </div>

                <div class="resumen-carrito text-right">
                    <h3 class="h-1-5 mb-15">
                        Total: $<?= number_format($total, 2, ',', '.'); ?>
                    </h3>

                    <?php if ($estaAutenticado): ?>
                        <a href="index.php?seccion=finalizar-compra" class="btn-pagar">Finalizar Compra</a>
                    <?php else: ?>
                        <a href="admin/index.php?seccion=iniciar-sesion" class="btn">Inicia Sesión para Pagar</a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    <?php endif; ?>
</section>