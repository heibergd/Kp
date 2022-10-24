<?php

class EnlacesModels{

	public static function enlacesModel($enlaces){

		// var_dump($enlaces);

		if( $enlaces == "emptyPage" ||
			$enlaces == "jsonUsers" ||
			$enlaces == "usuarios" ||
			$enlaces == "workers" ||
			$enlaces == "builders" ||
			$enlaces == "fixtures" ||
			$enlaces == "roughForms" ||
			$enlaces == "createRoughForm" ||
			$enlaces == "editRoughForm" ||
			$enlaces == "summaryPay" ||
			$enlaces == "summaryBill" ||
			$enlaces == "pays" ||
			$enlaces == "lot" ||
			$enlaces == "admins" ||
			$enlaces == "addWorker" ||
			$enlaces == "profile" ||
			$enlaces == "dashboard" ||
			$enlaces == "addAdmin" ||
			$enlaces == "login" ||
			$enlaces == "editAdmin" ||
			$enlaces == "cerrarSesion" ||
			$enlaces == "security" ||
			$enlaces == "subdivisions" ||
			$enlaces == "editClient" ||
			$enlaces == "galery" ||
			$enlaces == "activity" 
		){
				
			$module = "views/modules/".$enlaces.".php";

		}
		else if($enlaces == "index" || $enlaces == ""){
			$module = "views/modules/index.php";
		}else{
			$module = "views/modules/404.php";
		}

		return $module;

	}


}