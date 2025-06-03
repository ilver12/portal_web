<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $passwordInput = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM clientes WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($user = $res->fetch_assoc()) {
    if (password_verify($passwordInput, $user['password'])) {
      $_SESSION['usuario_id'] = $user['id'];
      $_SESSION['nombre'] = $user['nombre'];
      $_SESSION['rol_id'] = $user['rol_id'];

      if ($user['rol_id'] == 1) {
        header("Location: /../admin/dashboard.php");
      } else {
        header("Location: /../cliente/dashboard.php");
      }
      exit;
    } else {
      $error = "Contraseña incorrecta";
    }
  } else {
    $error = "Correo no registrado";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="/../style/login.css" />
</head>
<body>
  <div class="login-container">
    <h2>Bienvenido de nuevo</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
      <input type="email" name="email" placeholder="Correo electrónico" required />
      <input type="password" name="password" placeholder="Contraseña" required />
      <button type="submit">Iniciar sesión</button>
    </form>
    <p class="registro-link">¿No tienes cuenta? <a href="register.php">Regístrate</a></p>
  </div>

  <!-- Fondo animado -->
  <div class="ondas-svg">
    <svg viewBox="0 0 1440 320">
      <path fill="#ffffff33" fill-opacity="1" d="M0,160L40,170.7C80,181,160,203,240,186.7C320,171,400,117,480,117.3C560,117,640,171,720,186.7C800,203,880,181,960,176C1040,171,1120,181,1200,192C1280,203,1360,213,1400,218.7L1440,224L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
    </svg>
  </div>

  <ul class="burbujas">
    <li></li><li></li><li></li><li></li><li></li>
    <li></li><li></li><li></li><li></li><li></li>
  </ul>
</body>
</html>
