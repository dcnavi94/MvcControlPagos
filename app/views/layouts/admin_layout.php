<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es" class="sidebar-noneoverflow">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ICONO -->
    <link rel="icon" type="image/x-icon" href="/images/logo_unives.png"/>

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- Estilos principales -->
    <link href="/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/light/plugins.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/dark/plugins.css" rel="stylesheet" />
    <link href="/src/assets/css/light/elements/alert.css" rel="stylesheet" />
    <link href="/src/assets/css/dark/elements/alert.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" />

    <!-- Loader -->
    <script src="/layouts/collapsible-menu/loader.js"></script>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <style>
        .logo-img { height: 40px; width: auto; object-fit: contain; }
        .sbar-closed .menu-text { display: none !important; }
        .sbar-closed .sidebar-wrapper { display: none !important; }
        .sbar-open .sidebar-wrapper { display: block; }
        .sbar-closed #content { margin-left: 0 !important; width: 100%; }
        .menu-dropdown { display: none; }

        @media (max-width: 992px) {
            .sidebar-wrapper { display: none !important; }
            #content { margin-left: 0 !important; width: 100% !important; }
            .menu-dropdown { display: inline-block !important; }
        }

        body.dark .layout-px-spacing, .layout-px-spacing {
            min-height: calc(100vh - 155px) !important;
        }
    </style>
</head>

<body class="alt-menu layout-boxed sbar-open">
    <!-- Loader -->
    <div id="load_screen"><div class="loader"><div class="loader-content"><div class="spinner-grow align-self-center"></div></div></div></div>

    <!-- NAVBAR -->
    <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" id="toggleSidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="feather" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </a>

            <!-- Menú responsive -->
            <div class="dropdown ms-3 menu-dropdown">
                <a class="btn btn-outline-light dropdown-toggle" href="#" data-bs-toggle="dropdown">Menú</a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="/dashboard/admin">Dashboard</a></li>
                    <li><a class="dropdown-item" href="/dashboard/usuarios">Usuarios</a></li>
                    <li><a class="dropdown-item" href="/dashboard/pagos">Pagos</a></li>
                </ul>
            </div>

            <ul class="navbar-item flex-row ms-auto">
                <li class="nav-item dropdown user-profile-dropdown">
                    <a class="nav-link dropdown-toggle user" data-bs-toggle="dropdown">
                        <div class="avatar avatar-sm">
                            <img src="/src/assets/img/profile-30.png" class="rounded-circle" />
                        </div>
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="/dashboard/perfil"><span>Perfil</span></a>
                        <a class="dropdown-item" href="/auth/logout"><span>Salir</span></a>
                    </div>
                </li>
            </ul>
        </header>
    </div>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="main-container sbar-open" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!-- SIDEBAR -->
        <div class="sidebar-wrapper sidebar-theme">
            <nav id="sidebar">
                <br>
                <center>
                    <img src="/images/logo_unives.png" alt="logo" class="logo-img">
                    <a href="/dashboard/admin" class="navbar-brand text-truncate ms-2" style="color: white; max-width: 200px;">
                        <h6 class="mb-0">Metaeducacion</h6>
                    </a>
                </center>

                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu">
                        <a href="/dashboard/admin" class="dropdown-toggle">
                            <div class="d-flex align-items-center"><i class="feather feather-home"></i><span class="menu-text ms-2">Dashboard</span></div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="/dashboard/usuarios" class="dropdown-toggle">
                            <div class="d-flex align-items-center"><i class="feather feather-users"></i><span class="menu-text ms-2">Usuarios</span></div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="/dashboard/pagos" class="dropdown-toggle">
                            <div class="d-flex align-items-center"><i class="feather feather-credit-card"></i><span class="menu-text ms-2">Pagos</span></div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- CONTENIDO -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="middle-content container-xxl p-0">
                    <?php if (isset($content)) echo $content; ?>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p>© <?= date('Y') ?> Plataforma, Todos los derechos reservados.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p>Hecho con ❤️</p>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPTS ORDENADOS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/src/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <!-- JS Personalizados -->
    <script src="/js/global.js"></script>
    <script src="/js/forms.js"></script>
    <script src="/js/usuarios.js"></script>
    <script src="/js/pagos.js"></script>

    <!-- Sidebar Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('container');
            const toggleBtn = document.getElementById('toggleSidebar');

            function updateSidebarState() {
                if (window.innerWidth < 992) {
                    container.classList.remove('sbar-open');
                    container.classList.add('sbar-closed');
                } else {
                    const saved = localStorage.getItem('sidebarClosed');
                    if (saved === '1') {
                        container.classList.remove('sbar-open');
                        container.classList.add('sbar-closed');
                    } else {
                        container.classList.add('sbar-open');
                        container.classList.remove('sbar-closed');
                    }
                }
            }

            updateSidebarState();
            window.addEventListener('resize', updateSidebarState);

            toggleBtn.addEventListener('click', () => {
                container.classList.toggle('sbar-open');
                container.classList.toggle('sbar-closed');
                const isClosed = container.classList.contains('sbar-closed');
                localStorage.setItem('sidebarClosed', isClosed ? '1' : '0');
            });
        });
    </script>
</body>
</html>
