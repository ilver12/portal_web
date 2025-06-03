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
  <link rel="stylesheet" href="/../style/new.css" />
</head>
<body>


<header>
  <nav class="navbar">
    <div class="logo">
      <img src="/img/logo.avif" alt="Logo" />
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

<!-- TESTIMONIOS CON CARICATURAS -->
<section class="testimonios">
  <h2>💬 Lo que dicen nuestros clientes</h2>
  <div class="testimonio-lista">
    <div class="testimonio">
      <img src="/img/avatar 1.svg" alt="Cliente feliz" />
      <p>"Gracias a Portal Software, automatizamos nuestra empresa en menos de una semana."</p>
      <span>- Ana, Empresaria</span>
    </div>
    <div class="testimonio">
      <img src="/img/avatar2.svg" alt="Cliente satisfecho" />
      <p>"Sencillo, práctico y muy económico. ¡Ideal para pequeños negocios!"</p>
      <span>- Luis, Panadero</span>
    </div>
    <div class="testimonio">
      <img src="/img/avatar3.svg" alt="Cliente emprendedor" />
      <p>"El soporte es increíble y las herramientas son justo lo que necesitaba."</p>
      <span>- Marisol, Emprendedora Digital</span>
    </div>
  </div>
</section>

<!-- CÓMO FUNCIONA -->
<section class="como-funciona">
  <h2>🛠️ ¿Cómo funciona?</h2>
  <div class="pasos">
    <div class="paso" data-step="1">
      <h3>1. Regístrate</h3>
      <p>Crea tu cuenta en segundos. Solo necesitas un correo electrónico.</p>
    </div>
    <div class="paso" data-step="2">
      <h3>2. Personaliza</h3>
      <p>Selecciona los módulos que necesitas: ventas, facturación, inventario, y más.</p>
    </div>
    <div class="paso" data-step="3">
      <h3>3. ¡A trabajar!</h3>
      <p>Comienza a usar el software desde cualquier lugar, sin complicaciones.</p>
    </div>
  </div>
</section>

<!-- NUESTRO EQUIPO -->
<section class="equipo">
  <h2>👨‍💻 Conoce al equipo</h2>
  <div class="miembros">
    <div class="miembro">
      <img src="/img/avatar4.jpg" alt="Andrés, Desarrollador" />
      <h4>Andrés</h4>
      <p>Desarrollador Backend amante del café y los APIs robustos.</p>
    </div>
    <div class="miembro">
      <img src="/img/avatar5.jpg" alt="Lucía, Diseñadora UX" />
      <h4>Lucía</h4>
      <p>Diseñadora de experiencias simples, intuitivas y bonitas.</p>
    </div>
    <div class="miembro">
      <img src="/img/avatar6.jpg" alt="Carlos, Soporte Técnico" />
      <h4>Carlos</h4>
      <p>Especialista en atención al cliente con una sonrisa 24/7.</p>
    </div>
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

<script>
  const pasos = document.querySelectorAll('.paso');

  const mostrarPaso = () => {
    pasos.forEach(paso => {
      const top = paso.getBoundingClientRect().top;
      if (top < window.innerHeight - 100) {
        paso.classList.add('visible');
      }
    });
  };

  window.addEventListener('scroll', mostrarPaso);
  window.addEventListener('load', mostrarPaso);
</script>


</body>
</html>
