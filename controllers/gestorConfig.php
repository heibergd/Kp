<?php 


class GestorConfigController{

	
	public static function ver_paginas_permitidas($to){

		$resp = GestorConfigModel::ver_paginas_permitidas();

		
		return GestorConfigController::find_groups($resp);
		
	}
	public static function get_config($name){

		$resp = GestorConfigModel::get_config($name);

		return $resp;
		
	}

	public static function newType(){
	
		if ($_POST["name"] != "") {

			$respuesta = GestorConfigModel::newType();		
			
			return $respuesta;
		}else{
			return array("status" => false, "message" => "Name required.");
		}				

	}
	public static function updateType(){
	
		if ($_POST["name"] != "") {

			$respuesta = GestorConfigModel::updateType($_POST["id"]);		
			
			return $respuesta;
		}else{
			return array("status" => false, "message" => "Name required.");
		}				

	}
	public static function deleteType(){
		
		$respuesta = GestorConfigModel::deleteType();		
			
		return $respuesta;
				

	}
	
	public static function types(){
		$respuesta = GestorConfigModel::types();		
			
		return $respuesta;

	}

	public static function type($id){
	
		$respuesta = GestorConfigModel::type($id);		
			
		return $respuesta;			

	}

	public static function setMails(){
	
		$mails = ($_POST["mails"] != "") ?  explode(",",$_POST["mails"]) : [];

	
		if (count($mails)) {

			$respuesta = GestorConfigModel::setMails($mails);		
			
			return $respuesta;
		}else{
			return array("status" => false, "message" => "Emails required.", "mails" => $mails);
		}				

	}
	public static function deleteMail(){
		
		$respuesta = GestorConfigModel::deleteMail();		
			
		return $respuesta;
				

	}
	
	public static function daily_mails(){
		$respuesta = GestorConfigModel::daily_mails();		
			
		return $respuesta;

	}

	
	public static function list_items($items, $to){
		$values_config = GestorConfigModel::restrictions_pages($to);
		
		for ($i=0; $i < count($items); $i++) { 
			
			$item = $items[$i];

			$id = $item["config_id"];
			$name = $item["cName"];
			$value = $item["config_value"];

			$attr = (isset($values_config->$value)) ? "checked" : "";

			echo "

				<div class='kt-notification-v2__item'>
					<div class='kt-notification-v2__item-icon'>
						<label class='kt-checkbox kt-checkbox--brand'>
							<input type='checkbox' data-id='$id' data-key='$value' data-to='$to' class='actualizar_config_seguridad' $attr id='ch_$id'>
							<span class='center-checkbox'></span>
						</label>
					</div>
					<div class='kt-notification-v2__itek-wrapper text-left'>
						<div class='kt-notification-v2__item-title'>
							<label for='ch_$id'>$name</label>							
						</div>
					</div>
				</div>
				
			";
		}
	}

	public static function find_groups($array){

		$groups = [];

		for ($i=0; $i < count($array); $i++) { 
			$config_name = explode("-",$array[$i]["config_name"]);
			$name_group = $config_name[0];
			if (!isset($groups[$name_group])) {
				$bs = GestorConfigController::find_bothers($array, $name_group);
				$bsx["bName"] = $name_group;
				$bsx["list"] = $bs;
				$groups[$name_group] = $bsx;
			}
			
		}

		return $groups;
	}
	public static function find_bothers($array, $group){

		$brothers = [];

		for ($i=0; $i < count($array); $i++) { 
			$config_name = explode("-",$array[$i]["config_name"]);
			$name_group = $config_name[0];
			if ($name_group == $group) {
				$b = $array[$i];
				$b["cName"] = isset($config_name[1]) ? $config_name[1] : $name_group; 
				array_push($brothers, $b);
			}
			

		}

		return $brothers;
	}

	public static function verficar_usuario($name, $ban = true){

		$resp = GestorConfigModel::verficar_usuario();

		if (!isset($resp->$name)) {
			if ($ban) {
				echo "    
					<div class='access_d text-center'>
						<h3>You don't have access to this section</h3>
						<h1>:(</h1>
						<h2><a href='profile'>Profile</a></h2>
					</div>									
				";

				exit();
			}
			
			return false;
		}

		return true;
		

	}
	public static function update_field_config(){
		$error = false;
		
		if ($_POST["value"] == "") {
			$error = "ingrese un valor";
		}						

		if (!$error) {
					
			$respuesta = GestorConfigModel::update_field_config();		

			if ($respuesta == "ok") {

				$arr["status"] = "ok";

				return $arr;
			}else{				
				return $respuesta;
			}		

		}else{
			$arr["status"] = "error";
			$arr["error"] = $error;

			return $arr;
		}
	}
	public static function update_field_config_seg(){
		$error = false;
		
		if ($_POST["value"] == "") {
			$error = "ingrese un valor";
		}						

		if (!$error) {
					
			$respuesta = GestorConfigModel::update_field_config_seg();		

			if ($respuesta == "ok") {

				$arr["status"] = "ok";

				return $arr;
			}else{				
				return $respuesta;
			}		

		}else{
			$arr["status"] = "error";
			$arr["error"] = $error;

			return $arr;
		}
	}
	
	public static function add_page_restrinction(){
					
		$respuesta = GestorConfigModel::add_page_restrinction();	
		
		return $respuesta;
			
	}

	public static function ago($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'y' => 'año',
            'm' => 'mes',
            'w' => 'semana',
            'd' => 'día',
            'h' => 'hora',
            'i' => 'minuto',
            's' => 'segundo',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
    
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ?  'Hace ' . implode(', ', $string) : 'justo ahora';
    }

	
}