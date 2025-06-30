<?php
class ClaveMaestra {
    private $bd;
    private $modelBD;

    public function __construct() {
        $this->modelBD = new BaseDatos();
        $this->bd = $this->modelBD->conectar();

        if (!$this->bd) {
            throw new Exception("Error al conectar a la base de datos");
        }
    }

    public function obtenerClaveMaestra() {
        $sql = "SELECT Clave_Admin FROM clave";
        $resu = $this->bd->query($sql);

        if ($resu === false) {
            throw new Exception("Error en la consulta: " . $this->bd->error);
        }
        return $resu;
    }
}

?>