
<?php 

error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require './views/vendor/autoload.php';

$actividades = GestorActividadesController::act_without_workers();
// $usuerios = GestorActividadesController::act_without_workers_separeted();
$receivers = GestorConfigController::daily_mails();

// phpinfo();

$template = "";

$ahora = time();		
$ayer = strtotime("-1 day", $ahora);
$ayerLegible = date("m-d-Y", $ayer);

$mail = new PHPMailer(true);
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
); 

for($z = 0; $z < count($actividades); $z++){

    $item = $actividades[$z];
    $typ = $item["tipo_"];
   
    $textos = "";
    $f_group = "";

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


    $template .= '
        <tr class="fc-list-item position-relative">
            <td style="text-align:center;border: 1px solid #dadada;"> 
                '.$item["tipo_"]["nombre"].' <b>'.$textos.'</b>
            </td>
            <td style="text-align:center;border: 1px solid #dadada;"> 
                '.$item["subdivision_"]["nombre"].'
            </td>
            <td style="text-align:center;border: 1px solid #dadada;"> 
                '.$item["lote"].'
            </td>    
            <td style="text-align:center;border: 1px solid #dadada;" colspan="3"> 
                '.$item["descripcion"].'
            </td>
        </tr>
    ';
   
}

// Mail por usuarios --comentado

// for ($i=0; $i < count($usuerios); $i++) { 
//     $user = $usuerios[$i];
//     $acts = $user["actividades"];

//     $template_2 = "";

//     for($z = 0; $z < count($acts); $z++){

//         $item = $acts[$z];
//         $typ = $item["tipo_"];
       
//         $textos = "";
//         $f_group = "";
    
//         if (isset($item["textos"])) {
//             $selecteds = [];
        
//             for($x = 0; $x < count($item["textos"]); $x++){
//                 if ($item["textos"][$x]["valor"] == "1") {
//                     array_push($selecteds, $item["textos"][$x]["texto"]);
//                 }
//             }
    
//             if (count($selecteds) != 0) {
//                 $textos =  "(" . implode(",", $selecteds) . ")";
//             }
    
            
//         }
    
    
//         $template_2 .= '
//             <tr class="fc-list-item position-relative">
//                 <td style="text-align:center;border: 1px solid #dadada;"> 
//                     '.$item["tipo_"]["nombre"].' <b>'.$textos.'</b>
//                 </td>
//                 <td style="text-align:center;border: 1px solid #dadada;"> 
//                     '.$item["subdivision_"]["nombre"].'
//                 </td>
//                 <td style="text-align:center;border: 1px solid #dadada;"> 
//                     '.$item["lote"].'
//                 </td>    
//                 <td style="text-align:center;border: 1px solid #dadada;" colspan="3"> 
//                     '.$item["descripcion"].'
//                 </td>
//             </tr>
//         ';
       
//     }

//     $template_2 = '

//         <div style="background-color: #F7F7F7;
//             padding: 70px 0;
//             -webkit-text-size-adjust: none !important;
//             width: 100%;"  id="wrapper">

//             <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">

//                 <tr>
//                     <td align="center" valign="top">
//                         <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: white;color:#2E4057;">

//                             <tr>
//                                 <td align="center" valign="top">
                                
//                                     <h2 style="text-align:center;">
//                                         Activities without assigned worker
//                                     </h2>
//                                     <h3 style="text-align:center;">'.$ayerLegible.'</h3>
                                                                                                    
//                                     <table>
//                                         <thead>
//                                             <th style="width:110px">Type</th>
//                                             <th>Subdivision</th>
//                                             <th>Lot</th>
//                                             <th>Description</th>
//                                         </thead>
//                                         <tbody>
//                                             '.$template_2.'
//                                         </tbody>
//                                     </table>

//                                     <br/><br/><br/>
                                    
                                                                                    
//                                 </td>
//                             </tr>


//                             <tr>
//                             <td align="center" valign="top">
//                                 <!-- Footer -->
//                                 <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">

//                                     <tr>
//                                         <td valign="top" style="padding: 0;border-radius: 6px;">
//                                             <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                            
//                                                 <tr>
//                                                     <td colspan="2" valign="middle" id="credit">
//                                                         <div style="width:100%;background: #1E1E2D;height: 30px;"></div>
//                                                     </td>
//                                                 </tr>
                                        
//                                             </table>
//                                         </td>
//                                     </tr>
                            
//                                 </table>

//                             </td>

//                         </tr>

