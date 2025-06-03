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

  // Guardar imagen si se sube
  if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
    $imagen = uniqid("prod_") . "." . $ext;
    move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . "/../uploads/" . $imagen);
  }

  $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, imagen) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssds", $nombre, $descripcion, $precio, $imagen);
  $stmt->execute();
  $mensaje = "Producto creado correctamente.";
}

$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Administrador de Productos</title>
  <link rel="stylesheet" href="/../style/cerrar.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      height: 100vh;
      background: #f4f6f8;
    }
    aside {
      width: 220px;
      background: #2c3e50;
      color: #ecf0f1;
      padding: 2rem 1rem;
    }
    aside h2 {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      text-align: center;
    }
    aside a {
      display: block;
      color: #ecf0f1;
      text-decoration: none;
      margin: 1rem 0;
      padding: 0.5rem;
      border-radius: 6px;
    }
    aside a:hover {
      background: #34495e;
    }
    main {
      flex: 1;
      padding: 2rem;
      overflow-y: auto;
    }
    h1 {
      margin-top: 0;
      color: #34495e;
    }
    .form-card {
      background: #fff;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      max-width: 600px;
      margin-bottom: 2rem;
    }
    label {
      display: block;
      margin-top: 1rem;
      font-weight: bold;
    }
    input, textarea {
      width: 100%;
      padding: 0.6rem;
      margin-top: 0.5rem;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    button {
      margin-top: 1.5rem;
      padding: 0.7rem 1.5rem;
      background-color: #3498db;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-size: 1rem;
      cursor: pointer;
    }
    .productos {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1.5rem;
    }
    .producto {
      background: #fff;
      padding: 1rem;
      border-radius: 8px;
      box-shadow: 0 0 6px rgba(0,0,0,0.05);
      text-align: center;
    }
    .producto img {
      max-width: 100%;
      max-height: 140px;
      object-fit: cover;
      border-radius: 6px;
      margin-bottom: 0.5rem;
    }
    .toast {
      background-color: #27ae60;
      color: white;
      padding: 1rem 1.5rem;
      position: fixed;
      top: 1rem;
      right: 1rem;
      border-radius: 6px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.2);
      z-index: 1000;
      animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }
    @keyframes fadein { from { opacity: 0; } to { opacity: 1; } }
    @keyframes fadeout { from { opacity: 1; } to { opacity: 0; } }
  </style>
</head>
<body>

<div class="topbar">
  <form action="/../php/logout.php" method="POST">
    <button type="submit" title="Cerrar sesión">🔓 Cerrar sesión</button>
  </form>
</div>

<aside>
  <h2>Admin Panel</h2>
  <a href="#">📦 Crear Producto</a>
  <a href="#">🗂 Ver Productos</a>
</aside>

<main>
  <h1>Crear Producto</h1>

  <?php if ($mensaje): ?>
    <div class="toast"><?= htmlspecialchars($mensaje) ?></div>
    <script>
      setTimeout(() => {
        document.querySelector('.toast')?.remove();
      }, 3000);
    </script>
  <?php endif; ?>

  <form class="form-card" method="POST" enctype="multipart/form-data">
    <label for="nombre">Nombre del producto</label>
    <input id="nombre" name="nombre" required>

    <label for="descripcion">Descripción</label>
    <textarea id="descripcion" name="descripcion"></textarea>

    <label for="precio">Precio</label>
    <input id="precio" type="number" step="0.01" name="precio" required>

    <label for="imagen">Imagen del producto</label>
    <input type="file" name="imagen" accept="image/*">

    <button type="submit">Guardar producto</button>
  </form>

  <h2>Lista de productos</h2>
  <div class="productos">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="producto">
        <?php if ($row['imagen']): ?>
          <img src="../uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="Producto">
        <?php endif; ?>
        <strong><?= htmlspecialchars($row['nombre']) ?></strong>
        <p>$<?= number_format($row['precio'], 2) ?></p>
      </div>
    <?php endwhile; ?>
  </div>
</main>

</body>
</html>
