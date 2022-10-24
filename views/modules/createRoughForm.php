<?php
if (!isset($_SESSION["usuario_validado"])) {
    echo "<script> window.location.href = 'login' </script>";
    exit();
}

$id = $_GET["id"];
$code = $_GET["code"];
$actividad = GestorActividadesController::info_actividad($id);
$info = GestorRoughFormController::info_rough_form($code);
$idconstructoras = $actividad['subdivision_']['constructoras'];

if (isset($actividad['have_form_rough'])) {
    if ($actividad['have_form_rough'] > 0) {
        $id_form = $actividad['id_form_rough'];
        echo "<script> window.location.href = 'editRoughForm_$id_form' </script>";
    }
} else if (!isset($actividad['id'])) {
    echo "<script> window.location.href = 'roughForms' </script>";
}


$neighbor = (isset($actividad['subdivision_'])) ? $actividad['subdivision_']['nombre'] : '';
$neighborhood = $actividad['subdivision_']['id'];
$address = (isset($actividad['address'])) ? $actividad['address'] : '';
$worker_name = (isset($actividad['worker_'])) ? $actividad['worker_']['nombre'] : '';


$lot = (isset($actividad['lote'])) ? $actividad['lote'] : '';

$constructoras = GestorConstructoraController::constructoras($idconstructoras);
$workers = GestorWorkersController::roughWorkers();
$usuarios = GestorUsuariosController::usuarios();
$mainFixtures = GestorFixtureController::Mainfixtures();
$extrafixtures = GestorFixtureController::Extrafixtures(); // Los extras
$plantillas = GestorRoughFormController::plantillas();
$future = GestorFixtureController::future();
// Nueva consulta para la seccion de others
$others_info = GestorFixtureController::others();
// var_dump($others_info);
?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    <a href="roughForms" class="mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect id="bound" x="0" y="0" width="24" height="24" />
                                <path d="M21.4451171,17.7910156 C21.4451171,16.9707031 21.6208984,13.7333984 19.0671874,11.1650391 C17.3484374,9.43652344 14.7761718,9.13671875 11.6999999,9 L11.6999999,4.69307548 C11.6999999,4.27886191 11.3642135,3.94307548 10.9499999,3.94307548 C10.7636897,3.94307548 10.584049,4.01242035 10.4460626,4.13760526 L3.30599678,10.6152626 C2.99921905,10.8935795 2.976147,11.3678924 3.2544639,11.6746702 C3.26907199,11.6907721 3.28437331,11.7062312 3.30032452,11.7210037 L10.4403903,18.333467 C10.7442966,18.6149166 11.2188212,18.596712 11.5002708,18.2928057 C11.628669,18.1541628 11.6999999,17.9721616 11.6999999,17.7831961 L11.6999999,13.5 C13.6531249,13.5537109 15.0443703,13.6779456 16.3083984,14.0800781 C18.1284272,14.6590944 19.5349747,16.3018455 20.5280411,19.0083314 L20.5280247,19.0083374 C20.6363903,19.3036749 20.9175496,19.5 21.2321404,19.5 L21.4499999,19.5 C21.4499999,19.0068359 21.4451171,18.2255859 21.4451171,17.7910156 Z" id="Shape" fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>
                    </a>
                    New Rough Sheet
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body kt-portlet__body--fit p-2">
                <!--begin::Form-->
                <form class="kt-form" id="formNewRoughForm">
                    <div class="kt-portlet__body">
                        <h4 class="kt-subheader__title text-center">
                            House Rough Sheet
                        </h4>
                        <?php if ($plantillas) { ?>
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Model Sheet</label>
                                    <select name="plantilla" class="form-control" id="plantillas">
                                        <option value="">none</option>
                                        <?php
                                        foreach ($plantillas as $plantilla) {
                                            $selected = "";
                                            if ($code == $plantilla['id']) {
                                                $selected = "selected";
                                            }
                                            echo "<option value='" . $plantilla['id'] . "' $selected>" . $plantilla['nombre_plantilla'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        <?php
                        }
                        if (!$info) {
                        ?>
                            <div class="kt-section kt-section--first">
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Neighborhood: <code>*</code></label>
                                        <input type="text" class="form-control" name="neighborhood_name" id="subdivision" value="<?php echo $neighbor ?>">
                                        <input type="hidden" class="form-control" name="neighborhood" id="neighborhood" value="<?php echo $neighborhood ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Lot: <code>*</code></label>
                                        <input type="text" class="form-control" name="lot" id="lote" value="<?php echo $lot ?>">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Builder <code>*</code></label>
                                        <select name="builder" class="form-control" id="builders">
                                            <?php
                                            foreach ($constructoras as $builder) {
                                                $selected_builder = "";
                                                if ($info && $builder['id'] == $info['builder']) {
                                                    $selected_builder = "selected";
                                                }
                                                echo "<option value='" . $builder['id'] . "' $selected_builder>" . $builder['nombre'] . "</option>";
                                            }
                                            if ($neighborhood == 7) {
                                                echo "<option  selected ></option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Sewer System:</label>
                                        <select name="sewer_system" id="sewer_system" class="form-control">
                                            <option value="0" <?php echo ($actividad['subdivision_']['sewer'] == "0" ? 'selected' : '');  ?>>Sewer</option>
                                            <option value="1" <?php echo ($actividad['subdivision_']['sewer'] == "1" ? 'selected' : '');  ?>>Septic System</option>
                                            <?php if ($neighborhood == 7) {
                                                echo "<option  selected ></option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Well:</label>
                                        <select name="well" id="well" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No" selected>No</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Installer <code>*</code></label>
                                        <select name="payroll" id="payroll" class="form-control">
                                            <option value="" selected disabled>Select Installer</option>
                                            <?php
                                            $find = false;
                                            foreach ($workers as $worker) {
                                                $selected = "";
                                                if ($worker['id'] == $actividad['trabajador']) {
                                                    $find = true;
                                                    $selected = "selected";
                                                }
                                                echo "<option value='" . $worker['id'] . "' $selected>" . $worker['nombre'] . "</option>";
                                            }
                                            ?>
                                        </select>
                                        <?php
                                        if (!$find) {
                                            echo "<span class='form-text text-danger'>Worker <b>$worker_name</b> no found in rough workers </span>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <div><?php //var_dump($actividad['subdivision_']) 
                                                ?></div>
                                        <label>Basement or slab house:</label>
                                        <select name="basement_or_slab" id="basement_or_slab" class="form-control trigger_show" data-field="future_bath">
                                            <option value="No">Slab house</option>
                                            <option value="Yes">Basement</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <div class="future_bath hidden">
                                                    <label>Future Bath-Basement:</label>
                                                    <select name="future_bath_basement" id="future_bath_basement" class="form-control">
                                                        <option selected value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="future_bath hidden">
                                                    <label>Ejector Tank or Gravity fall:</label>
                                                    <select name="ejector_tank" id="ejector_tank" class="form-control">
                                                        <option selected value="Ejector Tank">Ejector Tank</option>
                                                        <option value="Gravity fall">Gravity fall</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Brand of fixtures/valves: <code>*</code></label>
                                        <select name="fixtures" id="brand_of_fixtures" class="form-control" required>
                                            <option selected disabled>Select</option>
                                            <option value="Delta">Delta</option>
                                            <option value="Pfizter">Pfizter</option>
                                            <option value="Moen">Moen</option>
                                            <option value="Brizo">Brizo</option>
                                            <option value="Mix">Mix</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Supervisor <code>*</code></label>
                                        <select name="supervisor" id="supervisor" class="form-control">
                                            <?php foreach ($usuarios as $user) {
                                                if ($_SESSION["id_usuario"] == $user['id']) {
                                                    echo "<option value='" . $user['id'] . "' selected >" . $user['usuario'] . "</option>";
                                                } else {
                                                    echo "<option value='" . $user['id'] . "'>" . $user['usuario'] . "</option>";
                                                }
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Rough type:</label>
                                        <select name="rough_type" id="rough_type" class="form-control">
                                            <option selected value="Regular">Regular</option>
                                            <option value="Basement">Basement</option>
                                            <option value="Remodel">Remodel</option>
                                            <option value="Pool House">Pool House</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Forsyth county:</label>
                                        <select name="forsyth_county" id="forsyth_county" class="form-control">
                                            <option value="Yes" <?php if ($actividad['subdivision_']['forsyth_county'] == "Yes") {
                                                                    echo "selected";
                                                                } ?>>Yes</option>
                                            <option value="No" <?php if ($actividad['subdivision_']['forsyth_county'] == "No") {
                                                                    echo "selected";
                                                                } ?>>No</option>
                                            <?php if ($neighborhood == 7) {
                                                echo "<option  selected ></option>";
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <?php
                                $titulo_provider_mostrado = false;
                                $titulo_valves_mostrado = false;
                                ?>
                                <h4 class="kt-subheader__title text-center">
                                    Main Fixtures
                                </h4>
                                <div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="font-size: 1.2rem; font-weight: bold;">Fixture</th>
                                                <th class="text-center" style="font-size: 1.2rem; font-weight: bold;">Quantity</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody style="font-weight: 600;">
                                            <?php
                                            foreach ($mainFixtures as $fixture) {
                                            ?>
                                                <tr id="tr_<?php echo $fixture['id'] ?>" class="tr_fixture" data-id="<?php echo $fixture['id'] ?>">
                                                    <td>
                                                        <?php echo $fixture['nombre'] ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!$titulo_provider_mostrado && $fixture["mostrar_provider"] == 1) { ?>
                                                            <span style="height: 29px;display: block;"></span>
                                                        <?php } ?>
                                                        <?php //generación de los fixtures

                                                        // En esta seccion esta el problema con el dog shower que no carga la valvula
                                                        if ($fixture['tipo'] == 0) { ?>
                                                            <?php if ($fixture["multiple"] == 1) { ?>
                                                                <i class="fa fa-arrow-right desplegar" id="desplegar_<?php echo $fixture['id'] ?>" data-field="<?php echo $fixture['id'] ?>"></i>
                                                            <?php } ?>
                                                            <input type="text" style="width: auto; display: inline;" class="form-control trigger_show mains <?php echo $fixture['valve'] > 0 ? 'count_valve' : '' ?>" data-field="fixture_hidden__<?php echo $fixture['id'] ?>" name="quantity_<?php echo $fixture['id'] ?>" id="quantity_<?php echo $fixture['id'] ?>" data-id="<?php echo $fixture['id'] ?>">
                                                        <?php } else if ($fixture['tipo'] == 1) { ?>
                                                            <select name="quantity_<?php echo $fixture['id'] ?>" id="quantity_<?php echo $fixture['id'] ?>" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id'] ?>">
                                                                <option value="No" selected>No</option>
                                                                <option value="Yes">Yes</option>
                                                            </select>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!$titulo_provider_mostrado && $fixture["mostrar_provider"] == 1) { ?>
                                                            <span style="height: 29px;display: block;"></span>
                                                        <?php } ?>
                                                        <?php if ($fixture["items"] != "") { ?>
                                                            <?php $items = explode(',', $fixture["items"]); ?>
                                                            <select class="form-control sub_pregunta_1 fixture_hidden__<?php echo $fixture['id'] ?>  hidden">
                                                                <option value="">Select</option>
                                                                <?php foreach ($items as $item) { ?>
                                                                    <option><?php echo $item; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if (!$titulo_provider_mostrado && $fixture["mostrar_provider"] == 1) { ?>
                                                            <label class="others hidden">Provide by Other?</label>
                                                        <?php
                                                            $titulo_provider_mostrado = true;
                                                        }
                                                        ?>
                                                        <?php if ($fixture["mostrar_provider"] == 1) { ?>
                                                            <select class="form-control sub_pregunta_2 fixture_hidden__<?php echo $fixture['id'] ?> hidden">
                                                                <option>Yes</option>
                                                                <option selected>No</option>
                                                            </select>
                                                        <?php } ?>
                                                    </td>
                                                    <?php if ($fixture["valve"] >= 1) { ?>
                                                        <td>
                                                            <?php if (!$titulo_valves_mostrado && $fixture["valve"] >= 1) { ?>
                                                                <label class="valvesLabel others hidden">Two Valves?</label><?php $titulo_valves_mostrado = true;
                                                                                                                        } ?>
                                                            <?php if ($fixture["valve"] >= 2) { ?>
                                                                <select class="form-control sub_pregunta_3 valvulaselect fixture_hidden__<?php echo $fixture['id'] ?> hidden" data-id="<?php echo $fixture['id'] ?>">
                                                                    <option>Yes</option>
                                                                    <option selected>No</option>
                                                                </select>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td>
                                                    <label>Future Bath - Bonus Room</label>
                                                </td>
                                                <td>
                                                    <select name="future_bath_bonus" class="form-control" id="future_bath_bonus">
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php
                                            foreach ($future as $fixtureFuture) {

                                            ?>
                                                <tr id="ftr_<?php echo $fixtureFuture['id'] ?>" class="next_future_bath hidden" data-id="<?php echo $fixtureFuture['id'] ?>">
                                                    <td>
                                                        <label><?php echo $fixtureFuture['nombre'] ?></label>
                                                    </td>
                                                    <td>
                                                        <?php //generación de los fixtureFutures
                                                        if ($fixtureFuture['tipo'] == "0") { ?>
                                                            <!-- <?php if ($fixtureFuture["multiple"] == 1) { ?>
                                                                <i class="fa fa-arrow-right fDesplegar" id="fDesplegar_<?php echo $fixtureFuture['id'] ?>" data-field="<?php echo $fixtureFuture['id'] ?>"></i>
                                                            <?php } ?> -->
                                                            <!-- <input type="text" style="width: auto; display: inline;" class="form-control fTrigger_show mains <?php echo $fixture['valve'] > 0 ? 'count_valve' : '' ?>" data-field="fixtureFuture_hidden__<?php echo $fixtureFuture['id'] ?>" id="fQuantity_<?php echo $fixtureFuture['id'] ?>" data-id="<?php echo $fixtureFuture['id'] ?>"> -->
                                                            <input type="text" style="width: auto; display: inline;" class="form-control fTrigger_show mains <?php echo $fixture['valve'] > 0 ? '' : '' ?>" data-field="fixtureFuture_hidden__<?php echo $fixtureFuture['id'] ?>" id="fQuantity_<?php echo $fixtureFuture['id'] ?>" data-id="<?php echo $fixtureFuture['id'] ?>">
                                                        <?php }  ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($fixtureFuture["items"] != "") { ?>
                                                    <td>
                                                        <select class="form-control sub_pregunta_2 fixtureFuture_hidden__<?php echo $fixtureFuture['id'] ?> hidden">
                                                            <option>Yes</option>
                                                            <option selected>No</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select class="form-control sub_pregunta_3 valvulaselect fixtureFuture_hidden__<?php echo $fixtureFuture['id'] ?> hidden">
                                                            <option>Yes</option>
                                                            <option selected>No</option>
                                                        </select>
                                                    </td>
                                                <?php } ?>
                                                </td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td>Extra Valves</td>
                                                <td>
                                                    <input type="text" class="valves form-control" id="extra_valves">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label style="font-weight: bold; font-size:1.1rem;">Total </label>
                                                </td>
                                                <td class="text-center">
                                                    <span class="total_mains" style="font-weight: bold; font-size:1.1rem;"></span>
                                                </td>
                                                <td>
                                                    <label style="font-weight: bold;font-size:1.1rem;">Total Valves:</label>
                                                </td>
                                                <td class="text-center">
                                                    <label class="total_valves"></label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <h4 class="kt-subheader__title text-center">
                                    Extra Fixtures
                                </h4>
                                <div>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="font-size: 1.2rem; font-weight: bold;">Fixture</th>
                                                <th class="text-center" style="font-size: 1.2rem; font-weight: bold;">Quantity</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Carga los extra fixtures
                                            foreach ($extrafixtures as $fixture) {
                                            ?>
                                                <tr class="extra_fixture" style="font-weight: 600;" data-id="<?php echo $fixture['id'] ?> ">
                                                    <td><?php echo $fixture['nombre'] ?></td>
                                                    <td>
                                                        <?php if ($fixture['tipo'] == 0) { ?>
                                                            <input type="text" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id'] ?>" id="quantity_<?php echo $fixture['id'] ?>">
                                                        <?php } else if ($fixture['tipo'] == 1) { ?>
                                                            <select id="quantity_<?php echo $fixture['id'] ?>" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id'] ?>">
                                                                <option value="No">No</option>
                                                                <option value="Yes">Yes</option>
                                                            </select>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($fixture["pan_title"] == 1) { ?>
                                                            <select class="form-control sub_pregunta_1 fixture_hidden__<?php echo $fixture['id'] ?> hidden">
                                                                <option>Select</option>
                                                                <option value="Pan">Pan</option>
                                                                <option value="Tile">Tile</option>
                                                            </select>
                                                        <?php } ?>
                                                        <!-- Seccion para otros  -->

                                                    </td>

                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td class="extra_fixture1" style="font-weight: 600;">Others</td>
                                                <td>
                                                    <select class="form-control" id="othersInfo">
                                                        <option value="No">No</option>
                                                        <option value="Yes">Yes</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea id="textAreaOther" cols="30" rows="3" class="form-control hidden" placeholder="What is extra" style="width: 400px;"></textarea></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <?php
                        if ($info) {
                            $lote = $lot;
                            include('cuerpoTemplateRoughSheet.php');
                        }
                        ?>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="kt-checkbox kt-checkbox--solid">
                                        <input type="checkbox" value="1" id="guardar_plantilla" name="guardar_plantilla">Save as template<span></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group hidden" id="content_nombre_plantilla">
                                    <input type="text" class="form-control " name="nombre_plantilla" id="nombre_plantilla" placeholder="Nombre">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button class="btn btn-primary" id="createRoughForm">CREATE</button>
                            <button type="reset" class="btn btn-default">CLEAR</button>
                            <span class="px-2 mensajeRespuesta"></span>
                        </div>
                    </div>
                </form>
                <!--end::Form-->
            </div>
        </div>
        <!--end::Portlet-->
    </div>
    <!-- end:: Content -->
</div>