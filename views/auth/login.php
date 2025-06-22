<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? 'Inicio' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <main class="flex-grow-1 d-flex align-items-center">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <!-- Tarjeta principal -->
                    <div class="card border-0 shadow-sm mb-3">
                        <!-- Barra superior delgada -->
                        <div class="card-header p-1" style="background: linear-gradient(90deg, #ff4d4d, #ffb347);"></div>
                        
                        <div class="card-body p-4 p-md-5">
                            <!-- Logo centrado -->
                            <div class="text-center mb-4">
                                <img src="assets/img/Logo1.png" alt="INVILARA" class="img-fluid mb-3" style="max-height: 70px;">
                                <h2 class="h5 fw-normal text-muted">BIENVENIDOS</h2>
                            </div>
                            
                            <!-- Formulario -->
                            <form action="?action=inicio&method=loginAuthenticate" method="POST">
                                <div class="mb-3">
                                    <input type="text" class="form-control border-light bg-light py-2" id="user" name="user" placeholder="Usuario" required>
                                </div>
                                
                                <div class="mb-3">
                                    <input type="password" class="form-control border-light bg-light py-2" id="password" name="password" placeholder="Contraseña" required>
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" name="init" class="btn py-2 text-white" style="background: linear-gradient(to right, #ff4d4d, #ffb347);">
                                        Ingresar
                                    </button>
                                </div>
                                
                                <!-- Divisor -->
                                <div class="position-relative my-3">
                                    <hr style="border-top: 1px solidrgb(197, 196, 196); margin: 1rem 0;">
                                    <div class="position-absolute top-50 start-50 translate-middle bg-white px-2 text-muted small" style="font-size: 0.8rem;">O</div>
                                </div>
                                
                                <div class="text-center">
                                    <a href="?action=inicio&method=forgotPassword" class="text-decoration-none small d-block mb-2">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de registro -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-3 text-center">
                            <p class="mb-0 small">¿No tienes una cuenta? 
                                <a href="?action=inicio&method=register" class="text-decoration-none fw-bold" style="color: #ff4d4d;">
                                    Regístrate
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white py-3 mt-auto">
        <div class="container">
            <ul class="nav justify-content-center flex-column flex-sm-row mb-2">
                <li class="nav-item"><a href="?action=inicio" class="nav-link px-2 small text-muted">Inicio</a></li>
                <li class="nav-item"><a href="?action=projects" class="nav-link px-2 small text-muted">Proyectos</a></li>
                <li class="nav-item"><a href="?action=inicio&method=about" class="nav-link px-2 small text-muted">Nosotros</a></li>
                <li class="nav-item"><a href="?action=inicio&method=contact" class="nav-link px-2 small text-muted">Contacto</a></li>
            </ul>
            <p class="text-center text-muted small mb-0">&copy; <?= date('Y') ?> INVILARA</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>