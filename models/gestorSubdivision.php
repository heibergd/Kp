<?php 

class GestorSubdivisionesModel{

	public static function jsonSubdivisiones(){

		$consulta = new Consulta();
		$sql = "";
		if (isset($_SESSION["filtrarSubdivisions"])) {
			$q = $_SESSION["filtrarSubdivisions"];

			$sql = "SELECT `subdivisiones`.id,`subdivisiones`.nombre, direccion, group_concat(`constructoras`.nombre order by FIND_IN_SET(`constructoras`.id , `subdivisiones`.`constructoras`)) as grupoconst, forsyth_county, IF(sewer=0,'Sewer','Septic System') AS is_sewer FROM `constructoras` RIGHT JOIN `subdivisiones` ON FIND_IN_SET(`constructoras`.id , `subdivisiones`.`constructoras`) > 0 WHERE `subdivisiones`.nombre like '%$q%' or direccion like '%$q%' or lotes like '%$q%' GROUP BY `subdivisiones`.nombre ORDER BY `subdivisiones`.nombre"; 
			 
		} else {
			$sql = "SELECT `subdivisiones`.id,`subdivisiones`.nombre, direccion, group_concat(`constructoras`.nombre order by FIND_IN_SET(`constructoras`.id , `subdivisiones`.`constructoras`)) as grupoconst, forsyth_county, IF(sewer=0,'Sewer','Septic System') AS is_sewer FROM `constructoras` RIGHT JOIN `subdivisiones` ON FIND_IN_SET(`constructoras`.id , `subdivisiones`.`constructoras`) > 0 GROUP BY `subdivisiones`.nombre ORDER BY `subdivisiones`.nombre"; 
		}
	
		$subdivisiones = $consulta->ver_registros($sql);
		
		return $subdivisiones;

	}

	public static function subdivisiones(){

		$consulta = new Consulta();

		$subdivisiones = $consulta->ver_registros("SELECT `subdivisiones`.id,`subdivisiones`.nombre, direccion, group_concat(`constructoras`.nombre order by FIND_IN_SET(`constructoras`.id , `subdivisiones`.`constructoras`)) as grupoconst, forsyth_county, IF(sewer=0,'Sewer','Septic System') AS is_sewer FROM `constructoras` RIGHT JOIN `subdivisiones` ON FIND_IN_SET(`constructoras`.id , `subdivisiones`.`constructoras`) > 0 GROUP BY `subdivisiones`.nombre ORDER BY `subdivisiones`.nombre ASC");
		
		return $subdivisiones;

	}

	
	public static function info_sub($id){

		$consulta = new Consulta();

		$subdivision = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id'");

		if (isset($subdivision[0])) {
			$subdivision[0]["nombre"] = htmlspecialchars_decode($subdivision[0]["nombre"], ENT_QUOTES);
			$subdivision[0]["direccion"] = htmlspecialchars_decode($subdivision[0]["direccion"], ENT_QUOTES);
		}
		
		$subdivision = (isset($subdivision[0])) ? $subdivision[0] : [];
		
		//error_log( print_r( $subdivision, true ));
		return $subdivision;

	}

	//-- CREAR / ACTUALIZAR Subdivisiones
	public static function sim($datos, $option, $id = null){

		$consulta = new Consulta();
		$nombre = htmlspecialchars($datos["nombre"], ENT_QUOTES);
		$lotes = $datos["lotes"];
		$direccion = htmlspecialchars($datos["direccion"], ENT_QUOTES);
		$lat_lng = $datos["latlng"];
		$forsyth_county = $datos["forsyth_county"];
		$sewer_system = $datos["sewer_system"];
		if (isset($datos["textos"])) {
			$constructoras=  json_encode ($datos["textos"]);
			$constructoras = str_replace("[", "", $constructoras);
	  		$constructoras = str_replace("]", "", $constructoras);
			$buscar = ["\'", "\""];
			$reemplazar = ["", ""];
			foreach ($buscar as $index => $value)
			{
			$constructoras = preg_replace("/".$buscar[$index]."/", $reemplazar[$index], $constructoras);
			}

		error_log( print_r( $datos, true ));

		} else { $constructoras=""; }

		if ($option == "crear") {

			$consulta->nuevo_registro("INSERT into subdivisiones (nombre,lotes,direccion,lat_lng, constructoras,forsyth_county, sewer) values('$nombre', '$lotes', '$direccion', '$lat_lng', '$constructoras', '$forsyth_county', '$sewer_system')");

		}else if($option == "actualizar"){

			$consulta->actualizar_registro("UPDATE subdivisiones set nombre = '$nombre', lotes = '$lotes',  direccion = '$direccion', lat_lng = '$lat_lng' , constructoras = '$constructoras' , forsyth_county = '$forsyth_county', sewer = '$sewer_system'   where id = '$id'");
						
		}
		
		return array("status" => "ok");

	}

	public static function borrar_lista_subdivisiones($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$consulta->borrar_registro("DELETE from subdivisiones where id = '$id'");
						
		
		}

        return array("status" => "ok");

	}

	
}
