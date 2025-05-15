<?php
session_start();
include('conexion.php');

if (isset($_POST['iniciar'])) {
    if (strlen($_POST['username']) >= 3 && strlen($_POST['password']) >= 3) {
        $usuario = trim($_POST['username']);
        $clave = trim($_POST['password']);

        $consulta = "SELECT * FROM informacion WHERE usuario = '$usuario' AND clave = '$clave'";
        $resultado = mysqli_query($conex, $consulta);

        if ($filas = mysqli_fetch_array($resultado)) {
            $_SESSION['nombre'] = $filas['nombre']; 

            if ($filas['id_cargo'] == 1) {
                header("Location: ./php/Admin.php");
                exit();
            } elseif ($filas['id_cargo'] == 2) {
                header("Location: ./php/Usuarios.php");
                exit();
            } else {
                echo "<script>alert('Usuario o contraseña incorrectos');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, complete todos los campos correctamente');</script>";
    }
}
?>