<?php 
if(!isset($_SESSION)){ 
    session_start(); 
}
class GestorWorkersController{

	public static function jsonWorkers(){

        $workers = GestorWorkersModel::jsonWorkers();
        
		return $workers;

    }

    public static function workers(){

        $workers = GestorWorkersModel::workers();

		return $workers;

    }
    public static function workers_actives(){

        $workers = GestorWorkersModel::workers_actives();

		return $workers;

    }
    public static function roughWorkers(){

        $workers = GestorWorkersModel::roughWorkers();

		return $workers;

    }
    
    public static function crear(){

        $validado = GestorWorkersController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            $arr =  GestorWorkersModel::crear($_POST);

            GestorWorkersController::addPhoto($arr["id"]);
    
            return $arr;
        }

        
    }

    public static function update(){

        $validado = GestorWorkersController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            $arr =  GestorWorkersModel::update($_POST);

            $info = GestorWorkersModel::info_worker($_POST["id"]);

            GestorWorkersController::addPhoto($arr["id"], $info["imagen"]);
    
            return $arr;
        }

        
    }

    // LA ACTUALIZACION DE LOS USUARIOS LO HACE EL MODULO AUTH
    public static function validar($datos){
        $validado = array(
            "razon" => "",
            "mensaje" => "",
            "status" => "valido"
        );

        

        if ($datos["nombre"] == "" || $datos["correo"] == "") {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter the required fields.",
                "status" => "error"
            );
        }else if (!filter_var($datos["correo"], FILTER_VALIDATE_EMAIL)) {
            $validado = array(
                "status" => "error",
                "razon" => "Email no válido",
                "mensaje" => "Wrong mail format."
            );
        }else {
            if (isset($_POST["id"])) {
                $check_email = GestorWorkersModel::check_email_2($datos["correo"]);
                if ($check_email) {
                    if ($check_email["id"] != $_POST["id"]) {
                        $validado = array(
                            "status" => "error",
                            "razon" => "Email duplicado",
                            "mensaje" => "This email is already registered."
                        );
                    }
                }
                   
            }else if (GestorWorkersModel::check_email($datos["correo"])) {
                $validado = array(
                    "status" => "error",
                    "razon" => "Email duplicado",
                    "mensaje" => "This email is already registered."
                );
            }
                 
        }

        return $validado;

    }

    public static function info_worker($id){
        return GestorWorkersModel::info_worker($id);
    }

    public static function borrar_lista_workers(){
        $lista = $_POST["lista"];
        return GestorWorkersModel::borrar_lista_workers($lista);
    
    }
  
    public static function addPhoto($id, $oldImage = false){
       
        if (isset($_FILES["image_worker"])) {
            if ($_FILES["image_worker"]["name"] != "") {
                $imagen = $_FILES["image_worker"];
       
                if ($oldImage) {
                    unlink("../../$oldImage");
                }

                $ruta = "views/images/workers/imagen_$id.jpg";

                move_uploaded_file($imagen["tmp_name"], "../../".$ruta);

                return GestorWorkersModel::addPhoto($ruta, $id);

            }else{
                return array("status" => "error", "mensaje" => "Selec an image");
            }
            
        }


    
    }

    
}
