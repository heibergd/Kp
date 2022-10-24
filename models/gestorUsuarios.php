<?php 

class GestorUsuariosModel{

	public static function jsonUsers(){

		$consulta = new Consulta();
		$myId = $_SESSION["id_usuario"];
		$sql = "";
		if (isset($_SESSION["filtroUsers"])) {
			$q = $_SESSION["filtroUsers"];

			$sql = " AND (usuario like '%$q%' or correo like '%$q%')"; 
			// echo $sql;
		}
		

		if($_SESSION["tipo"] == 0){
			$users = $consulta->ver_registros("select * from usuarios where id != '$myId' $sql");
		}else{
			$users = $consulta->ver_registros("select * from usuarios where id != '$myId' AND tipo != '0' $sql");
		}

		
		// "SELECT * from usuarios"
		return $users;

	}

	public static function usuarios(){

		$consulta = new Consulta();

		$usuarios = $consulta->ver_registros("select * from usuarios");
		
		return $usuarios;

	}


	//actualizar está en Auth
	public static function crear($datos){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
		$correo = $datos["correo"];
		$tipo = $datos["tipo"];
		$password = $datos["password"];
		

		$encrypt = password_hash($password, PASSWORD_DEFAULT);
				
		$consulta->nuevo_registro("INSERT into usuarios (usuario, correo,imagen, tipo, estado, password, tag_on_signal) values('$nombre', '$correo','', '$tipo','1', '$encrypt', '')");

		$idLast = $consulta->ver_registros("SELECT max(id) as id from usuarios");
		$_id = $idLast[0]["id"];

		return array("status" => "ok");

	}

	public static function check_email($email){
		$consulta = new Consulta();
		$user = $consulta->ver_registros("SELECT correo from usuarios where correo = '$email'");

		if(count($user) != 0){
			return true;
		}else{
			return false;
		}
	}

	public static function ver_info_usuario($id){

        $consulta = new Consulta();

		$info = $consulta->ver_registros("SELECT * from usuarios where id = '$id'");
		


		$info = count($info) != 0 ? $info[0] : [];
		$info["cartera"] = ["nombre" => ""];

        return $info;

	}
	
	public static function borrar_lista_usuarios($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$info = $consulta->ver_registros("SELECT * from usuarios where id = '$id'");
			$consulta->borrar_registro("DELETE from usuarios where id = '$id'");
			$consulta->borrar_registro("DELETE from notificaciones where id_usuario = '$id'");

			if (isset($info[0])) {
				if ($info[0]["imagen"] != "") {
					$ruta = "../../" . $info[0]["imagen"];
					if (file_exists($ruta)) {
						unlink($ruta);
					}
				}				
			}
			
			
			
		}

        return array("status" => "ok");

	}
	
	public static function actualizar_estados($lista, $estado){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];
			$consulta->actualizar_registro("UPDATE usuarios set estado = '$estado' where id = '$id'");

		}

        return array("status" => "ok");

	}
	public static function actualizar_tipos($lista, $tipo){

		$consulta = new Consulta();

		$access = GestorConfigModel::verficar_usuario();

		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			if($_SESSION["tipo"] == 0){
				$consulta->actualizar_registro("UPDATE usuarios set tipo = '$tipo' where id = '$id'");
			}else if (isset($access->listUsers) && $id != $_SESSION["id_usuario"] && $tipo != 0) {
				$consulta->actualizar_registro("UPDATE usuarios set tipo = '$tipo' where id = '$id'");
			}


			if ($tipo == '0') {
				GestorNotificacionesController::crear_notificacion($id, "estado_usuario", "¡Tu rango de usuario ha sido actualizado a Master!", "profile");
			}else if ($tipo == '1') {
				GestorNotificacionesController::crear_notificacion($id, "estado_usuario", "¡Tu rango de usuario ha sido actualizado a Administrador!", "profile");
			}else{
				GestorNotificacionesController::crear_notificacion($id, "estado_usuario", "¡Tu rango de usuario ha sido actualizado a Vendedor!", "profile");
			}
			

		}

        return array("status" => "ok");

	}
	
	public static function cambiarImagenPerfil($ruta, $id){

		$consulta = new Consulta();
		
		$consulta->borrar_registro("UPDATE usuarios set imagen = '$ruta' where id = '$id'");

		$_SESSION["imagen"] = $ruta;

        return array("status" => "ok");

	}
	
}
