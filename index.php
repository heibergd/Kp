<?php  
ini_set("session.cookie_lifetime","31540000"); // un año :v
ini_set("session.gc_maxlifetime","31540000");
require_once 'models/conexion.php';
require_once 'models/consulta.php';
require_once 'models/enlaces.php';
require_once './models/gestorConfig.php';
require_once './models/gestorUsuarios.php';
require_once './models/gestorSubdivision.php';
require_once './models/gestorWorkers.php';
require_once './models/gestorActividades.php';
require_once './models/gestorRoughForm.php';
require_once './models/gestorFixture.php';
require_once './models/gestorConstructora.php';



require_once 'controllers/enlaces.php';
require_once 'controllers/template.php';
require_once './controllers/gestorConfig.php';
require_once './controllers/gestorUsuarios.php';
require_once './controllers/gestorSubdivision.php';
require_once './controllers/gestorWorkers.php';
require_once './controllers/gestorActividades.php';
require_once './controllers/gestorRoughForm.php';
require_once './controllers/gestorFixture.php';
require_once './controllers/gestorConstructora.php';



$template = new templateControllers();
$template -> template();
