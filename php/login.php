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
    // Verifica la contraseña con password_verify
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
<head><meta charset="UTF-8"><title>Iniciar sesión</title></head>
<body>
<h2>Iniciar sesión</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="">
  <input type="email" name="email" placeholder="Correo electrónico" required /><br />
  <input type="password" name="password" placeholder="Contraseña" required /><br />
  <button type="submit">Iniciar sesión</button>
</form>
<a href="register.php">¿No tienes cuenta? Regístrate</a>
</body>
</html>
