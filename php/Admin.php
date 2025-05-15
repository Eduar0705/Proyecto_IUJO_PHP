<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA-Admin</title>
    <link rel="stylesheet" href="../templates/admin.css">
    <link rel="shortcut icon" href="./templates/logo.jpg" type="image/x-icon">
</head>
<?php
include ('../funciones/funcion_inicio.php'); 
if (isset($_SESSION['nombre'])) {
    $nombre = htmlspecialchars($_SESSION['nombre']);
} else {
    $nombre = "Invitado";
}

?>
<body>
    <header>
        <nav>
            <div class="logo-header">
                <img src="../templates/logo.jpg" alt="INVILARA Logo">
                <h1>INVILARA</h1>
                <p><?php echo "Bienvenido " . $nombre; ?></p>
            </div>
            <ul>
                <li><a href="./Admin.php">Inicio</a></li>
                <li><a href="./registro.php">Registros de Usuarios</a></li>
                <li><a href="../index.php">Salir</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Bienvenido al Panel de Administración</h1>
        <p>Aquí puedes gestionar los registros de usuarios y otras configuraciones.</p>
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>
</html>