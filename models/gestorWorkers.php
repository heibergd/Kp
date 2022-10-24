<?php 

class GestorWorkersModel{

	public static function jsonWorkers(){

		$consulta = new Consulta();
		$sql = "";
		if (isset($_SESSION["filtroWorkers"])) {
			$q = $_SESSION["filtroWorkers"];

			$sql = " where nombre like '%$q%' or email like '%$q%' or telefono like '%$q%'"; 
			// echo $sql;
		}
		
		$workers = $consulta->ver_registros("SELECT * from workers $sql");

		for ($i=0; $i < count($workers); $i++) { 
			$id_tipo = $workers[$i]['especialidad'];
			$especialidad = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
			if ($especialidad) {
				$workers[$i]['nombre_especialidad'] = $especialidad[0]['nombre'];
			}else{
				$workers[$i]['nombre_especialidad'] = "";
			}
		}

	
		
		// "SELECT * from usuarios"
		return $workers;

	}

	public static function workers(){

		$consulta = new Consulta();

		$workers = $consulta->ver_registros("SELECT * from workers");
		
		return $workers;

	}
	
	public static function workers_actives(){

		$consulta = new Consulta();

		$workers = $consulta->ver_registros("SELECT * from workers where activo = '1'");
		
		return $workers;

	}

	public static function roughWorkers(){

		$consulta = new Consulta();

		$workers = $consulta->ver_registros("SELECT * from workers where especialidad = 7");
		
		return $workers;

	}

	public static function crear($datos){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
		$email = $datos["correo"];
		$telefono = $datos["telefono"];
		$especialidad = $datos["especialidad"];
		$active = $datos["active"];

				
		$consulta->nuevo_registro("INSERT into workers (nombre, email,telefono, imagen, especialidad, activo) values('$nombre', '$email','$telefono', '', '$especialidad', '$active')");

		$idLast = $consulta->ver_registros("SELECT max(id) as id from workers");
		$_id = $idLast[0]["id"];


		return array("status" => "ok", "id" => $_id, "name" => $nombre);

	}
	public static function update($datos){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
		$email = $datos["correo"];
		$telefono = $datos["telefono"];
		$especialidad = isset($datos["especialidad"]) ? '0' : $datos["especialidad"];
		$active = $datos["active"];
		$id = $datos["id"];
		
		
		$consulta->actualizar_registro("UPDATE workers set nombre = '$nombre', email = '$email', telefono = '$telefono', especialidad = '$especialidad', activo = '$active' where id = '$id'");
		

		return array("status" => "ok", "id" => $id);

	}

	public static function check_email($email){
		$consulta = new Consulta();
		$user = $consulta->ver_registros("SELECT email from workers where email = '$email'");

		if(count($user) != 0){
			return true;
		}else{
			return false;
		}
	}
	public static function check_email_2($email){
		$consulta = new Consulta();
		$user = $consulta->ver_registros("SELECT * from workers where email = '$email'");

		if(count($user) != 0){
			return $user[0];
		}else{
			return false;
		}
	}

	public static function info_worker($id){

        $consulta = new Consulta();

		$info = $consulta->ver_registros("SELECT * from workers where id = '$id'");
		
		$info = count($info) != 0 ? $info[0] : [];

        return $info;

	}
	
	public static function borrar_lista_workers($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$info = $consulta->ver_registros("SELECT * from workers where id = '$id'");
			$consulta->borrar_registro("DELETE from workers where id = '$id'");

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
	

	public static function addPhoto($ruta, $id){

		$consulta = new Consulta();
		
		$consulta->actualizar_registro("UPDATE workers set imagen = '$ruta' where id = '$id'");

        return array("status" => "ok");

	}
	
}
