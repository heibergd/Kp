<?php 
	// session_start();
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../controllers/gestorActividades.php';
	require_once '../../models/gestorActividades.php';


class  JsonActiviAjax{

	public function jsonActividades(){
		$respuesta = GestorActividadesController::jsonActividades();


		$resp = array(
			"meta" => [
				"page" => 1,
				"pages"=> 1,
				"perpage"=>-1,
				"total"=> count($respuesta),
				"sort"=>"asc",
				"field"=>"id"
			],
			"data" => $respuesta
		);
		echo json_encode($resp);
	}



}


$a = new JsonActiviAjax();

$a ->jsonActividades();
	

