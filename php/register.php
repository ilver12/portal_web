<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include 'db.php';

  $nombre = trim($_POST['nombre']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $rol_id = 2;

  if ($nombre && filter_var($email, FILTER_VALIDATE_EMAIL) && $_POST['password']) {
    $stmt = $conn->prepare("INSERT INTO clientes (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $email, $password, $rol_id);
    if ($stmt->execute()) {
      header("Location: login.php");
      exit;
    } else {
      $error = "Error al registrar usuario. Quizá el correo ya está en uso.";
    }
  } else {
    $error = "Completa todos los campos correctamente.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registro</title>
  <link rel="stylesheet" href="/../style/register.css" />
</head>
<body>
  <div class="registro-container">
    <h2>Crear cuenta</h2>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
      <input type="text" name="nombre" placeholder="Nombre completo" required />
      <input type="email" name="email" placeholder="Correo electrónico" required />
      <input type="password" name="password" placeholder="Contraseña" required />
      <button type="submit">Registrarse</button>
    </form>
    <p class="login-link">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
  </div>

  <!-- Ondas y burbujas -->
  <div class="ondas-svg">
    <svg viewBox="0 0 1440 320">
      <path fill="#ffffff33" fill-opacity="1" d="M0,64L40,69.3C80,75,160,85,240,117.3C320,149,400,203,480,202.7C560,203,640,149,720,128C800,107,880,117,960,117.3C1040,117,1120,107,1200,117.3C1280,128,1360,160,1400,176L1440,192L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z"></path>
    </svg>
  </div>

  <ul class="burbujas">
    <li></li><li></li><li></li><li></li><li></li>
    <li></li><li></li><li></li><li></li><li></li>
  </ul>
</body>
</html>
