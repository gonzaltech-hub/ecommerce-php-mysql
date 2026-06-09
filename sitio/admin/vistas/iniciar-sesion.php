<section id="login">
  <div class="login">
    <h1>Iniciar sesión</h1>

    <form action="acciones/login.php" method="post">
      <div class="form-fila">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>
      <div class="form-fila">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>
      <button type="submit" class="button">Ingresar</button>
    </form>
  </div>
</section>