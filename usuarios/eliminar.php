<?php
// ============================================================
// ELIMINAR USUARIO (solo admin)
// ------------------------------------------------------------
// Archivo de solo lógica: no muestra HTML. Borra el usuario y
// vuelve al listado.
// ============================================================

session_start();
// Portero: si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

// Página exclusiva del admin.
if ($_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/conexion.php';
$conexion = conexion();

// El ID del usuario a eliminar llega por la URL (?id=...).
$id = $_GET['id'];

// Borramos el usuario y volvemos al listado.
eliminarUsuario($conexion, $id);

header('Location: listar.php');
exit;
?>
