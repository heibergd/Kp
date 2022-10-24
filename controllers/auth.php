<?php
session_start();
class AuthController {

  
	public static function validarRegistro(){
        $datos["nombre"] = $_POST["nombre"];
        $datos["correo"] = $_POST["correo"];
        $datos["password"] = isset($_POST["password"]) ? $_POST["password"] : false;
        $datos["updatePass"] = $_POST["updatePass"];
        
        $passwordPass = false;

        if ($datos["updatePass"] == "false") {
            $passwordPass = true;
        }else if ($datos["password"] !== "") {
            $passwordPass = true;
        }

        
        if ($datos["nombre"] != "" && $datos["correo"] != "" && $passwordPass) {
            
            if (!filter_var($datos["correo"], FILTER_VALIDATE_EMAIL)) {
                return array(
                    "status" => false,
                    "message" => "Wrong mail format.",
                    "datos" => $datos
                );
            }else{
                return array(
                    "status" => true,
                    "message" => "Go.",
                    "datos" => $datos
                );
            }

        }else{
            return array(
                "status" => false,
                "message" => "Enter the required fields.",
                "datos" => $datos,
                "passwordPass" => $passwordPass
            );
        }
        
    }

    //inicio session

    public static function inicio_sesion(){
        $validado = AuthController::validarLogin();
        if($validado["status"]){

            $session= AuthController::sessions($validado["datos"]);

            return array(
                "status" => true,
                "session" => $session
            );
        }else{
            return $validado;
        }
    }


    public static function validarLogin(){
        $datos["email"] = $_POST["email"];
        $datos["password"] = $_POST["password"];

        if ($datos["email"] != "" && $datos["password"] != "") {
            
           if (!filter_var($datos["email"], FILTER_VALIDATE_EMAIL)) {
                return array(
                    "status" => false,
                    "message" => "Wrong mail format.",
                    "datos" => $datos
                );
            }else{

                $info = AuthModel::ver_info_usuario($datos["email"]);

                if ($info != []) {
                    if (!password_verify($datos["password"], $info["password"])) {
                        return array(
                            "status" => false,
                            "message" => "Wrong password.",
                            "datos" => $datos
                        );
                    }else{
                        return array(
                            "status" => true,
                            "message" => "Go.",
                            "datos" => $info
                        );
                    }
                }else{
                    return array(
                        "status" => false,
                        "message" => "This email is not registered.",
                        "datos" => $datos
                    );
                }
                
            }

        }else{
            return array(
                "status" => false,
                "message" => "Enter the required fields.",
                "datos" => $datos
            );
        }
    }

    //actualizar informacion

    public static function actualizar_info(){
        $validado = AuthController::validarRegistro();
        if($validado["status"]){
            $res = AuthModel::actualizar_info($validado["datos"]);
            $info = AuthModel::ver_info_usuario($validado["datos"]["correo"]);
            
            //Actualizar sesion si se está editando el usuario actual
            if ($_POST['id'] == $_SESSION["id_usuario"]) {
                AuthController::sessions($info);
            }

            

            return $res;
        }else{
            return $validado;
        }
    }

    public static function actualizar_password(){
        if($_POST["passwordActual"] != "" && $_POST["passwordNueva"] != ""){

            if (password_verify($_POST["passwordActual"],$_SESSION["password"])) {
                $res = AuthModel::actualizar_password($_POST["passwordNueva"]);

                $info = AuthModel::ver_info_cliente($_SESSION["email"]);

                AuthController::sessions($info);

                return $res;
                # code...
            }else{
                return array(
                    "status" => false,
                    "message" => "Wrong current password."
                );
            }

           
        }else{
            return array(
                "status" => false,
                "message" => "Enter the required fields."
            );
        }
    }

    public static function ver_info_usuario($id){
        return AuthModel::ver_info_usuario(false, $id);
    }

    

    public static function sessions($datos){
        $_SESSION["usuario_validado"] = true;
        $_SESSION["id_usuario"] = $datos["id"];
        $_SESSION["usuario"] = $datos["usuario"];
        $_SESSION["correo"] = $datos["correo"];
        $_SESSION["imagen"] = $datos["imagen"];
        $_SESSION["tipo"] = $datos["tipo"];
        $_SESSION["estado"] = $datos["estado"];
        $_SESSION["tag_on_signal"] = $datos["tag_on_signal"];
        $_SESSION["cesta"] = [];

        return $_SESSION;
    }
}