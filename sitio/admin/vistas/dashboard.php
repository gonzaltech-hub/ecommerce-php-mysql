<section id="dashboard">
  <h1>Panel de Administración</h1>
  <p class="intro">
    Bienvenido al sistema de gestión de <strong>GonzalTech</strong>. Selecciona una opción para comenzar.
  </p>

  <div class="dashboard-grid">
    <div class="card-dashboard">
      <h3><i class="fa-solid fa-box-open"></i> Productos</h3>
      <p>Administra el catálogo, edita precios, descripciones, imágenes y controla el estado de publicación.</p>
      <a href="index.php?seccion=productos" class="button">Gestionar Productos</a>
    </div>
    <!-- SuperAdmin -->
    <?php if (Autenticacion::esSuperAdmin()): ?>
      <div class="card-dashboard card-superadmin">
        <h3><i class="fa-solid fa-users-gear"></i> Usuarios</h3>
        <p>Gestión avanzada de permisos. Crea nuevos administradores, edita roles y elimina usuarios.</p>
        <a href="index.php?seccion=usuarios" class="button button-secundario">Gestionar Usuarios</a>
      </div>
    <?php endif; ?>
  </div>
</section>