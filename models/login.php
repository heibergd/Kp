<?php


class IngresoModels{

	public static function ingresoModel($datosModel, $tabla){

		$consulta = new Consulta();

		$usuario = $datosModel["usuario"];
		$stmt = $consulta->ver_registros("SELECT * FROM $tabla WHERE usuario = '$usuario'");

		return $stmt;
		
	}

	

}