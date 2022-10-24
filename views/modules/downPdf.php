<?php 
    error_reporting(E_ALL);
    include_once "./views/vendor/autoload.php";
    use Dompdf\Dompdf;
    try {       
        $dompdf = new Dompdf();
    } catch (\Throwable $th) {
        var_dump($th);
    }
    


    $actividades = GestorActividadesModel::actividades_total(false, true);

    $template = "";
    $init_ = false;
    $f_group = "";

    for($i = 0; $i < count($actividades); $i++){



        $item = $actividades[$i];
        $typ = $item["tipo"];
        $title_date = ($item["fecha_group"]["today"]) ? "TODAY" : $item["fecha_group"]["format"];


        $bg = "";
        $status = "";
        $textos = "";
        
        
        

        if ($item["estado"] == 1) {                       
            $bg = $typ["settings"]->color_ordered;
        }else if($item["estado"] == 2){
            $bg = $typ["settings"]->color_marked;
        }else{
            $bg = $typ["settings"]->defaul_color_;
        }
    
        if ($typ["settings"]->ordered && $item["estado"] != 0) {
            $status = `<div>Ordered</div>`;
        }
        if ($typ["settings"]->marked && $item["estado"] == 2) {
            $status = `<div>Marked</div>`;
        }

        if ($typ["nombre"] == "@Special") {
            $str_ = "";
        }else{
            $str_ = '
                <span>'.$item["subdivision"]["nombre"].'</span> 
                <div>
                    <a href="#" class="set_filter_lot">
                        Lot : '.$item["lote"].'
                    </a>
                </div>                              
                <div>Worker : '. $item["worker"]["nombre"] .'</div> 
            ';
        }

        if (isset($item["textos"])) {
            $selecteds = [];
        
            for($x = 0; $x < count($item["textos"]); $x++){
                if ($item["textos"][$x]["valor"] == "1") {
                    array_push($selecteds, $item["textos"][$x]["texto"]);
                }
            }

            if (count($selecteds) != 0) {
                $textos =  "(" . implode(",", $selecteds) . ")";
            }

            
        }

        if ($f_group != $item["worker"]["nombre"] . $item["fecha_group"]["format_us"]) {
            $f_group = $item["worker"]["nombre"] . $item["fecha_group"]["format_us"];

            $template .= '
            
                <tr class="fc-list-item position-relative">                      
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:center;border: 1px solid #dadada;"> 
                     <b>'.$item["worker"]["nombre"].'</b>
                    </td>
                    <td style="text-align:center;border: 1px solid #dadada;"> 
                        <b>'.$item["fecha_group"]["format_us"].'</b>
                    </td> 
                    <td></td>
                </tr>
            ';
        }

        $textAddress = "";

        if ($item['address'] != "") {
            $textAddress = '  <br><b>Addres</b>: '.$item["address"].'  <br>
            <b>Time</b>: '.$item["time"];
        }

        $template .= '
            <tr class="fc-list-item position-relative">
                <td style="text-align:center;border: 1px solid #dadada;"> 
                    '.$item["tipo"]["nombre"].' <b>'.$textos.'</b>
                </td>
                <td style="text-align:center;border: 1px solid #dadada;"> 
                    '.$item["subdivision"]["nombre"].'
                </td>
                <td style="text-align:center;border: 1px solid #dadada;"> 
                    '.$item["lote"].'
                </td>    
                <td style="text-align:left ;border: 1px solid #dadada;" colspan="3"> 
                    '.nl2br($item["descripcion"]).'
                    <br>
                    '.$textAddress.'
                </td>
            </tr>
            <tr><td colspan="6" style="text-align:center;height:97px;"></td></tr>
        ';
       
    }

    $template = "
        <table>
            <thead>
                <tr>
                    <th style='width:110px'>Type</th>
                    <th>Subdivision</th>
                    <th>Lot</th>
                    <th>Installer</th>
                    <th style='width:110px'>Date</th>
                    <th>Description</th>
                </tr>
                
            </thead>
            <tbody>

                $template
            </tbody>
        </table>
    ";
    $template .= "<div style='text-align:center; margin-top:15px'>Total: ".count($actividades)."</div>";
    // echo $template;

    $dompdf->loadHtml($template);
    $dompdf->render();
    $d = date("Y-m-d");
    $name = "$d ActivitiesList";

    // echo $template;

    header("Content-type: application/pdf");
    header("Content-Disposition: inline; filename=$name.pdf");
    
    echo $dompdf->output();
?>
