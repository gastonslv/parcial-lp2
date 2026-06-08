<?php
// ============================================================
// CREAR VENTA
// ------------------------------------------------------------
// Accesible para todos los roles. El usuario elige un cliente y un
// auto, y registra la venta. El auto se ofrece filtrado por la gama
// del vendedor (el admin puede elegir cualquier auto).
// El id_usuario se toma de la sesión, no del formulario.
// ============================================================

session_start();
// Portero: si no hay sesión activa, volvemos al login.
if (empty($_SESSION['idUsuario'])) { require_once '../auth/logout.php'; }

require_once '../config/conexion.php';
$conexion = conexion();

// Variables para el mensaje de resultado.
$mensaje = "";
$class = "info";

// Si llegó el formulario, registramos la venta.
// Pasamos el id del usuario logueado para que quede grabado quién la cargó.
if (isset($_POST['btnAceptar'])) {
    // Verificamos si el auto está libre antes de crear la venta.
    // Ejemplo: si alguien ya reservó el BMW Serie 3, no podemos
    // crear otra venta sobre ese mismo auto.
    $auto = buscarAuto($conexion, $_POST['id_auto']);
    if ($auto['estado'] != 'disponible') {
        $mensaje = "Este auto no está disponible para la venta. Estado actual: " . $auto['estado'];
        $class = "danger";
    } else {
        // Recién acá insertamos la venta; crearVenta() también reserva el auto.
        if (crearVenta($conexion, $_SESSION['idUsuario'])) {
            $mensaje = "Venta registrada correctamente.";
            $class = "success";
        } else {
            $mensaje = "Ocurrió un error al registrar la venta.";
            $class = "danger";
        }
    }
}

// Lista de clientes para el primer select.
$clientes = listarClientes($conexion);

// Cargamos los autos disponibles para mostrar en el formulario.
// El admin ve todos los disponibles; el vendedor solo los de su gama.
// Así no se ofrece un auto que ya está reservado o vendido.
if ($_SESSION['rol'] == 'admin') {
    // El admin ve todos los autos que estén disponibles.
    $consulta = "SELECT id, marca, modelo, gama FROM autos WHERE estado = 'disponible'";
} elseif ($_SESSION['rol'] == 'vendedor_baja') {
    $consulta = "SELECT id, marca, modelo, gama FROM autos WHERE estado = 'disponible' AND gama = 'baja'";
} elseif ($_SESSION['rol'] == 'vendedor_media') {
    $consulta = "SELECT id, marca, modelo, gama FROM autos WHERE estado = 'disponible' AND gama = 'media'";
} elseif ($_SESSION['rol'] == 'vendedor_alta') {
    $consulta = "SELECT id, marca, modelo, gama FROM autos WHERE estado = 'disponible' AND gama = 'alta'";
}
$rs = mysqli_query($conexion, $consulta);
$autosDisponibles = array();
$i = 0;
while ($data = mysqli_fetch_array($rs)) {
    $autosDisponibles[$i]['id']     = $data['id'];
    $autosDisponibles[$i]['marca']  = $data['marca'];
    $autosDisponibles[$i]['modelo'] = $data['modelo'];
    $autosDisponibles[$i]['gama']   = $data['gama'];
    $i++;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Nueva Venta - Concesionaria</title>
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
                    <h1 class="mt-4">Nueva Venta</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="listar.php">Ventas</a></li>
                        <li class="breadcrumb-item active">Nueva</li>
                    </ol>

                    <!-- Mensaje de resultado -->
                    <?php if ($mensaje != ""): ?>
                        <div class="alert alert-<?= $class ?>"><?= $mensaje ?></div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header"><i class="fas fa-plus me-1"></i> Datos de la Venta</div>
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Cliente</label>
                                        <select name="id_cliente" class="form-control" required>
                                            <option value="">-- Elegí un cliente --</option>
                                            <!-- Llenamos el select con todos los clientes -->
                                            <?php for ($i = 0; $i < count($clientes); $i++): ?>
                                            <option value="<?= $clientes[$i]['id'] ?>">
                                                <?= $clientes[$i]['nombre'] . ' ' . $clientes[$i]['apellido'] ?>
                                            </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Auto</label>
                                        <select name="id_auto" class="form-control" required>
                                            <option value="">Seleccione un auto...</option>
                                            <!-- Llenamos el select solo con los autos disponibles -->
                                            <?php for ($i = 0; $i < count($autosDisponibles); $i++): ?>
                                            <option value="<?= $autosDisponibles[$i]['id'] ?>">
                                                <?= $autosDisponibles[$i]['marca'] . ' ' . $autosDisponibles[$i]['modelo'] ?>
                                                (<?= $autosDisponibles[$i]['gama'] ?>)
                                            </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Estado</label>
                                        <select name="estado" class="form-control" required>
                                            <option value="interesado">interesado</option>
                                            <option value="reservado">reservado</option>
                                            <option value="vendido">vendido</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Observaciones</label>
                                        <textarea name="observaciones" class="form-control" rows="3"></textarea>
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
