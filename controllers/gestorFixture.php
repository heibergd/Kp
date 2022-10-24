<?php 
if(!isset($_SESSION)){ 
    session_start(); 
}
class GestorFixtureController{

	public static function jsonConstructoras(){

        $fixtures = GestorFixtureModel::jsonConstructoras();
        
		return $fixtures;

    }

    public static function Mainfixtures($form = 'Rough'){
        
        $fixtures = GestorFixtureModel::Mainfixtures($form);

		return $fixtures;

    }

    public static function future($form = 'Rough'){
        
        $fixtures = GestorFixtureModel::future($form);

        return $fixtures;

    }
    /* =============================================================
    CONTROLADOR PARA BUSCAR LA INFORMACION EN OTHERS
    ============================================================= */
    public static function others(){
        $query = GestorFixtureModel::others();
        return $query;
    }
    /* =============================================================
    CONTROLADOR PARA LISTAR EL EXTRAFIXTURES
    ============================================================= */
    public static function Extrafixtures($form = 'Rough'){
        $fixtures = GestorFixtureModel::Extrafixtures($form);
		return $fixtures;
    }
    
    public static function crear(){

        $validado = GestorFixtureController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            $arr =  GestorFixtureModel::crear($_POST);

            return $arr;
        }

        
    }

    public static function update(){

        $validado = GestorFixtureController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            $arr =  GestorFixtureModel::update($_POST);

    
            return $arr;
        }

        
    }


    public static function validar($datos){
        $validado = array(
            "razon" => "",
            "message" => "",
            "status" => "valido"
        );


        if ($datos["nombre"] == ""  || $datos["precio_cobrar"] == ""|| $datos["precio_pagar"] == "") {
            $validado = array(
                "razon" => "campos vacíos",
                "message" => "Enter the required fields.",
                "status" => false
            );
        }elseif (!is_numeric($datos["precio_cobrar"])) {
            $validado = array(
                "razon" => "invalid data",
                "message" => "Price charge must be numeric.",
                "status" => false
            );
        }elseif (!is_numeric($datos["precio_pagar"])) {
            $validado = array(
                "razon" => "invalid data",
                "message" => "Price to pay must be numeric.",
                "status" => false
            );
        }

        return $validado;

    }

    public static function info_fixture($id){
        return GestorFixtureModel::info_fixture($id);
    }

    public static function borrar_lista_fixtures(){
        $lista = $_POST["lista"];
        return GestorFixtureModel::borrar_lista_fixtures($lista);
    
    }

    
}
