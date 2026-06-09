<?php
// ============================================================
// ELIMINAR USUARIO (solo admin)
// ------------------------------------------------------------
// Archivo de solo lógica: no muestra HTML. Borra el usuario y
// vuelve al listado.
// ============================================================

session_start();
// Si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

if ($_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/conexion.php';
$conexion = conexion();

// URL
$id = $_GET['id'];

eliminarUsuario($conexion, $id);

header('Location: listar.php');
exit;
?>
