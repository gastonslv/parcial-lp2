<?php
// ============================================================
// LISTADO DE USUARIOS (solo admin)
// ------------------------------------------------------------
// Muestra todos los usuarios del sistema. Exclusivo del admin.
// ============================================================

session_start();
// Portero: si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

// Página exclusiva del admin.
if ($_SESSION['rol'] != 'admin') {
    header('Location: ../index.php');
    exit;
}

require_once '../config/conexion.php';
$conexion = conexion();

// Variables para el mensaje de resultado.
$mensaje = "";
$class = "info";

// Traemos todos los usuarios.
$usuarios = listarUsuarios($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Usuarios - Concesionaria</title>
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
                    <h1 class="mt-4">Gestión de Usuarios</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-user-cog me-1"></i> Listado de Usuarios</div>
                            <a href="crear.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nuevo Usuario
                            </a>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Apellido</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorremos el array de usuarios con un for clásico -->
                                    <?php for ($i = 0; $i < count($usuarios); $i++): ?>
                                    <tr>
                                        <td><?= $usuarios[$i]['id'] ?></td>
                                        <td><?= $usuarios[$i]['nombre'] ?></td>
                                        <td><?= $usuarios[$i]['apellido'] ?></td>
                                        <td><?= $usuarios[$i]['email'] ?></td>
                                        <td><?= $usuarios[$i]['rol'] ?></td>
                                        <td>
                                            <a href="editar.php?id=<?= $usuarios[$i]['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="eliminar.php?id=<?= $usuarios[$i]['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Seguro que querés eliminar este usuario?')">
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
