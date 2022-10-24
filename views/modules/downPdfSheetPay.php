<?php
error_reporting(E_ALL);
include_once "../vendor/autoload.php";
require_once '../../models/conexion.php';
require_once '../../models/consulta.php';
require_once '../../controllers/gestorFixture.php';
require_once '../../controllers/gestorWorkers.php';
require_once '../../controllers/gestorRoughForm.php';
require_once '../../models/gestorFixture.php';
require_once '../../models/gestorWorkers.php';
require_once '../../models/gestorRoughForm.php';

use Dompdf\Dompdf;

try {
    $dompdf = new Dompdf();
} catch (\Throwable $th) {
    var_dump($th);
}

/** Actalizado 21/10/2020 11:30 */
$id = $_POST["id"];
$roughForm = GestorRoughFormController::info_rough_form($id);
$mainFixtures = $roughForm["main_fixtures"];
$fixtureFutures = $roughForm["future_fixtures"];
$extrafixtures = $roughForm["extra_fixtures"];
$extrafixtures2 = $roughForm["extra_fixtures2"];
$info_others = $roughForm["otro"];
$worker = GestorWorkersController::info_worker($roughForm['payroll']);
$template = "";
$total_main_quantity = 0;
$total_extra_quantity = 0;
$total_main_total = 0;
$total_main_total_valve = 0;
$total_extra_total = 0;

foreach ($mainFixtures as $fixture) {
    $valid = ($fixture['quantity'] != '' && $fixture['quantity'] != 'No') ? true : false;
    $total = 0;
    if ($valid) {
        if ($fixture["info_fixture"]['tipo'] == 0 && is_numeric($fixture['quantity'])) {
            $quantity = $fixture['quantity'];
            $total = $fixture["info_fixture"]['precio_pagar'] * $fixture['quantity'];
        } else {
            $quantity = 1;
            $total = $fixture["info_fixture"]['precio_pagar'];
        }
        $items_json = ($fixture['json'] != "") ? json_decode($fixture['json']) : [];
        $marca = (isset($items_json[0]) && isset($items_json[0]->sub_pregunta_1))  ? $items_json[0]->sub_pregunta_1 : '';
        $proveedor = (isset($items_json[0]) && isset($items_json[0]->sub_pregunta_2))  ? $items_json[0]->sub_pregunta_2 : '';
        $valve = (isset($items_json[0]) && isset($items_json[0]->sub_pregunta_3))  ? $items_json[0]->sub_pregunta_3 : '';
        $total_main_total += $total;
        $total_main_quantity += $quantity;
        if ($valve == "Yes") {
            $quantity = $quantity * 2;
        }
        if ($fixture['info_fixture']['valve'] > 0) {
            $total_main_total_valve += $quantity;
        }
        /* ===========================================================================
        SHOW THE TYPE OF THE VALVES READY
        =========================================================================== */
        $quantityValves = '';
        $valvesInfo = '';
        if($fixture['quantity'] > '1' && $fixture['json'] != ''){
            
            $quantity = $fixture['quantity'];
            // $quantityValves = "rowspan='$quantity'";
            if ($fixture['id_fixture'] == '6' || $fixture['id_fixture'] == '5' || $fixture['id_fixture'] == '11') {
                $quantityValves = "rowspan='1'";
            }else{
                $quantityValves = "rowspan='$quantity'";
            }
            $infoJson  = json_decode($fixture['json']);
            foreach ($infoJson as $key => $value) {
                if($key != 0){
                    $tr1 = "<tr>";
                    $tr2 = "</tr>";
                }else {
                    $tr1 = "";
                    $tr2 = "";
                }
                $type = $value->sub_pregunta_1;
                
                if(isset($value->sub_pregunta_2)){
                    $provide = $value->sub_pregunta_2;
                }else{
                    $provide = '';
                }


                if(isset($value->sub_pregunta_3)){
                    $twoValves = $value->sub_pregunta_3;
                }else{
                    $twoValves = '';
                }
                
                $valvesInfo .= "
                    $tr1<td style='text-align:center;border: 1px solid #dadada;'>  $type  </td>
                        <td style='text-align:center;border: 1px solid #dadada;'>  $provide   </td>
                        <td style='text-align:center;border: 1px solid #dadada;'>  $twoValves   </td>$tr2
                    ";
            }
        }else{
            $valvesInfo .= "
                <td style='text-align:center;border: 1px solid #dadada;'>" . $marca . "</td>
                <td style='text-align:center;border: 1px solid #dadada;'>" . $proveedor . "</td>
                <td style='text-align:center;border: 1px solid #dadada;'>" . $valve  . "</td>
            ";
        }
        /************************************************************************** */
        $template .= "
                <tr>
                    <td style='text-align:left;border: 1px solid #dadada;'$quantityValves>" . $fixture["info_fixture"]['nombre'] . "</td>
                    <td style='text-align:center;border: 1px solid #dadada;'$quantityValves.>" . $fixture['quantity']  . "</td>
                    $valvesInfo
                </tr>
            ";
    }
}
if ($roughForm['future_bath_bonus'] == 'Yes') {
    $futureTemplate = "";
    foreach ($fixtureFutures as $fixture) {
        $valid = ($fixture['quantity'] != '' && $fixture['quantity'] != 'No') ? true : false;
        $total = 0;
        if ($valid) {
            if ($fixture["info_fixture"]['tipo'] == 0 && is_numeric($fixture['quantity'])) {
                $quantity = $fixture['quantity'];
                $total = $fixture["info_fixture"]['precio_pagar'] * $fixture['quantity'];
            } else {
                $quantity = 1;
                $total = $fixture["info_fixture"]['precio_pagar'];
            }
            $total_main_total += $total;
            $total_main_quantity += $quantity;
            $items_json = ($fixture['json'] != "") ? json_decode($fixture['json']) : [];
            $tipo = (isset($items_json[0]) && isset($items_json[0]->sub_pregunta_1))  ? $items_json[0]->sub_pregunta_1 : '';
            $provee = (isset($items_json[0]) && isset($items_json[0]->sub_pregunta_2))  ? $items_json[0]->sub_pregunta_2 : '';
            $valve = (isset($items_json[0]) && isset($items_json[0]->sub_pregunta_3))  ? $items_json[0]->sub_pregunta_3 : '';
            if ($valve == "Yes") {
                $quantity = $quantity * 2;
            }
            $futureTemplate .= "
                    <tr>
                        <td style='text-align:left;border: 1px solid #dadada;'>" . $fixture["info_fixture"]['nombre'] . "</td>
                        <td style='text-align:center;border: 1px solid #dadada;'>" . $fixture['quantity']  . "</td>
                        <td style='text-align:center;border: 1px solid #dadada;'>" . $tipo  . "</td>
                        <td style='text-align:center;border: 1px solid #dadada;'>" . $provee  . "</td>
                        <td style='text-align:center;border: 1px solid #dadada;'>" . $valve  . "</td>
                    </tr>
                ";
        }
    }
    $template .= "
            <tr>
                <td style='text-align:center;border: 1px solid #dadada;'>Future Bath - Bonus Room</td>
                <td style='text-align:center;border: 1px solid #dadada;'>Yes</td>
                <td style='text-align:center;border: 1px solid #dadada;'></td>
                <td style='text-align:center;border: 1px solid #dadada;'></td>
                <td style='text-align:center;border: 1px solid #dadada;'></td>
            </tr>
            " . $futureTemplate  . "
        ";
}

