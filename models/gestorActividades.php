<?php 

class GestorActividadesModel{

	public static function jsonActividades(){

		$consulta = new Consulta();

		$sql = "";
		$actividades = [];
		if (isset($_SESSION["filtrarAcivitys"])) {
			$q = htmlspecialchars($_SESSION["filtrarAcivitys"], ENT_QUOTES);

			if ($q != "") {
				$sub = $consulta->ver_registros("SELECT * from subdivisiones where nombre like '%$q%' or direccion like '%$q%'");
				$work_s = $consulta->ver_registros("SELECT * from workers where nombre like '%$q%'");
				$archivos_add = $consulta->ver_registros("SELECT * from multimedia where name like '%$q%'");

				for ($i=0; $i < count($sub); $i++) { 
					$id_sub_f = $sub[$i]["id"];
					if ($sql == "") {
						$sql = "WHERE subdivision = '$id_sub_f' ";
					}else{
						$sql .= " or subdivision = '$id_sub_f' ";
					}
				}

				for ($i=0; $i < count($work_s); $i++) { 
					$id_worker_f = $work_s[$i]["id"];
					if ($sql == "") {
						$sql = "WHERE trabajador = '$id_worker_f' ";
					}else{
						$sql .= " or trabajador = '$id_worker_f' ";
					}
				}

				for ($i=0; $i < count($archivos_add); $i++) { 
					$id_add = $archivos_add[$i]["id_actividad"];
					if ($sql == "") {
						$sql = "WHERE id = '$id_add' ";
					}else{
						$sql .= " or id = '$id_add '";
					}
				}

				if ($sql == "") {
					$sql = "WHERE lote like '%$q%' or descripcion like '%$q%' or creado_por like  '%$q%' or address like '%$q%'";
				}else{
					$sql .= " or lote like '%$q%' or descripcion like '%$q%' or creado_por like '%$q%' or address like '%$q%'";
				}

				$actividades = $consulta->ver_registros("SELECT * from actividades $sql order by fecha asc");
			}

						
			// echo $sql;

			
		}
	
		

		// return $actividades;

		for ($i=0; $i < count($actividades); $i++) { 
			
			// var_dump($actividades[$i]);
			$id = $actividades[$i]["id"];
			$id_sub = $actividades[$i]["subdivision"];
			$id_worker = $actividades[$i]["trabajador"];
			$id_tipo = $actividades[$i]["tipo"];

			$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
			$info2 = $consulta->ver_registros("SELECT * from workers where id = '$id_worker'");
			$info3 = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
			$actividades[$i]["textos"] = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");

			$f = $actividades[$i]["fecha"];

			$actividades[$i]["format_us"] = date("m",strtotime($f)) . "-" .date("d",strtotime($f)) . "-". date("Y",strtotime($f));

			if (isset($info1[0])) {
				$info1[0]["nombre"] = htmlspecialchars_decode($info1[0]["nombre"], ENT_QUOTES);
				$actividades[$i]["subdivision"] = $info1[0];
			}else{
				$actividades[$i]["subdivision"] = ["nombre" => ""];
			}
			if (isset($info2[0])) {
				$actividades[$i]["worker"] = $info2[0];
			}else{
				$actividades[$i]["worker"] = ["nombre" => ""];
			}
			if (isset($info3[0])) {
				$info3[0]["settings"] = json_decode($info3[0]["settings"]);
				$actividades[$i]["tipo"] = $info3[0];
			}else{
				$actividades[$i]["tipo"] = ["nombre" => ""];
			}

		}
		
		return $actividades;

	}
	public static function actividades_total($direccion = false, $all_info = false){
		$consulta = new Consulta();

		$sql = GestorActividadesModel::filters();

	

		$hoy = date("Y-m-d");

		if ($sql == "") {
			$sql = "WHERE tipo != '0' AND archivo = '0'";
		}else{
			$sql .= " AND tipo != '0' AND archivo = '0'";
		}

		if(!$direccion){
			$actividades = $consulta->ver_registros("SELECT * from actividades $sql order by fecha ASC");
		}else{
			if ($sql != "") {
				$sql .= " AND fecha $direccion '$hoy'";
			}else{
				$sql = " WHERE fecha $direccion '$hoy'";
			}
	
			$actividades = $consulta->ver_registros("SELECT * from actividades $sql order by fecha ASC");
		}

		if ($all_info) {

			$actividades = $consulta->ver_registros("SELECT actividades.id as id, actividades.descripcion as descripcion, actividades.administracion as administracion, actividades.subdivision AS subdivision, actividades.lote as lote, actividades.trabajador as trabajador, actividades.tipo as tipo, actividades.estado as estado, actividades.facturable as facturable, actividades.archivo as archivo, actividades.fecha as fecha, actividades.fecha_group as fecha_group, actividades.address as address,actividades.time as time, actividades.creado_por as creado_por, workers.id as idw, workers.nombre as namew, subdivisiones.id as idsub, subdivisiones.nombre as namesub  from actividades LEFT JOIN workers ON workers.id = actividades.trabajador LEFT JOIN subdivisiones ON subdivisiones.id = actividades.subdivision $sql ORDER BY actividades.fecha ASC, workers.nombre, subdivisiones.nombre");

			for ($i=0; $i < count($actividades); $i++) { 
			
				$id = $actividades[$i]["id"];
				$id_sub = $actividades[$i]["subdivision"];
				$id_worker = $actividades[$i]["trabajador"];
				$id_tipo = $actividades[$i]["tipo"];
				$f = $actividades[$i]["fecha_group"];
	
				$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
				$info2 = $consulta->ver_registros("SELECT * from workers where id = '$id_worker'");
				$info3 = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
				$actividades[$i]["textos"] = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");
	
				$today = ($f == date("Y-m-d")) ? true : false;
				$diff_d = false;
				if ($f < date("Y-m-d")) {
					$date1 = new DateTime($f);
					$date2 = new DateTime(date("Y-m-d"));
					$diff = $date1->diff($date2);
	
					$diff_d = $diff->days;
				}
				
	
				$actividades[$i]["fecha_group"] = array(
					"f" => $f,
					"day" => date("D",strtotime($f)),
					"day_n" => date("d",strtotime($f)),
					"day_all" => date("l",strtotime($f)),
					"Month" => date("M",strtotime($f)),
					"Year" => date("Y",strtotime($f)),
					"today" => $today,
					"diff" => $diff_d,
					"format" => date("M",strtotime($f)) . " " .date("d",strtotime($f)) . ", ". date("Y",strtotime($f)),
					"format_us" => date("m",strtotime($f)) . "-" .date("d",strtotime($f)) . "-". date("Y",strtotime($f)),
				);
	
				if (isset($info1[0])) {
					$actividades[$i]["subdivision"] = $info1[0];
					$actividades[$i]["nombre_sub"] = $info1[0]["nombre"];
				}else{
					$actividades[$i]["subdivision"] = ["nombre" => ""];
					$actividades[$i]["nombre_sub"] = "none";
				}
				if (isset($info2[0])) {
					$actividades[$i]["worker"] = $info2[0];
				}else{
					$actividades[$i]["worker"] = ["nombre" => ""];
				}
				if (isset($info3[0])) {
					$info3[0]["settings"] = json_decode($info3[0]["settings"]);
					$actividades[$i]["tipo"] = $info3[0];
				}else{
					$actividades[$i]["tipo"] = ["nombre" => ""];
				}
	
			}

		}
	
		return $actividades;
	}
	public static function actividades($inicio, $limit, $direccion = false,$init = false){

		$consulta = new Consulta();

		$filter_sub = isset($_SESSION["paginas"]) ? $_SESSION["paginas"] : 3;

		$hoy = date("Y-m-d");
		$sql = GestorActividadesModel::filters();
		
		if ($sql == "") {
			$sql = "WHERE tipo != '0' AND archivo = '0'";
		}else{
			$sql .= " AND tipo != '0' AND archivo = '0'";
		}

		$unic_day = GestorActividadesModel::filter_date_specific();


		if(!$direccion || $unic_day){
			// limit $inicio, $limit

			if ($unic_day && $direccion == "<") {
				$actividades = [];
			}else{
				$all_sql = "SELECT actividades.id as id, actividades.descripcion as descripcion, actividades.subdivision AS subdivision, actividades.lote as lote, actividades.trabajador as trabajador, actividades.tipo as tipo, actividades.estado as estado, actividades.facturable as facturable, actividades.archivo as archivo, actividades.fecha as fecha, actividades.fecha_group as fecha_group, actividades.address as address,actividades.time as time, actividades.creado_por as creado_por, workers.id as idw, workers.nombre as namew, subdivisiones.id as idsub, subdivisiones.nombre as namesub from actividades LEFT JOIN workers ON workers.id = actividades.trabajador LEFT JOIN subdivisiones ON subdivisiones.id = actividades.subdivision $sql order by fecha ASC, workers.nombre, subdivisiones.nombre";
				$actividades = $consulta->ver_registros($all_sql);
			}

			
		}else{
			$aux_sql = $sql;

			if ($direccion == "<") {
				$ord = "ORDER BY actividades.fecha DESC, workers.nombre, subdivisiones.nombre";
			}else{
				$ord = "ORDER BY actividades.fecha ASC, workers.nombre, subdivisiones.nombre";
			}

			if ($sql != "") {
				$sql .= " AND fecha $direccion '$hoy'";
			}else{
				$sql = " WHERE fecha $direccion '$hoy'";
			}

			// limit $inicio, $limit
			$all_sql = "SELECT actividades.id as id, actividades.descripcion as descripcion, actividades.subdivision AS subdivision, actividades.lote as lote, actividades.trabajador as trabajador, actividades.tipo as tipo, actividades.estado as estado, actividades.facturable as facturable, actividades.archivo as archivo, actividades.fecha as fecha, actividades.fecha_group as fecha_group, actividades.address as address,actividades.time as time, actividades.creado_por as creado_por, workers.id as idw, workers.nombre as namew, subdivisiones.id as idsub, subdivisiones.nombre as namesub  from actividades LEFT JOIN workers ON workers.id = actividades.trabajador LEFT JOIN subdivisiones ON subdivisiones.id = actividades.subdivision $sql $ord";


			$actividades = $consulta->ver_registros($all_sql);

			if ($direccion == ">=" && count($actividades) == false && $init == "true") {
				// if ($aux_sql != "") {
				// 	$aux_sql .= " AND fecha < '$hoy' order by fecha DESC";
				// }else{
				// 	$aux_sql = " WHERE fecha < '$hoy' order by fecha DESC";
				// }
				
				// // limit $inicio, $limit
				// $all_sql = "SELECT * from actividades $aux_sql";

				// $actividades = $consulta->ver_registros($all_sql);	

				// $all_sql .= " ?init $init";
			}
		}
		
		$actividades = GestorActividadesModel::my_limit($inicio, $limit, $actividades);

		for ($i=0; $i < count($actividades); $i++) { 
			
			$id = $actividades[$i]["id"];
			$id_sub = $actividades[$i]["subdivision"];
			$lote =  $actividades[$i]["lote"];
			$id_worker = $actividades[$i]["trabajador"];
			$id_tipo = $actividades[$i]["tipo"];
			$f = $actividades[$i]["fecha_group"];

			$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
			$info2 = $consulta->ver_registros("SELECT * from workers where id = '$id_worker'");
			$info3 = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
			$actividades[$i]["textos"] = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");

			$have_form_rough = $consulta->ver_registros("SELECT * from rough_forms where neighborhood = '$id_sub' and lot = '$lote'");

			$today = ($f == date("Y-m-d")) ? true : false;
			$diff_d = false;
			if ($f < date("Y-m-d")) {
				$date1 = new DateTime($f);
				$date2 = new DateTime(date("Y-m-d"));
				$diff = $date1->diff($date2);

				$diff_d = $diff->days;
			}
			
			$actividades[$i]["fecha_group"] = array(
				"f" => $f,
				"hoy" => $hoy,
				"sql" => $all_sql,
				"day" => date("D",strtotime($f)),
				"day_n" => date("d",strtotime($f)),
				"day_all" => date("l",strtotime($f)),
				"Month" => date("M",strtotime($f)),
				"Year" => date("Y",strtotime($f)),
				"today" => $today,
				"diff" => $diff_d,
				"format" => date("M",strtotime($f)) . " " .date("d",strtotime($f)) . ", ". date("Y",strtotime($f)),
				"sql" => $all_sql
			);

			if (isset($info1[0])) {
				$info1[0]["nombre"] = htmlspecialchars_decode($info1[0]["nombre"], ENT_QUOTES);
				$actividades[$i]["subdivision"] = $info1[0];
			}else{
				$actividades[$i]["subdivision"] = ["nombre" => ""];
			}
			if (isset($info2[0])) {
				$actividades[$i]["worker"] = $info2[0];
			}else{
				$actividades[$i]["worker"] = ["nombre" => ""];
			}
			if (isset($info3[0])) {
				$info3[0]["settings"] = json_decode($info3[0]["settings"]);
				$actividades[$i]["tipo"] = $info3[0];
				if($info3[0]['nombre'] == 'Rough'){
					$actividades[$i]['can_form_rough'] = true;
				}else{
					$actividades[$i]['can_form_rough'] = false;
				}
			}else{
				$actividades[$i]["tipo"] = ["nombre" => ""];
				$actividades[$i]['can_form_rough'] = false;
			}

			$actividades[$i]['have_form_rough'] = count($have_form_rough);

			if($actividades[$i]['have_form_rough'] > 0){
				$actividades[$i]['id_form_rough'] = $have_form_rough[0]['id'];
			}

		}

		
		
		return $actividades;

	}

