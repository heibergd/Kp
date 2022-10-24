<?php 

class AuthModel{

    public static function actualizar_info($datos){

        $consulta = new Consulta();
        $nombre = htmlspecialchars($datos['nombre'], ENT_QUOTES);
        $email = $datos['correo'];
        $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 1;
        $estado = isset($_POST['estado']) ? $_POST['estado'] : 1;
        $id = $_POST['id'];


        $check_email = $consulta -> ver_registros("SELECT * FROM usuarios WHERE correo = '$email'");

        if (count($check_email) == 1 &&  $check_email[0]["id"] == $id || count($check_email) == 0 ) {


            if ($datos['updatePass'] == "true") {
                AuthModel::actualizar_password($_POST["password"], $id);
            }

            // 
            if ($_SESSION["tipo"] != 0) {
                $access = GestorConfigModel::verficar_usuario();

                if (isset($access->listUsers) && $id != $_SESSION["id_usuario"] && $tipo != 0) {

                    $consulta->actualizar_registro("UPDATE usuarios set usuario = '$nombre', correo = '$email', tipo='$tipo', estado ='$estado' where id = '$id'");
                }else{
                    $consulta->actualizar_registro("UPDATE usuarios set usuario = '$nombre', correo = '$email' where id = '$id'");
                }
            }else{
                $consulta->actualizar_registro("UPDATE usuarios set usuario = '$nombre', correo = '$email', tipo='$tipo', estado ='$estado' where id = '$id'");
            }

			

            return array(
                'status' => true
            );

		}else {
            return array(
                'status' => false,
                "message" => "Este email ya está registrado.",
                "datos" => $datos
            );
		}

    }
    public static function actualizar_password($password, $id){

        $consulta = new Consulta();

        $encriptacion = password_hash($password, PASSWORD_DEFAULT);
       
        $consulta->actualizar_registro("UPDATE usuarios set password = '$encriptacion' where id = '$id'");

        return array(
            'status' => true
        );

    

    }
    
    public static function ver_info_usuario($email){

        $consulta = new Consulta();

        $info = $consulta->ver_registros("SELECT * from usuarios where correo = '$email'");

        $info = count($info) != 0 ? $info[0] : [];

        return $info;

    }

}