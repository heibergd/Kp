<?php 
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
if(!isset($_SESSION)){ 
    session_start(); 
} 
class GestorActividadesController{

	public static function jsonActividades(){

        $actividades = GestorActividadesModel::jsonActividades();
     
		return $actividades;

    }
    
    public static function actividades_total(){
        return count(GestorActividadesModel::actividades_total());
    }

    public static function actividades($pagina, $direccion, $init){

        $paginas = 10;
        
        // $numeros = count(GestorActividadesModel::actividades_total($direccion));

        // $total_paginas = ceil($numeros / $paginas);

        $inicio = ($pagina - 1) * $paginas;

        // if ($pagina > "1") {
        //     $inicio += 1;
        // }

        $actividades = GestorActividadesModel::actividades($inicio, $paginas,$direccion, $init);
     
		return $actividades;

    }
    
    public static function act_by_lot($subdivision, $lot){
        
        $actividades = GestorActividadesModel::act_by_lot($subdivision, $lot);

        return $actividades;
    }
    public static function search_descipciones($subdivision, $lot){
        
        $actividades = GestorActividadesModel::search_descipciones($subdivision, $lot);

        return $actividades;
    }

    public static function act_without_workers(){
        
        $actividades = GestorActividadesModel::act_without_workers();

        return $actividades;
    }

    public static function act_without_workers_separeted(){
        
        $actividades = GestorActividadesModel::act_without_workers_separeted();

        return $actividades;
    }

    
    public static function searchLot($val){

        if ($val != "") {
            return GestorActividadesModel::searchLot($val);
        }else{
            return [];
        }
            
    }

    public static function crear(){

        $validado = GestorActividadesController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{        
           
            $resp = GestorActividadesModel::sim($_POST, "crear");


            return $resp;
        }

        
    }
    public static function actualizar(){

        $validado = GestorActividadesController::validar($_POST);

        if ($validado["status"] == "error") {
            return $validado;
        }else{

            $resp = GestorActividadesModel::sim($_POST, "actualizar", $_POST["id"]);

            return $resp;
        }

        
    }
    
    public static function info_actividad($id){
        return GestorActividadesModel::info_actividad($id);
    }
    public static function ver_ultima_direccion(){
        return GestorActividadesModel::ver_ultima_direccion();
    }
    
    public static function validar($datos){
        $validado = array(
            "razon" => "",
            "mensaje" => "",
            "status" => "valido"
        );
        
        $info_type = GestorConfigController::type($datos["tipo"]);

        if (isset($info_type["nombre"])) {
            if ($info_type["nombre"] == "@Special") {
                if ($datos["fecha"] == "" || $datos["tipo"] == "#") {
                    $validado = array(
                        "razon" => "campos vacíos",
                        "mensaje" => "Enter the required fields.",
                        "status" => "error"
                    );
                }
            }elseif ($datos["fecha"] == "" || $datos["subdivision"] == "#" || $datos["lote"] == "" || $datos["tipo"] == "#") {
                $validado = array(
                    "razon" => "campos vacíos",
                    "mensaje" => "Enter the required fields.",
                    "status" => "error"
                );
            }
        }else if (!isset($info_type["nombre"])) {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Select Type.",
                "status" => "error"
            );
        }
        
        if (!isset($_SESSION["id_usuario"]) || $_SESSION["usuario"] == "") {
            $validado = array(
                "razon" => "Session invalid",
                "mensaje" => "No user found in session, please log in again.",
                "status" => "error"
            );
        }else{
            $s = GestorActividadesModel::ver_info_usuario($_SESSION["id_usuario"]);
            
            if (!isset($s["usuario"])) {
                $validado = array(
                    "razon" => "Session invalid",
                    "mensaje" => "No user found in session, please log in again.",
                    "status" => "error"
                );
            }
            
        }

        return $validado;

    }

    public static function borrar_lista_actividades(){
        $lista = $_POST["lista"];
        return GestorActividadesModel::borrar_lista_actividades($lista);
    
    }

    public static function actualizar_estados(){
        $lista = $_POST["lista"];
        $estado = $_POST["estado"];
        return GestorActividadesModel::actualizar_estados($lista, $estado);
    
    }
  
    public static function filters(){
    
        return GestorActividadesModel::filters();
    }

    public static function procesar_files($file_s,$id_acividad){
		
		$files = [];
		$file_count = count($file_s["name"]);
		$file_keys = array_keys($file_s);
		$srcs = [];

		for ($i=0; $i < $file_count; $i++) { 
			foreach ($file_keys as $key) {
				$files[$i][$key] = $file_s[$key][$i];
			}
		}

		$orden = GestorActividadesModel::ver_ultimo_archivo($id_acividad);
		
		for ($i=0; $i < count($files); $i++) { 

			$orden++;

			$random = rand(0,10000);

			if(isset($files[$i]["tmp_name"]) && $files[$i]["tmp_name"] != ""){

				$file = $files[$i]["tmp_name"];

				if ($files[$i]["type"] == "image/jpeg" || $files[$i]["type"] == "image/png"){
					if (is_uploaded_file($file)) {

						
						$ruta_aux = "views/files/activity_img_$random-$orden-$id_acividad.jpg";
						
						$res_move = move_uploaded_file($file, "../../".$ruta_aux);
						
						$aux_file = [
                            'path' => $ruta_aux,
                            'name' => $files[$i]["name"],
						  	'type' => "image"
						];

						array_push($srcs, $aux_file);
					}
				}


				if ($files[$i]["type"] == "video/mp4" || $files[$i]["type"] == "video/avi" || $files[$i]["type"] == "video/mkv" || $files[$i]["type"] == "video/wmv" || $files[$i]["type"] == "video/flv" || $files[$i]["type"] == "video/dvd" || $files[$i]["type"] == "video/webm"){

					if (is_uploaded_file($file)) {

						$ruta_aux = "views/files/activity_video_$random-$orden-$id_acividad.mp4";
						move_uploaded_file($file, "../../".$ruta_aux);

						$aux_file = [
                            'path' => $ruta_aux,
                            'name' => $files[$i]["name"],
						  	'type' => "video"
						];
						array_push($srcs, $aux_file);
					}
				}


				if ($files[$i]["type"] == "application/pdf"){
					if (is_uploaded_file($file)) {

						$ruta_aux = "views/files/activity_pdf_$random-$orden-$id_acividad.pdf";
						
						$res_move = move_uploaded_file($file, "../../".$ruta_aux);
						
						$aux_file = [
                            'path' => $ruta_aux,
                            'name' => $files[$i]["name"],
						  	'type' => "pdf"
						];

						array_push($srcs, $aux_file);
					}
				}


			}
			
		}

		return $srcs;

	}
  
    public static function archivos($lote, $sub){
    
        return GestorActividadesModel::archivos($lote, $sub);
    }
    
    public static function deleteAttachment($id){
    
        return GestorActividadesModel::deleteAttachment($id);
    }

    
}
