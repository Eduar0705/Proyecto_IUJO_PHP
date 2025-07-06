<?php
include_once './model/conexion.php';
// Función para actualizar la configuración
function updateAdminConfig($nuevaClaveSuper, $nuevoNombreAPP, $nuevaDescripcionAPP) {
    $database = new BaseDatos();
    $connection = $database->conectar();

    $sql = "UPDATE configTotal SET claveSuper = ?, nombreAPP = ?, descripcionAPP = ? LIMIT 1";
    $stmt = mysqli_prepare($connection, $sql);
    //Obtener los valores actuales
    $query = "SELECT claveSuper, nombreAPP, descripcionAPP FROM configTotal LIMIT 1";
    $result = mysqli_query($connection, $query);
    $configActual = mysqli_fetch_assoc($result);

    // Comparar si hay cambios
    if (
        $nuevaClaveSuper === $configActual['claveSuper'] &&
        $nuevoNombreAPP === $configActual['nombreAPP'] &&
        $nuevaDescripcionAPP === $configActual['descripcionAPP']
    ) {
            mysqli_stmt_close($stmt);
            return 'queda igual';
    }
    
    mysqli_stmt_bind_param($stmt, "sss", $nuevaClaveSuper, $nuevoNombreAPP, $nuevaDescripcionAPP);
    mysqli_stmt_execute($stmt);
    
    $success = mysqli_stmt_affected_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    return $success;
}
// Función para obtener la configuración actual
function getAdminConfig() {
    $database = new BaseDatos();
    $connection = $database->conectar();
    
    $config = [
        'claveSuper' => '',
        'nombreAPP' => '',
        'descripcionAPP' => ''
    ];
    
    $sql = "SELECT claveSuper, nombreAPP, descripcionAPP FROM configTotal LIMIT 1";
    $result = mysqli_query($connection, $sql);
    
    if (!$result) {
        die("Error al obtener la configuración: " . mysqli_error($connection));
    }
    
    if ($row = mysqli_fetch_assoc($result)) {
        $config['claveSuper'] = $row['claveSuper'];
        $config['nombreAPP'] = $row['nombreAPP'];
        $config['descripcionAPP'] = $row['descripcionAPP'];
    }
    
    mysqli_free_result($result);
    return $config;
}
// Función para verificar la clave de administrador
function verificarClaveAdmin($claveIngresada) {
    $config = getAdminConfig();
    return ($claveIngresada === $config['claveSuper']) ? 'correcta' : 'incorrecta';
}
// Función para actualizar un campo específico
function actualizarCampoConfig($campo, $valor) {
    $config = getAdminConfig();

    // Preparar datos para update
    $nuevaClave = ($campo === 'claveSuper') ? $valor : $config['claveSuper'];
    $nuevoNombre = ($campo === 'nombreAPP') ? $valor : $config['nombreAPP'];
    $nuevaDesc = ($campo === 'descripcionAPP') ? $valor : $config['descripcionAPP'];
    
    $resultado = updateAdminConfig($nuevaClave, $nuevoNombre, $nuevaDesc);
    if($resultado === true) { return 'exito';}
    else if ($resultado === false) {return 'Error al actualizar';}
    else if ($resultado === 'queda igual') {return $resultado;}
}
// Obtener configuración actual y definir constantes
$config = getAdminConfig();
define('APP_NAME', $config['nombreAPP']);
define('APP_DESC', $config['descripcionAPP']);
define('PASSWORD', $config['claveSuper']);