<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include 'db.php';

  $nombre = trim($_POST['nombre']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
  $rol_id = 2; // Cliente por defecto;

  // Validaciones básicas
  if ($nombre && filter_var($email, FILTER_VALIDATE_EMAIL) && $_POST['password']) {
    // Insertar en DB
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
<head><meta charset="UTF-8"><title>Registrarse</title></head>
<body>
<h2>Registrarse</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="">
  <input type="text" name="nombre" placeholder="Nombre completo" required /><br />
  <input type="email" name="email" placeholder="Correo electrónico" required /><br />
  <input type="password" name="password" placeholder="Contraseña" required /><br />
  <button type="submit">Registrarse</button>
</form>
<a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
</body>
</html>