	public static function my_limit($page, $limit, $actividades){

		$counter = 0;
		$result = [];

		for ($i=$page; $i < count($actividades); $i++) { 
			
			if ($counter == $limit) {
				break;
			}
			if (!isset($actividades[$i])) {
				break;
			}

			array_push($result, $actividades[$i]);


			$counter++;
		}

		return $result;

	}

	public static function act_by_lot($subdivision, $lot){
		$consulta = new Consulta();

		$lot = htmlspecialchars($lot, ENT_QUOTES);

		$actividades = $consulta->ver_registros("SELECT * from actividades where subdivision = '$subdivision' and lote = '$lot' and tipo != '0' AND archivo = '0' order by fecha ASC");

		for ($i=0; $i < count($actividades); $i++) { 
			
			$id = $actividades[$i]["id"];
			$id_sub = $actividades[$i]["subdivision"];
			$id_worker = $actividades[$i]["trabajador"];
			$id_tipo = $actividades[$i]["tipo"];
			$f = $actividades[$i]["fecha_group"];

			$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
			$info2 = $consulta->ver_registros("SELECT * from workers where id = '$id_worker'");
			$info3 = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
			$actividades[$i]["textos"] = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");

			$actividades[$i]["descripcion"] = htmlspecialchars_decode($actividades[$i]["descripcion"], ENT_QUOTES);
			$actividades[$i]["lote"] = htmlspecialchars_decode($actividades[$i]["lote"], ENT_QUOTES);

			$lote = $actividades[$i]["lote"];

			$have_form_rough = $consulta->ver_registros("SELECT * from rough_forms where neighborhood = '$id_sub' and lot = '$lote'");


			$today = ($f == date("Y-m-d")) ? true : false;
			$diff_d = false;
			if ($f < date("Y-m-d")) {
				$date1 = new DateTime($f);
				$date2 = new DateTime(date("Y-m-d"));
				$diff = $date1->diff($date2);

				$diff_d = $diff->days;
			}
			
			$actividades[$i]["fecha_group"] = array(
				"f" => $f,
				"day" => date("D",strtotime($f)),
				"day_n" => date("d",strtotime($f)),
				"day_all" => date("l",strtotime($f)),
				"Month" => date("M",strtotime($f)),
				"Year" => date("Y",strtotime($f)),
				"today" => $today,
				"diff" => $diff_d,
				"format" => date("M",strtotime($f)) . " " .date("d",strtotime($f)) . ", ". date("Y",strtotime($f))
			);

			if (isset($info1[0])) {
				$info1[0]["nombre"] = htmlspecialchars_decode($info1[0]["nombre"], ENT_QUOTES);
				$actividades[$i]["subdivision"] = $info1[0];
			}else{
				$actividades[$i]["subdivision"] = ["nombre" => ""];
			}
			if (isset($info2[0])) {
				$actividades[$i]["worker"] = $info2[0];
			}else{
				$actividades[$i]["worker"] = ["nombre" => ""];
			}
			if (isset($info3[0])) {
				$info3[0]["settings"] = json_decode($info3[0]["settings"]);
				$actividades[$i]["tipo"] = $info3[0];
				if($info3[0]['nombre'] == 'Rough'){
					$actividades[$i]['can_form_rough'] = true;
				}else{
					$actividades[$i]['can_form_rough'] = false;
				}
			}else{
				$actividades[$i]["tipo"] = ["nombre" => ""];
				$actividades[$i]['can_form_rough'] = false;
			}

			$actividades[$i]['have_form_rough'] = count($have_form_rough);

			if($actividades[$i]['have_form_rough'] > 0){
				$actividades[$i]['id_form_rough'] = $have_form_rough[0]['id'];
			}

		}
		
		return $actividades;

	}

