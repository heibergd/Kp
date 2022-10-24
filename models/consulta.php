<?php  
	class Consulta extends Conexion{
		public function __construct(){
			parent::__construct();
		}
		public function registro($sql)
		{
			$consulta = $this->conexion->prepare($sql);

			$consulta->execute();

			$resultado = $consulta->fetch(PDO::FETCH_BOTH);

			return $resultado;
		}

		public function ver_registros($sql){
			$consulta = $this->conexion->prepare($sql);

			$consulta->execute();

			$resultado = $consulta->fetchAll(PDO::FETCH_BOTH);

			return $resultado;
		}
		public function nuevo_registro($sql){
			try{
				$consulta = $this->conexion->prepare($sql);
				$resultado = $consulta->execute();
				return $resultado;
			}catch(Exception $e){
				die("Ha ocurrido un error al insertar un nuevo registro (" . $e->getLine() . ") " . $e->getMessage());
			}
		}
		public function nuevo_registro_bim_param($table,$items){
			try{

				$stritems = "";
				$campos = "";
				$valores = "";

				for ($i=0; $i < count($items); $i++) { 					
					$name= $items[$i]["name"];
					$value = $items[$i]["value_name"];
					$campos .= ($campos == "") ? $name : ", " . $name;
					$valores .= ($valores == "") ? ":".$value : ", :" . $value;
				}

				echo "INSERT INTO $table ($campos) VALUES ($valores)";

				$consulta = $this->conexion->prepare("INSERT INTO $table ($campos) VALUES ($valores)");					

				for ($j=0; $j < count($items); $j++) { 
					
					if ($items[$j]["type"] == "int") {
						$consulta -> bindParam(":".$items[$j]["value_name"], $items[$j]["value"], PDO::PARAM_INT);
					}else{
						$consulta -> bindParam(":".$items[$j]["value_name"], $items[$j]["value"], PDO::PARAM_STR);
					}
				}
				
				$resultado = $consulta->execute();
				return $resultado;
			}catch(Execption $e){
				die("Ha ocurrido un error al actualizar el registro " . $e->getMessage());
			}
		}
		public function borrar_registro($sql){
			try{
				$consulta = $this->conexion->prepare($sql);
				$resultado = $consulta->execute();
				return $resultado;
				
			}catch(Exception $e){
				die("Ha ocurrido un error al borrar el registro (" . $e->getLine() . ") " . $e->getMessage());
			}
		}
		public function actualizar_registro($sql){
			try{
				$consulta = $this->conexion->prepare($sql);
				$resultado = $consulta->execute();
				return $resultado;
			}catch(Execption $e){
				die("Ha ocurrido un error al actualizar el registro");
			}
		}

		public function actualizar_registro_bim_param($table,$items,$condicions,$op){
			try{

				$stritems = "";

				for ($i=0; $i < count($items); $i++) { 					
					$name= $items[$i]["name"];
					$value = $items[$i]["value_name"];
					$itemString = $name . " = " . $value; 
					$stritems .= $itemString;
				}

				$consulta = $this->conexion->prepare("UPDATE $table SET $stritems $condicions");

				for ($j=0; $j < count($items); $j++) { 
					if ($op == "string") {
						$consulta -> bindParam($items[$j]["value_name"], $items[$j]["value"], PDO::PARAM_STR);	
					}else{
						$consulta -> bindParam($items[$j]["value_name"], $items[$j]["value"], PDO::PARAM_INT);
					}					
				}
				
				$resultado = $consulta->execute();
				return $resultado;
			}catch(Execption $e){
				die("Ha ocurrido un error al actualizar el registro " . $e->getMessage());
			}
		}

		public function crearItems($names,$values){

			$arrayNames = explode(",",$names);
			$arrayValues = explode(",",$values);
			$items = [];

			for ($i=0; $i < count($arrayNames); $i++) {

				$v = explode("-",$arrayNames[$i]); 

				$item["name"] = $v[0];
				$item["value_name"] = $v[0];				
				$item["value"] = $arrayValues[$i];
				$item["type"] = $v[1];

				array_push($items,$item);
			}

			return $items;
			
		}
	}
