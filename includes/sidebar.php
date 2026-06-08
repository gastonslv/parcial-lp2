<!-- ============================================================
     MENÚ LATERAL (sidebar) — se incluye en todas las páginas del dashboard.
     Muestra los datos del usuario logueado y los links de navegación.
     La sección "Usuarios" solo aparece si el rol es admin.
     ============================================================ -->
<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <!-- Datos del usuario logueado (nombre completo + rol) -->
                <div class="sb-sidenav-menu-heading">Usuario</div>
                <div class="px-3 py-2 text-white small">
                    <i class="fas fa-user-circle fa-2x mb-1"></i><br>
                    <?= $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?><br>
                    <span class="badge bg-secondary"><?= $_SESSION['rol'] ?></span>
                </div>

                <!-- Links de navegación visibles para todos los roles -->
                <div class="sb-sidenav-menu-heading">Gestión</div>

                <a class="nav-link" href="../autos/listar.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-car"></i></div>
                    Autos
                </a>

                <a class="nav-link" href="../clientes/listar.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Clientes
                </a>

                <a class="nav-link" href="../ventas/listar.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-handshake"></i></div>
                    Ventas
                </a>

                <!-- La gestión de usuarios es exclusiva del administrador -->
                <?php if ($_SESSION['rol'] == 'admin'): ?>
                <a class="nav-link" href="../usuarios/listar.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div>
                    Usuarios
                </a>
                <?php endif; ?>

            </div>
        </div>

        <!-- Pie del sidebar: recuerda quién tiene la sesión activa -->
        <div class="sb-sidenav-footer">
            <div class="small">Sesión activa:</div>
            <?= $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?>
        </div>
    </nav>
</div>
