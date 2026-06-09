<?php
// 1. REUTILIZACIÓN DE LÓGICA
// En lugar de escribir una consulta SQL nueva, uso un método específico de mi modelo.
// Le pido solo 4 productos para no cargar toda la base de datos en la home.
$productos = Producto::obtenerDestacados(4);
// Traigo la configuración de colores.
$colores_badges = Producto::obtenerColoresBadges();
?>
<section class="banner">
  <h1>Bienvenido a GonzalTech</h1>
  <p class="subtitulo">¡Descubre el futuro en tus manos!</p>
  <a href="index.php?seccion=productos">
    <button>Comprar ahora</button>
  </a>
</section>

<section class="nosotros" id="nosotros">
  <div>
    <img src="img/home/signo.png" alt="Signo de interrogacion" />
  </div>
  <div>
    <h2>Sobre nosotros</h2>
    <p>
      En GonzalTech, estamos comprometidos con ofrecerte la mejor tecnología de última generación para que disfrutes de tus dispositivos al máximo. Desde nuestra fundación, nos hemos especializado en productos Apple y relojes Montreal, garantizando la calidad y funcionalidad que buscas.
    </p>
    <p>
      Nuestra filosofía se basa en la idea de que la tecnología debe mejorar tu vida diaria de manera eficiente y sencilla. Por eso, seleccionamos cuidadosamente cada producto, desde iPhones hasta MacBooks, asegurándonos de que ofrezcan rendimiento, estilo y durabilidad.
    </p>
    <h3>¿Qué nos distingue?</h3>
    <ul>
      <li>
        <p>
          <strong>Tecnología de vanguardia a tu alcance:</strong>
          En GonzalTech, no solo vendemos productos, te ofrecemos soluciones tecnológicas que mejoran tu vida diaria.
          Estamos especializados en los dispositivos más avanzados de Apple y relojes Montreal, seleccionados para usuarios que buscan calidad y funcionalidad.
        </p>
      </li>
      <li>
        <p>
          <strong>Siempre lo último:</strong>
          Mantente al día con lo más reciente en tecnología.
          Desde el último modelo de iPhone hasta las notebooks MacBook más potentes, en GonzalTech siempre encontrarás lo mejor.
        </p>
      </li>
      <li>
        <p>
          <strong>Compromiso con la sostenibilidad:</strong>
          Nos preocupamos por el medio ambiente, por eso buscamos constantemente formas de reducir
          nuestro impacto ambiental y promover prácticas sostenibles en toda nuestra cadena de
          suministro.
        </p>
      </li>
    </ul>
  </div>
</section>

<section class="productos" id="productos">
  <div class="productos-titulo">
    <h2>Nuestros productos destacados</h2>
    <p class="subtitulo">Dispositivos de alta gama para potenciar tu estilo de vida</p>
  </div>

  <div class="productos-destacados-items">
    <ul>
      <?php foreach ($productos as $producto): ?>
        <li class="item">

          <?php if (!empty($producto->getEtiquetas())): ?>
            <div class="badge-etiquetas">
              <?php foreach ($producto->getEtiquetas() as $etiqueta): ?>
                <span class="badge <?= $etiqueta->getClaseColor() ?>">
                  <?= $etiqueta->getNombre() ?>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <a href="index.php?seccion=detalle&id=<?= $producto->getProductoId(); ?>">
            <img src="img/productos/<?= $producto->getImg() ?>" alt="<?= $producto->getNombre() ?>" />
            <h3><?= $producto->getNombre() ?></h3>
          </a>

          <div class="acciones-producto">
            <p class="precio">$<?= number_format($producto->getPrecio(), 2, ',', '.'); ?></p>

            <?php if (!$esAdmin): // Uso la variable helper del index 
            ?>
              <a href="acciones/carrito-agregar.php?id=<?= $producto->getProductoId() ?>" class="btn-carrito">
                Agregar al Carrito
              </a>
            <?php else: ?>
              <span class="btn-admin">Vista Admin</span>
            <?php endif; ?>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  <a href="index.php?seccion=productos" class="btn">Ver Más</a>
</section>

<section class="newsletter">
  <div class="newsletter-texto">
    <h2>¡Conéctate con la tecnología de una manera diferente!</h2>
    <p class="subtitulo">Suscríbete a nuestro newsletter para recibir actualizaciones, promociones y tips sobre el uso de nuestros productos.</p>

    <form action="index.php?seccion=newsletter" method="post">
      <label>
        <input type="email" name="email" id="email" required placeholder="ejemplo@correo.com">
      </label>
      <button type="submit">Suscribirme</button>
    </form>

    <p class="visita-contacto">Si tienes alguna duda o necesitas asistencia personalizada, no dudes en contactarnos.</p>
    <p class="visita-contacto">Visita nuestra página de <a href="index.php?seccion=contacto">Contacto</a> para más información.</p>
  </div>
  <div>
    <img src="img/home/newsletter.png" alt="Personas interactuando con tecnologia">
  </div>
</section>