<?php
session_start();
require_once 'Enrutador.php';
$enrutador = new Enrutador();
$enrutador->ejecutar();
