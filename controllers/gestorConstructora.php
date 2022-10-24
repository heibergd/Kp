<?php 
if(!isset($_SESSION)){ 
    session_start(); 
}
class GestorConstructoraController{

	public static function jsonConstructoras(){

        $constructoras = GestorConstructoraModel::jsonConstructoras();
        
		return $constructoras;

    }

    public static function constructoras($id = false){

        if (!$id){ 
        
        $constructoras = GestorConstructoraModel::constructoras();

		

    } else {
        $constructoras = GestorConstructoraModel::constructoras_filtro($id);
            //error_log(print_r($id));
        }

    return $constructoras;
    
    }  
    
    public static function crear(){

        $validado = GestorConstructoraController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            $arr =  GestorConstructoraModel::crear($_POST);

            return $arr;
        }

        
    }

    public static function update(){

        $validado = GestorConstructoraController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            $arr =  GestorConstructoraModel::update($_POST);

    
            return $arr;
        }

        
    }


    public static function validar($datos){
        $validado = array(
            "razon" => "",
            "mensaje" => "",
            "status" => "valido"
        );


        if ($datos["nombre"] == "") {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter the required fields.",
                "status" => "error"
            );
        }

        return $validado;

    }

    public static function info_constructora($id){
        return GestorConstructoraModel::info_constructora($id);
    }

    public static function borrar_lista_constructoras(){
        $lista = $_POST["lista"];
        return GestorConstructoraModel::borrar_lista_constructoras($lista);
    
    }

     public static function listar_constructoras(){
        return GestorConstructoraModel::constructoras();
    }

    
}
