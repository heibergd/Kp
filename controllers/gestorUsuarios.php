<?php 
if(!isset($_SESSION)){ 
    session_start(); 
}
class GestorUsuariosController{

	public static function jsonUsers(){

        $users = GestorUsuariosModel::jsonUsers();
        
        for ($i=0; $i < count($users); $i++) { 
            $users[$i]["CompanyAgent"] = $users[$i]["usuario"];
        }

		return $users;

    }

    public static function usuarios(){

        $usuarios = GestorUsuariosModel::usuarios();
     
		return $usuarios;

    }
    
    public static function crear(){

        $validado = GestorUsuariosController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{
            return GestorUsuariosModel::crear($_POST);
        }

        
    }

    // LA ACTUALIZACION DE LOS USUARIOS LO HACE EL MODULO AUTH

    public static function validar($datos){
        $validado = array(
            "razon" => "",
            "mensaje" => "",
            "status" => "valido"
        );

        if ($datos["nombre"] == "" || $datos["correo"] == "" || $datos["password"] == "") {
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
        }else if (GestorUsuariosModel::check_email($datos["correo"])) {
            $validado = array(
                "status" => "error",
                "razon" => "Email duplicado",
                "mensaje" => "This email is already registered."
            );
        }

        return $validado;

    }

    public static function ver_info_usuario($id){
        return GestorUsuariosModel::ver_info_usuario($id);
    }

    public static function borrar_lista_usuarios(){
        $lista = $_POST["lista"];
        return GestorUsuariosModel::borrar_lista_usuarios($lista);
    
    }
    public static function actualizar_estados(){
        $lista = $_POST["lista"];
        $estado = $_POST["estado"];
        return GestorUsuariosModel::actualizar_estados($lista, $estado);
    
    }
    public static function actualizar_tipos(){
        $lista = $_POST["lista"];
        $tipo = $_POST["tipo"];
        return GestorUsuariosModel::actualizar_tipos($lista, $tipo);
    
    }
    public static function cambiarImagenPerfil(){
       

        if (isset($_FILES["imageUser"])) {
            if ($_FILES["imageUser"]["name"] != "") {
                $imagen = $_FILES["imageUser"];
                $oldImage = $_SESSION["imagen"];
                $id_usuario = $_SESSION["id_usuario"];
                //borrar imagen anterior
                if ($oldImage != "") {
                    unlink("../../$oldImage");
                }

                $ruta = "views/images/usuarios/imagen_$id_usuario.jpg";

                move_uploaded_file($imagen["tmp_name"], "../../".$ruta);

                return GestorUsuariosModel::cambiarImagenPerfil($ruta, $id_usuario);

            }else{
                return array("status" => "error", "mensaje" => "selecciona una imagen");
            }
            
        }


    
    }

    
}
