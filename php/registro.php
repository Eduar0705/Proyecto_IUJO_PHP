<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA-Registro</title>
    <link rel="stylesheet" href="../templates/admin.css">
    <link rel="shortcut icon" href="./templates/logo.jpg" type="image/x-icon">
</head>
<body>
    <?php 
    include ('../funciones/funcion_registro.php');
    ?>
    <header>
        <nav>
            <div class="logo-header">
                <img src="../templates/logo.jpg" alt="INVILARA Logo">
                <h1>INVILARA</h1>
            </div>
            <ul>
                <li><a href="./Admin.php">Inicio</a></li>
                <li><a href="./registro.php">Registros de Usuarios</a></li>
                <li><a href="../index.php">Salir</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form method="post" autocomplete="off">
            <div class="logo">
                <img src="../templates/logo.jpg" alt="INVILARA Logo">
                <h1>INVILARA</h1>
            </div>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre">
            <input type="text" name="username" id="username" placeholder="Usuario">
            <input type="text" name="CI" id="CI" placeholder="Cedula">
            <input type="password" name="password" id="password" placeholder="Contraseña">
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="submit" value="Registar" name="registar">
        </form>
    </main>
</body>
</html>