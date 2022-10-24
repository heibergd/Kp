<?php 

class GestorRoughFormModel{

	public static function jsonRoughForms(){

		$consulta = new Consulta();
		$sql = "";
		if (isset($_SESSION["filtroRoughForm"])) {
			$q = $_SESSION["filtroRoughForm"];

			$sql = " and (neighborhood like '%$q%' or Lot like '%$q%' or builder like '%$q%')"; 

			// echo $sql;
		}
		
		$rough_forms = $consulta->ver_registros("SELECT * from rough_forms INNER JOIN subdivisiones ON rough_forms.`neighborhood` = subdivisiones.id where plantilla = '0' $sql");

		foreach ($rough_forms as $key => $rough_form) {
			$id = $rough_form['id'];
			$fixtures =  $consulta->ver_registros("SELECT * from form_fixtures where id_form = $id");

			$total = 0;

			foreach ($fixtures as $fixture) {
				$id_fixture = $fixture['id_fixture'];
				$base_fixture = $consulta->ver_registros("SELECT * from fixtures where id = $id_fixture and form = 'Rough'")[0];

				if($fixture['quantity'] != "" && $fixture['quantity'] != null && $fixture['quantity'] != "No"){

					if ($base_fixture['tipo'] == 0) {
						if (ctype_digit($fixture['quantity'])) {
							$total += $fixture['quantity'] * $base_fixture['precio_cobrar'];
						}else{
							$total += $base_fixture['precio_cobrar'];
						}
					}
				}
			}

			$rough_forms[$key]['total'] = number_format($total, 2);

		}

		error_log( print_r($rough_forms, TRUE) );
		// "SELECT * from usuarios"
		return $rough_forms;

	}

	public static function rough_forms(){

		$consulta = new Consulta();

		$rough_forms = $consulta->ver_registros("SELECT * from rough_forms where plantilla = '0'");
		
		return $rough_forms;

	}

	public static function plantillas(){

		$consulta = new Consulta();

		$rough_forms = $consulta->ver_registros("SELECT id,plantilla,nombre_plantilla from rough_forms where plantilla = '1'");
		
		return $rough_forms;

	}

