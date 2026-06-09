<?php
// ============================================================
// ELIMINAR AUTO (solo admin)
// ------------------------------------------------------------
// Archivo de solo lógica: no muestra HTML. Borra el auto y vuelve
// al listado.
// ============================================================

session_start();
// Si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

// Página exclusiva del admin.
if ($_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/conexion.php';
$conexion = conexion();

// El ID del auto a eliminar llega por la URL (?id=).
$id = $_GET['id'];

// Borramos el auto y volvemos al listado.
eliminarAuto($conexion, $id);

header('Location: listar.php');
exit;
?>
