<?php
session_start();
class Ingreso{

	public static function ingresoController(){

		$array = [];

		if(isset($_POST["usuario"])){

			$datosController = array("usuario"=>$_POST["usuario"],
			                     "password"=>$_POST["password"]);

			$respuesta = IngresoModels::ingresoModel($datosController, "usuarios");
		

			if (count($respuesta) == 0) {
				$array["status"] = "error";
				$array["mensaje"] = "Error al ingresar, usuario incorrecto";

				return $array;
			}else{
				if($respuesta[0]["usuario"] == $_POST["usuario"]){

					if (password_verify($_POST["password"], $respuesta[0]["password"])) {
						Ingreso::actualizar_sessions($respuesta[0]);

						$array["status"] = "ok";

						return $array;
					}else{
						$array["status"] = "error";
						$array["mensaje"] = "Error al ingresar, contraseña incorrecta";

						return $array;
					}
				}
				else{

					$array["status"] = "error";
					$array["mensaje"] = "Error al ingresar, usuario incorrecto";

					return $array;

				}
			}

		}
	}

	public static function actualizar_sessions($array){


			$_SESSION["validar_ali"] = true;
			$_SESSION["usuario"] = $array["usuario"];
			$_SESSION["id"] = $array["idusuario"];
			$_SESSION["password"] = $array["password"];
			$_SESSION["email"] = $array["correo"];
						
	}

}