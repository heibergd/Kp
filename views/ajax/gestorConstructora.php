<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorConstructora.php';
	require_once '../../controllers/gestorConstructora.php';
	
	
class GestorConstructora{

	public function crear(){
		$respuesta = GestorConstructoraController::crear();

		echo json_encode($respuesta);
	}
	public function update(){
		$respuesta = GestorConstructoraController::update();

		echo json_encode($respuesta);
	}
	
    public function borrar_lista_constructoras(){
		$respuesta = GestorConstructoraController::borrar_lista_constructoras();

		echo json_encode($respuesta);
	}

	public function listar_constructoras(){
		$respuesta = GestorConstructoraController::listar_constructoras();

		echo json_encode($respuesta);
	}

	public function info_constructora(){

		$respuesta = GestorConstructoraController::info_constructora($_POST["id"]);

		echo json_encode($respuesta);
	}
	
	
}


$a = new GestorConstructora();

if (isset($_POST["crear"])) {
	$a->crear();		
}

if (isset($_POST["update"])) {
	$a->update();		
}

if (isset($_POST["listar"])) {
	$a->listar_constructoras();		
}

if (isset($_POST["borrar_lista_constructoras"])) {
	$a->borrar_lista_constructoras();
}

if (isset($_POST["info_constructora"])) {
    $a->info_constructora();
}

if (isset($_POST["filtrar"])) {
	$_SESSION["filtroConstructora"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtroConstructora"]));
}

	

