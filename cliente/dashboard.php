<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';
checkLogin();
if (!isCliente()) die("Acceso denegado");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel del Cliente</title>
  <link rel="stylesheet" href="/../style/adminDashboard.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <aside class="sidebar">
    <div class="logo">🛍 Cliente</div>
    <nav>
    <!-- <a href="?"><i class="fas fa-home"></i> Inicio</a>-->
      <a href="/../php/venta_producto.php"><i class="fas fa-store"></i> Ver Productos</a>
    </nav>
    <form class="logout" action="/php/logout.php" method="POST">
      <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
    </form>
  </aside>

  <main class="contenido">
      <div class="bienvenida">
        <h1>👋 ¡Hola, <?= htmlspecialchars($_SESSION['nombre']) ?>!</h1>
        <p>Explora los productos disponibles desde el menú lateral.</p>
      </div>
  </main>

</body>
</html>