	public static function search_descipciones($subdivision, $lot){
		$consulta = new Consulta();

		$lot = htmlspecialchars($lot, ENT_QUOTES);

		$actividades = $consulta->ver_registros("SELECT * from actividades where subdivision = '$subdivision' and lote = '$lot' and tipo != '0' order by fecha ASC");

		for ($i=0; $i < count($actividades); $i++) { 
			
			$id = $actividades[$i]["id"];
			$id_sub = $actividades[$i]["subdivision"];
			$id_worker = $actividades[$i]["trabajador"];
			$id_tipo = $actividades[$i]["tipo"];
			$f = $actividades[$i]["fecha_group"];

			$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
			$info2 = $consulta->ver_registros("SELECT * from workers where id = '$id_worker'");
			$info3 = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
			$actividades[$i]["textos"] = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");

			$actividades[$i]["descripcion"] = htmlspecialchars_decode($actividades[$i]["descripcion"], ENT_QUOTES);
			$actividades[$i]["lote"] = htmlspecialchars_decode($actividades[$i]["lote"], ENT_QUOTES);

			$today = ($f == date("Y-m-d")) ? true : false;
			$diff_d = false;
			if ($f < date("Y-m-d")) {
				$date1 = new DateTime($f);
				$date2 = new DateTime(date("Y-m-d"));
				$diff = $date1->diff($date2);

				$diff_d = $diff->days;
			}
			
			$actividades[$i]["fecha_group"] = array(
				"f" => $f,
				"day" => date("D",strtotime($f)),
				"day_n" => date("d",strtotime($f)),
				"day_all" => date("l",strtotime($f)),
				"Month" => date("M",strtotime($f)),
				"Year" => date("Y",strtotime($f)),
				"today" => $today,
				"diff" => $diff_d,
				"format" => date("M",strtotime($f)) . " " .date("d",strtotime($f)) . ", ". date("Y",strtotime($f))
			);

			if (isset($info1[0])) {
				$info1[0]["nombre"] = htmlspecialchars_decode($info1[0]["nombre"], ENT_QUOTES);
				$actividades[$i]["subdivision"] = $info1[0];
			}else{
				$actividades[$i]["subdivision"] = ["nombre" => ""];
			}
			if (isset($info2[0])) {
				$actividades[$i]["worker"] = $info2[0];
			}else{
				$actividades[$i]["worker"] = ["nombre" => ""];
			}
			if (isset($info3[0])) {
				$info3[0]["settings"] = json_decode($info3[0]["settings"]);
				$actividades[$i]["tipo"] = $info3[0];
			}else{
				$actividades[$i]["tipo"] = ["nombre" => ""];
			}

		}

		
		return $actividades;

	}	

