<?php 
    error_reporting(E_ALL);
    include_once "../vendor/autoload.php";
    require_once '../../models/conexion.php';
	require_once '../../models/consulta.php';
    require_once '../../controllers/gestorFixture.php';
	require_once '../../controllers/gestorRoughForm.php';
    require_once '../../models/gestorFixture.php';
    require_once '../../models/gestorRoughForm.php';


    use Dompdf\Dompdf;
    try {       
        $dompdf = new Dompdf();
    } catch (\Throwable $th) {
        var_dump($th);
    }

    $id = $_POST["id"];

    $mainFixtures = GestorFixtureController::Mainfixtures();
    $extrafixtures = GestorFixtureController::Extrafixtures();
    $roughForm = GestorRoughFormController::info_rough_form($id);

    $template = "";
    $total_main_quantity = 0;
    $total_extra_quantity = 0;
    $total_main_total = 0;
    $total_extra_total = 0;

    foreach ($mainFixtures as $fixture){

        $db_fixture = GestorRoughFormModel::fixture_form($roughForm['id'], $fixture['id']);

        $valid = false;
        if ($db_fixture) {
            $valid = ($db_fixture['quantity'] != '' && $db_fixture['quantity'] != 'No') ? true : false;
        }

        $total = 0;

        if ($db_fixture && $valid){
            if ($fixture['tipo'] == 0 && is_numeric($db_fixture['quantity'])) {
                $total_main_quantity += $db_fixture['quantity'];
                $total = $fixture['precio_pagar'] * $db_fixture['quantity'];
            }else{
                $total_main_quantity += 1;
                $total = $fixture['precio_pagar'];
            }
            $total_main_total += $total;
        }

        if ($db_fixture && $valid) {
            $template .= "
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $fixture['nombre'] ."</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $db_fixture['quantity']  ."</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>$ ".number_format($fixture['precio_pagar'], 2)  ."</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>$ ". number_format($total,2)  ."</td>
                </tr>
            ";
        }
    }

    $template .= "
        <tr>
            <td style='text-align:center;border: 1px solid #dadada;'>Total Fixture Count</td>
            <td style='text-align:center;border: 1px solid #dadada;'> ". $total_main_quantity ."</td>
            <td style='text-align:center;border: 1px solid #dadada;'></td>
            <td style='text-align:center;border: 1px solid #dadada;'><b>$ ". number_format($total_main_total, 2) ."</b></td>
        </tr>
        <tr>
            <th style='padding: 4px;'></th>
            <th style='padding: 4px;'></th>
            <th style='padding: 4px;'></th>
            <th style='padding: 4px;'></th>
        </tr>
        <tr>
            <th style='text-align:center;border: 1px solid #dadada;'>NOT FIXTURE COUNT</th>
            <th style='text-align:center;border: 1px solid #dadada;'></th>
            <th style='text-align:center;border: 1px solid #dadada;'></th>
            <th style='text-align:center;border: 1px solid #dadada;'></th>
        </tr>
    ";

    foreach ($extrafixtures as $fixture){

        $db_fixture = GestorRoughFormModel::fixture_form($roughForm['id'], $fixture['id']);

        $valid = false;
        if ($db_fixture) {
            $valid = ($db_fixture['quantity'] != '' && $db_fixture['quantity'] != 'No') ? true : false;
        }

        $total = 0;
        if ($db_fixture && $valid){
            if ($fixture['tipo'] == 0 && is_numeric($db_fixture['quantity'])) {
                $total_extra_quantity += $db_fixture['quantity'];
                $total = $fixture['precio_pagar'] * $db_fixture['quantity'];
            }else{
                $total_extra_quantity += 1;
                $total = $fixture['precio_pagar'];
            }
            $total_extra_total += $total;
        }
                                     

        if ($db_fixture && $valid) {
            $template .= "
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $fixture['nombre'] ."</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $db_fixture['quantity']  ."</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>$ ".number_format($fixture['precio_pagar'], 2)  ."</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>$ ". number_format($total,2)  ."</td>
                </tr>
            ";
        }
       
    }

    $template .= "
        <tr>
            <td style='text-align:center;border: 1px solid #dadada;'>Total Fixture Count</td>
            <td style='text-align:center;border: 1px solid #dadada;'></td>
            <td style='text-align:center;border: 1px solid #dadada;'></td>
            <td style='text-align:center;border: 1px solid #dadada;'><b>$ ". number_format($total_extra_total, 2) ."</b></td>
        </tr>

        <tr>
            <td style='text-align:center;border: 1px solid #dadada;'>Total</td>
            <td style='text-align:center;border: 1px solid #dadada;'></td>
            <td style='text-align:center;border: 1px solid #dadada;'></td>
            <td style='text-align:center;border: 1px solid #dadada;'>$ ". number_format($total_main_total + $total_extra_total, 2) . "</td>
        </tr>
    ";

    $basement = "";
    $basement_value = ($roughForm['basement_or_slab'] == 'Yes') ? 'Basement' : 'Slab house';
    if($roughForm['basement_or_slab'] == 'Yes'){
        $basement = "
            <tr>
                <td style='text-align:center;border: 1px solid #dadada;'>Future Bath-Basement</td>
                <td style='text-align:center;border: 1px solid #dadada;'>".$roughForm['future_bath_basement']."</td>
            </tr>
            <tr>
                <td style='text-align:center;border: 1px solid #dadada;'>Future Bath - Bonus Room</td>
                <td style='text-align:center;border: 1px solid #dadada;'>".$roughForm['future_bath_bonus']."</td>
            </tr>
            <tr>
                <td style='text-align:center;border: 1px solid #dadada;'>Ejector Tank or gravity fall</td>
                <td style='text-align:center;border: 1px solid #dadada;'>".$roughForm['ejector_tank']."</td>
            </tr>
        ";
    }

    $template = "
    
        <h4 style='text-align:center;'>House Sheets</h4>

        <table class='table table-bordered' style='font-size: 14px;'>
           
            <tbody>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Neighborhood</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>".$roughForm['neighborhood']."</td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Lot number</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $roughForm['lot']."</td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Builder</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $roughForm['builder_obj']['nombre']."</td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Address</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $roughForm['address']."</td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Who is doing the work- payroll</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $roughForm['payroll']."</td>
                </tr>
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Basement or slab house</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>". $basement_value ."</td>
                </tr>  
                <tr>
                    <td style='text-align:center;border: 1px solid #dadada;'>Brand of fixtures/valves</td>
                    <td style='text-align:center;border: 1px solid #dadada;'>".$roughForm['brand_of_fixtures']."</td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <table style='width:100%;'>
            <thead>
                <tr>
                    <th style='text-align:center;border: 1px solid #dadada;'>FIXTURE COUNT</th>
                    <th style='text-align:center;border: 1px solid #dadada;'>Quantity</th>
                    <th style='text-align:center;border: 1px solid #dadada;'>$</th>
                    <th style='text-align:center;border: 1px solid #dadada;'>Total</th>
                </tr>
            </thead>
            <tbody>

                $template
            </tbody>
        </table>
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
?>
