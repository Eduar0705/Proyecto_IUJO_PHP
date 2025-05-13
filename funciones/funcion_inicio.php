<?php
include ('conexion.php');

if(isset($_POST['iniciar'])){
    if(strlen($_POST['username']) >=3 && strlen($_POST['password']) >= 3){
        $usuario = trim($_POST['username']);
        $clave = trim($_POST['password']);

        $consulta = "SELECT * FROM informacion WHERE usuario = '$usuario' AND clave = '$clave' ";
        $resultado = mysqli_query($conex, $consulta);
        $filas = mysqli_num_rows($resultado);

        if ($filas) {
            header("Location: ./php/Admin.php");
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos');</script>";
        }

    }else {
        echo "<script>alert('Por favor, complete todos los campos correctamente');</script>";
    }
}
?>
