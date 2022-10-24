<?php 
	// session_start();
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../controllers/gestorUsuarios.php';
	require_once '../../models/gestorUsuarios.php';


class Ajax{

	public function jsonUsers(){
		$respuesta = GestorUsuariosController::jsonUsers();


		$resp = array(
			"meta" => [
				"page" => 1,
				"pages"=> 1,
				"perpage"=>-1,
				"total"=> count($respuesta),
				"sort"=>"asc",
				"field"=>"CompanyAgent"
			],
			"data" => $respuesta
		);
		echo json_encode($resp);
	}



}


$a = new Ajax();

$a ->jsonUsers();
	

