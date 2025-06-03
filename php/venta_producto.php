<?php
require_once __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/auth.php';
checkLogin();
if (!isCliente()) die("Acceso denegado");

$cliente_id = $_SESSION['usuario_id'];
if (!$cliente_id) die("Error: cliente_id no definido");

function obtenerPrecio($producto_id) {
  global $conn;
  $stmt = $conn->prepare("SELECT precio FROM productos WHERE id = ?");
  $stmt->bind_param("i", $producto_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();
  return $row ? $row['precio'] : 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['accion'])) {
    if ($_POST['accion'] === 'agregar') {
      $producto_id = intval($_POST['producto_id']);
      $cantidad = max(1, intval($_POST['cantidad']));
      $precio = obtenerPrecio($producto_id);
      $subtotal = $precio * $cantidad;

      $check = $conn->prepare("SELECT id, cantidad FROM carrito WHERE cliente_id = ? AND producto_id = ?");
      $check->bind_param("ii", $cliente_id, $producto_id);
      $check->execute();
      $resultado = $check->get_result();

      if ($resultado->num_rows > 0) {
        $row = $resultado->fetch_assoc();
        $nueva_cantidad = $row['cantidad'] + $cantidad;
        $nuevo_total = $precio * $nueva_cantidad;
        $update = $conn->prepare("UPDATE carrito SET cantidad = ?, total = ? WHERE id = ?");
        $update->bind_param("idi", $nueva_cantidad, $nuevo_total, $row['id']);
        if (!$update->execute()) die("Error en UPDATE: " . $update->error);
      } else {
        $insert = $conn->prepare("INSERT INTO carrito (cliente_id, producto_id, cantidad, total, fecha_agregado) VALUES (?, ?, ?, ?, NOW())");
        $insert->bind_param("iiid", $cliente_id, $producto_id, $cantidad, $subtotal);
        if (!$insert->execute()) die("Error en INSERT: " . $insert->error);
      }
      exit(json_encode(["success" => true]));
    }

    if ($_POST['accion'] === 'eliminar') {
      $producto_id = intval($_POST['producto_id']);
      $delete = $conn->prepare("DELETE FROM carrito WHERE cliente_id = ? AND producto_id = ?");
      $delete->bind_param("ii", $cliente_id, $producto_id);
      if (!$delete->execute()) die("Error al eliminar: " . $delete->error);
      exit(json_encode(["success" => true]));
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['accion']) && $_GET['accion'] === 'carrito') {
  $query = $conn->prepare("SELECT c.producto_id, c.cantidad, c.total, p.nombre, p.precio FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.cliente_id = ?");
  $query->bind_param("i", $cliente_id);
  $query->execute();
  $result = $query->get_result();

  $items = [];
  $total = 0;
  while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['total'];
    $items[] = $row;
    $total += $row['subtotal'];
  }
  exit(json_encode(["items" => $items, "total" => $total]));
}

$result = $conn->query("SELECT * FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Comprar Productos</title>
  <link rel="stylesheet" href="/../style/adminDashboard.css?v=<?= time() ?>">
  <link rel="stylesheet" href="/../style/ver_producto.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<aside class="sidebar">
  <div class="logo">🛍 Cliente</div>
  <nav>
    <a href="#"><i class="fas fa-store"></i> Ver Productos</a>
  </nav>
  <form class="logout" action="/php/logout.php" method="POST">
    <button type="submit"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</button>
  </form>
</aside>

<main class="contenido">
  <h1>🛍 Productos en venta</h1>
  <div class="productos">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="producto">
        <?php if ($row['imagen']): ?>
          <img src="../uploads/<?= htmlspecialchars($row['imagen']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
        <?php endif; ?>
        <h3><?= htmlspecialchars($row['nombre']) ?></h3>
        <p><?= htmlspecialchars($row['descripcion']) ?></p>
        <strong>$<?= number_format($row['precio'], 2) ?></strong>
        <input type="number" id="cantidad-<?= $row['id'] ?>" min="1" value="1" style="width: 50px;">
        <button onclick="agregarAlCarrito(<?= $row['id'] ?>)">
          <i class="fas fa-cart-plus"></i> Agregar
        </button>
      </div>
    <?php endwhile; ?>
  </div>
</main>

<!-- Carrito flotante -->
<div class="carrito-flotante" onclick="toggleMiniCarrito()">
  <i class="fas fa-shopping-cart"></i>
  <span id="contador-carrito">0</span>
</div>

<!-- Mini resumen de carrito -->
<div id="mini-carrito" class="mini-carrito oculto">
  <h4>📟 Tu carrito</h4>
  <ul id="lista-carrito"></ul>
  <p>Total: $<span id="total-pagar">0.00</span></p>
  <button onclick="simularPago()">Pagar</button>
</div>

<script>
function agregarAlCarrito(id) {
  const cantidad = document.getElementById('cantidad-' + id).value;
  fetch(window.location.href, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `accion=agregar&producto_id=${id}&cantidad=${cantidad}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      actualizarCarrito();
      Swal.fire({
        icon: 'success',
        title: 'Producto agregado',
        text: 'El producto se agregó al carrito correctamente',
        timer: 1500,
        showConfirmButton: false
      });
    }
  });
}

function eliminarDelCarrito(id) {
  fetch(window.location.href, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `accion=eliminar&producto_id=${id}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) actualizarCarrito();
  });
}

function actualizarCarrito() {
  fetch(window.location.href + '?accion=carrito')
    .then(res => res.json())
    .then(data => {
      document.getElementById('contador-carrito').textContent = data.items.length;
      document.getElementById('lista-carrito').innerHTML = '';
      data.items.forEach(item => {
        const li = document.createElement('li');
        li.innerHTML = `
          ${item.nombre} x${item.cantidad} - $${item.subtotal.toFixed(2)}
          <button onclick="eliminarDelCarrito(${item.producto_id})" style="margin-left:10px;color:red;">🗑</button>
        `;
        document.getElementById('lista-carrito').appendChild(li);
      });
      document.getElementById('total-pagar').textContent = data.total.toFixed(2);
    });
}

function toggleMiniCarrito() {
  document.getElementById('mini-carrito').classList.toggle('oculto');
}

function simularPago() {
  Swal.fire({
    title: '¿Deseas finalizar la compra?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, pagar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      // Limpiar solo en pantalla
      document.getElementById('lista-carrito').innerHTML = '';
      document.getElementById('total-pagar').textContent = '0.00';
      document.getElementById('contador-carrito').textContent = '0';
      Swal.fire({
        icon: 'success',
        title: '🎉 ¡Gracias por tu compra!',
        showConfirmButton: false,
        timer: 2000
      });
      document.getElementById('mini-carrito').classList.add('oculto');
    }
  });
}

document.addEventListener('DOMContentLoaded', actualizarCarrito);
</script>

</body>
</html>