	public static function act_without_workers(){
		$consulta = new Consulta();

		$ahora = time();		
		$ayer = strtotime("-1 day", $ahora);
		$ayerLegible = date("Y-m-d", $ayer);

		$actividades = $consulta->ver_registros("SELECT actividades.id as id, actividades.descripcion as descripcion, actividades.administracion as administracion, actividades.subdivision AS subdivision, actividades.lote as lote, actividades.trabajador as trabajador, actividades.tipo as tipo, actividades.estado as estado, actividades.facturable as facturable, actividades.archivo as archivo, actividades.fecha as fecha, actividades.fecha_group as fecha_group, actividades.address as address,actividades.time as time, actividades.creado_por as creado_por, workers.id as idw, workers.nombre as namew, subdivisiones.id as idsub, subdivisiones.nombre as namesub  from actividades LEFT JOIN workers ON workers.id = actividades.trabajador LEFT JOIN subdivisiones ON subdivisiones.id = actividades.subdivision where trabajador = '0' and fecha = '$ayerLegible' and tipo != '0' AND archivo = '0' ORDER BY actividades.fecha ASC, workers.nombre, subdivisiones.nombre");

	
		for ($i=0; $i < count($actividades); $i++) { 
			
			$id = $actividades[$i]["id"];

			$actividades[$i] = self::info_actividad($id);

		}
		
		return $actividades;

	}

