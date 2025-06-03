<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';
checkLogin();
if (!isCliente()) die("Acceso denegado");

if (isset($_POST['producto_id'])) {
  $usuario_id = $_SESSION['usuario_id'];
  $producto_id = $_POST['producto_id'];

  $stmt = $conn->prepare("INSERT INTO carrito (usuario_id, producto_id) VALUES (?, ?)");
  $stmt->bind_param("ii", $usuario_id, $producto_id);
  $stmt->execute();
}

$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos disponibles</title>
  <link rel="stylesheet" href="/../style/cerrar.css" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f9f9f9;
      margin: 2rem;
      color: #333;
    }

    h2, h3 {
      color: #2c3e50;
    }

    .productos {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .producto {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      padding: 1rem;
      width: 250px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .producto h4 {
      margin: 0 0 0.5rem;
      color: #34495e;
    }

    .producto p {
      font-size: 0.95rem;
      color: #666;
    }

    .producto strong {
      margin-top: 0.5rem;
      color: #27ae60;
      font-size: 1.1rem;
    }

    .producto form {
      margin-top: 1rem;
    }

    .producto button {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 0.5rem;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.95rem;
    }

    .producto button:hover {
      background-color: #2980b9;
    }

    .logout {
      display: inline-block;
      margin-top: 2rem;
      color: #e74c3c;
      text-decoration: none;
      font-weight: bold;
    }

    .logout:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="topbar">
  <form action="/../php/logout.php" method="POST">
    <button type="submit" title="Cerrar sesión">🔓 Cerrar sesión</button>
  </form>
</div>

  <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?></h2>
  <h3>Productos disponibles:</h3>

  <div class="productos">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="producto">
        <h4><?= htmlspecialchars($row['nombre']) ?></h4>
        <p><?= htmlspecialchars($row['descripcion']) ?></p>
        <strong>$<?= number_format($row['precio'], 2) ?></strong>
        <form method="POST">
          <input type="hidden" name="producto_id" value="<?= $row['id'] ?>">
          <button type="submit">Agregar al carrito</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>

  <a class="logout" href="../logout.php">Cerrar sesión</a>

</body>
</html>
