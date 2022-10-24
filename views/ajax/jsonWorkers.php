<?php 
	// session_start();
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../controllers/gestorWorkers.php';
	require_once '../../models/gestorWorkers.php';


class  JsonWorkersAjax{

	public function jsonWorkers(){
		$respuesta = GestorWorkersController::jsonWorkers();


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


$a = new JsonWorkersAjax();

$a ->jsonWorkers();
	

