<?php 
	
	require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
	require_once '../../models/gestorUsuarios.php';
	require_once '../../models/gestorConfig.php';
	require_once '../../controllers/gestorUsuarios.php';
	require_once '../../controllers/gestorConfig.php';
	
	
class GestorUsuarios{

	public function crear(){
		$respuesta = GestorUsuariosController::crear();

		echo json_encode($respuesta);
    }
    public function borrar_lista_usuarios(){
		$respuesta = GestorUsuariosController::borrar_lista_usuarios();

		echo json_encode($respuesta);
	}

	public function actualizar_estados(){
		$respuesta = GestorUsuariosController::actualizar_estados();

		echo json_encode($respuesta);
	}
	public function actualizar_tipos(){
		$respuesta = GestorUsuariosController::actualizar_tipos();

		echo json_encode($respuesta);
	}

	public function cambiarImagenPerfil(){
		$respuesta = GestorUsuariosController::cambiarImagenPerfil();

		echo json_encode($respuesta);
	}
	public function crear_peticion(){
		$respuesta = GestorUsuariosController::crear_peticion();

		echo json_encode($respuesta);
	}
	public function peticiones(){
		$respuesta = GestorUsuariosController::peticiones();

		echo json_encode($respuesta);
	}

	
	public function nueva_ruta(){
		$respuesta = GestorUsuariosController::nueva_ruta();

		echo json_encode($respuesta);
	}
	public function borrar_ruta(){
		$respuesta = GestorUsuariosController::borrar_ruta();

		echo json_encode($respuesta);
	}
    
	
	
}


$a = new GestorUsuarios();

//ACIONES AJAX PARA GESTIONAR USUARIOS

if (isset($_POST["crear"])) {
    $a->crear();
}

if (isset($_POST["borrar_lista_usuarios"])) {
    $a->borrar_lista_usuarios();
}

if (isset($_POST["actualizar_estados"])) {
    $a->actualizar_estados();
}

if (isset($_POST["actualizar_tipos"])) {
    $a->actualizar_tipos();
}

if (isset($_POST["cambiarImagenPerfil"])) {
    $a->cambiarImagenPerfil();
}
if (isset($_POST["crear_peticion"])) {
    $a->crear_peticion();
}
if (isset($_POST["peticiones"])) {
    $a->peticiones();
}



if (isset($_POST["filtrar"])) {
	$_SESSION["filtroUsers"] = $_POST["search"];
	echo json_encode(array("q" => $_SESSION["filtroUsers"]));
}

// rutas

if (isset($_POST["nueva_ruta"])) {	
    $a->nueva_ruta();
}

if (isset($_POST["borrar_ruta"])) {	
    $a->borrar_ruta();
}


	

