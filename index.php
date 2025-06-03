<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Portal de Software</title>
  <link rel="stylesheet" href="/../style/index.css" />
  <link rel="stylesheet" href="/../style/estrella.css" />
</head>
<body>


<header>
  <nav class="navbar">
    <div class="logo">
      <img src="images/logo.png" alt="Logo" />
      <span>Portal Software</span>
    </div>
    <div class="nav-links">
      <?php if(isset($_SESSION['cliente_id'])): ?>
        <a href="dashboard.php">Mi cuenta</a>
        <a href="php/logout.php">Cerrar sesión</a>
      <?php else: ?>
        <a href="php/register.php">Registrarse</a>
        <a href="php/login.php">Iniciar sesión</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

<!-- HERO SECCIÓN INICIO -->
<section class="hero">
  <h1>Todo tu negocio en<br><span class="resaltado-naranja">una sola plataforma</span></h1>
  <p>¡Sencillo, eficiente y a <span class="resaltado-azul">buen precio!</span></p>
  <div class="botones">
    <a href="php/register.php" class="btn-primario">Comienza ahora, es gratis</a>
    <a href="php/contacto.php" class="btn-secundario">Contacta a un consultor</a>
  </div>
  <div class="precio">
    US$ 7.25 al mes por TODAS las aplicaciones
  </div>
  <!-- Ondas SVG decorativas -->
<div class="ondas-svg">
  <svg viewBox="0 0 1440 320">
    <path fill="#ffffff33" fill-opacity="1" d="M0,64L40,69.3C80,75,160,85,240,117.3C320,149,400,203,480,202.7C560,203,640,149,720,128C800,107,880,117,960,117.3C1040,117,1120,107,1200,117.3C1280,128,1360,160,1400,176L1440,192L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
  </svg>
</div>

<!-- Burbujas flotantes -->
<ul class="burbujas">
  <li></li><li></li><li></li><li></li><li></li>
  <li></li><li></li><li></li><li></li><li></li>
</ul>
</section>
<!-- HERO SECCIÓN FIN -->

<main>
  <section class="productos">
    <h2>Productos disponibles</h2>
    <div class="producto-lista">
      <?php
        include 'php/db.php';
        $query = $conn->query("SELECT * FROM productos");
        while ($producto = $query->fetch_assoc()):
      ?>
        <div class="producto-card">
        <img src="uploads/<?= htmlspecialchars($producto['imagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" />
          <h3><?= $producto['nombre'] ?></h3>
          <p><?= $producto['descripcion'] ?></p>
          <p class="precio">$<?= number_format($producto['precio'], 2) ?></p>
          <?php if(isset($_SESSION['cliente_id'])): ?>
            <form method="POST" action="php/carrito.php">
              <input type="hidden" name="producto_id" value="<?= $producto['id'] ?>" />
              <button type="submit" name="agregar_carrito">Agregar al carrito</button>
            </form>
          <?php else: ?>
            <p><a href="php/login.php">Inicia sesión para comprar</a></p>
          <?php endif; ?>
        </div>
      <?php endwhile; ?>
    </div>
  </section>

<section class="recomendados">
  <h2>⭐ Califica tu experiencia</h2>
  <div class="calificacion-container">
    <div class="estrellas" id="estrellas">
      <span class="estrella" data-valor="1">&#9733;</span>
      <span class="estrella" data-valor="2">&#9733;</span>
      <span class="estrella" data-valor="3">&#9733;</span>
      <span class="estrella" data-valor="4">&#9733;</span>
      <span class="estrella" data-valor="5">&#9733;</span>
    </div>
    <p id="mensaje" class="mensaje-exito">¡Gracias por tu calificación!</p>
  </div>
</section>

</main>

<footer class="footer">
  <p>&copy; <?= date("Y") ?> Portal Software - Todos los derechos reservados.</p>
  <p>Contacto: soporte@portalsoftware.com | Tel: +123 456 789</p>
</footer>


<script>
  const estrellas = document.querySelectorAll('.estrella');
  const mensaje = document.getElementById('mensaje');

  estrellas.forEach((estrella, index) => {
    estrella.addEventListener('click', () => {
      const valor = parseInt(estrella.getAttribute('data-valor'));

      estrellas.forEach((e, i) => {
        e.classList.toggle('seleccionada', i < valor);
      });

      mensaje.style.display = 'block';
      mensaje.textContent = '¡Gracias por tu calificación!';
    });
  });
</script>

</body>
</html>
