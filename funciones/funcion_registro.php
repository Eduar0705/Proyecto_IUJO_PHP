<?php
include ('conexion.php');

if (isset($_POST['registar'])){
    //VERIFICACION SI LOS CAMPOS ESTAN RELLENOS
    if(strlen($_POST['nombre']) >=3 && strlen($_POST['username']) >= 3
    && strlen($_POST['CI']) >= 7 && strlen($_POST['password']) >= 5
    && strlen($_POST['email']) >= 5 && strlen($_POST['cargo']) >= 0) {

        //CREACION DE VARIABLES
        $name = trim($_POST['nombre']);
        $usuario = trim($_POST['username']);
        $cedula = trim($_POST['CI']);
        $clave = trim($_POST['password']);
        $correo = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
        $fechas = date("d/m/Y");
        $cargo = trim($_POST['cargo']);

        //VERIFICACION SI YA SE ENCUENTRAN EN LA BASE DE DATOS
        $stmt = $conex->prepare("SELECT * FROM informacion WHERE usuario = ? OR email = ? OR cedula = ?");
        $stmt->bind_param("sss", $usuario, $correo, $cedula);
        $stmt->execute();
        $resultado_verificar = $stmt->get_result();

        if(mysqli_num_rows($resultado_verificar) > 0) {
            // SI EL USUARIO, EMAIL O CÉDULA YA EXISTEN
            $fila = mysqli_fetch_assoc($resultado_verificar);
            
            if($fila['usuario'] == $usuario) {
                echo "<script>alert('El nombre de usuario ya está registrado');</script>";
            }
            
            if($fila['email'] == $correo) {
                echo "<script>alert('El correo electrónico ya está registrado');</script>";
            }
            if($fila['cedula'] == $cedula){
                echo "<script>alert('La cédula ingresada ya se encuentra en el sistema');</script>";
            }
        }
        else{
            $consulta = "INSERT INTO informacion(nombre, usuario, clave, email, cedula, id_cargo, fecha) VALUES ('$name','$usuario','$clave','$correo','$cedula','$cargo','$fechas')";
            $resultado = mysqli_query($conex, $consulta); 

            if ($resultado) {
                echo "<script>alert('Usuario registrado correctamente');</script>";
            } else {
                echo "<script>alert('Error al registrar el usuario: " . mysqli_error($conex) . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, complete todos los campos correctamente');</script>";
    }
}
?>