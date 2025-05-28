<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA-Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="shortcut icon" href="./img/logo.jpg" type="image/x-icon">
</head>
<body>
    <?php
    if (isset($_SESSION['nombre'])) {
        $nombre = htmlspecialchars($_SESSION['nombre']);
    } else {
        $nombre = "Invitado";
    }
    ?>
    <header class="admin-header">
        <?php include ('./inc/nav_Admin.php'); ?>
    </header>
    <div class="sidebar-container">
        <?php include ('./inc/menu_admin.php'); ?>
    </div>
    
    <main class="main-content">
        <form method="post" autocomplete="off">
            <div class="logo">
                <img src="./img/logo.jpg" alt="INVILARA Logo">
                <h1>INVILARA</h1>
            </div>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre">
            <input type="text" name="username" id="username" placeholder="Usuario">
            <input type="text" name="CI" id="CI" placeholder="Cedula">
            <select name="cargo" id="cargo">
                <option value="">Seleccione un cargo</option>
                <option value="1">1 - Administración</option>
                <option value="2">2 - Usuario</option>
            </select>
            <input type="password" name="password" id="password" placeholder="Contraseña">
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="submit" value="Registar" name="registar">
        </form>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/menu.js"></script>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src=""></script>
</body>
</html>