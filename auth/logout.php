<?php
// ============================================================
// CIERRE DE SESIÓN (logout)
// ------------------------------------------------------------
// Borra todos los datos de la sesión y manda al usuario de vuelta
// al login. También funciona como "portero": las páginas protegidas
// incluyen este archivo cuando detectan que no hay una sesión activa.
// ============================================================

session_start();   // Cargamos la sesión para poder borrarla
session_unset();   // Vaciamos todas las variables de sesión
session_destroy(); // Destrucción de la sesión

// Redirigimos a la página de login
header('Location: login.php');
// Siempre que se use header, ponemos exit para cortar el flujo de ejecución de
// PHP. Es como el switch con los break. Esto se hace para no causar comportamientos
// inesperados por parte de PHP.
exit;
?>
