<?php 
	// session_start();
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../controllers/gestorConstructora.php';
	require_once '../../models/gestorConstructora.php';


class  JsonConstructorasAjax{

	public function jsonConstructoras(){
		$respuesta = GestorConstructoraController::jsonConstructoras();


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


$a = new JsonConstructorasAjax();

$a ->jsonConstructoras();
	