	public static function crear($datos){

		$guardar_plantilla = isset($datos["guardar_plantilla"]) ? $datos["guardar_plantilla"] : 0;
		$nombre_plantilla = isset($datos["nombre_plantilla"]) ? $datos["nombre_plantilla"] : 0;

		$datos["guardar_plantilla"] = null;
		$datos["nombre_plantilla"] = null;

		$response = GestorRoughFormModel::guardar($datos);

		if ($guardar_plantilla == "1") {
			$datos["guardar_plantilla"] = $guardar_plantilla;
			$datos["nombre_plantilla"] = $nombre_plantilla;
			GestorRoughFormModel::guardar($datos);
		}
		

		return $response;

	}
	/* =============================================================
    MODELO PARA ACTUALIZAR LA BASE DE DATOS (OTHERS)
    ============================================================= */
	public static function updatedatabase(){
		$consulta = new Consulta();
		$temp_update = $consulta->ver_registros("SELECT * FROM `form_fixtures` WHERE `id_fixture` = 18");
		foreach ($temp_update as  $value) {
			$contenido = json_decode($value['json']);
			if($contenido !=''){
				$id = $value['id'];
				$json = '';
				$content = $contenido[0]->sub_pregunta_1;
				$consulta->actualizar_registro("UPDATE form_fixtures set json = '$json', info_extra = '$content', extra = '2' where id = '$id'");
			}
		}
		return array('status' => 'ok');
	}
	/* =============================================================
    MODELO PARA GUARDAR LA INFORMACION EN LA BASE DE DATOS
    ============================================================= */
	public static function guardar($datos){
		$consulta = new Consulta();
		$neighborhood = htmlspecialchars($datos["neighborhood"], ENT_QUOTES);
		$lot = htmlspecialchars($datos["lot"], ENT_QUOTES);
		$builder = $datos["builder"];
		$sewer_system = $datos["sewer_system"];
		$forsyth_county = $datos["forsyth_county"];
		$well = $datos["well"];
		$payroll = $datos["payroll"];
		$basement_or_slab = $datos["basement_or_slab"];
		$future_bath_basement = $datos["future_bath_basement"];
		$future_bath_bonus = $datos["future_bath_bonus"];
		$extra_valves = $datos["extra_valves"];
		$ejector_tank = $datos["ejector_tank"];
		$brand_of_fixtures = $datos["brand_of_fixtures"];
		$rough_type = $datos["rough_type"];
		$supervisor = $datos["supervisor"];
		$plantilla = ($datos["guardar_plantilla"] != "") ? $datos["guardar_plantilla"] : 0;
		$nombre_plantilla = $datos["nombre_plantilla"];
		$others = $datos['others'];
		$consulta->nuevo_registro("INSERT into rough_forms (neighborhood, lot, builder, sewer_system,forsyth_county,well, payroll, basement_or_slab, future_bath_basement, future_bath_bonus,extra_valves, ejector_tank, brand_of_fixtures,rough_type, supervisor, plantilla, nombre_plantilla) 
									values('$neighborhood', '$lot', '$builder', '$sewer_system','$forsyth_county', '$well', '$payroll', '$basement_or_slab', '$future_bath_basement', '$future_bath_bonus','$extra_valves', '$ejector_tank', '$brand_of_fixtures','$rough_type', '$supervisor', '$plantilla', '$nombre_plantilla')");
		$idLast = $consulta->ver_registros("SELECT max(id) as id from rough_forms");
		$id_form = $idLast[0]["id"];
		//$fixtures = $consulta->ver_registros("SELECT * from fixtures where extra = 0 and form = 'Rough'");
		$fixtures = $datos["fixtures"];
		foreach ($fixtures as $fixture) {
			// http_response_code(500);
			// return $fixture;
			$id_fixture = $fixture['id_fixture'];
			$quantity = $fixture['quantity'];
			$future_bath = $fixture['future_bath'];
			$extra = $fixture['extra'];
			$json = "";
			if (isset($fixture['items'])) {
				$json = json_encode($fixture['items']);
			}
			$consulta->nuevo_registro("INSERT into form_fixtures (id_form, id_fixture, quantity, json, future_bath,extra) 
									values('$id_form', '$id_fixture', '$quantity', '$json', '$future_bath','$extra')");
		}
		if($others['items']!=''){
			$id_fixture = $others['id_fixture'];
			$quantity = $others['quantity'];
			$future_bath = $others['future_bath'];
			$extra = $others['extra'];
			$json = '';
			$info = htmlspecialchars($others['items'], ENT_QUOTES);
			$consulta->nuevo_registro("INSERT INTO form_fixtures (id_form, id_fixture, quantity, json, info_extra, future_bath,extra) values('$id_form', '$id_fixture', '$quantity', '$json','$info', '$future_bath','$extra')");
		}
		return array("status" => true);
	}
	/* =============================================================
    MODELO PARA EL UPDATE DE LA INFORMACION EN LA BASE DE DATOS
    ============================================================= */
	public static function update($datos){
		$consulta = new Consulta();
		$neighborhood = htmlspecialchars($datos["neighborhood"], ENT_QUOTES);
		$lot = htmlspecialchars($datos["lot"], ENT_QUOTES);
		$builder = $datos["builder"];
		$sewer_system = $datos["sewer_system"];
		$forsyth_county = $datos["forsyth_county"];
		$well = $datos["well"];
		$payroll = $datos["payroll"];
		$basement_or_slab = $datos["basement_or_slab"];
		$future_bath_basement = $datos["future_bath_basement"];
		$future_bath_bonus = $datos["future_bath_bonus"];
		$extra_valves = $datos["extra_valves"];
		$ejector_tank = $datos["ejector_tank"];
		$brand_of_fixtures = $datos["brand_of_fixtures"];
		$rough_type = $datos["rough_type"];
		$supervisor = $datos["supervisor"];
		$id_form = $datos["id"];
		$others = $datos['others'];
		$consulta->actualizar_registro("UPDATE rough_forms set neighborhood = '$neighborhood', lot = '$lot', well = '$well', builder = '$builder', sewer_system = '$sewer_system', forsyth_county = '$forsyth_county', payroll = '$payroll', basement_or_slab = '$basement_or_slab', future_bath_basement = '$future_bath_basement', future_bath_bonus = '$future_bath_bonus', extra_valves = '$extra_valves', rough_type = '$rough_type', ejector_tank = '$ejector_tank', brand_of_fixtures = '$brand_of_fixtures', supervisor = '$supervisor' where id = '$id_form'");
		$fixtures = $datos["fixtures"];
		foreach ($fixtures as $fixture) {
			$id = $fixture['id'];
			$quantity = $fixture['quantity'];
			$json = "";
			if (isset($fixture['items'])) {
				$json = json_encode($fixture['items']);
			}
			$consulta->actualizar_registro("UPDATE form_fixtures set quantity = '$quantity', json = '$json' where id = '$id'");
		}
		$id_others = $others['id_fixture'];
		$quantity_others = $others['quantity'];
		$info_others = htmlspecialchars($others['items'], ENT_QUOTES);
		$consulta->actualizar_registro("UPDATE form_fixtures SET quantity = '$quantity_others', info_extra = '$info_others'  WHERE (id_fixture = '$id_others' AND id_form = '$id_form')  ");
		return array("status" => "ok");
	}
	/* =========================================================
	MODELO PARA LA CONSULTA DEL ROUGH SHEET
	========================================================= */
	public static function info_rough_form($id){
        $consulta = new Consulta();
		$info = $consulta->ver_registros("SELECT * from rough_forms where id = '$id'");
		$info = count($info) != 0 ? $info[0] : [];
		if ($info) {
			$id_form = $info['id'];
			$id_const = $info['builder'];
			$id_sub = $info['neighborhood'];
			$super = $info['supervisor'];
			$temp_main_fixtures = $consulta->ver_registros("SELECT * from form_fixtures where id_form = '$id_form' and extra = 0 and future_bath = 0");
			$temp_future_fixtures = $consulta->ver_registros("SELECT * from form_fixtures where id_form = '$id_form' and extra = 0 and future_bath = 1");
			$temp_extra_fixtures = $consulta->ver_registros("SELECT * from form_fixtures where id_form = '$id_form' AND extra = 1 ");
			$temp_extra_fixtures2 = $consulta->ver_registros("SELECT * from form_fixtures where id_form = '$id_form' AND (extra = 1 OR extra =2) ");
			$others_extra_fixtures = $consulta->registro("SELECT * from form_fixtures where id_form = '$id_form' and extra = 2");
			$builder = $consulta->ver_registros("SELECT * from constructoras where id = '$id_const'");
			$info1 = $consulta->ver_registros("SELECT * from subdivisiones where id = '$id_sub'");
			$superv = $consulta->ver_registros("SELECT * from usuarios where id = '$super'");
			if (isset($info1[0])) {
				$info['neighborhood'] = htmlspecialchars_decode($info1[0]["nombre"], ENT_QUOTES);
				$info['neighborhood_id'] =  $info1[0]['id'];
			}else{
				$info["neighborhood"] = "";
				$info["neighborhood_id"] = "";
			}
			$main_fixtures = [];
			$future_fixtures = [];
			$extra_fixtures = [];
			$extra_fixtures2 = [];
			$other_fix = $others_extra_fixtures;
			foreach ($temp_main_fixtures as $fixture) {
				$id_fixture = $fixture["id_fixture"];
				$info_fixture = $consulta->ver_registros("SELECT * from fixtures where id = '$id_fixture'");
				$fixture["info_fixture"] = $info_fixture[0];
				array_push($main_fixtures, $fixture );
			}
			foreach ($temp_future_fixtures as $fixture) {
				$id_fixture = $fixture["id_fixture"];
				$info_fixture = $consulta->ver_registros("SELECT * from fixtures where id = '$id_fixture'");
				$fixture["info_fixture"] = $info_fixture[0];
				array_push($future_fixtures, $fixture );
			}
			foreach ($temp_extra_fixtures as $fixture) {
				$id_fixture = $fixture["id_fixture"];
				$info_fixture = $consulta->ver_registros("SELECT * from fixtures where id = '$id_fixture'");
				$fixture["info_fixture"] = $info_fixture[0];
				array_push($extra_fixtures, $fixture );
			}
			foreach ($temp_extra_fixtures2 as $fixture) {
				$id_fixture = $fixture["id_fixture"];
				$info_fixture = $consulta->ver_registros("SELECT * from fixtures where id = '$id_fixture'");
				$fixture["info_fixture"] = $info_fixture[0];
				array_push($extra_fixtures2, $fixture);
			}
			$info['main_fixtures'] = $main_fixtures;
			$info['future_fixtures'] = $future_fixtures;
			$info['extra_fixtures'] = $extra_fixtures;
			$info['extra_fixtures2'] = $extra_fixtures2;
			$info['superv'] = $superv[0]['usuario'];
			$info['otro'] = $other_fix;
			$info['builder_obj'] = count($builder) != 0 ? $builder[0] : [];
		}
        return $info;
	}

	public static function fixture_form($id_form, $id_fixture){

        $consulta = new Consulta();

		$fixture = $consulta->ver_registros("SELECT * from form_fixtures where id_form = '$id_form' and id_fixture = '$id_fixture'");

		$fixture = count($fixture) != 0 ? $fixture[0] : [];

        return $fixture;
	}

	public static function buscar_lote(){

		$consulta = new Consulta();
		$subdivision = $_POST["subdivision"];
		$lote = $_POST["lote"];
		$info = false;
	
		$info = $consulta->ver_registros("SELECT address,time from actividades where subdivision = '$subdivision' && lote = '$lote'");

		if (count($info)) {
			$info = $info[0];
		}else{
			$info = false;
		}
		
        return array("status" => "ok",'info' => $info);

	}

	public static function pagos(){

		$consulta = new Consulta();
		$builder = $_POST["builder"];
		$worker = $_POST["worker"];
		$lot = $_POST["lot"];
		$subdivision = $_POST["subdivision"];
		$sheet = $_POST["sheet"];
		// $type = $_POST["type"];
		$range = str_replace(' ', '', $_POST['date_range']);
		$range = explode('-', $range);
		$date1 = date("Y-m-d", strtotime($range[0]));
		$date2 = date("Y-m-d", strtotime($range[1]));

		$sql = "SELECT rough_forms.id as id_form, rough_forms.neighborhood, rough_forms.lot, rough_forms.payroll, actividades.subdivision, actividades.lote, actividades.fecha, actividades.trabajador, actividades.id as id_actividad, rough_forms.builder  from rough_forms left join actividades on rough_forms.neighborhood = actividades.subdivision and rough_forms.lot = actividades.lote and rough_forms.payroll = actividades.trabajador where rough_forms.payroll = '$worker' and rough_forms.lot = '$lot' and rough_forms.neighborhood = '$subdivision' and  rough_forms.builder = '$builder'  ";

		// and actividades.fecha BETWEEN '$date1' and '$date2'
		$pagos = $consulta->ver_registros($sql);

		$pagos_filtrados = [];
		$ids_usados = [];

		foreach ($pagos as $key => $pago) {
			$id_form = $pago['id_form'];
			$fixtures = $consulta->ver_registros("SELECT * from fixtures where form = 'Rough'");
			$total_main_total = 0;
			$fix = 0;

			foreach ($fixtures as $fixture) {
				$db_fixture = self::fixture_form($id_form, $fixture['id']);

				$valid = false;
				if ($db_fixture) {
					$valid = ($db_fixture['quantity'] != '' && $db_fixture['quantity'] != 'No') ? true : false;
				}
				$total = 0;
				if ($db_fixture && $valid){
					if ($fixture['tipo'] == 0 && is_numeric($db_fixture['quantity'])) {
						$total = $fixture['precio_pagar'] * $db_fixture['quantity'];
					}else{
						$total = $fixture['precio_pagar'];
					}
					$total_main_total += $total;
				}
			}

			$pago['total'] = $total_main_total;

			if (!in_array($pago['id_actividad'], $ids_usados)) {
				array_push($pagos_filtrados, $pago);
				array_push($ids_usados, $pago['id_actividad']);
			}
			
			$pagos[$key] = $pago;
		}

        return array("status" => "ok", 'pagos' => $pagos, 'pagos_filtrados' => $pagos_filtrados, 'post' => $_POST, 'sql' => $sql);

	}
	
	public static function borrar_lista_rough_forms($lista){

		$consulta = new Consulta();
		
		for ($i=0; $i < count($lista); $i++) { 
			
			$id = $lista[$i];

			$consulta->borrar_registro("DELETE from form_fixtures where id_form = '$id'");

			$consulta->borrar_registro("DELETE from rough_forms where id = '$id'");

		}

        return array("status" => "ok");

	}

}
