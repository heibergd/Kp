<?php 

class RegistroModel{
	public static function nuevoRegistroModel($datos){

		$consulta = new Consulta();
		
		$usuario = $datos["usuario"];
		$password = $datos["password"];
		$correo = $datos["correo"];
		// $photo = "views/images/perfiles/avatar.jpg";

		$opciones = [  'cost' => 11 ];
		$encriptacion = password_hash($password, PASSWORD_DEFAULT, $opciones);

		$sql = "INSERT INTO usuarios (usuario, password, correo) values ('$usuario','$encriptacion','$correo')";

		$consulta -> nuevo_registro($sql);
		
	
		return "ok";
		
	}
}
