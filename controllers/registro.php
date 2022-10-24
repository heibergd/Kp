<?php 

class Registro{
	public static function registroController(){
		$consulta = new Consulta();
		$usuario = $_POST["usuarioRegistro"];
		$password = $_POST["passwordRegistro"];
		$password2 = $_POST["passwordRegistro2"];
		$correo = $_POST["emailRegistro"];

		if ($usuario != "") {
				
			$sql="SELECT * FROM usuarios WHERE usuario ='$usuario'";
			$resultado = $consulta -> ver_registros($sql);

			if ($resultado) {
				$error=["mensaje"=>"El nombre de usuario ya esta siendo utilizado."];
			}
		}

		if ($password != "") {
			if ($password != $password2) {
				$error=["mensaje"=>"Las contraseñas no coinciden."];
			}
		}

		if ($correo != "") {

			$sql="SELECT * FROM usuarios WHERE correo ='$correo'";
			$resultado = $consulta -> ver_registros($sql);
			if ($resultado) {
				$error=["mensaje"=>"El email ya esta siendo utilizado."];
			}else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
				$error=["mensaje"=>"Formato de correo incorrecto."];
			}
		}

		if ($usuario=="" || $password == "" || $password2 == "" || $correo == "") {
			$error=["mensaje"=>"Todos los campos deben ser completados."];
		}

		if (isset($error)) {
			return $error;

		} else{
			$datos["usuario"] = $usuario;
			$datos["password"] = $password;	
			$datos["correo"] = $correo;

			$registroModel = new RegistroModel();
			$respuesta = $registroModel -> nuevoRegistroModel($datos);

			if ($respuesta == "ok") {
				$array["status"] = "ok";
			}else{
				$array["status"] = "error";
			}

			return $array;
		}

	}
}
