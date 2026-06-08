<?php
// ============================================================
// LISTADO DE AUTOS
// ------------------------------------------------------------
// El admin ve TODOS los autos; cada vendedor ve solo los de su gama.
// Los botones de crear/editar/eliminar solo aparecen para el admin.
// ============================================================

session_start();
// Portero: si no hay sesión activa, cerramos sesión y volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

// Archivo con la conexión y las funciones de autos.
require_once '../config/conexion.php';
$conexion = conexion();

// Variables para el mensaje de resultado (lo usa, por ejemplo, eliminar.php).
$mensaje = "";
$class = "info";

// Filtramos los autos según el rol del usuario logueado.
// Ejemplo: un "vendedor_baja" solo verá los autos de gama 'baja'
// (Onix, Sandero, etc.); el admin los ve todos.
if ($_SESSION['rol'] == 'admin') {
    $autos = listarAutos($conexion);
} elseif ($_SESSION['rol'] == 'vendedor_baja') {
    $autos = listarAutosPorGama($conexion, 'baja');
} elseif ($_SESSION['rol'] == 'vendedor_media') {
    $autos = listarAutosPorGama($conexion, 'media');
} elseif ($_SESSION['rol'] == 'vendedor_alta') {
    $autos = listarAutosPorGama($conexion, 'alta');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Autos - Concesionaria</title>
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
                    <h1 class="mt-4">Gestión de Autos</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Autos</li>
                    </ol>

                    <!-- Mensaje de resultado (éxito o error) -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-car me-1"></i> Listado de Autos</div>
                            <!-- El botón "Nuevo" es exclusivo del admin -->
                            <?php if ($_SESSION['rol'] == 'admin'): ?>
                            <a href="crear.php" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Nuevo Auto
                            </a>
                            <?php endif; ?>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Año</th>
                                        <th>Precio</th>
                                        <th>Color</th>
                                        <th>Km</th>
                                        <th>Gama</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Recorremos el array de autos con un for clásico -->
                                    <?php for ($i = 0; $i < count($autos); $i++): ?>
                                    <tr>
                                        <td><?= $autos[$i]['id'] ?></td>
                                        <td><?= $autos[$i]['marca'] ?></td>
                                        <td><?= $autos[$i]['modelo'] ?></td>
                                        <td><?= $autos[$i]['anio'] ?></td>
                                        <td><?= $autos[$i]['precio'] ?></td>
                                        <td><?= $autos[$i]['color'] ?></td>
                                        <td><?= $autos[$i]['kilometraje'] ?></td>
                                        <td><?= $autos[$i]['gama'] ?></td>
                                        <td>
                                            <?php
                                            // Mostramos el estado del auto con un color según su situación:
                                            // verde = disponible, amarillo = reservado, rojo = vendido.
                                            $badgeEstado = 'secondary'; // color por defecto
                                            if ($autos[$i]['estado'] == 'disponible') $badgeEstado = 'success';
                                            if ($autos[$i]['estado'] == 'reservado')  $badgeEstado = 'warning';
                                            if ($autos[$i]['estado'] == 'vendido')    $badgeEstado = 'danger';
                                            ?>
                                            <span class="badge bg-<?= $badgeEstado ?>">
                                                <?= $autos[$i]['estado'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <!-- Editar y eliminar solo para el admin -->
                                            <?php if ($_SESSION['rol'] == 'admin'): ?>
                                            <a href="editar.php?id=<?= $autos[$i]['id'] ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="eliminar.php?id=<?= $autos[$i]['id'] ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('¿Seguro que querés eliminar este auto?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php endif; ?>
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
