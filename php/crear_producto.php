<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';
checkLogin();
if (!isAdmin()) die("Acceso denegado");

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre']);
  $descripcion = trim($_POST['descripcion']);
  $precio = floatval($_POST['precio']);
  $imagen = null;

  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $imagen = uniqid("prod_") . "." . $ext;
    move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../uploads/' . $imagen);
  }

  $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen);
  $stmt->execute();
  $mensaje = "✅ Producto creado correctamente.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Producto</title>
  <link rel="stylesheet" href="/../style/adminDashboard.css">
  <link rel="stylesheet" href="/../style/crear_producto.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<aside class="sidebar">
  <div class="logo">🛠 Admin</div>
  <nav>
    <a href="/../admin/dashboard.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="crear_producto.php" class="active"><i class="fas fa-plus-circle"></i> Crear Producto</a>
    <a href="ver_producto.php"><i class="fas fa-boxes"></i> Ver Productos</a>
  </nav>
  <form class="logout" action="/php/logout.php" method="POST">
    <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
  </form>
</aside>

<main class="contenido">
  <h1>🛒 Crear Nuevo Producto</h1>

  <?php if ($mensaje): ?>
    <div class="toast"><?= htmlspecialchars($mensaje) ?></div>
    <script>
      setTimeout(() => {
        document.querySelector('.toast')?.remove();
      }, 4000);
    </script>
  <?php endif; ?>

  <form class="form-card" method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre del producto</label>
    <input type="text" id="nombre" name="nombre" required>

    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion" rows="3"></textarea>

    <label for="precio">Precio</label>
    <input type="number" step="0.01" id="precio" name="precio" required>

    <label for="imagen">Imagen</label>
    <input type="file" id="imagen" name="imagen" accept="image/*">

    <button type="submit"><i class="fas fa-save"></i> Guardar Producto</button>
  </form>
</main>

</body>
</html>
