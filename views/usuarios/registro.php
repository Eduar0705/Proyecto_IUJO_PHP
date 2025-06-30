<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA - Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="shortcut icon" href="assets/img/Logo1.png" type="image/x-icon">
</head>
<body>
    <header class="admin-header">
        <?php include ('views/layout/header_Admin.php'); ?>
    </header>
     <div class="sidebar-container">
        
     <?php  include 'views/layout/menuAdmin.php'; ?>
    </div>
   <main class="main-content">
        <form method="POST" autocomplete="off" class="form-container">
            <input type="hidden" name="action" value="admin">  
            <input type="hidden" name="method" value="registrarUsuario">  
            <div class="form-logo">
                <img src="assets/img/Logo.jpg" alt="INVILARA Logo">
                <h1>INVILARA</h1>
                <p>Registro de usuario</p>
            </div>
            <div class="form-group">
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre completo" required>
            </div>
            <div class="form-group">
                <input type="text" name="username" id="username" class="form-control" placeholder="Nombre de usuario" required>
            </div>
            <div class="form-group">
                <input type="text" name="CI" id="CI" class="form-control" placeholder="Cédula de identidad" required>
            </div>
            <div class="form-group">
                <select name="cargo" id="cargo" class="form-control" required>
                    <option value="">Seleccione un cargo</option>
                    <option value="1">Administrador</option>
                    <option value="2">Usuario</option>
                </select>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
            </div>
            <div class="form-group">
                <input type="password" name="password2" id="password2" class="form-control" placeholder="Confirmar contraseña" required>
            </div>
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <button type="submit" name="registar" class="btn btn-submit"><center>Registrar usuario</center></button>
        </form>
    </main>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="assets/js/menu.js"></script>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="/js/script.js"></script>
</body>
</html>