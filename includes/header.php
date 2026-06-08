<!-- ============================================================
     BARRA SUPERIOR (navbar) — se incluye en todas las páginas del dashboard.
     Muestra el nombre del sistema, el botón para colapsar el sidebar
     y el nombre del usuario logueado con la opción de cerrar sesión.
     ============================================================ -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Nombre del sistema (lleva al inicio) -->
    <a class="navbar-brand ps-3" href="../index.php">Concesionaria</a>

    <!-- Botón que colapsa/expande el sidebar (lo maneja scripts.js) -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Usuario logueado, alineado a la derecha -->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
                <!-- Mostramos nombre y apellido guardados en la sesión -->
                <?= $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="../auth/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>