//                         </table>
//                     </td>
//                 </tr>

//             </table>
            

//         </div>

//     ';

//     // email por usuarios
//     if (count($acts)) {
//         try {
//             //Server settings
//             $mail->SMTPDebug = 0;                      
//             $mail->isSMTP();                                          
//             $mail->CharSet = "UTF-8";
//             $mail->Host       = 'mail.kp-plumbing.com';                    
//             $mail->Port       = 587;   
//             $mail->SMTPSecure =  "tls"; //PHPMailer::ENCRYPTION_STARTTLS;
//             $mail->SMTPAuth   = true;      
//             $mail->Username   = "plumbermailer@kp-plumbing.com";                     // SMTP username
//             $mail->Password   = "hUX2KbwtG";                              // SMTP password
            
//             //Recipients
//             $mail->setFrom("plumbermailer@kp-plumbing.com", "KP Plumbing");
//             $mail->addAddress($user["correo"], $user["usuario"]);                  
    
//             // Content
//             $mail->isHTML(true);                                  
//             $mail->Subject = "Daily mail";
//             $mail->Body    = $template_2;
    
//             $mail->send();
    
//             echo "sended!";
    
//         } catch (Exception $e) {
    
//             $error = "tMessage could not be sent. Mailer Error: {$mail->ErrorInfo}";
            
//             var_dump($error);
//             // return $error;
//         }
//     }

    
// }

$template = '

    <div style="background-color: #F7F7F7;
        padding: 70px 0;
        -webkit-text-size-adjust: none !important;
        width: 100%;"  id="wrapper">

        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">

            <tr>
                <td align="center" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="template_container" style="background-color: white;color:#2E4057;">

                        <tr>
                            <td align="center" valign="top">
                            
                                <h2 style="text-align:center;">
                                    Activities without assigned worker
                                </h2>
                                <h3 style="text-align:center;">'.$ayerLegible.'</h3>
                                                                                                
                                <table>
                                    <thead>
                                        <th style="width:110px">Type</th>
                                        <th>Subdivision</th>
                                        <th>Lot</th>
                                        <th>Description</th>
                                    </thead>
                                    <tbody>
                                        '.$template.'
                                    </tbody>
                                </table>

                                <br/><br/><br/>
                                
                                                                                
                            </td>
                        </tr>


                        <tr>
                        <td align="center" valign="top">
                            <!-- Footer -->
                            <table border="0" cellpadding="10" cellspacing="0" width="600" id="template_footer">

                                <tr>
                                    <td valign="top" style="padding: 0;border-radius: 6px;">
                                        <table border="0" cellpadding="10" cellspacing="0" width="100%">
                                        
                                            <tr>
                                                <td colspan="2" valign="middle" id="credit">
                                                    <div style="width:100%;background: #1E1E2D;height: 30px;"></div>
                                                </td>
                                            </tr>
                                    
                                        </table>
                                    </td>
                                </tr>
                           
                            </table>

                        </td>

                    </tr>

                    </table>
                </td>
            </tr>

        </table>
        

    </div>



';

// email global -- todas las actividades sin importar el usuario

// var_dump($receivers);

if (count($actividades)) {
    for ($i=0; $i < count($receivers); $i++) { 
    
        $receiver = $receivers[$i]["mail"];
      
        // $headers = "From:" . $receiver;
        // mail($receiver, "Daily mail", $template);
    
        try {
            //Server settings
            $mail->SMTPDebug = 0;                      
            $mail->isSMTP();                                          
            $mail->CharSet = "UTF-8";
            $mail->Host       = 'mail.kp-plumbing.com';                    
            $mail->Port       = 587;   
            $mail->SMTPSecure =  "tls"; //PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAuth   = true;      
            $mail->Username   = "plumbermailer@kp-plumbing.com";                     // SMTP username
            $mail->Password   = "hUX2KbwtG";                              // SMTP password
            
            //Recipients
            $mail->setFrom("plumbermailer@kp-plumbing.com", "KP Plumbing");
            $mail->addAddress($receiver, "Jesus David Martinez");                  
    
            // Content
            $mail->isHTML(true);                                  
            $mail->Subject = "Daily mail";
            $mail->Body    = $template;
    
            $mail->send();
    
            echo "sended!";
    
        } catch (Exception $e) {
    
            $error = "tMessage could not be sent. Mailer Error: {$mail->ErrorInfo}";
            
            var_dump($error);
            // return $error;
        }
    }
}






?>