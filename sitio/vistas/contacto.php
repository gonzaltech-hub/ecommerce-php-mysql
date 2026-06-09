<section class="contacto">
  <h2>Contáctate con nosotros</h2>

  <div class="contacto-datos">
    <div>
      <i class="fa-solid fa-phone"></i>
      <h3>Teléfono</h3>
      <p>+54 11 1234-5678</p>
    </div>
    <div>
      <i class="fas fa-map-marker-alt"></i>
      <h3>Dirección</h3>
      <p>Capital Federal 1234</p>
    </div>
    <div>
      <i class="fa-solid fa-envelope"></i>
      <h3>Email</h3>
      <p>gonzaltech@gmail.com</p>
    </div>
  </div>

  <h2>Si tiene alguna pregunta, no dude en enviarnos un mensaje</h2>

  <form action="index.php?seccion=contacto-enviado" method="post">

    <label for="nombre">Nombre</label>
    <input type="text" name="nombre" id="nombre" required placeholder="Nombre">

    <label for="email">Email</label>
    <input type="email" name="email" id="email" required placeholder="Email">

    <label for="telefono">Teléfono</label>
    <input type="tel" name="telefono" id="telefono" required placeholder="Número de teléfono">

    <label for="asunto">Asunto</label>
    <input type="text" name="asunto" id="asunto" required placeholder="Asunto">

    <label for="mensaje">Mensaje</label>
    <textarea name="mensaje" id="mensaje" required placeholder="Mensaje"></textarea>

    <button type="submit">Enviar Mensaje</button>
  </form>
</section>