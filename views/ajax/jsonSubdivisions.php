<?php 
	// session_start();
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../controllers/gestorSubdivision.php';
	require_once '../../models/gestorSubdivision.php';


class  JsonSubAjax{

	public function jsonSubdivisiones(){
		$respuesta = GestorSubdivisionesController::jsonSubdivisiones();


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


$a = new JsonSubAjax();

$a ->jsonSubdivisiones();
	