	public static function act_without_workers_separeted(){
		$consulta = new Consulta();

		$ahora = time();		
		$ayer = strtotime("-1 day", $ahora);
		$ayerLegible = date("Y-m-d", $ayer);

		$usuarios = $consulta->ver_registros("SELECT id,usuario,correo from usuarios");

		for ($j=0; $j < count($usuarios); $j++) { 
			
			$user = $usuarios[$j];
			$me = $user["usuario"];

			$actividades = $consulta->ver_registros("SELECT actividades.id as id, actividades.descripcion as descripcion, actividades.administracion as administracion, actividades.subdivision AS subdivision, actividades.lote as lote, actividades.trabajador as trabajador, actividades.tipo as tipo, actividades.estado as estado, actividades.facturable as facturable, actividades.archivo as archivo, actividades.fecha as fecha, actividades.fecha_group as fecha_group, actividades.address as address,actividades.time as time, actividades.creado_por as creado_por, workers.id as idw, workers.nombre as namew, subdivisiones.id as idsub, subdivisiones.nombre as namesub  from actividades LEFT JOIN workers ON workers.id = actividades.trabajador LEFT JOIN subdivisiones ON subdivisiones.id = actividades.subdivision where trabajador = '0' and fecha = '$ayerLegible' and creado_por = '$me' and tipo != '0' AND archivo = '0' ORDER BY actividades.fecha ASC, workers.nombre, subdivisiones.nombre");

			for ($i=0; $i < count($actividades); $i++) { 
			
				$id = $actividades[$i]["id"];
	
				$actividades[$i] = self::info_actividad($id);
	
			}

			$usuarios[$j]["actividades"] = $actividades;

		}
	
		
		return $usuarios;

	}

	public static function searchLot($lote){

		$consulta = new Consulta();

		$lote =  htmlspecialchars($lote, ENT_QUOTES);

		$lotes = $consulta -> ver_registros("SELECT lote from actividades where lote like '%$lote%' limit 0,5");

		return $lotes;
	}

	public static function filter_date_specific(){

		$result = false;

		$filter_daterange = isset($_SESSION["filter_daterange"]) ? $_SESSION["filter_daterange"] : false;
		$filter_specific_date = isset($_SESSION["specific_date"]) ? $_SESSION["specific_date"] : false;


		if ($filter_daterange == "" || $filter_daterange == "#") {
			$filter_daterange = false;
		}

		if ($filter_daterange !== false) {
			$arr = explode("-", $filter_daterange);
			if (isset($arr[0])) {
				$arr[0] = str_replace(" ", "", $arr[0]);
			}
			if (isset($arr[1])) {
				$arr[1] = str_replace(" ", "", $arr[1]);
			}
			if (isset($arr[0]) && isset($arr[1])) {
				$f1 = date("Y-m-d", strtotime($arr[0]));
				$f2 = date("Y-m-d", strtotime($arr[1]));

				if ($f1 == $f2) {
					$result = true;
				}
			}
			
		}

		if ($filter_specific_date == "" || $filter_specific_date == "#" || $filter_specific_date == "null" || $filter_specific_date == "undefined") {
			$filter_specific_date = false;
		}

		if ($filter_specific_date) {
			$result = true;
		}

		return $result;
	}

