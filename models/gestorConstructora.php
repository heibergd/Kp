<?php 

class GestorConstructoraModel{

	public static function jsonConstructoras(){

		$consulta = new Consulta();
		$sql = "";
		if (isset($_SESSION["filtroConstructora"])) {
			$q = $_SESSION["filtroConstructora"];

			$sql = " where nombre like '%$q%'"; 
			// echo $sql;
		}
		
		$constructoras = $consulta->ver_registros("SELECT * from constructoras $sql");

	
		// "SELECT * from usuarios"
		return $constructoras;

	}

	public static function constructoras(){

		$consulta = new Consulta();

		$constructoras = $consulta->ver_registros("SELECT * from constructoras");
		
		return $constructoras;

	}

	public static function constructoras_filtro($idconstructoras){


		$consulta = new Consulta();

		$constructoras = $consulta->ver_registros("SELECT * FROM `constructoras` WHERE FIND_IN_SET(`id`, '$idconstructoras')");
		
		return $constructoras;

		
	}

	public static function crear($datos){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
	
				
		$consulta->nuevo_registro("INSERT into constructoras (nombre) values('$nombre')");

		$idLast = $consulta->ver_registros("SELECT max(id) as id from constructoras");
		$_id = $idLast[0]["id"];


		return array("status" => "ok", "id" => $_id, "name" => $nombre);

	}
	public static function update($datos){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
	
		$id = $datos["id"];
		
				
		$consulta->actualizar_registro("UPDATE constructoras set nombre = '$nombre' where id = '$id'");
		

		return array("status" => "ok", "id" => $id);

	}


	public static function info_constructora($id){

        $consulta = new Consulta();

		$info = $consulta->ver_registros("SELECT * from constructoras where id = '$id'");
		
		$info = count($info) != 0 ? $info[0] : [];

        return $info;

	}
	
	public static function borrar_lista_constructoras($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$consulta->borrar_registro("DELETE from constructoras where id = '$id'");

		}

        return array("status" => "ok");

	}

}
