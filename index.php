<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVILARA</title>
</head>
<body>
    <?php require_once './templates/style.php'; ?>
    <main>
        <form action="login.php" method="post">

            <input type="text" name="username" id="username" placeholder="Usuario" required>
            <input type="password" name="password" id="password" placeholder="Contraseña" required>
            
            <a class="recuperacion" href="recuperar_contrasena.php">Recuperar Contraseña</a>
            <a class="recuperacion" href="recuperar_usuario.php">Recuperar Usuario</a>
            
            <input type="submit" value="Iniciar">
        </form>
    </main>
    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();

            if (username === '' || password === '') {
                event.preventDefault();
                alert('Por favor, complete todos los campos.');
            }
        });
    </script>
</body>
</html>