	public static function filters(){

		$filter_sub = isset($_SESSION["filter_sub"]) ? $_SESSION["filter_sub"] : false;
		$filter_lot = isset($_SESSION["filter_lot"]) ? $_SESSION["filter_lot"] : false;
		$filter_type = isset($_SESSION["filter_type"]) ? $_SESSION["filter_type"] : false;
		$filter_billi = isset($_SESSION["filter_billi"]) ? $_SESSION["filter_billi"] : false;
		$filter_worker = isset($_SESSION["filter_worker"]) ? $_SESSION["filter_worker"] : false;
		$filter_status = isset($_SESSION["filter_status"]) ? $_SESSION["filter_status"] : false;
		$filter_specific_date = isset($_SESSION["specific_date"]) ? $_SESSION["specific_date"] : false;
		$filter_daterange = isset($_SESSION["filter_daterange"]) ? $_SESSION["filter_daterange"] : false;

		if ($filter_sub == "" || $filter_sub == "#") {
			$filter_sub = false;
		}
		if ($filter_lot == "" || $filter_lot == "#") {
			$filter_lot = false;
		}
		if ($filter_type == "" || $filter_type == "#") {
			$filter_type = false;
		}
		if ($filter_billi == "" || $filter_billi == "#") {
			$filter_billi = false;
		}
		if ($filter_worker == "" || $filter_worker == "#") {
			$filter_worker = false;
		}
		if ($filter_status == "" || $filter_status == "#") {
			$filter_status = false;
		}
		if ($filter_specific_date == "" || $filter_specific_date == "#" || $filter_specific_date == "null" || $filter_specific_date == "undefined") {
			$filter_specific_date = false;
		}
		if ($filter_daterange == "" || $filter_daterange == "#") {
			$filter_daterange = false;
		}
		if ($filter_daterange) {
			$arr = explode("-", $filter_daterange);
			if (isset($arr[0])) {
				$arr[0] = str_replace(" ", "", $arr[0]);
			}
			if (isset($arr[1])) {
				$arr[1] = str_replace(" ", "", $arr[1]);
			}
			// if ($arr[0] == $arr[1]) {
			// 	$filter_daterange = false;
			// }
		}

		

		$sql = "";
		$back = "";
		if ($filter_sub !== false || $filter_lot !== false || $filter_type !== false || $filter_worker !== false || $filter_status !== false || $filter_daterange !== false || $filter_specific_date != false || $filter_billi !== false) {
			$sql .= " WHERE ";
		}
		if ($filter_sub !== false) {
			$sql .= " subdivision = '$filter_sub' ";
			$back = " AND ";
		}
		if ($filter_lot !== false) {
			$filter_lot = htmlspecialchars($filter_lot, ENT_QUOTES);
			$sql .= "$back lote = '$filter_lot' ";
			$back = " AND ";
		}
		if ($filter_type !== false) {
			$sql .= "$back tipo = '$filter_type' ";
			$back = " AND ";
		}
		if ($filter_billi !== false) {
			$sql .= "$back facturable = '1' ";
			$back = " AND ";
		}
		if ($filter_worker !== false) {
			$sql .= "$back trabajador = '$filter_worker' ";
			$back = " AND ";
		}
		if ($filter_status !== false) {

			if ($filter_status == "-1") {
				$sql .= "$back (estado = '2' or estado = '0')  ";	
			}else if ($filter_status == "-2") {
				$sql .= "$back (estado = '1' or estado = '0')  ";	
			}else if ($filter_status == "1") {
				$sql .= "$back estado = '1'  ";	
			}else if ($filter_status == "2") {
				$sql .= "$back estado = '2'  ";	
			}

				
			$back = " AND ";
		}
		if ($filter_specific_date !== false) {
			$sql .= "$back fecha = '$filter_specific_date' ";		
			$back = " AND ";
		}
		if ($filter_daterange !== false) {
			$arr = explode("-", $filter_daterange);
			if (isset($arr[0])) {
				$arr[0] = str_replace(" ", "", $arr[0]);
			}
			if (isset($arr[1])) {
				$arr[1] = str_replace(" ", "", $arr[1]);
			}
			if (isset($arr[0]) && isset($arr[1])) {
				$f1 = date("Y-m-d", strtotime($arr[0]));
				$f2 = date("Y-m-d", strtotime($arr[1]));
				$sql .= "$back fecha_group between '$f1' and '$f2' ";		
				$back = " AND ";
			}
			
		}

		// if ($sql != "") {
		// 	$sql .= ")";
		// }
	
		return $sql;
	}

