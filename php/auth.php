<?php
session_start();

function checkLogin() {
  if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit;
  }
}

function isAdmin() {
  return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1;
}

function isCliente() {
  return isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 2;
}
?>
