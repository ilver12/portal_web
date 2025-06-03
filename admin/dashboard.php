<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';
checkLogin();
if (!isAdmin()) die("Acceso denegado");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Administración</title>
  <link rel="stylesheet" href="/../style/adminDashboard.css">
  <link rel="stylesheet" href="/../style/cerrar.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <aside class="sidebar">
    <div class="logo">🛠 Admin</div>
    <nav>
      <a href="/../admin/dashboard.php" class="active"><i class="fas fa-home"></i> Inicio</a>
      <a href="/../php/crear_producto.php"><i class="fas fa-plus-circle"></i> Crear Producto</a>
      <a href="/../php/ver_producto.php"><i class="fas fa-boxes"></i> Ver Productos</a>
    </nav>
    <form class="logout" action="/php/logout.php" method="POST">
      <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
    </form>
  </aside>

  <main class="contenido">
    <div class="alerta-bienvenida">
      <strong>🎉 ¡Bienvenido, Jefe!</strong>
      <p>Administra los productos desde el menú lateral.</p>
    </div>
    <img src="/assets/bienvenida.svg" alt="Bienvenida" class="img-bienvenida">
  </main>

</body>
</html>