if ($roughForm["extra_valves"] != "" && is_numeric($roughForm['extra_valves'])) {
    $total_main_quantity += $roughForm["extra_valves"];
    $total_main_total_valve += $roughForm["extra_valves"];
    $template .= "
            <tr>
                <td style='text-align:center;border: 1px solid #dadada;'>Extra Valves</td>
                <td style='text-align:center;border: 1px solid #dadada;'>" . $roughForm["extra_valves"] . "</td>
                <td style='text-align:center;border: 1px solid #dadada;'></td>
                <td style='text-align:center;border: 1px solid #dadada;'></td>
                <td style='text-align:center;border: 1px solid #dadada;'></td>
            </tr>
        ";
}

$template .= "
        <tr>
            <td style='text-align:center;border: 1px solid #dadada;'>Total Fixture Count</td>
            <td style='text-align:center;border: 1px solid #dadada;'colspan='4'><b> " . $total_main_quantity . "</b></td>
        </tr>
        <tr>
            <td style='text-align:center;border: 1px solid #dadada;'>Total Valves Count</td>
            <td style='text-align:center;border: 1px solid #dadada;' colspan='4'><b> " . $total_main_total_valve . "</b></td>
            <th style='padding: 4px;'></th>
        </tr>
    ";
$template_not_count = "";
$others = '';

