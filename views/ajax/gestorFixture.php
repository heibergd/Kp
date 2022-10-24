<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorFixture.php';
	require_once '../../models/gestorConfig.php';
	require_once '../../controllers/gestorFixture.php';
	require_once '../../controllers/gestorConfig.php';
	
	
class GestorFixture{

	public function crear(){
		$respuesta = GestorFixtureController::crear();

		echo json_encode($respuesta);
	}
	public function update(){
		$respuesta = GestorFixtureController::update();

		echo json_encode($respuesta);
	}
	
    public function borrar_lista_fixtures(){
		$respuesta = GestorFixtureController::borrar_lista_fixtures();

		echo json_encode($respuesta);
	}

	public function info_fixture(){

		$respuesta = GestorFixtureController::info_fixture($_POST["id"]);

		echo json_encode($respuesta);
	}
	
	
}


$a = new GestorFixture();

if (isset($_POST["crear"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->newFixture) || $_SESSION["tipo"] == 0) {
		$a->crear();		
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
}

if (isset($_POST["update"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->editFixture) || $_SESSION["tipo"] == 0) {
		$a->update();				
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}
}

if (isset($_POST["borrar_lista_fixtures"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->deleteFixture) || $_SESSION["tipo"] == 0) {
		$a->borrar_lista_fixtures();
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
}

if (isset($_POST["info_fixture"])) {
    $a->info_fixture();
}

if (isset($_POST["filtrar"])) {
	$_SESSION["filtroFixture"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtroFixture"]));
}

	

