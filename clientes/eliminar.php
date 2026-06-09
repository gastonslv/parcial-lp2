<?php
// ============================================================
// ELIMINAR CLIENTE
// ------------------------------------------------------------
// Archivo de solo lógica: no muestra HTML. Borra el cliente y
// vuelve al listado. Accesible para todos los roles.
// ============================================================

session_start();
// Si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

require_once '../config/conexion.php';
$conexion = conexion();

// URL
$id = $_GET['id'];

// Borramos el cliente y volvemos al listado.
eliminarCliente($conexion, $id);

header('Location: listar.php');
exit;
?>