foreach ($extrafixtures2 as $fixture) {
    $valid = ($fixture['quantity'] != '' && $fixture['quantity'] != 'No') ? true : false;

    /* ================================================================================
    SECTION TO PLACE THE OTHERS FIELD AT THE BOTTOM OF THE REPORT
    ================================================================================ */
    if ($fixture['id_fixture'] == 18) {
        $valor = $fixture['info_extra'];
        if ($valor != '') {
            $others =
                "<table style='font-size: 12px;width:100%;' class='table table-bordered'>
                    <thead>
                        <tr>
                            <th style='text-align:center;border: 1px solid #dadada;'>OTHERS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td style='text-align:center;border: 1px solid #dadada;'>" .  $valor . "</td>
                    </tbody>
                </table>";
        }
        continue;
    } else {
        $others = "";
    }
    /*********************************************************************************/

    /* ================================================================================
    INDICATES THE TYPE OF LAUNDRY PAN DRAIN
    ================================================================================ */ 
    if($fixture['id_fixture'] == 17){
        $items_others = ($fixture['json'] != "") ? json_decode($fixture['json']) : [];
        $laundry = (isset($items_others[0]) && isset($items_others[0]->sub_pregunta_1))  ? $items_others[0]->sub_pregunta_1 : '';
        $quantity = "<td style='text-align:center;border: 1px solid #dadada;'>" . $fixture['quantity'] . ' - '. $laundry  . "</td>";
    }else{
        $quantity ="<td style='text-align:center;border: 1px solid #dadada;'>" . $fixture['quantity']  . "</td>";
    }
    /*********************************************************************************/

    $total = 0;
    if ($valid) {
        if ($fixture["info_fixture"]['tipo'] == 0 && is_numeric($fixture['quantity'])) {
            $total_extra_quantity += $fixture['quantity'];
            $total = $fixture["info_fixture"]['precio_pagar'] * $fixture['quantity'];
        } else {
            $total_extra_quantity += 1;
            $total = $fixture["info_fixture"]['precio_pagar'];
        }
        $total_extra_total += $total;
    }
    if ($valid) {
        $template_not_count .= "
                <tr>
                    <td style='text-align:left;border: 1px solid #dadada;'>" . $fixture["info_fixture"]['nombre'] . "</td>
                    $quantity
                </tr>
            ";
    }
}

$basement = "";
$basement_value = ($roughForm['basement_or_slab'] == 'Yes') ? 'Basement' : 'Slab house';
$well = ($roughForm['well'] == 'Yes') ? 'Well' : '';

if ($roughForm['well'] == 'Yes') {
    $well2 = "<td style='text-align:center;border: 1px solid #dadada;'><b>" .  $well .  "</b></td>";
} else {
    $well2 = "";
}

if ($roughForm['forsyth_county'] != "No") {
    $forsyth_county = "<td style='text-align:center;border: 1px solid #dadada;'>Forsyth County</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['forsyth_county'] . "</b></td>";
} else {
    $forsyth_county = "";
}

$sewer =  ($roughForm['sewer_system'] == 1) ? 'Septic System' : 'Sewer';
if ($roughForm['basement_or_slab'] == 'Yes') {
    $basement =  $roughForm['ejector_tank'];
}

// <tr>
//     <td style='text-align:center;border: 1px solid #dadada;'>Future Bath - Bonus Room</td>
//     <td style='text-align:center;border: 1px solid #dadada;'>".$roughForm['future_bath_bonus']."</td>
// </tr>

$template = "
        <h4 style='text-align:center;'>House Sheet</h4>
        <table class='table table-bordered' style='font-size: 14px;'>
            <tbody>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Neighborhood</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['neighborhood'] . "</b></td>
                    <td style='text-align:center;border: 1px solid #dadada;'>Lot number</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['lot'] . "</b></td>
                    <td style='text-align:center;border: 1px solid #dadada;'>Supervisor</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['superv'] . "</b></td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Builder</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['builder_obj']['nombre'] . "</b></td>
                    <td style='text-align:center;border: 1px solid #dadada;'>Installer</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $worker['nombre'] . "</b></td>
                    <td style='text-align:center;border: 1px solid #dadada;'>Brand of fixtures/valves</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['brand_of_fixtures'] . "</b></td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Rough type</td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $roughForm['rough_type'] . "</b></td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $sewer . "</b></td>
                    <td style='text-align:center;border: 1px solid #dadada;'><b>" . $basement_value . " - " . $basement . "</b></td>  
                </tr>
                <tr>
                    $forsyth_county
                    $well2   
                </tr> 
                </tr>
            </tbody>
        </table>
        <br>
        <div style='display: flex' >
            <table  style='width:55%;   font-size: 12px; '>
                <thead>
                    <tr>
                        <th style='text-align:center;border: 1px solid #dadada;'>FIXTURE COUNT</th>
                        <th style='text-align:center;border: 1px solid #dadada;'>Quantity</th>
                        <th style='text-align:center;border: 1px solid #dadada;'>Type</th>
                        <th style='text-align:center;border: 1px solid #dadada;'>Provide by other</th>
                        <th style='text-align:center;border: 1px solid #dadada;'>Two Valves</th>
                    </tr>
                </thead>
                <tbody>
                    $template
                </tbody>
            </table>
            <table  style='width:40%;    font-size: 12px;  float: right'>
                <thead>
                    <tr>
                        <th style='text-align:center;border: 1px solid #dadada;'>NOT FIXTURE COUNT</th>
                        <th style='text-align:center;border: 1px solid #dadada;'></th>
                    </tr>
                </thead>
                <tbody>
                    $template_not_count
                </tbody>
            </table>
            </div>
            $others
        </br>
        
    ";
// echo $template;
$dompdf->loadHtml($template);
$dompdf->render();
$d = date("Y-m-d");
$name = "$d Rough sheet summary pay";
// echo $template;
header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=$name.pdf");
echo $dompdf->output();