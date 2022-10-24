<?php 
if(!isset($_SESSION)){ 
    session_start(); 
} 
class GestorSubdivisionesController{

	public static function jsonSubdivisiones(){


        $subdivisiones = GestorSubdivisionesModel::jsonSubdivisiones();

     
		return $subdivisiones;

    }
    
    public static function subdivisiones(){

        $subdivisiones = GestorSubdivisionesModel::subdivisiones();
     
		return $subdivisiones;

    }
    public static function info_sub($id){

        $subdivision = GestorSubdivisionesModel::info_sub($id);
     
		return $subdivision;

    }
    
    public static function crear(){

        $validado = GestorSubdivisionesController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{

            $resp = GestorSubdivisionesModel::sim($_POST, "crear");
            return $resp;
        }

        
    }
    public static function actualizar(){

        $validado = GestorSubdivisionesController::validar($_POST);

        

        if ($validado["status"] == "error") {
            return $validado;
        }else{

            $resp = GestorSubdivisionesModel::sim($_POST, "actualizar", $_POST["id"]);
            
            return $resp;
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

    public static function borrar_lista_subdivisiones(){
        $lista = $_POST["lista"];
        return GestorSubdivisionesModel::borrar_lista_subdivisiones($lista);
    
    }


    
}
