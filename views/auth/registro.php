<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA - Registro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="shortcut icon" href="img/logo.jpg" type="image/x-icon">
</head>
<body>
    <main class="main-content">
        <form method="post">
            <input type="text" name="nombre" placeholder="Nombre completo" required>
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="text" name="CI" placeholder="Cédula" required>
            <select name="cargo" required>
                <option value="">Seleccione un cargo</option>
                <option value="1">Administración</option>
                <option value="2">Usuario</option>
            </select>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="submit" name="registrar" value="Registrarse">
            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>
