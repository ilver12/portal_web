<?php
session_start();
session_unset();    // Elimina todas las variables de sesión
session_destroy();  // Destruye la sesión
setcookie(session_name(), '', time() - 3600); // Borra la cookie de sesión

header("Location: /../php/login.php"); // Redirige al login
exit;