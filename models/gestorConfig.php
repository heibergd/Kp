<?php 

class GestorConfigModel{


	public static function ver_paginas_permitidas(){

		$consulta = new Consulta();

		$pages = $consulta->ver_registros("select * from config where config_code = 'SSU'");

		return $pages;
		
	}

	public static function get_config($name){
		$consulta = new Consulta();

		$resp = $consulta->ver_registros("SELECT * from config where config_name = '$name'");

		$resp = isset($resp[0]) ? $resp[0] : [];

		return $resp;
	}
	
	public static function verficar_usuario(){

		$consulta = new Consulta();

		$t = ($_SESSION["tipo"] == 0) ? "admin" : "users";

		$config = GestorConfigModel::restrictions_pages($t);

		if ($_SESSION["tipo"] == 0) {
			$config->activities = true;
			$config->newActivitie = true;
			$config->editActivitie = true;
			$config->deleteActivitie = true;
			$config->security = true;
			$config->subdivisions = true;
			$config->newSubdivision = true;
			$config->editSubdivision = true;
			$config->deleteSubdivision = true;
			$config->workers = true;
			$config->newWorker = true;
			$config->editWorker = true;
			$config->deleteWorker = true;
			$config->types = true;
			$config->newType = true;
			$config->editType = true;
			$config->deleteType = true;
			$config->mail = true;
			$config->newMail = true;
			$config->editMail = true;
			$config->deleteMail = true;

		}
		
		return $config;		
	}
	
	// CRUD tipos de actividad
	public static function newType(){

		$consulta = new Consulta();

		$name = htmlspecialchars($_POST["name"], ENT_QUOTES);

		$textos = isset($_POST["textos"]) ? $_POST["textos"] : [];

		$settigns = json_encode(array(
			"attachment" => isset($_POST["attachment"]) ? true : false,
			"default_billiable" => isset($_POST["default_billiable"]) ? true : false,			
			"archive" => isset($_POST["archive"]) ? true : false,
			"warning" => isset($_POST["warning"]) ? true : false,
			"billiable" => isset($_POST["billiable"]) ? true : false,
			"marked" => isset($_POST["marked"]) ? true : false,
			"ordered" => isset($_POST["ordered"]) ? true : false,
			"color_marked" => $_POST["color_marked"],
			"color_ordered" => $_POST["color_ordered"],
			"defaul_color_" => $_POST["defaul_color_"],
			"textos" => $textos
		));

		$consulta->nuevo_registro("INSERT into tipos (nombre, settings) values ('$name', '$settigns')");

		return array("status" => true);

	}

	public static function updateType($id){

		$consulta = new Consulta();

		$name = htmlspecialchars($_POST["name"], ENT_QUOTES);

		$textos = isset($_POST["textos"]) ? $_POST["textos"] : [];

		$settigns = json_encode(array(
			"attachment" => isset($_POST["attachment"]) ? true : false,
			"default_billiable" => isset($_POST["default_billiable"]) ? true : false,	
			"archive" => isset($_POST["archive"]) ? true : false,
			"warning" => isset($_POST["warning"]) ? true : false,
			"billiable" => isset($_POST["billiable"]) ? true : false,
			"marked" => isset($_POST["marked"]) ? true : false,
			"ordered" => isset($_POST["ordered"]) ? true : false,
			"color_marked" => $_POST["color_marked"],
			"color_ordered" => $_POST["color_ordered"],
			"defaul_color_" => $_POST["defaul_color_"],
			"textos" => $textos
		));

		$consulta->actualizar_registro("UPDATE tipos set nombre = '$name', settings = '$settigns' where id = '$id'");

		$ar = isset($_POST["archive"]) ? "1" : "0";

		$consulta->actualizar_registro("UPDATE actividades set archivo = '$ar' where tipo = '$id'");

		return array("status" => true);

	}

