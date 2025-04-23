<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Login - Plataforma Educativa</title>
    <link rel="icon" type="image/x-icon" href="/images/logo_unives.png"/>
    <link href="/layouts/collapsible-menu/css/light/loader.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/dark/loader.css" rel="stylesheet" />
    <script src="/layouts/collapsible-menu/loader.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="/src/bootstrap/css/bootstrap.rtl.min.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/light/plugins.css" rel="stylesheet" />
    <link href="/src/assets/css/light/authentication/auth-boxed.css" rel="stylesheet" />
    <link href="/layouts/collapsible-menu/css/dark/plugins.css" rel="stylesheet" />
    <link href="/src/assets/css/dark/authentication/auth-boxed.css" rel="stylesheet" />
    <style>
        body, html {
            height: 100%;
        }
        .auth-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body {
            padding: 2rem;
        }
        @media (max-width: 576px) {
            .card {
                margin: 1rem;
            }
        }
    </style>
</head>
<body class="form">
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>

    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-3 mb-3">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <center>
                                <img src="/images/logo_unives.png" style="height: 50px;">
                                <h2 class="mt-3">Iniciar sesión</h2>
                                </center>
                                <p class="text-muted">Ingresa tu correo y contraseña</p>
                            </div>
                            <?php if ($error): ?>
                                <div class="alert alert-danger text-center"> <?= htmlspecialchars($error) ?> </div>
                            <?php endif; ?>
                            <form method="POST" action="/auth/authenticate">
                                <div class="mb-3">
                                    <label class="form-label">Email &nbsp; &nbsp; &nbsp; &nbsp; </label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <br>
                                <div class="mb-4">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <br>
                               <center> <button type="submit" class="btn btn-primary w-100">Ingresar</button>  </center> 
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/src/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