	//-- CREAR / ACTUALIZAR
	public static function sim($datos, $option, $id = null){

		$consulta = new Consulta();
		$descripcion = htmlspecialchars($datos["descripcion"], ENT_QUOTES);
		$administracion = htmlspecialchars($datos["administracion"], ENT_QUOTES);
		$subdivision = isset($datos["subdivision"]) ? $datos["subdivision"] : 0;
		$subdivision = ($subdivision != "" && $subdivision != "#") ? $subdivision : 0;
		$lote = isset($datos["lote"]) ? htmlspecialchars($datos["lote"], ENT_QUOTES) : "";
		$worker = isset($datos["worker"]) ? $datos["worker"] : "#";
		$worker = ($worker != "" && $worker != "#") ? $worker : 0;
		$type = $datos["tipo"];		
		$time = isset($datos["time"]) ? $datos["time"] : "";
		$address = isset($datos["address"]) ? $datos["address"] : "";
		$infoType = GestorConfigModel::type($type);
		
		$textos_type = $infoType["settings"]->textos;
		$fecha = date("Y-m-d", strtotime( str_replace("-","/",$datos["fecha"]) ) );

		$ordened = isset($datos["ordened"]) ? true : false;
		$marked = isset($datos["marked"]) ? true : false;
		$billiable = isset($datos["billiable_"]) ? $datos["billiable_"] : 1;

		// $archive = isset($datos["archive"]) ? "1" : "0";

		if ($marked && $ordened) {
			$estado = 3;
		}else if ($marked) {
			$estado = 2;
		}else if ($ordened) {
			$estado = 1;
		}else{
			$estado = 0;
		}

		$lote = preg_replace('/\s+/', ' ',$lote);


		$fecha_g = $fecha;
		$usuario = $_SESSION["usuario"];

		if ($option == "crear") {

			$arch = ($infoType["settings"]->archive == "true") ? "1" : "0";

			$exist = $consulta->ver_registros("SELECT * from actividades where tipo = '$type' AND lote = '$lote' AND fecha = '$fecha_g'");
			error_log("SELECT * from actividades where tipo = '$type' AND lote = '$lote' AND fecha = '$fecha_g'");
			if (!count($exist)) {

				$consulta->nuevo_registro("INSERT into actividades (descripcion,administracion, subdivision, lote, trabajador, tipo, estado, facturable,archivo, fecha, fecha_group,time, address, creado_por) values('$descripcion', '$administracion', '$subdivision', '$lote', '$worker', '$type', '$estado', '$billiable', '$arch', '$fecha', '$fecha_g','$time','$address' ,'$usuario')");
				
				
				$r = $consulta->ver_registros("SELECT max(id) as id from actividades");
				$id = $r[0]["id"];

				for ($i=0; $i < count($textos_type); $i++) { 
					$t_ = $textos_type[$i];
					if (isset($_POST["text_$i"])) {
						$consulta->nuevo_registro("INSERT INTO textos (id_actividad, texto, valor) values ('$id', '$t_', '1')");
					}else{
						$consulta->nuevo_registro("INSERT INTO textos (id_actividad, texto, valor) values ('$id', '$t_', '0')");
					}
				}
			}else{
				return array("status" => "error", "mensaje" => "This activity already exists");
			}

		
		}else if($option == "actualizar"){

			$arch = ($infoType["settings"]->archive == "true") ? "1" : "0";

			$consulta->actualizar_registro("UPDATE actividades set descripcion = '$descripcion', administracion = '$administracion', subdivision = '$subdivision', lote = '$lote', trabajador = '$worker', tipo = '$type', estado = '$estado', facturable = '$billiable' , archivo = '$arch', fecha = '$fecha', fecha_group = '$fecha_g', time = '$time', address = '$address' where id = '$id'");

			$consulta->actualizar_registro("UPDATE multimedia set lote = '$lote', id_sub = '$subdivision' where id_actividad = '$id'");

			$textSaved = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");

			for ($i=0; $i < count($textos_type); $i++) { 
				$t_ = $textos_type[$i];

				if (isset($textSaved[$i])) {
					$id__t = $textSaved[$i]["id"];
					$v = isset($_POST["text_$i"]) ? "1" : "0";

					$consulta->actualizar_registro("UPDATE textos set texto = '$t_', valor = '$v' where id = '$id__t'");
				}else{
					$v = isset($_POST["text_$i"]) ? "1" : "0";

					$consulta->nuevo_registro("INSERT INTO textos (id_actividad, texto, valor) values ('$id', '$t_', '$v')");	
				}

				
			}
						
		}

		if (isset($_FILES["attachment_f"])) {
			$srcs = GestorActividadesController::procesar_files($_FILES["attachment_f"], $id);

			for ($i=0; $i < count($srcs); $i++) { 
				
				$path = $srcs[$i]["path"];
				$type_file = $srcs[$i]["type"];
				$f_name = $srcs[$i]["name"];

				$orden = GestorActividadesModel::ver_ultimo_archivo($id);

				$orden = $orden + 1;

				$consulta->nuevo_registro("INSERT INTO multimedia (name_key, name, path_media, id_actividad,lote,id_sub,orden) values ('$type_file', '$f_name', '$path', '$id','$lote','$subdivision', '$orden')");
				
			}

		}
		
		return array("status" => "ok", "id" => $id, "d" => $_POST);

	}

