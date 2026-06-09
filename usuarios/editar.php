<?php
// ============================================================
// EDITAR USUARIO (solo admin)
// ------------------------------------------------------------
// Trae el usuario por su ID, precarga el formulario y guarda los
// cambios. No se edita la contraseña desde acá (modificarUsuario
// solo actualiza nombre, apellido, email y rol).
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

$mensaje = "";
$class = "info";

// URL
$id = $_GET['id'];

// Guardamos los cambios.
if (isset($_POST['btnAceptar'])) {
    if (modificarUsuario($conexion, $id)) {
        $mensaje = "Usuario modificado correctamente.";
        $class = "success";
    } else {
        $mensaje = "Ocurrió un error al modificar el usuario.";
        $class = "danger";
    }
}

// Buscamos el usuario para precargar el formulario.
$usuario = buscarUsuario($conexion, $id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Editar Usuario - Concesionaria</title>
    <link href="../css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <?php require_once '../includes/header.php'; ?>
    <div id="layoutSidenav">
        <?php require_once '../includes/sidebar.php'; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Editar Usuario</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="listar.php">Usuarios</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-edit me-1"></i> Datos del Usuario</div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre</label>
                                        <input type="text" name="nombre" class="form-control" value="<?= $usuario['nombre'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Apellido</label>
                                        <input type="text" name="apellido" class="form-control" value="<?= $usuario['apellido'] ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= $usuario['email'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Rol</label>
                                        <select name="rol" class="form-control" required>
                                            <!-- Marcamos como seleccionado el rol actual del usuario -->
                                            <option value="admin"          <?= $usuario['rol'] == 'admin'          ? 'selected' : '' ?>>admin</option>
                                            <option value="vendedor_baja"  <?= $usuario['rol'] == 'vendedor_baja'  ? 'selected' : '' ?>>vendedor_baja</option>
                                            <option value="vendedor_media" <?= $usuario['rol'] == 'vendedor_media' ? 'selected' : '' ?>>vendedor_media</option>
                                            <option value="vendedor_alta"  <?= $usuario['rol'] == 'vendedor_alta'  ? 'selected' : '' ?>>vendedor_alta</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" name="btnAceptar" value="aceptar" class="btn btn-primary">
                                    <i class="fas fa-check-circle"></i> Aceptar
                                </button>
                                <a href="listar.php" class="btn btn-secondary">
                                    <i class="fas fa-times-circle"></i> Cancelar
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once '../includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>
