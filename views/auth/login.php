<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA</title>
    <link rel="stylesheet" href="./views/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="shortcut icon" href="./views/img/logo.jpg" type="image/x-icon">
</head>
<body>
    <form method="post" autocomplete="off">
        <div class="container">
            <div class="logo">
                <img src="./views/img/logo.jpg" alt="Logo">
            </div>
            <h1>INICIO DE SESIÓN</h1>
            <div class="input-group">
                <label for="usuario">
                    <i class="fa-solid fa-user"></i>
                    Usuario
                </label>
                <input type="text" name="usuario" required>
            </div>
            <div class="input-group">
                <label for="clave">
                    <i class="fa-solid fa-lock"></i>
                    Contraseña
                </label>
                <input type="password" name="clave" required>
            </div>
            <button type="submit" name="iniciar">Iniciar Sesión</button>
        
        </div>
    </form>
</body>
</html>