	public static function info_actividad($id){

        $consulta = new Consulta();

		$info = $consulta->ver_registros("SELECT * from actividades where id = '$id'");
		
		for ($i=0; $i < count($info); $i++) { 
			
			$id_sub = $info[$i]["subdivision"];
			$id_worker = $info[$i]["trabajador"];
			$id_tipo = $info[$i]["tipo"];
			$lote_ = $info[$i]["lote"];
			$f = $info[$i]["fecha_group"];

			$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
			$info2 = $consulta->ver_registros("SELECT * from workers where id = '$id_worker'");
			$info3 = $consulta->ver_registros("SELECT * from tipos where id = '$id_tipo'");
			$info[$i]["textos"] = $consulta->ver_registros("SELECT * from textos where id_actividad = '$id'");


			$info[$i]["descripcion"] = htmlspecialchars_decode($info[$i]["descripcion"], ENT_QUOTES);
			$info[$i]["administracion"] = htmlspecialchars_decode($info[$i]["administracion"], ENT_QUOTES);
			$info[$i]["lote"] = htmlspecialchars_decode($info[$i]["lote"], ENT_QUOTES);

			$have_form_rough = $consulta->ver_registros("SELECT * from rough_forms where neighborhood = '$id_sub' and lot = '$lote_'");
			
			$today = ($f == date("Y-m-d")) ? true : false;
			$diff_d = false;
			if ($f < date("Y-m-d")) {
				$date1 = new DateTime($f);
				$date2 = new DateTime(date("Y-m-d"));
				$diff = $date1->diff($date2);

				$diff_d = $diff->days;
			}
			

			$info[$i]["fecha_group"] = array(
				"f" => $f,
				"day" => date("D",strtotime($f)),
				"day_n" => date("d",strtotime($f)),
				"day_all" => date("l",strtotime($f)),
				"Month" => date("M",strtotime($f)),
				"Year" => date("Y",strtotime($f)),
				"today" => $today,
				"diff" => $diff_d,
				"format" => date("M",strtotime($f)) . " " .date("d",strtotime($f)) . ", ". date("Y",strtotime($f)),
				"format_us" => date("m",strtotime($f)) . "-" .date("d",strtotime($f)) . "-". date("Y",strtotime($f)),
			);

			if (isset($info1[0])) {
				$info1[0]["nombre"] = htmlspecialchars_decode($info1[0]["nombre"], ENT_QUOTES);
				$info[$i]["subdivision_"] =  $info1[0];
			}else{
				$info[$i]["subdivision_"]["nombre"] = "";
			}
			if (isset($info2[0])) {
				$info[$i]["worker_"] = $info2[0];
			}else{
				$info[$i]["worker_"]["nombre"] = "";
			}
			if (isset($info3[0])) {
				$info3[0]["settings"] = json_decode($info3[0]["settings"]);
				$info[$i]["tipo_"] = $info3[0];
				if($info3[0]['nombre'] == 'Rough'){
					$info[$i]['can_form_rough'] = true;
				}else{
					$info[$i]['can_form_rough'] = false;
				}
			}else{
				$info[$i]["tipo_"]["nombre"] = "";
				$info[$i]['can_form_rough'] = false;

			}

			$info[$i]['have_form_rough'] = count($have_form_rough);
			if($info[$i]['have_form_rough'] > 0){
				$info[$i]['id_form_rough'] = $have_form_rough[0]['id'];
			}

		}


        $info = count($info) != 0 ? $info[0] : [];

        return $info;

	}

	public static function ver_ultima_direccion(){

		$consulta = new Consulta();
		$tipo = $_POST["tipo"];
		$subdivision = $_POST["subdivision"];
		$lote = $_POST["lote"];
		$info = false;
		if ($tipo == 15 || $tipo == 16) {
			$info = $consulta->ver_registros("SELECT address,time from actividades where (tipo = '15' || tipo = '16') && subdivision = '$subdivision' && lote = '$lote' ORDER BY id DESC");

			if (count($info)) {
				$info = $info[0];
			}else{
				$info = false;
			}
		}
		
	

		
        return array("status" => "ok",'info' => $info);

	}

	public static function borrar_lista_actividades($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$consulta->borrar_registro("DELETE from actividades where id = '$id'");

			$archives = $consulta->ver_registros("SELECT * from multimedia where id_actividad = '$id'");

			$consulta->borrar_registro("DELETE from multimedia where id_actividad = '$id'");
			$consulta->borrar_registro("DELETE from textos where id_actividad = '$id'");

			for ($j=0; $j < count($archives); $j++) { 
				
				$ruta = "../../" . $archives[$j]["path_media"];
				
				if (file_exists($ruta)) {
					unlink($ruta);
				}
			}


						
		}

        return array("status" => "ok");

	}
	
	public static function actualizar_estados($lista, $estado){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];
			$consulta->actualizar_registro("UPDATE actividades set estado = '$estado' where id = '$id'");

		}

        return array("status" => "ok");

	}

	public static function ver_ultimo_archivo($id){

		$consulta = new Consulta();

		$order = $consulta -> ver_registros("SELECT MAX(orden) as orden from multimedia where id_actividad = '$id'");

		if (isset($order[0])) {
			$orden = $order[0]["orden"];
		}else {
			$orden = 0;
		}

		return $orden;
	}

	public static function archivos($lote, $sub){

		$lote = htmlspecialchars($lote, ENT_QUOTES);

		$consulta = new Consulta();

		$archivos = $consulta -> ver_registros("SELECT * from multimedia where lote = '$lote' AND id_sub = '$sub'");

		return $archivos;
	}

	public static function deleteAttachment($id){

		$consulta = new Consulta();
		
		$info = $consulta->ver_registros("SELECT * from multimedia where id_media = '$id'");
		$consulta->borrar_registro("DELETE from multimedia where id_media = '$id'");
		

		if (isset($info[0])) {
		
			$ruta = "../../" . $info[0]["path_media"];
				
			if (file_exists($ruta)) {
				unlink($ruta);
			}
		}
		

        return array("status" => "ok");

	}

	public static function ver_info_usuario($id){

        $consulta = new Consulta();

        $info = $consulta->ver_registros("SELECT * from usuarios where id = '$id'");

        $info = count($info) != 0 ? $info[0] : [];

        return $info;

    }
	
	
}
