<?php
// ============================================================
// EDITAR AUTO (solo admin)
// ------------------------------------------------------------
// Trae el auto por su ID, precarga el formulario y, al enviarlo,
// guarda los cambios.
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

$mensaje = "";
$class = "info";

// El ID del auto a editar llega por la URL (?id=).
$id = $_GET['id'];

// Si llegó el formulario, guardamos los cambios.
if (isset($_POST['btnAceptar'])) {
    if (modificarAuto($conexion, $id)) {
        $mensaje = "Auto modificado correctamente.";
        $class = "success";
    } else {
        $mensaje = "Ocurrió un error al modificar el auto.";
        $class = "danger";
    }
}

// Buscamos el auto para precargar el formulario con sus datos actuales.
$auto = buscarAuto($conexion, $id);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Editar Auto - Concesionaria</title>
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
                    <h1 class="mt-4">Editar Auto</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="listar.php">Autos</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-edit me-1"></i> Datos del Auto</div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Marca</label>
                                        <input type="text" name="marca" class="form-control" value="<?= $auto['marca'] ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Modelo</label>
                                        <input type="text" name="modelo" class="form-control" value="<?= $auto['modelo'] ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Año</label>
                                        <input type="number" name="anio" class="form-control" value="<?= $auto['anio'] ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Precio</label>
                                        <input type="number" step="0.01" name="precio" class="form-control" value="<?= $auto['precio'] ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kilometraje</label>
                                        <input type="number" name="kilometraje" class="form-control" value="<?= $auto['kilometraje'] ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Color</label>
                                        <input type="text" name="color" class="form-control" value="<?= $auto['color'] ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Gama</label>
                                        <select name="gama" class="form-control" required>
                                            <!-- Marcamos como seleccionada la gama actual del auto -->
                                            <option value="baja"  <?= $auto['gama'] == 'baja'  ? 'selected' : '' ?>>baja</option>
                                            <option value="media" <?= $auto['gama'] == 'media' ? 'selected' : '' ?>>media</option>
                                            <option value="alta"  <?= $auto['gama'] == 'alta'  ? 'selected' : '' ?>>alta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="disponible" <?= $auto['estado'] == 'disponible' ? 'selected' : '' ?>>disponible</option>
                                            <option value="reservado"  <?= $auto['estado'] == 'reservado'  ? 'selected' : '' ?>>reservado</option>
                                            <option value="vendido"    <?= $auto['estado'] == 'vendido'    ? 'selected' : '' ?>>vendido</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Botones al final: Aceptar y Cancelar -->
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
