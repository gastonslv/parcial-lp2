<?php
// ============================================================
// CIERRE DE SESIÓN (logout)
// ------------------------------------------------------------
// Borra todos los datos de la sesión y manda al usuario de vuelta
// al login. También funciona como "portero": las páginas protegidas
// incluyen este archivo cuando detectan que no hay una sesión activa.
// ============================================================

session_start();   // Reanudamos la sesión para poder borrarla
session_unset();   // Vaciamos todas las variables de sesión
session_destroy(); // Destruimos la sesión por completo

// Redirigimos a la página de login.
header('Location: login.php');
exit; // Cortamos la ejecución para que no siga corriendo nada más
?>
