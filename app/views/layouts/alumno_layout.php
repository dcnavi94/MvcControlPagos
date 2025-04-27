<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="es" class="sidebar-noneoverflow dark">
<head>
    <meta charset="UTF-8">
    <title>Portal del Alumno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ICONO -->
    <link rel="icon" type="image/x-icon" href="/images/logo_unives.png"/>

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

    <!-- Estilos principales -->
    <link href="/src/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/dark/plugins.css" rel="stylesheet" />
    <link href="/src/assets/css/dark/elements/alert.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" />

    <!-- Loader -->
    <script src="/layouts/collapsible-menu/loader.js"></script>

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        .logo-img { height: 40px; width: auto; object-fit: contain; }
        body.dark .layout-px-spacing, .layout-px-spacing {
            min-height: calc(100vh - 155px) !important;
        }
        /* Botón del Chatbot flotante */
        #chatbot-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.3);
            z-index: 999;
        }
        /* Ventana del chatbot (oculta al inicio) */
        #chatbot-window {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 300px;
            height: 400px;
            background-color: #1e1e2f;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 999;
        }
        #chatbot-header {
            background-color: #0d6efd;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }
        #chatbot-body {
            flex: 1;
            padding: 10px;
            color: white;
            overflow-y: auto;
            font-size: 14px;
        }
    </style>
</head>

<body class="layout-boxed dark">
    <!-- Loader -->
    <div id="load_screen"><div class="loader"><div class="loader-content"><div class="spinner-grow align-self-center"></div></div></div></div>

    <!-- NAVBAR -->
    <div class="header-container container-xxl">
        <header class="header navbar navbar-expand-sm bg-dark">
            <a href="/alumno/pagos" class="navbar-brand d-flex align-items-center">
                <img src="/images/logo_unives.png" alt="logo" class="logo-img">
                <span class="ms-2 text-white">Portal Alumno</span>
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/alumno/pagos">Mis Pagos</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-white" href="/alumno/perfil">Mi Perfil</a>
                    </li>

                    <li class="nav-item">
                        <div class="nav-icon-btn" id="btnNotificaciones" style="color: white; font-size: 20px; margin-right: 20px; cursor: pointer;">
                            🔔
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="/src/assets/img/profile-30.png" class="rounded-circle" width="30" height="30" alt="Avatar">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="/alumno/perfil">Perfil</a></li>
                            <li><a class="dropdown-item" href="/logout">Cerrar Sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
    </div>

    <!-- CONTENIDO -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="middle-content container-xxl p-0">
                <?php if (isset($content)) echo $content; ?>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer-wrapper text-center">
            <div class="footer-section">
                <p class="text-white mb-0">© <?= date('Y') ?> Metaeducación - Portal del Alumno</p>
                <p class="text-white">Hecho con ❤️</p>
            </div>
        </div>
    </div>

    <!-- BOTÓN flotante del Chatbot -->
    <button id="chatbot-btn">💬</button>

    <!-- Ventana del Chatbot -->
    <div id="chatbot-window">
        <div id="chatbot-header">Chat de Ayuda</div>
        <div id="chatbot-body">
            Hola 👋 ¿En qué puedo ayudarte?
        </div>
    </div>

    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/src/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- JS Personalizado -->
    <script src="/js/global.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Notificaciones
        document.getElementById('btnNotificaciones').addEventListener('click', function () {
            alert('🔔 No tienes notificaciones nuevas.');
        });

        // Chatbot toggle
        const chatbotBtn = document.getElementById('chatbot-btn');
        const chatbotWindow = document.getElementById('chatbot-window');

        chatbotBtn.addEventListener('click', () => {
            if (chatbotWindow.style.display === 'flex') {
                chatbotWindow.style.display = 'none';
            } else {
                chatbotWindow.style.display = 'flex';
            }
        });
    });
    </script>

</body>
</html>
