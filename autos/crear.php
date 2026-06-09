<?php
// ============================================================
// CREAR AUTO (solo admin)
// ------------------------------------------------------------
// Muestra el formulario y, al enviarlo, inserta el auto nuevo.
// ============================================================

session_start();
// Si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

// Esta página es exclusiva para el admin: si no lo es, lo sacamos.
if ($_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/conexion.php';
$conexion = conexion();

$mensaje = "";
$class = "info";

// Intentamos crear el auto.
if (isset($_POST['btnAceptar'])) {
    if (crearAuto($conexion)) {
        $mensaje = "Auto creado correctamente.";
        $class = "success";
    } else {
        $mensaje = "Ocurrió un error al crear el auto.";
        $class = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Nuevo Auto - Concesionaria</title>
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
                    <h1 class="mt-4">Nuevo Auto</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="listar.php">Autos</a></li>
                        <li class="breadcrumb-item active">Nuevo</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-plus me-1"></i> Datos del Auto</div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Marca</label>
                                        <input type="text" name="marca" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Modelo</label>
                                        <input type="text" name="modelo" class="form-control" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Año</label>
                                        <input type="number" name="anio" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Precio</label>
                                        <input type="number" step="0.01" name="precio" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kilometraje</label>
                                        <input type="number" name="kilometraje" class="form-control" value="0" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Color</label>
                                        <input type="text" name="color" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Gama</label>
                                        <select name="gama" class="form-control" required>
                                            <option value="baja">baja</option>
                                            <option value="media">media</option>
                                            <option value="alta">alta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="disponible">disponible</option>
                                            <option value="reservado">reservado</option>
                                            <option value="vendido">vendido</option>
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