	public static function deleteType(){

		$consulta = new Consulta();

		$id = $_POST["id"];

		$consulta->borrar_registro("DELETE from tipos where id = '$id'");

		$consulta->actualizar_registro("UPDATE actividades set tipo = '0' where tipo = '$id'");
		
		return array("status" => true);

	}

	public static function types(){
		$consulta = new Consulta();


		$resp = $consulta->ver_registros("SELECT * from tipos");

		for ($i=0; $i < count($resp); $i++) { 
			$resp[$i]["settings"] = json_decode($resp[$i]["settings"]);
		}

		return $resp;
	}

	public static function type($id){
		$consulta = new Consulta();


		$resp = $consulta->ver_registros("SELECT * from tipos where id = '$id'");

		for ($i=0; $i < count($resp); $i++) { 
			$resp[$i]["settings"] = json_decode($resp[$i]["settings"]);
		}

		return isset($resp[0]) ? $resp[0] : [];
	}

	// ------------------------

	// establecer emails para enviar avisos diarios
	public static function setMails($mails){

		$consulta = new Consulta();

		for ($i=0; $i < count($mails); $i++) { 
			$mail = $mails[$i];

			if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
				$consulta->nuevo_registro("INSERT into daily_mails (mail) values ('$mail')");
			}
		}

		return array("status" => true);

	}
	public static function deleteMail(){

		$consulta = new Consulta();

		$id = $_POST["id"];

		$consulta->borrar_registro("DELETE from daily_mails where id = '$id'");
	
		return array("status" => true);

	}
	
	public static function daily_mails(){
		$consulta = new Consulta();


		$resp = $consulta->ver_registros("SELECT * from daily_mails");

		return $resp;
	}

	// actualizar configuración según su nombre
	public static function update_field_config(){

		$consulta = new Consulta();

		$val = $_POST["value"];
		$key = $_POST["key"];


		$consulta -> actualizar_registro("update config set config_value = '$val' where config_name = '$key'");

		return "ok";

	}

	// actualizar configuracion se seguridad para restringir páginas
	public static function update_field_config_seg(){

		$consulta = new Consulta();

		$val = $_POST["value"];
		$key = $_POST["key"];
		$id = $_POST["id_p"];
		$to = $_POST["to"];

		$config = GestorConfigModel::restrictions_pages($to);
	
		if ($val == "true") {
			$config->$key = "true";
		}else{
			unset($config->$key);
		}
		$string = json_encode($config);
		$a = $to . "_r";

		$consulta -> actualizar_registro("update config set config_value = '$string' where config_name = '$a'");

		return "ok";

	}

	// ver configuracion de restriccion de páginas
	public static function restrictions_pages($to){

		$consulta = new Consulta();
		$a = $to . "_r";
		
		$config = $consulta->ver_registros("select * from config where config_name = '$a'");

		if (isset($config[0])) {
			$array = json_decode($config[0]["config_value"]);

			return $array;
		}else{
			return json_decode("{}");
		}
		
		
	}

	public static function add_page_restrinction(){

		$consulta = new Consulta();

		$nombre = $_POST["nombre"];

		$consulta ->nuevo_registro("insert into config (config_name,config_value,config_code) values ('$nombre', 'true', 'SSU')");

		
		return array("status" => 'ok');


	}

	public static function divice_type(){
		  
		$tablet_browser = 0;
		$mobile_browser = 0;
		$body_class = 'desktop';
		
		if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$tablet_browser++;
			$body_class = "tablet";
		}
		
		if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			$mobile_browser++;
			$body_class = "mobile";
		}
		
		if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
			$mobile_browser++;
			$body_class = "mobile";
		}
		
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
		$mobile_agents = array(
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
			'newt','noki','palm','pana','pant','phil','play','port','prox',
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
			'wapr','webc','winw','winw','xda ','xda-');
		
		if (in_array($mobile_ua,$mobile_agents)) {
			$mobile_browser++;
		}
		
		if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
			$mobile_browser++;
			//Check for tablets on opera mini alternative headers
			$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
			if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
			$tablet_browser++;
			}
		}

		return $body_class;
	}


}