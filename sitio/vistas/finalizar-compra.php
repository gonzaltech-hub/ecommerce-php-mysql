<?php
// 1. VALIDACIÓN DE SEGURIDAD
// Recupero los ítems del carrito.
$items = Carrito::get_carrito();

// Si por alguna razón el usuario entra a esta URL directo con el carrito vacío, lo saco y lo mando al catálogo.
if (empty($items)) {
    Alerta::add_alerta('warning', 'Tu carrito está vacío.');
    header('Location: index.php?seccion=productos');
    exit;
}

// 2. DATOS PARA LA VISTA
// Calculo el total final una vez más para mostrarlo.
$total = Carrito::precio_total();
// Recupero el nombre del usuario para personalizar el mensaje.
$usuario = $_SESSION['loggedIn']['username'] ?? 'Invitado';
?>
<section class="contenedor-carrito">
    <h2>Resumen de Compra</h2>
    <p>Estás a un paso de finalizar tu pedido. Por favor revisa los datos.</p>

    <div class="resumen-final">
        <h3>Usuario: <?= $usuario ?></h3>
        <hr>
        <ul>
            <?php foreach ($items as $item): ?>
                <li class="resumen-item">
                    <span><?= $item['cantidad'] ?>x <?= $item['titulo'] ?></span>
                    <span>$<?= number_format($item['precio'] * $item['cantidad'], 2, ',', '.') ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <h3 class="text-right">Total a Pagar: $<?= number_format($total, 2, ',', '.') ?></h3>

        <form action="acciones/checkout.php" method="post" class="text-center mt-20">
            <button type="submit" class="btn-pagar btn-full-width">CONFIRMAR Y PAGAR</button>
        </form>
    </div>
</section>