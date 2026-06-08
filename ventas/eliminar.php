<?php
// ============================================================
// ELIMINAR VENTA (solo admin)
// ------------------------------------------------------------
// Archivo de solo lógica: no muestra HTML. Borra la venta y vuelve
// al listado.
// ============================================================

session_start();
// Portero: si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

// Eliminar ventas es exclusivo del admin.
if ($_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/conexion.php';
$conexion = conexion();

// El ID de la venta a eliminar llega por la URL (?id=...).
$id = $_GET['id'];

// Borramos la venta y volvemos al listado.
eliminarVenta($conexion, $id);

header('Location: listar.php');
exit;
?>
