<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?: 'Nueva Solicitud' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/solicitud-form.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body>
    <!-- Header -->
    <header class="sys-navbar">
        <?php include 'views/layout/header_User.php'; ?>
    </header>

    <main class="sys-main">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9">
                    <nav aria-label="breadcrumb" class="sys-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="?action=users">
                                    <i class="fas fa-home me-1"></i>Inicio
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-file-alt me-1"></i>Nueva Solicitud
                            </li>
                        </ol>
                    </nav>

                    <!-- Form Card -->
                    <div class="sys-form-card">
                        <div class="sys-form-header">
                            <div class="d-flex align-items-center">
                                <div class="sys-form-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h1 class="sys-form-title"><?= $title ?: 'Nueva Solicitud' ?></h1>
                                    <p class="sys-form-subtitle">Complete el formulario para enviar su solicitud</p>
                                </div>
                            </div>
                        </div>

                        <div class="sys-form-body">
                            <?php if (isset($error)): ?>
                                <div class="sys-alert sys-alert--danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            <?php endif; ?>

                            <form method="post" id="solicitudForm" action="?action=users&method=envioSolicitud" class="sys-form">
                                <!-- Título común -->
                                <div class="sys-form-group">
                                    <label class="sys-form-label">
                                        <i class="fas fa-heading me-2"></i>Título de la solicitud
                                        <span class="sys-required">*</span>
                                    </label>
                                    <input type="text" 
                                            name="titulo" 
                                            id="titulo"
                                            class="sys-form-control sys-form-control--lg" 
                                            placeholder="Ej: Solicitud de material para oficina" 
                                            required>
                                    <div class="sys-form-help">
                                        Proporcione un título descriptivo para su solicitud
                                    </div>
                                </div>
                                <!-- nombre del solicitante -->
                                <div class="sys-form-group">
                                    <label class="sys-form-label">
                                        <i class="fas fa-user me-2"></i>Nombre del solicitante
                                        <span class="sys-required">*</span>
                                    </label>
                                    <input type="text" 
                                            name="nombre"
                                            id="nombre" 
                                            class="sys-form-control sys-form-control--lg" 
                                            placeholder="Ej: Arturo Roman" 
                                            required>
                                    <div class="sys-form-help">
                                        Proporcione el nombre completo del solicitante
                                    </div>
                                </div>

                                <!-- Tipo de solicitud -->
                                <div class="sys-form-group">
                                    <label class="sys-form-label">
                                        <i class="fas fa-list me-2"></i>Tipo de solicitud
                                        <span class="sys-required">*</span>
                                    </label>
                                    <select name="tipoSolicitud" id="tipoSolicitud" class="sys-form-control sys-form-control--lg" required>
                                        <option value="">Seleccione un tipo...</option>
                                        <option value="oficina"> Material de Oficina</option>
                                        <option value="comida"> Material de alimentos</option>
                                        <option value="proyecto"></i> Proyecto</option>
                                    </select>
                                </div>
                                <div id="camposDinamicos" class="sys-dynamic-fields"><!-- Campos dinámicos -->
                                </div>
                                <div class="sys-form-actions">
                                    <a href="?action=users" class="sys-btn sys-btn--secondary">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                </div>
                                <button type="submit" class="sys-btn sys-btn--primary">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                                    </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="sys-loading-overlay">
        <div class="sys-loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Procesando solicitud...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/solicitud-form.js"></script>
</body>
</html>
