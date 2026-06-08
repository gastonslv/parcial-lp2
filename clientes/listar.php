<?php
// ============================================================
// LISTADO DE CLIENTES
// ------------------------------------------------------------
// Accesible para todos los roles. Cualquier usuario puede ver,
// crear, editar y eliminar clientes.
// ============================================================

session_start();
// Portero: si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

require_once '../config/conexion.php';
$conexion = conexion();

// Variables para el mensaje de resultado.
$mensaje = "";
$class = "info";

// Traemos todos los clientes.
$clientes = listarClientes($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Clientes - Concesionaria</title>
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
                    <h1 class="mt-4">Gestión de Clientes</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Clientes</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-users me-1"></i> Listado de Clientes</div>
                            <!-- Todos los roles pueden crear clientes -->
                            <a href="crear.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nuevo Cliente
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorremos el array de clientes con un for clásico -->
                                    <?php for ($i = 0; $i < count($clientes); $i++): ?>
                                    <tr>
                                        <td><?= $clientes[$i]['id'] ?></td>
                                        <td><?= $clientes[$i]['nombre'] ?></td>
                                        <td><?= $clientes[$i]['apellido'] ?></td>
                                        <td><?= $clientes[$i]['telefono'] ?></td>
                                        <td><?= $clientes[$i]['email'] ?></td>
                                        <td>
                                            <a href="editar.php?id=<?= $clientes[$i]['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="eliminar.php?id=<?= $clientes[$i]['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Seguro que querés eliminar este cliente?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
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
