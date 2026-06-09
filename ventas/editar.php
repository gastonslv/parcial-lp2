<?php
// ============================================================
// EDITAR VENTA (con permisos según rol)
// ------------------------------------------------------------
// - El admin puede modificar todo: cliente, auto, estado y observaciones.
// - El vendedor solo puede modificar estado y observaciones, y únicamente
//   en SUS propias ventas (no puede tocar las de otros vendedores).
// ============================================================

session_start();
// Si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

require_once '../config/conexion.php';
$conexion = conexion();

$mensaje = "";
$class = "info";

$venta = buscarVenta($conexion, $_GET['id']);

// Si no existe la venta, volvemos al listado.
if (empty($venta)) {
    header('Location: listar.php');
    exit;
}

// Si es vendedor, verificamos que la venta sea suya.
// Ejemplo: si idUsuario en sesión es 2 (Laura) y la venta tiene
// id_usuario = 3 (Diego), Laura no debería poder editarla
if ($_SESSION['rol'] != 'admin') {
    if ($venta['id_usuario'] != $_SESSION['idUsuario']) {
        header('Location: listar.php');
        exit;
    }
}

// Guardamos los cambios según el rol.
if (isset($_POST['btnAceptar'])) {
    if ($_SESSION['rol'] == 'admin') {
        // El admin puede modificar todo. Le pasamos el id_auto para que
        // se pueda sincronizar el estado del auto vinculado.
        $resultado = modificarVentaCompleta($conexion, $_GET['id'], $venta['id_auto']);
    } else {
        // El vendedor solo puede modificar estado y observaciones.
        $resultado = modificarVentaParcial($conexion, $_GET['id'], $venta['id_auto']);
    }

    if ($resultado) {
        $mensaje = "Venta actualizada correctamente.";
        $class = "success";
    } else {
        $mensaje = "Error al actualizar la venta.";
        $class = "danger";
    }

    // Volvemos a buscar la venta para mostrar los datos actualizados.
    $venta = buscarVenta($conexion, $_GET['id']);
}

// Si el que edita es admin, necesitamos las listas para los select
// de cliente y auto. El admin elige cualquier auto.
if ($_SESSION['rol'] == 'admin') {
    $clientes = listarClientes($conexion);
    $autos = listarAutos($conexion);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Editar Venta - Concesionaria</title>
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
                    <h1 class="mt-4">Editar Venta</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="listar.php">Ventas</a></li>
                        <li class="breadcrumb-item active">Editar</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-edit me-1"></i> Datos de la Venta</div>
                        <div class="card-body">
                            <form method="post" action="">

                                <!-- CLIENTE: editable solo para admin, bloqueado para vendedor -->
                                <div class="mb-3">
                                    <label class="form-label">Cliente</label>
                                    <?php if ($_SESSION['rol'] == 'admin'): ?>
                                        <!-- Admin: select con todos los clientes -->
                                        <select name="id_cliente" class="form-control">
                                            <?php for ($i = 0; $i < count($clientes); $i++): ?>
                                                <option value="<?= $clientes[$i]['id'] ?>"
                                                    <?php if ($clientes[$i]['id'] == $venta['id_cliente']) echo 'selected'; ?>>
                                                    <?= $clientes[$i]['nombre'] . ' ' . $clientes[$i]['apellido'] ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    <?php else: ?>
                                        <!-- Vendedor: solo lectura, no puede cambiarlo -->
                                        <input type="text" class="form-control"
                                               value="<?= $venta['cliente_nombre'] . ' ' . $venta['cliente_apellido'] ?>"
                                               disabled>
                                        <!-- Campo oculto para que el valor se envíe igual aunque esté disabled -->
                                        <input type="hidden" name="id_cliente" value="<?= $venta['id_cliente'] ?>">
                                    <?php endif; ?>
                                </div>

                                <!-- AUTO: editable solo para admin, bloqueado para vendedor -->
                                <div class="mb-3">
                                    <label class="form-label">Auto</label>
                                    <?php if ($_SESSION['rol'] == 'admin'): ?>
                                        <select name="id_auto" class="form-control">
                                            <?php for ($i = 0; $i < count($autos); $i++): ?>
                                                <option value="<?= $autos[$i]['id'] ?>"
                                                    <?php if ($autos[$i]['id'] == $venta['id_auto']) echo 'selected'; ?>>
                                                    <?= $autos[$i]['marca'] . ' ' . $autos[$i]['modelo'] ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    <?php else: ?>
                                        <input type="text" class="form-control"
                                               value="<?= $venta['marca'] . ' ' . $venta['modelo'] ?>"
                                               disabled>
                                        <input type="hidden" name="id_auto" value="<?= $venta['id_auto'] ?>">
                                    <?php endif; ?>
                                </div>

                                <!-- ESTADO: editable para todos -->
                                <div class="mb-3">
                                    <label class="form-label">Estado</label>
                                    <select name="estado" class="form-control">
                                        <option value="interesado"  <?php if ($venta['estado'] == 'interesado')  echo 'selected'; ?>>Interesado</option>
                                        <option value="reservado"   <?php if ($venta['estado'] == 'reservado')   echo 'selected'; ?>>Reservado</option>
                                        <option value="vendido"     <?php if ($venta['estado'] == 'vendido')     echo 'selected'; ?>>Vendido</option>
                                    </select>
                                </div>

                                <!-- OBSERVACIONES: editable para todos -->
                                <div class="mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3"><?= $venta['observaciones'] ?></textarea>
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
