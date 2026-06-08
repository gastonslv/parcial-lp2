<?php
// ============================================================
// PUNTO DE ENTRADA DEL SISTEMA (index.php)
// ------------------------------------------------------------
// No muestra contenido: solo decide a dónde mandar al usuario
// según su rol. Si no hay sesión activa, lo manda al login.
// ============================================================

session_start();

// Si no hay sesión iniciada, mandamos al login.
if (empty($_SESSION['idUsuario'])) {
    header('Location: auth/login.php');
    exit;
}

// Redirigimos según el rol del usuario logueado:
//   - admin       → gestión de usuarios
//   - vendedor_*  → listado de autos (que verá filtrado por su gama)
if ($_SESSION['rol'] == 'admin') {
    header('Location: usuarios/listar.php');
    exit;
} else {
    header('Location: autos/listar.php');
    exit;
}
?>
