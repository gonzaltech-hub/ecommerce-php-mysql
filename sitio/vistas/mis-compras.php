<?php
// 1. SEGURIDAD Y DATOS
// Primero recupero el ID del usuario desde la sesión.
$idUsuario = $_SESSION['loggedIn']['id'] ?? null;

// Validacion
// Si alguien intenta entrar a esta vista sin estar logueado, lo redirijo al Home.
if (!$idUsuario) {
    header('Location: index.php?seccion=home');
    exit;
}

// 2. OBTENCIÓN DEL HISTORIAL
// Le pido a mi clase Compra que busque en la base de datos todas las ventas asociadas a este ID de usuario específico.
// Esto me devuelve un array de objetos Compra.
$compras = Compra::historial_por_usuario($idUsuario);
?>

<section class="contenedor-historial">
    <h2>Mis Compras Realizadas</h2>

    <?php if (empty($compras)): ?>
        <p class="alerta">Todavía no realizaste ninguna compra.</p>
        <div class="text-center mt-20">
            <a href="index.php?seccion=productos" class="btn">Ir a Comprar</a>
        </div>
    <?php else: ?>
        <table class="tabla-carrito">
            <thead>
                <tr>
                    <th>N° Compra</th>
                    <th>Fecha</th>
                    <th>Total Abonado</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra): ?>
                    <tr>
                        <td>#<?= $compra->getId(); ?></td>
                        <td><?= $compra->getFecha(); ?></td>
                        <td>$<?= number_format($compra->getTotal(), 2, ',', '.'); ?></td>
                        <td><span class="badge-success">Finalizada</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>