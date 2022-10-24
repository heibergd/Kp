<?php

$mainFixtures = $info["main_fixtures"];
$fixtureFutures = $info["future_fixtures"];
$extraFixtures = $info["extra_fixtures"];
$otro = $info['otro']; // Solo para la seccion de otros

?>

<div class="kt-section kt-section--first">
    <div class="row">
        <div class="form-group col-md-4">
            <label>Neighborhood: <code>*</code></label>
            <input type="text" class="form-control" name="neighborhood_name" id="subdivision" value="<?php echo $info["neighborhood"] ?>">
            <input type="hidden" class="form-control" name="neighborhood" id="neighborhood" value="<?php echo $info["neighborhood_id"] ?>">
        </div>
        <div class="form-group col-md-4">
            <label>Lot: <code>*</code></label>
            <input type="text" class="form-control" name="lot" id="lote" value="<?php echo $lote ?>">
        </div>
        <div class="form-group col-md-4">
            <label>Builder <code>*</code></label>
            <select name="builder" class="form-control" id="builders">
                <?php
                foreach ($constructoras as $builder) {
                    $selected_builder = "";

                    if ($builder['id'] == $info['builder']) {
                        $selected_builder = "selected";
                    }
                    echo "<option value='" . $builder['id'] . "' $selected_builder>" . $builder['nombre'] . "</option>";
                }

                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Sewer System:</label>
            <select name="sewer_system" id="sewer_system" class="form-control">
                <option value="0" <?php echo ($info['sewer_system'] == "0" ? 'selected' : '');  ?>>Sewer</option>
                <option value="1" <?php echo ($info['sewer_system'] == "1" ? 'selected' : '');  ?>>Septic System</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Well:</label>
            <select id="well" class="form-control">
                <option value="No" <?php echo ($info["well"] == "No") ? 'selected' : '' ?>>No</option>
                <option value="Yes" <?php echo ($info["well"] == "Yes") ? 'selected' : '' ?>>Yes</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Installer <code>*</code></label>
            <select id="payroll" class="form-control">
                <?php
                foreach ($workers as $worker) {
                    $selected = "";
                    if ($worker['id'] == $info['payroll']) {
                        $selected = "selected";
                    }
                    echo "<option value='" . $worker['id'] . "' $selected>" . $worker['nombre'] . "</option>";
                }
                ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Basement or slab house:</label>
            <select id="basement_or_slab" class="form-control trigger_show" data-field="future_bath">
                <option value="No" <?php echo ($info["basement_or_slab"] == "No") ? 'selected' : '' ?>>
                    Slab house
                </option>
                <option value="Yes" <?php echo ($info["basement_or_slab"] == "Yes") ? 'selected' : '' ?>>
                    Basement
                </option>
            </select>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="form-group col-md-6">
                    <div class="future_bath  <?php echo ($info["basement_or_slab"] == "No") ? 'hidden' : '' ?>">
                        <label>Future Bath-Basement:</label>
                        <select id="future_bath_basement" class="form-control">
                            <option value="No" <?php echo ($info["future_bath_basement"] == "No") ? 'selected' : '' ?>>
                                No
                            </option>
                            <option value="Yes" <?php echo ($info["future_bath_basement"] == "Yes") ? 'selected' : '' ?>>
                                Yes
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <div class="future_bath <?php echo ($info["basement_or_slab"] == "No") ? 'hidden' : '' ?>">
                        <label>Ejector Tank or Gravity fall:</label>
                        <select id="ejector_tank" class="form-control">
                            <option value="">Seleccionar</option>
                            <option value="Ejector Tank" <?php echo ($info["ejector_tank"] == "Ejector Tank") ? 'selected' : '' ?>>Ejector Tank</option>
                            <option value="Gravity fall" <?php echo ($info["ejector_tank"] == "Gravity fall") ? 'selected' : '' ?>>Gravity fall</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <label>Brand of fixtures/valves:</label>
            <select id="brand_of_fixtures" class="form-control" required>
                <option value="">Select</option>
                <option value="Delta" <?php echo ($info["brand_of_fixtures"] == "Delta") ? 'selected' : '' ?>>
                    Delta
                </option>
                <option value="Pfizter" <?php echo ($info["brand_of_fixtures"] == "Pfizter") ? 'selected' : '' ?>>
                    Pfizter
                </option>
                <option value="Moen" <?php echo ($info["brand_of_fixtures"] == "Moen") ? 'selected' : '' ?>>
                    Moen
                </option>
                <option value="Moen" <?php echo ($info["brand_of_fixtures"] == "Brizo") ? 'selected' : '' ?>>
                    Brizo
                </option>
                <option value="Moen" <?php echo ($info["brand_of_fixtures"] == "Mix") ? 'selected' : '' ?>>
                    Mix
                </option>
                <option value="Other" <?php echo ($info["brand_of_fixtures"] == "Other") ? 'selected' : '' ?>>
                    Moen
                </option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Supervisor <code>*</code></label>
            <select id="supervisor" class="form-control">
                <?php
                foreach ($usuarios as $user) {
                    $selected = "";
                    if ($user['id'] == $info['supervisor']) {
                        $selected = "selected";
                    }
                    echo "<option value='" . $user['id'] . "' $selected>" . $user['usuario'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Rough type:</label>
            <select id="rough_type" class="form-control">
                <option value="Regular" <?php echo ($info["rough_type"] == "Regular") ? 'selected' : '' ?>>Regular</option>
                <option value="Basement" <?php echo ($info["rough_type"] == "Basement") ? 'selected' : '' ?>>Basement</option>
                <option value="Remodel" <?php echo ($info["rough_type"] == "Remodel") ? 'selected' : '' ?>>Remodel</option>
                <option value="Pool House" <?php echo ($info["rough_type"] == "Pool House") ? 'selected' : '' ?>>Pool House</option>
                <option value="Others" <?php echo ($info["rough_type"] == "Others") ? 'selected' : '' ?>>Others</option>
            </select>
        </div>
        <div class="form-group col-md-4">
            <label>Forsyth county:</label>
            <select id="forsyth_county" class="form-control">
                <option value="No" <?php echo ($info["forsyth_county"] == "No") ? 'selected' : '' ?>>No</option>
                <option value="Yes" <?php echo ($info["forsyth_county"] == "Yes") ? 'selected' : '' ?>>Yes</option>
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
                    <th>Fixture</th>
                    <th>Quantity</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($mainFixtures as $fixture) {
                    $items_json = ($fixture['json'] != "") ? json_decode($fixture['json']) : [];
                    $id_fixture = $fixture['id_fixture'];
                    foreach ($items_json as $key => $item_json) {
                ?>
                        <tr <?php echo ($key == 0) ? 'id="tr_' . $id_fixture . '"' : '' ?> class="<?php echo ($key > 0) ? 'esclon' : 'tr_fixture' ?>" data-id="<?php echo $fixture['id_fixture'] ?>" data-dbid="<?php echo $fixture['id'] ?>">
                            <td>
                                <?php
                                if ($key == 0) {
                                    echo $fixture["info_fixture"]['nombre'];
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($key == 0) { ?>
                                    <?php if (!$titulo_provider_mostrado && $fixture["info_fixture"]["mostrar_provider"] == 1) { ?>
                                        <span style="height: 29px;display: block;"></span>
                                    <?php } ?>
                                    <?php //generación de los fixtures
                                    if ($fixture["info_fixture"]['tipo'] == 0) { ?>
                                        <?php if ($fixture["info_fixture"]["multiple"] == 1) { ?>
                                            <i class="fa <?php echo (count($items_json) > 1) ? 'fa-arrow-up' : 'fa-arrow-right' ?>  desplegar" id="desplegar_<?php echo $fixture['id_fixture'] ?>" data-field="<?php echo $fixture['id_fixture'] ?>" <?php echo ($fixture["quantity"] != "No" && $fixture["quantity"] != '') ? 'style="visibility: visible;"' : '' ?> <?php echo (count($items_json) > 1) ? 'data-dis="1"' : '' ?>></i>
                                        <?php } ?>
                                        <input type="text" value="<?php echo $fixture['quantity'] ?>" style="width: auto; display: inline;" class="form-control trigger_show mains <?php echo $fixture['info_fixture']['valve'] > 0 ? 'count_valve' : '' ?>" data-field="fixture_hidden__<?php echo $fixture['id_fixture'] ?>" id="quantity_<?php echo $fixture['id_fixture'] ?>" data-id="<?php echo $fixture['id_fixture'] ?>">
                                    <?php } else if ($fixture["info_fixture"]['tipo'] == 1) { ?>
                                        <select name="quantity_<?php echo $fixture['id_fixture'] ?>" id="quantity_<?php echo $fixture['id_fixture'] ?>" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id_fixture'] ?>">
                                            <option value="No" <?php echo ($fixture["quantity"] == "No") ? 'selected' : '' ?>>No</option>
                                            <option value="Yes" <?php echo ($fixture["quantity"] == "Yes") ? 'selected' : '' ?>>Yes</option>
                                        </select>
                                    <?php } ?>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!$titulo_provider_mostrado && $fixture["info_fixture"]["mostrar_provider"] == 1 && $key == 0) { ?>
                                    <span style="height: 29px;display: block;"></span>
                                <?php } ?>
                                <?php if ($fixture["info_fixture"]["items"] != "") { ?>
                                    <?php $items = explode(',', $fixture["info_fixture"]["items"]); ?>
                                    <select class="form-control sub_pregunta_1 fixture_hidden__<?php echo $fixture['id_fixture'] ?> <?php echo ($fixture["quantity"] == "No" || $fixture["quantity"] == '') ? 'hidden' : '' ?> ">
                                        <option value="">Select</option>
                                        <?php foreach ($items as $item) { ?>
                                            <option <?php echo ($item_json->sub_pregunta_1 == $item) ? 'selected' : '' ?>><?php echo $item; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if (!$titulo_provider_mostrado && $fixture["info_fixture"]["mostrar_provider"] == 1 && $key == 0) { ?>
                                    <label class="others hidden">Provide by Other?</label>
                                <?php $titulo_provider_mostrado = true;
                                } ?>
                                <?php if ($fixture["info_fixture"]["mostrar_provider"] == 1) { ?>
                                    <select class="form-control sub_pregunta_2 fixture_hidden__<?php echo $fixture['id_fixture'] ?> <?php echo ($fixture["quantity"] == "No" || $fixture["quantity"] == '') ? 'hidden' : '' ?>">
                                        <option <?php echo ($item_json->sub_pregunta_2 == "Yes") ? 'selected' : '' ?>>Yes</option>
                                        <option <?php echo ($item_json->sub_pregunta_2 == "No") ? 'selected' : '' ?>>No</option>
                                    </select>
                                <?php } ?>
                            </td>
                            <?php if ($fixture["info_fixture"]["valve"] >= 1) { ?>
                                <td>
                                    <?php if (!$titulo_valves_mostrado && $fixture["info_fixture"]["valve"] >= 1 && $key == 0) { ?> <label class="valvesLabel others hidden">Two Valves?</label><?php $titulo_valves_mostrado = true;
                                                                                                                                                                                            } ?>
                                    <?php if ($fixture["info_fixture"]["valve"] >= 2) { ?>
                                        <select class="form-control sub_pregunta_3 valvulaselect fixture_hidden__<?php echo $fixture['id_fixture'] ?> <?php echo ($fixture["quantity"] == "No" || $fixture["quantity"] == '') ? 'hidden' : '' ?>" data-id="<?php echo $fixture['id_fixture'] ?>">
                                            <option <?php echo ($item_json->sub_pregunta_3 == "Yes") ? 'selected' : '' ?>>Yes</option>
                                            <option <?php echo ($item_json->sub_pregunta_3 == "No") ? 'selected' : '' ?>>No</option>
                                        </select>
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    <?php if (count($items_json) == 0) { ?>
                        <tr id="tr_<?php echo $id_fixture ?>" class="tr_fixture" data-id="<?php echo $fixture['id_fixture'] ?>" data-dbid="<?php echo $fixture['id'] ?>">
                            <td>
                                <?php echo $fixture["info_fixture"]['nombre'] ?>
                            </td>
                            <td>
                                <?php //generación de los fixtures
                                if ($fixture["info_fixture"]['tipo'] == 0) { ?>
                                    <input type="text" value="<?php echo $fixture['quantity'] ?>" style="width: auto; display: inline;" class="form-control trigger_show mains" data-field="fixture_hidden__<?php echo $fixture['id_fixture'] ?>" id="quantity_<?php echo $fixture['id_fixture'] ?>" data-id="<?php echo $fixture['id_fixture'] ?>">
                                <?php } else if ($fixture["info_fixture"]['tipo'] == 1) { ?>
                                    <select name="quantity_<?php echo $fixture['id_fixture'] ?>" id="quantity_<?php echo $fixture['id_fixture'] ?>" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id_fixture'] ?>">
                                        <option value="No" <?php echo ($fixture["quantity"] == "No") ? 'selected' : '' ?>>No</option>
                                        <option value="Yes" <?php echo ($fixture["quantity"] == "Yes") ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                <?php } ?>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <tr>
                    <td>
                        <label>Future Bath - Bonus Room</label>
                    </td>
                    <td>
                        <select class="form-control" id="future_bath_bonus">
                            <option value="No" <?php echo ($info["future_bath_bonus"] == "No") ? 'selected' : '' ?>>No</option>
                            <option value="Yes" <?php echo ($info["future_bath_bonus"] == "Yes") ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </td>
                </tr>
                <?php
                foreach ($fixtureFutures as $fixtureFuture) {
                    $items_json = ($fixtureFuture['json'] != "") ? json_decode($fixtureFuture['json']) : [];
                    $id_fixture = $fixtureFuture['id_fixture'];
                    $quantity = ($info["future_bath_bonus"] == "Yes") ? $fixtureFuture['quantity'] : '';
                    foreach ($items_json as $key => $item_json) {
                        if ($info["future_bath_bonus"] == "No") {
                            $item_json->sub_pregunta_1 = "";
                            $item_json->sub_pregunta_2 = "";
                        }
                ?>
                        <tr <?php echo ($key == 0) ? 'id="ftr_' . $id_fixture . '"' : '' ?> class="<?php echo ($key > 0) ? 'esclon2' : 'next_future_bath' ?> <?php echo ($info["future_bath_bonus"] == "No") ? 'hidden' : '' ?> " data-id="<?php echo $id_fixture ?>" data-dbid="<?php echo $fixtureFuture['id'] ?>">
                            <td>
                                <label>
                                    <?php
                                    if ($key == 0) {
                                        echo $fixtureFuture["info_fixture"]['nombre'];
                                    }
                                    ?>
                                </label>
                            </td>
                            <td>
                                <?php if ($key == 0) { ?>
                                    <?php //generación de los fixtureFutures
                                    if ($fixtureFuture["info_fixture"]['tipo'] == "0") { ?>
                                        <!-- <?php if ($fixtureFuture["info_fixture"]["multiple"] == 1) { ?>
                                            <i class="fa <?php echo (count($items_json) > 1) ? 'fa-arrow-up' : 'fa-arrow-right' ?>  fDesplegar" id="fDesplegar_<?php echo $id_fixture ?>" data-field="<?php echo $id_fixture ?>" <?php echo ($fixtureFuture["quantity"] != "No" && $fixtureFuture["quantity"] != '') ? 'style="visibility: visible;"' : '' ?> <?php echo (count($items_json) > 1) ? 'data-dis="1"' : '' ?>></i>
                                        <?php } ?> -->
                                        <input type="text" value="<?php echo $quantity ?>" style="width: auto; display: inline;" class="form-control fTrigger_show mains" data-field="fixtureFuture_hidden__<?php echo $id_fixture ?>" id="fQuantity_<?php echo $id_fixture ?>" data-id="<?php echo $id_fixture ?>">
                                    <?php }  ?>
                                <?php }  ?>
                            </td>
                            <td>
                                <?php if ($fixtureFuture["info_fixture"]["items"] != "") {
                                ?>
                                    <?php $items = explode(',', $fixtureFuture["info_fixture"]["items"]); ?>
                                    <!-- <select class="form-control sub_pregunta_1 fixtureFuture_hidden__<?php echo $id_fixture ?> <?php echo ($fixtureFuture["quantity"] == "No" || $fixtureFuture["quantity"] == '') ? 'hidden' : '' ?> ">
                                    <option value="">Select</option>
                                    <?php foreach ($items as $item) { ?>
                                    <option <?php echo ($item_json->sub_pregunta_1 == $item) ? 'selected' : '' ?>><?php echo $item; ?></option>
                                    <?php } ?>
                                </select> -->
                            <td>
                                <select class="form-control sub_pregunta_2 fixtureFuture_hidden__<?php echo $id_fixture ?> <?php echo ($fixtureFuture["quantity"] == "No" || $fixtureFuture["quantity"] == '') ? 'hidden' : '' ?>">
                                    <option <?php echo ($item_json->sub_pregunta_2 == "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option <?php echo ($item_json->sub_pregunta_2 == "No") ? 'selected' : '' ?>>No</option>
                                </select>
                            </td>
                            <td>
                                <select class="form-control sub_pregunta_3 fixtureFuture_hidden__<?php echo $id_fixture ?> <?php echo ($fixtureFuture["quantity"] == "No" || $fixtureFuture["quantity"] == '') ? 'hidden' : '' ?>">
                                    <option <?php echo ($item_json->sub_pregunta_3 == "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option <?php echo ($item_json->sub_pregunta_3 == "No") ? 'selected' : '' ?>>No</option>
                                </select>
                            </td>
                        <?php } ?>
                        </td>
                        </tr>
                    <?php } ?>
                    <?php if (count($items_json) == 0) { ?>
                        <tr id="ftr_<?php echo $id_fixture ?>" class="next_future_bath <?php echo ($info["future_bath_bonus"] == "No") ? 'hidden' : '' ?> " data-id="<?php echo $id_fixture ?>" data-dbid="<?php echo $fixtureFuture['id'] ?>">
                            <td>
                                <label>
                                    <?php echo $fixtureFuture["info_fixture"]['nombre'] ?>
                                </label>
                            </td>
                            <td>
                                <?php if ($fixtureFuture["info_fixture"]['tipo'] == "0") { ?>
                                    <input type="text" value="<?php echo $quantity ?>" style="width: auto; display: inline;" class="form-control fTrigger_show mains" data-field="fixtureFuture_hidden__<?php echo $id_fixture ?>" id="fQuantity_<?php echo $id_fixture ?>" data-id="<?php echo $id_fixture ?>">
                                <?php }  ?>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
                <tr>
                    <td>Extra Valves</td>
                    <td>
                        <input type="text" class="valves form-control" id="extra_valves" value="<?php echo $info["extra_valves"] ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <label>Total </label>
                    </td>
                    <td class="text-center">
                        <span class="total_mains"></span>
                    </td>
                    <td>
                        <label>Total Valves:</label>
                    </td>
                    <td class="text-center">
                        <label class="total_valves"></label>
                    </td>
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
                    <th>Fixture</th>
                    <th>Quantity</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($extraFixtures as $fixture) {
                    $items_json = ($fixture['json'] != "") ? json_decode($fixture['json']) : [];
                    $hidden = ($fixture['quantity'] == '' || $fixture['quantity'] == 'No') ? 'hidden' : '';
                ?>
                    <tr class="extra_fixture" data-id="<?php echo $fixture['id_fixture'] ?>" data-dbid="<?php echo $fixture['id'] ?>">
                        <td><?php echo $fixture["info_fixture"]['nombre'] ?></td>
                        <td>
                            <?php if ($fixture["info_fixture"]['tipo'] == 0) { ?>
                                <input type="text" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id_fixture'] ?>" id="quantity_<?php echo $fixture['id_fixture'] ?>" value="<?php echo $fixture['quantity'] ?>">
                            <?php } else if ($fixture["info_fixture"]['tipo'] == 1) { ?>
                                <select id="quantity_<?php echo $fixture['id_fixture'] ?>" class="form-control trigger_show" data-field="fixture_hidden__<?php echo $fixture['id_fixture'] ?>">
                                    <option value="No" <?php echo ($fixture['quantity'] == "No") ? "selected" : ""  ?>>No</option>
                                    <option value="Yes" <?php echo ($fixture['quantity'] == "Yes") ? "selected" : ""  ?>>Yes</option>
                                </select>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($fixture["info_fixture"]["pan_title"] == 1) { ?>
                                <select class="form-control sub_pregunta_1 fixture_hidden__<?php echo $fixture['id_fixture'] ?> <?php echo $hidden ?>">
                                    <option value="">Select</option>
                                    <option <?php echo (isset($items_json[0]) && $items_json[0]->sub_pregunta_1 == "Pan") ? "selected" : ""  ?>>Pan</option>
                                    <option <?php echo (isset($items_json[0]) && $items_json[0]->sub_pregunta_1 == "Tile") ? "selected" : ""  ?>>Tile</option>
                                </select>
                            <?php } ?>
                            <!-- <?php if ($fixture["info_fixture"]["text_extra"] == 1) { ?>
                                <textarea cols="30" rows="3" class="form-control sub_pregunta_1 fixture_hidden__<?php echo $fixture['id_fixture'] ?> <?php echo $hidden ?>" placeholder="What is extra" style="width: 400px;"><?php echo (isset($items_json[0])) ? $items_json[0]->sub_pregunta_1 : ""  ?></textarea>
                            <?php } ?> -->
                        </td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>Others</td>
                    <td>
                        <select class="form-control" id="othersInfo">
                            <option <?php echo (isset($otro['quantity']) && $otro['quantity'] == 'No') ? 'selected' : '' ?>>No</option>
                            <option <?php echo (isset($otro['quantity']) && $otro['quantity'] == 'Yes') ? 'selected' : '' ?>>Yes</option>
                        </select>
                    </td>
                    <td>
                        <textarea id="textAreaOther" cols="30" rows="3" class="form-control hidden " placeholder="What is extra" style="width: 400px;"><?php echo $otro['info_extra']  ?></textarea>
                    </td>
                </tr>

            </tbody>
        </table>

    </div>
</div>