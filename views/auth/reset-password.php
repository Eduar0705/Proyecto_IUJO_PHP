<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <main class="flex-grow-1 d-flex align-items-center">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header p-1" style="background: linear-gradient(90deg, #ff4d4d, #ffb347);"></div>
                        
                        <div class="card-body p-4 p-md-5">
                            <div class="text-center mb-4">
                                <img src="assets/img/Logo1.png" alt="INVILARA" class="img-fluid mb-3" style="max-height: 70px;">
                                <h2 class="h5 fw-normal text-muted">Nueva Contraseña</h2>
                            </div>
                            
                            <?php if (isset($_GET['alert'])) : ?>
                                <div class="alert alert-<?= $_GET['alert'] ?>">
                                    <?= $_GET['message'] ?? '' ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="?action=inicio&method=updatePassword" method="POST">
                                <div class="mb-3">
                                    <label for="password" class="form-label small text-muted">Nueva Contraseña</label>
                                    <input type="password" class="form-control border-light bg-light py-2" 
                                           name="password" id="password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label small text-muted">Confirmar Contraseña</label>
                                    <input type="password" class="form-control border-light bg-light py-2" 
                                           name="confirm_password" id="confirm_password" required>
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn py-2 text-white" style="background: linear-gradient(to right, #ff4d4d, #ffb347);">
                                        Cambiar Contraseña
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
    <script>
        // Validación de contraseñas coincidentes
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
            
            // Podrías añadir más validaciones aquí (longitud mínima, etc.)
        });
    </script>
</body>
</html>