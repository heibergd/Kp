<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorWorkers.php';
	require_once '../../models/gestorConfig.php';
	require_once '../../controllers/gestorWorkers.php';
	require_once '../../controllers/gestorConfig.php';
	
	
class GestorWorkers{

	public function crear(){
		$respuesta = GestorWorkersController::crear();

		echo json_encode($respuesta);
	}
	public function update(){
		$respuesta = GestorWorkersController::update();

		echo json_encode($respuesta);
	}
	
    public function borrar_lista_workers(){
		$respuesta = GestorWorkersController::borrar_lista_workers();

		echo json_encode($respuesta);
	}

	public function info_worker(){

		$access = GestorConfigModel::verficar_usuario();

		$respuesta = GestorWorkersController::info_worker($_POST["id"]);
		$respuesta["access"] = $access;

		echo json_encode($respuesta);
	}
	

	
	
}


$a = new GestorWorkers();


if (isset($_POST["crear"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->newWorker) || $_SESSION["tipo"] == 0) {
		$a->crear();		
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
}
if (isset($_POST["update"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->editWorker) || $_SESSION["tipo"] == 0) {
		$a->update();	
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
    
}

if (isset($_POST["borrar_lista_workers"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->deleteWorker) || $_SESSION["tipo"] == 0) {		
		$a->borrar_lista_workers();
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
}

if (isset($_POST["info_worker"])) {
    $a->info_worker();
}


if (isset($_POST["filtrar"])) {
	$_SESSION["filtroWorkers"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtroWorkers"]));
}

	

