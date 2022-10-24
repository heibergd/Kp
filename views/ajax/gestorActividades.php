<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorConfig.php';
	require_once '../../models/auth.php';
	require_once '../../models/gestorActividades.php';
	require_once '../../controllers/gestorConfig.php';
	require_once '../../controllers/auth.php';
	require_once '../../controllers/gestorActividades.php';


class GestorActividades{

	public function crear(){
		$respuesta = GestorActividadesController::crear();

		echo json_encode($respuesta);
	}
	public function info_actividad(){

		$access = GestorConfigModel::verficar_usuario();

		$respuesta = GestorActividadesController::info_actividad($_POST["id"]);
		$respuesta["access"] = $access;

		echo json_encode($respuesta);
	}
	
	public function actualizar(){
		$respuesta = GestorActividadesController::actualizar();

		echo json_encode($respuesta);
    }
    public function borrar_lista_actividades(){
		$respuesta = GestorActividadesController::borrar_lista_actividades();

		echo json_encode($respuesta);
	}

	public function actualizar_estados(){
		$respuesta = GestorActividadesController::actualizar_estados();

		echo json_encode($respuesta);
	}
	public function list_act(){
		$respuesta = GestorActividadesController::actividades($_POST["page"], $_POST["direc"], $_POST["init"]);

		echo json_encode($respuesta);
	}

	public function act_by_lot(){
		$respuesta = GestorActividadesController::act_by_lot($_POST["subdivision"], $_POST["lote"]);

		echo json_encode($respuesta);
	}
	public function search_descipciones(){
		$respuesta = GestorActividadesController::search_descipciones($_POST["subdivision"], $_POST["lote"]);

		echo json_encode($respuesta);
	}
	
	public function searchLot(){
		$respuesta = GestorActividadesController::searchLot($_POST["val"]);

		echo json_encode($respuesta);
	}

	public function archivos(){
		$respuesta = GestorActividadesController::archivos($_POST["lote"],$_POST["sub"]);

		echo json_encode($respuesta);
	}
	public function deleteAttachment(){
		$respuesta = GestorActividadesController::deleteAttachment($_POST["id"]);

		echo json_encode($respuesta);
	}

	public function ver_ultima_direccion(){
		$respuesta = GestorActividadesController::ver_ultima_direccion();

		echo json_encode($respuesta);
	}

	
	
	
}


$a = new GestorActividades();


if (isset($_POST["crear"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->newActivitie) || $_SESSION["tipo"] == 0) {
		$a->crear();	
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}	
}
if (isset($_POST["info_actividad"])) {	
	$a->info_actividad();	
}

if (isset($_POST["verUltimaDireccion"])) {	
	$a->ver_ultima_direccion();	
}

if (isset($_POST["actualizar"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->editActivitie) || $_SESSION["tipo"] == 0) {
		$a->actualizar();		
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}		
}
if (isset($_POST["actualizar_estados"])) {
	$a->actualizar_estados();
}
if (isset($_POST["borrar_lista_actividades"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->deleteActivitie) || $_SESSION["tipo"] == 0) {
		$a->borrar_lista_actividades();
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}
	
}
if (isset($_POST["list_act"])) {
	$a->list_act();
}
if (isset($_POST["act_by_lot"])) {
	$a->act_by_lot();
}
if (isset($_POST["search_descipciones"])) {
	$a->search_descipciones();
}
if (isset($_POST["searchLot"])) {
	$a->searchLot();
}
if (isset($_POST["archivos"])) {
	$a->archivos();
}
if (isset($_POST["deleteAttachment"])) {
	$a->deleteAttachment();
}


if (isset($_POST["filtrar"])) {
	$_SESSION["filtrarAcivitys"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtrarAcivitys"]));
}
if (isset($_POST["set_filter_list"])) {
	$key = $_POST["key"];
	$val = $_POST["val"];
	$_SESSION[$key] = $val;

	if ($key == "filter_daterange") {
		$_SESSION["specific_date"] = "";	
	}
	if ($key == "specific_date") {
		$_SESSION["filter_daterange"] = "";
	}
	

	echo json_encode(array("q" => $_SESSION[$key]));
}

if (isset($_POST["set_filter_lot"])) {
	$sub = $_POST["sub"];
	$lot = $_POST["lot"];
	$_SESSION["sub_lot"] = $sub;
	$_SESSION["lot_"] = $lot;
	echo json_encode(array("q" => $sub, "l" => $lot));
}
if (isset($_POST["set_filters"])) {
	
	$_SESSION["filter_sub"] = $_POST["filter_sub"];
	$_SESSION["filter_lot"] = $_POST["filter_lot"];
	$_SESSION["filter_type"] = $_POST["filter_type"];
	$_SESSION["filter_worker"] = $_POST["filter_worker"];
	$_SESSION["filter_status"] = $_POST["filter_status"];
	// $_SESSION["specific_date"] = "";
	// $_SESSION["filter_daterange"] = $_POST["filter_daterange"];
	$_SESSION["filter_billi"] = isset($_POST["filter_billi"]) ? $_POST["filter_billi"] : "";
	
	echo json_encode(array("status" => true, "data" => $_POST));
}

if (isset($_POST["removeAllFilters"])) {
	$_SESSION["filter_sub"] = "";
	$_SESSION["filter_lot"] = "";
	$_SESSION["filter_type"] = "";
	$_SESSION["filter_worker"] = "";
	$_SESSION["filter_status"] = "";
	$_SESSION["specific_date"] = "";
	$_SESSION["filter_daterange"] = "";
	$_SESSION["filter_billi"] = "";
	
	echo json_encode(array("status" => true));
}

	

