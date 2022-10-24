<?php 

class GestorFixtureModel{

	public static function jsonFixtures(){

		$consulta = new Consulta();
		$sql = "";
		if (isset($_SESSION["filtroFixture"])) {
			$q = $_SESSION["filtroFixture"];

			$sql = " where nombre like '%$q%'"; 
			// echo $sql;
		}
		
		$fixtures = $consulta->ver_registros("SELECT * from fixtures $sql");

	
		// "SELECT * from usuarios"
		return $fixtures;

	}

	public static function Mainfixtures($form = 'Rough'){

		$consulta = new Consulta();

		$fixtures = $consulta->ver_registros("SELECT * from fixtures where extra = 0 and form = '$form'");
		
		return $fixtures;

	}

	public static function future($form = 'Rough'){

		$consulta = new Consulta();

		$fixtures = $consulta->ver_registros("SELECT * from fixtures where future = 1 and form = '$form'");
		
		return $fixtures;

	}
	/* =============================================================
    MODELO PARA LA INFORMACION DE OTHERS
    ============================================================= */
	public static function others(){
		$consulta = new Consulta();
		$query = $consulta->registro("SELECT * FROM fixtures WHERE extra = 2 and form = 'Rough'");
		return $query;
	}
	/* =============================================================
    MODELO PARA LISTAR EL EXTRAFIXTURES
    ============================================================= */
	public static function Extrafixtures($form = 'Rough'){
		$consulta = new Consulta();
		$fixtures = $consulta->ver_registros("SELECT * from fixtures where extra = 1 and form = '$form' order by tipo asc, orden asc ");
		return $fixtures;
	}
	/* =============================================================
    MODELO PARA CREAR FIXTURES
    ============================================================= */
	public static function crear($datos){
		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
		$extra = $datos["extra"];
		$precio_cobrar = $datos["precio_cobrar"];
		$precio_pagar = $datos["precio_pagar"];
		$tipo = $datos["tipo"];
		// El orden de 15 es para que los nuevos fixures se agregen antes del Othres
		$orden = 15;
		$consulta->nuevo_registro("INSERT into fixtures (nombre, extra, precio_cobrar, precio_pagar,tipo, orden) values('$nombre', '$extra', '$precio_cobrar', '$precio_pagar', '$tipo', '$orden')");
		$idLast = $consulta->ver_registros("SELECT max(id) as id from fixtures");
		$_id = $idLast[0]["id"];
		return array("status" => "ok", "id" => $_id, "name" => $nombre);
	}

	public static function update($datos){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
		$extra = $datos["extra"];
		$precio_cobrar = $datos["precio_cobrar"];
		$precio_pagar = $datos["precio_pagar"];
		$items = $datos["items"];
		$tipo = $datos["tipo"];
		$id = $datos["id"];
		
				
		$consulta->actualizar_registro("UPDATE fixtures set nombre = '$nombre', extra = '$extra', precio_cobrar = '$precio_cobrar', precio_pagar = '$precio_pagar', tipo = '$tipo', items = '$items' where id = '$id'");

		return array("status" => "ok", "id" => $id);

	}

	public static function info_fixture($id){

        $consulta = new Consulta();

		$info = $consulta->ver_registros("SELECT * from fixtures where id = '$id'");
		
		$info = count($info) != 0 ? $info[0] : [];

        return $info;

	}
	
	public static function borrar_lista_fixtures($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$consulta->borrar_registro("DELETE from fixtures where id = '$id'");

		}

        return array("status" => "ok");

	}

}
