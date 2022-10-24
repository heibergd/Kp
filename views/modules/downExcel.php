<?php 
    error_reporting(E_ALL);
    require_once './views/vendor/PHPExcel-1.8/Classes/PHPExcel.php';

    $actividades = GestorActividadesModel::actividades_total(false, true);

    $objPHPExcel = new PHPExcel();

    // Set document properties
    $objPHPExcel->getProperties()->setCreator("KP-Plumbing Administrator")
                ->setLastModifiedBy("KP-Plumbing Administrator")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");


    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)                    
                ->setCellValue('A1', 'Type')
                ->setCellValue('B1', 'Subdivision')
                ->setCellValue('C1', 'Lot')
                ->setCellValue('D1', 'Installer')                    
                ->setCellValue('E1', 'Date')
                ->setCellValue('F1', 'Description')
                ->setCellValue('F2', '');

    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15); 
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(50); 



    $linea1 = 2;
    $fila = 2;

    for ($j=0; $j < count($actividades); $j++) { 
        $item = $actividades[$j];

        $bg = "";
        $status = "";
        $textos = "";

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


        $aMas = $fila + 1;

        $t_name = $item["tipo"]["nombre"] . ' ' . $textos;

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$fila", $t_name)
                    ->setCellValue("B$fila", $item["subdivision"]["nombre"])
                    ->setCellValue("C$fila", $item["lote"])
                    ->setCellValue("D$fila", $item["worker"]["nombre"])
                    ->setCellValue("E$fila", $item["fecha_group"]["format_us"])
                    ->setCellValue("F$fila", $item["descripcion"]);

        

        $title = "Excel report";
        $objPHPExcel->getActiveSheet()->setTitle($title);

        $fila = $fila + 1;

        $linea1++;
    }

    




    $objPHPExcel->setActiveSheetIndex(0);


    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');

    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
    header ('Cache-Control: cache, must-revalidate'); 
    header ('Pragma: public'); 


    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');


?>