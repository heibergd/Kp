<?php 
	// session_start();
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/auth.php';
	require_once '../../models/gestorConfig.php';
	require_once '../../controllers/auth.php';
	require_once '../../controllers/gestorConfig.php';
    


class AuthAjax{

	public function nuevo_registro(){
		$respuesta = AuthController::nuevo_registro();

		echo json_encode($respuesta);
	}

	public function inicio_sesion(){
		$respuesta = AuthController::inicio_sesion();

		echo json_encode($respuesta);
	}

	public function actualizar_info(){
		$respuesta = AuthController::actualizar_info();

		echo json_encode($respuesta);
	}
	public function actualizar_password(){
		$respuesta = AuthController::actualizar_password();

		echo json_encode($respuesta);
	}
	
	

}


$a = new AuthAjax();

//ACIONES AJAX PARA GESTIONAR USUARIOS

	if (isset($_POST["nuevo_registro"])) {

		$a ->nuevo_registro();
	}

	if (isset($_POST["inicio_sesion"])) {

		$a ->inicio_sesion();
	}

	if (isset($_POST["actualizar_info"])) {

		$a ->actualizar_info();
	}

	if (isset($_POST["actualizar_password"])) {

		$a ->actualizar_password();
	}
	
	
