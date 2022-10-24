<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorRoughForm.php';
	require_once '../../controllers/gestorRoughForm.php';
	
	
class GestorRoughForm{

	public function crear(){
		$respuesta = GestorRoughFormController::crear();

		echo json_encode($respuesta);
	}
	public function update(){
		$respuesta = GestorRoughFormController::update();

		echo json_encode($respuesta);
	}
	
    public function borrar_lista_rough_forms(){
		$respuesta = GestorRoughFormController::borrar_lista_rough_forms();

		echo json_encode($respuesta);
	}

	public function info_rough_form(){

		$respuesta = GestorRoughFormController::info_rough_form($_POST["id"]);

		echo json_encode($respuesta);
	}
	
	public function buscar_lote(){
		$respuesta = GestorRoughFormController::buscar_lote();

		echo json_encode($respuesta);
	}

	public function pagos(){
		$respuesta = GestorRoughFormController::pagos();

		echo json_encode($respuesta);
	}
	
}


$a = new GestorRoughForm();

if (isset($_POST["crear"])) {
	$a->crear();		
}

if (isset($_POST["update"])) {
	$a->update();		
}

if (isset($_POST["borrar_lista_rough_forms"])) {
	$a->borrar_lista_rough_forms();
}

if (isset($_POST["info_rough_form"])) {
    $a->info_rough_form();
}

if (isset($_POST["buscar_lote"])) {
    $a->buscar_lote();
}

if (isset($_POST["pagos"])) {
    $a->pagos();
}


if (isset($_POST["filtrar"])) {
	$_SESSION["filtroRoughForm"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtroRoughForm"]));
}

	

