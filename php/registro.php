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
    include ('../funciones/funcion_inicio.php'); 

    if (isset($_SESSION['nombre'])) {
        $nombre = htmlspecialchars($_SESSION['nombre']);
    } else {
        $nombre = "Invitado";
    }
    ?>
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
        <form method="post" autocomplete="off">
            <div class="logo">
                <img src="../templates/logo.jpg" alt="INVILARA Logo">
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
    <footer>
        <div class="footer-content">
            <p>&copy; 2023 INVILARA. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script>
        //validacion para que en cargo escaja una opcion sea administrador o usuario
        document.getElementById('cargo').addEventListener('change', function() {
            var cargo = this.value;
            if (cargo !== '1' && cargo !== '2') {
                alert('Por favor, seleccione un cargo válido (1 o 2).');
                this.value = ''; // Limpiar el campo
            }
        });
        //validacion de que en el campo nombre solo se ingresen letras
        document.getElementById('nombre').addEventListener('input', function() {
            var nombre = this.value;
            if (!/^[a-zA-Z\s]+$/.test(nombre)) {
                alert('Por favor, ingrese un nombre válido (solo letras y espacios).');
                this.value = ''; // Limpiar el campo
            }
        });
        //validacion de que en el campo usuario solo se ingresen letras y numeros
        document.getElementById('username').addEventListener('input', function() {
            var usuario = this.value;
            if (!/^[a-zA-Z0-9]+$/.test(usuario)) {
                alert('Por favor, ingrese un nombre de usuario válido (solo letras y números).');
                this.value = ''; // Limpiar el campo
            }
        });
    </script>
</body>
</html>