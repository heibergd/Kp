<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorConfig.php';
	require_once '../../models/gestorSubdivision.php';
	require_once '../../controllers/gestorConfig.php';
	require_once '../../controllers/gestorSubdivision.php';


class GestorSubdivisiones{

	public function crear(){
		$respuesta = GestorSubdivisionesController::crear();

		echo json_encode($respuesta);
	}
	public function actualizar(){

		$access = GestorConfigModel::verficar_usuario();

		$respuesta = GestorSubdivisionesController::actualizar();
		$respuesta["access"] = $access;

		echo json_encode($respuesta);
	}
	public function info_sub(){
		$access = GestorConfigModel::verficar_usuario();
		$respuesta = GestorSubdivisionesController::info_sub($_POST["id"]);
		$respuesta["access"] = $access;

		echo json_encode($respuesta);
	}
	
    public function borrar_lista_subdivisiones(){
		$respuesta = GestorSubdivisionesController::borrar_lista_subdivisiones();

		echo json_encode($respuesta);
	}
	
	
}


$a = new GestorSubdivisiones();


if (isset($_POST["crear"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->newSubdivision) || $_SESSION["tipo"] == 0) {
		$a->crear();		
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}		
}
if (isset($_POST["actualizar"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->editSubdivision) || $_SESSION["tipo"] == 0) {
		$a->actualizar();			
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
}

if (isset($_POST["info_sub"])) {
	$a->info_sub();	
}

if (isset($_POST["borrar_lista_subdivisiones"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->deleteSubdivision) || $_SESSION["tipo"] == 0) {
		$a->borrar_lista_subdivisiones();    	
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}		
}


if (isset($_POST["filtrar"])) {
	$_SESSION["filtrarSubdivisions"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtrarSubdivisions"]));
}


	

