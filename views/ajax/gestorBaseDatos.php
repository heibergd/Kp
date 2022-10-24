<?php
require_once '../../models/conexion.php';
require_once '../../models/consulta.php';
require_once '../../models/gestorRoughForm.php';
require_once '../../controllers/gestorRoughForm.php';

class GestorDataBase{
    public function updatedatabase()
    {
        $respuesta = GestorRoughFormController::updatedatabase();
        echo json_encode($respuesta);
    }
}

$a = new GestorDataBase();

if (isset($_POST["updateBase"])) {
    $a->updatedatabase();
}