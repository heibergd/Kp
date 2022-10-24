<?php 
if(!isset($_SESSION)){ 
    session_start(); 
} 
require_once '../../models/conexion.php';
require_once '../../models/consulta.php';
require_once '../../models/gestorConfig.php';

require_once '../../controllers/gestorConfig.php';

class GestorConfigAjax{


	public function update_field_config(){
		$respuesta = GestorConfigController::update_field_config();
		echo json_encode($respuesta);
	}
	public function update_field_config_seg(){
		$respuesta = GestorConfigController::update_field_config_seg();
		echo json_encode($respuesta);
	}
	
	public function addPageRes(){
		$respuesta = GestorConfigController::add_page_restrinction();
		echo json_encode($respuesta);
	}

	public function update_config_user(){
		$respuesta = GestorConfigController::update_field_config_user();
		echo json_encode($respuesta);
	}
	
	public function newType(){
		$respuesta = GestorConfigController::newType();
		echo json_encode($respuesta);
	}
	public function updateType(){
		$respuesta = GestorConfigController::updateType();
		echo json_encode($respuesta);
	}
	
	public function deleteType(){
		$respuesta = GestorConfigController::deleteType();
		echo json_encode($respuesta);
	}
	public function type(){
		$access = GestorConfigModel::verficar_usuario();

		$respuesta = GestorConfigController::type($_POST["id"]);
		$respuesta["access"] = $access;

		echo json_encode($respuesta);
	}
	
	public function setMails(){
		$respuesta = GestorConfigController::setMails();
		echo json_encode($respuesta);
	}
	public function deleteMail(){
		$respuesta = GestorConfigController::deleteMail();
		echo json_encode($respuesta);
	}

}

$config = new GestorConfigAjax();


if (isset($_POST["update_field_config"])) {
	$config -> update_field_config();
}
if (isset($_POST["update_field_config_seg"])) {
	$config -> update_field_config_seg();
}

if (isset($_POST["addPageRes"])) {
	$config -> addPageRes();
}
if (isset($_POST["update_config_user"])) {
	$config -> update_config_user();
}
if (isset($_POST["newType"])) {
	$config -> newType();
}
if (isset($_POST["updateType"])) {
	$config -> updateType();
}

if (isset($_POST["deleteType"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->deleteType) || $_SESSION["tipo"] == 0) {
		$config -> deleteType();
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}
	
}

if (isset($_POST["type"])) {
	$config -> type();
}


if (isset($_POST["setMails"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->newMail) || $_SESSION["tipo"] == 0) {
		$config -> setMails();
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}
	
}

if (isset($_POST["deleteMail"])) {
	$access = GestorConfigModel::verficar_usuario();
	if (isset($access->deleteMail) || $_SESSION["tipo"] == 0) {
		$config -> deleteMail();
	}else{
		echo json_encode(array("status"=> false, "message" => "You don't have access to this action"));
	}
	
}


if (isset($_POST["access"])) {
	$access = GestorConfigModel::verficar_usuario();
	echo json_encode($access);
}
