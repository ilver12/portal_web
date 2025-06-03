<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';
checkLogin();
if (!isAdmin()) die("Acceso denegado");

$mensaje = "";

// Editar producto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
  $id = intval($_POST['editar_id']);
  $nombre = trim($_POST['editar_nombre']);
  $descripcion = trim($_POST['editar_descripcion']);
  $precio = floatval($_POST['editar_precio']);

  $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ? WHERE id = ?");
  $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $id);
  $stmt->execute();
  $mensaje = "✅ Producto actualizado correctamente.";
}

// Eliminar producto
if (isset($_GET['eliminar'])) {
  $id = intval($_GET['eliminar']);
  $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $mensaje = "🗑 Producto eliminado.";
}

$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ver Productos</title>
  <link rel="stylesheet" href="/style/adminDashboard.css">
  <link rel="stylesheet" href="/style/ver_producto.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<aside class="sidebar">
  <div class="logo">🛠 Admin</div>
  <nav>
    <a href="/../admin/dashboard.php"><i class="fas fa-home"></i> Inicio</a>
    <a href="crear_producto.php"><i class="fas fa-plus-circle"></i> Crear Producto</a>
    <a href="ver_producto.php" class="active"><i class="fas fa-boxes"></i> Ver Productos</a>
  </nav>
  <form class="logout" action="/php/logout.php" method="POST">
    <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
  </form>
</aside>

<main class="contenido">
  <h1>📦 Lista de Productos</h1>

  <?php if ($mensaje): ?>
    <div class="toast"><?= htmlspecialchars($mensaje) ?></div>
    <script> setTimeout(() => document.querySelector('.toast')?.remove(), 4000); </script>
  <?php endif; ?>

  <div class="productos">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="producto" 
           data-id="<?= $row['id'] ?>"
           data-nombre="<?= htmlspecialchars($row['nombre']) ?>"
           data-descripcion="<?= htmlspecialchars($row['descripcion']) ?>"
           data-precio="<?= $row['precio'] ?>">
        <?php if ($row['imagen']): ?>
          <img src="../uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
        <?php endif; ?>
        <h3><?= htmlspecialchars($row['nombre']) ?></h3>
        <p><?= htmlspecialchars($row['descripcion']) ?></p>
        <strong>$<?= number_format($row['precio'], 2) ?></strong>
        <div class="acciones">
          <button class="btn editar" onclick="abrirModal(this)"><i class="fas fa-edit"></i> Editar</button>
          <a href="?eliminar=<?= $row['id'] ?>" onclick="return confirm('¿Eliminar este producto?');" class="btn eliminar"><i class="fas fa-trash-alt"></i> Eliminar</a>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<!-- Modal de edición -->
<div class="modal" id="modalEditar">
  <div class="modal-contenido">
    <h2>✏️ Editar Producto</h2>
    <form method="POST">
      <input type="hidden" name="editar_id" id="editar_id">
      <label>Nombre</label>
      <input type="text" name="editar_nombre" id="editar_nombre" required>
      <label>Descripción</label>
      <textarea name="editar_descripcion" id="editar_descripcion"></textarea>
      <label>Precio</label>
      <input type="number" step="0.01" name="editar_precio" id="editar_precio" required>
      <div class="modal-botones">
        <button type="submit" class="btn guardar"><i class="fas fa-save"></i> Guardar</button>
        <button type="button" class="btn cerrar" onclick="cerrarModal()">Cancelar</button>
      </div>
    </form>
  </div>
</div>

<script>
  function abrirModal(boton) {
    const card = boton.closest('.producto');
    document.getElementById('editar_id').value = card.dataset.id;
    document.getElementById('editar_nombre').value = card.dataset.nombre;
    document.getElementById('editar_descripcion').value = card.dataset.descripcion;
    document.getElementById('editar_precio').value = card.dataset.precio;
    document.getElementById('modalEditar').style.display = 'flex';
  }

  function cerrarModal() {
    document.getElementById('modalEditar').style.display = 'none';
  }

  window.onclick = function(e) {
    if (e.target === document.getElementById('modalEditar')) {
      cerrarModal();
    }
  }
</script>

</body>
</html>
