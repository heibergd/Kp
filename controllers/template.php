<?php 
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
class templateControllers{
	public function template()
	{
		if (isset($_GET["action"])) {
			if ($_GET["action"] == "exportPdf") {
				include 'views/modules/downPdf.php';
			}else if ($_GET["action"] == "exportPdfAdmin") {
				include 'views/modules/downPdfAdmin.php';
			}else if ($_GET["action"] == "exportExcel") {
				include 'views/modules/downExcel.php';
			}else if ($_GET["action"] == "mail") {
				include 'views/modules/mail.php';
			}else{
				include 'views/template.php';
			}
		}else{
			include 'views/template.php';
		}
	}
}
