<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - Verificar Código</title>
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
                                <h2 class="h5 fw-normal text-muted">Verificar Código</h2>
                            </div>
                            
                           <?php if (isset($_GET['alert'])): ?>
    <div class="alert alert-<?= $_GET['alert'] ?>">
        <?= $_GET['message'] ?? '' ?>
    </div>
<?php endif; ?>
                            
                            <p class="text-muted small text-center mb-4">
                                Hemos enviado un código de 6 dígitos a tu correo electrónico. Por favor ingrésalo a continuación.
                            </p>
                            
                            <form action="?action=inicio&method=checkToken" method="POST">
                                <div class="mb-3">
                                    <input type="text" class="form-control border-light bg-light py-2 text-center" 
                                           name="token" id="token" placeholder="Código de 6 dígitos" 
                                           maxlength="6" required autofocus>
                                </div>
                                
                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn py-2 text-white" style="background: linear-gradient(to right, #ff4d4d, #ffb347);">
                                        Verificar Código
                                    </button>
                                </div>
                                
                                <div class="text-center">
                                    <a href="?action=inicio&method=forgotPassword" class="text-decoration-none small">
                                        No recibí el código
                                    </a>
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
        // Auto-focus y movimiento entre dígitos (mejora UX)
        document.getElementById('token').addEventListener('input', function(e) {
            if (this.value.length === 6) {
                document.querySelector('form').submit();
            }
        });
    </script>
</body>
</html>