<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA</title>
    <link rel="stylesheet" href="./templates/style.css">
    <link rel="shortcut icon" href="./templates/logo.jpg" type="image/x-icon">
</head>
<body>
    <?php 
    include ('./funciones/funcion_inicio.php');
    ?>

    <main>
        <form method="post" autocomplete="off">
            <div class="logo-header">
                <img src="./templates/logo.jpg" alt="INVILARA Logo">
                <h1>INVILARA</h1>
            </div>
            <input type="text" name="username" id="username" placeholder="Usuario">
            <input type="password" name="password" id="password" placeholder="Contraseña">
            <input type="submit" value="Iniciar" name="iniciar">
        </form>
    </main>
</body>
</html>