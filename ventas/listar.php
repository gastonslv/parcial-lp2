<?php
// ============================================================
// LISTADO DE VENTAS
// ------------------------------------------------------------
// El admin ve TODAS las ventas; cada vendedor ve solo las que él
// mismo registró. Muestra cliente, auto, quién la cargó y el estado.
// ============================================================

session_start();
// Portero: si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

require_once '../config/conexion.php';
$conexion = conexion();

// Variables para el mensaje de resultado.
$mensaje = "";
$class = "info";

// Filtramos las ventas según el rol:
//   - admin    → ve todas las ventas
//   - vendedor → ve solo las suyas (las que cargó él)
if ($_SESSION['rol'] == 'admin') {
    $ventas = listarVentas($conexion);
} else {
    $ventas = listarVentasPorUsuario($conexion, $_SESSION['idUsuario']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Ventas - Concesionaria</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
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
                    <h1 class="mt-4">Gestión de Ventas</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Ventas</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-handshake me-1"></i> Listado de Ventas</div>
                            <!-- Todos los roles pueden registrar una venta -->
                            <a href="crear.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nueva Venta
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Auto</th>
                                        <th>Registró</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Observaciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorremos el array de ventas con un for clásico -->
                                    <?php for ($i = 0; $i < count($ventas); $i++): ?>
                                    <tr>
                                        <td><?= $ventas[$i]['id'] ?></td>
                                        <td><?= $ventas[$i]['cliente_nombre'] . ' ' . $ventas[$i]['cliente_apellido'] ?></td>
                                        <td><?= $ventas[$i]['marca'] . ' ' . $ventas[$i]['modelo'] ?></td>
                                        <td><?= $ventas[$i]['usuario_nombre'] . ' ' . $ventas[$i]['usuario_apellido'] ?></td>
                                        <td><?= $ventas[$i]['fecha'] ?></td>
                                        <td><?= $ventas[$i]['estado'] ?></td>
                                        <td><?= $ventas[$i]['observaciones'] ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php require_once '../includes/footer.php'; ?>
        </div>
    </div>
</body>
</html>
