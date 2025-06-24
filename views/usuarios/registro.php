<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA-Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../templates/admin.css">
    <link rel="shortcut icon" href="../templates/logo1.png" type="image/x-icon">
</head>
<body>
    <?php 
    include ('../funciones/funcion_registro.php');
    include ('../funciones/funcion_inicio.php'); 

    if (isset($_SESSION['nombre'])) {
        $nombre = htmlspecialchars($_SESSION['nombre']);
    } else {
        $nombre = "Invitado";
    }
    ?>
    <header class="admin-header">
        <?php include ('./inc/nav_admin.php'); ?>
    </header>
     <div class="sidebar-container">
        
     <?php  include './inc/menu_admin.php'; ?>
    </div>
   <main class="main-content">
        <form method="post" autocomplete="off" class="form-container">
            <div class="form-logo">
                <img src="../templates/logo.jpg" alt="INVILARA Logo">
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
                <input type="email" name="email" id="email" class="form-control" placeholder="Correo electrónico" required>
            </div>
            <button type="submit" name="registar" class="btn btn-submit"><center>Registrar usuario</center></button>
        </form>
    </main>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="./inc/menu.js"></script>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="../js/script.js"></script>
</body>
</html>