<?php
// ============================================================
// PÁGINA DE LOGIN
// ------------------------------------------------------------
// Muestra el formulario de ingreso. Cuando el usuario envía sus
// credenciales, las verifica contra la base de datos y, si son
// correctas, guarda los datos en la sesión y entra al sistema.
// Esta página NO usa el layout del dashboard (no tiene sidebar).
// ============================================================

// En PHP, el protocolo HTTP (HyperText Transfer Protocol) es sin memoria. Cada
// vez que el navegador hace un request al servidor, el servidor no recuerda
// quien es el que hace el request. Esta función es la que activa el mecanismo
// de sesiones para solucionar eso.
// Crea o retoma una sesión a través de cookies en el navegador, y habilita el
// array $_SESSION para poder leer los valores de los usuarios del sistema.
session_start();

// Incluimos el archivo con la conexión y la función login().
require_once '../config/conexion.php';
$conexion = conexion();

$mensaje = "";
$class = "danger";

// Intentamos el login si se completó el formulario.
if (isset($_POST['btnAceptar'])) {
    $email = $_POST['email'];
    $contra = $_POST['password'];

    // login() devuelve los datos del usuario, o un array vacío si falla.
    $usuario = login($conexion, $email, $contra);

    if (!empty($usuario)) {
        // Credenciales correctas: guardamos cada dato por separado en la sesión.
        $_SESSION['idUsuario'] = $usuario['id'];
        $_SESSION['nombre']    = $usuario['nombre'];
        $_SESSION['apellido']  = $usuario['apellido'];
        $_SESSION['rol']       = $usuario['rol'];

        // Entramos al sistema; index.php redirige dependiendo el rol.
        header('Location: ../index.php');
        exit;
    } else {
        // Credenciales incorrectas: preparamos el mensaje de error.
        $mensaje = "Email o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Concesionaria</title>
    <!-- Estilos de la plantilla -->
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Concesionaria</h3></div>
                                <div class="card-body">

                                    <!-- Mensaje de error si el login falla -->
                                    <?php if ($mensaje != ""): ?>
                                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                                    <?php endif; ?>

                                    <!-- El formulario se envía a sí mismo (action vacío) -->
                                    <form method="post" action="">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputEmail" name="email" type="email" placeholder="name@example.com" required />
                                            <label for="inputEmail">Email</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Password" required />
                                            <label for="inputPassword">Contraseña</label>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end mt-4 mb-0">
                                            <button type="submit" name="btnAceptar" value="aceptar" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Ingresar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
</body>
</html>
