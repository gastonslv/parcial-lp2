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

// Primero buscamos la venta para saber qué auto liberar.
$venta = buscarVenta($conexion, $_GET['id']);

if (!empty($venta)) {
    // Eliminamos la venta y, en la misma función, liberamos el auto
    // (lo dejamos como 'disponible').
    eliminarVenta($conexion, $_GET['id'], $venta['id_auto']);
}

header('Location: listar.php');
exit;
?>
