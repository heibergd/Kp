var $check1 = $("[d-check=true]").bootstrapSwitch();
var reload_w = true;

$(document).ready(function () {
    //setear chafin en 2 kitchen sink
    if ($("#builders").val() == 1) {
        $("#quantity_9").val(2);
    }
    $("#builders").change(function () {
        if ($("#builders").val() == 1) {
            $("#quantity_9").val(2);
        } else {
            $("#quantity_9").val("");
        }
    });
});

//mostrar modal
$("#addRoughForm").on("click", function () {
    $("#new_fixture").modal("show");
});
/* ======================================================
FUNCION PARA CREAR EL ROUGH FORM
====================================================== */
$("#createRoughForm").on("click", function (e) {
    e.preventDefault();
    let data = {
        plantilla: $("#plantillas").val(),
        neighborhood_name: $("#subdivision").val(),
        neighborhood: $("#neighborhood").val(),
        lot: $("#lote").val(),
        well: $("#well").val(),
        builder: $("#builders").val(),
        sewer_system: $("#sewer_system").val(),
        forsyth_county: $("#forsyth_county").val(),
        payroll: $("#payroll").val(),
        basement_or_slab: $("#basement_or_slab").val(),
        future_bath_basement: $("#future_bath_basement").val(),
        future_bath_bonus: $("#future_bath_bonus").val(),
        extra_valves: $("#extra_valves").val(),
        ejector_tank: $("#ejector_tank").val(),
        brand_of_fixtures: $("#brand_of_fixtures").val(),
        supervisor: $("#supervisor").val(),
        rough_type: $("#rough_type").val(),
        guardar_plantilla: $("#guardar_plantilla").is(":checked") ? 1 : 0,
        nombre_plantilla: $("#nombre_plantilla").val(),
        fixtures: [],
        crear: "true",
        others: [],
    };
    let fixtures = getFixtures();
    // Validacion de laundry pan drain en Extra Fixtures
    // Seccion para la validacion de todos los items que necesitan un select activado
    // Variables para validar

    let masterShower = "",
        masterTub = "",
        tubShowers = "",
        guestShower = "",
        waterHeater = "",
        dogShower = "",
        laundry = "";
    fixtures.forEach(function (element) {
        if (element.id_fixture == 5) {
            masterShower = element;
        }
        if (element.id_fixture == 6) {
            masterTub = element;
        }
        if (element.id_fixture == 7 && element.future_bath == 0) {
            tubShowers = element;
        }
        if (element.id_fixture == 8 && element.future_bath == 0) {
            guestShower = element;
        }
        if (element.id_fixture == 11) {
            waterHeater = element;
        }
        if (element.id_fixture == 31) {
            dogShower = element;
        }
        if (element.id_fixture == 17) {
            laundry = element;
        }
    });
    // Validacion del Master Shower
    if (masterShower.quantity != "") {
        const contenido = masterShower.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Master Shower!");
            return false;
        }
    }
    // Validacion del Master Tub
    if (masterTub.quantity != "") {
        const contenido = masterTub.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Master Tub!");
            return false;
        }
    }
    // Validacion del Tub Showers
    if (tubShowers.quantity != "") {
        const contenido = tubShowers.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Tub Showers!");
            return false;
        }
    }
    // Validacion del Guest Shower
    if (guestShower.quantity != "") {
        const contenido = guestShower.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Guest Shower!");
            return false;
        }
    }
    // Validacion del Water Heater
    if (waterHeater.quantity != "") {
        const contenido = waterHeater.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Water Heater!");
            return false;
        }
    }
    // Validacion de Dog Shower
    if (dogShower.quantity != "") {
        const contenido = dogShower.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "Select" ||
            validacion.sub_pregunta_1 == ""
        ) {
            util.alertError("Error", "Please select Dog Shower!");
            return false;
        }
    }
    // Validacion de laundry pan drain en Extra Fixtures
    if (laundry.quantity != "") {
        const contenido = laundry.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "Select" ||
            validacion.sub_pregunta_1 == ""
        ) {
            util.alertError("Error", "Please select laundry pan drain!");
            return false;
        }
    }
    // Validacion para que el campo de others no se envie vacio
    let informacion = $("#othersInfo").val()
    let contenido = $("#textAreaOther").val();
    if (informacion == 'Yes') {
        if (contenido == '') {
            util.alertError(
                "Error",
                "You must complete the description in others"
            );
            return false;
        }
        data.others = {
            id_fixture: 18,
            quantity: informacion,
            future_bath: 0,
            extra: 2,
            items: contenido
        };
    } else {
        data.others = data.others = {
            id_fixture: 18,
            quantity: informacion,
            future_bath: 0,
            extra: 2,
            items: "",
        };
    }
    data.fixtures = fixtures;
    $(this).html("Loading...");
    $.ajax({
        url: "views/ajax/gestorRoughForm.php",
        type: "POST",
        dataType: "JSON",
        data: data,
    })
        .done((res) => {
            //console.log(res);
            if (res.status == "error") {
                util.alertError("Error", res.mensaje);
            } else {
                let url = window.location.href;
                util.alertSuccess("Success", "Created!", false, false);
                let partes = url.split("_");
                let new_url = `${partes[0]}_${partes[1]}`;
                setTimeout(() => {
                    window.location.href = new_url;
                }, 300);
            }
            $(this).html("CREATE");
        })
        .fail((error) => {
            $(this).html("CREATE");
            console.log(error);
        });
});
/* ======================================================
FUNCION PARA EL UPDATE ROUGH FORM
====================================================== */
$("#updateRoughForm").on("click", function (e) {
    e.preventDefault();
    let data = {
        id: $("#idForm").val(),
        neighborhood_name: $("#subdivision").val(),
        neighborhood: $("#neighborhood").val(),
        lot: $("#lote").val(),
        well: $("#well").val(),
        builder: $("#builders").val(),
        sewer_system: $("#sewer_system").val(),
        forsyth_county: $("#forsyth_county").val(),
        payroll: $("#payroll").val(),
        basement_or_slab: $("#basement_or_slab").val(),
        future_bath_basement: $("#future_bath_basement").val(),
        future_bath_bonus: $("#future_bath_bonus").val(),
        extra_valves: $("#extra_valves").val(),
        ejector_tank: $("#ejector_tank").val(),
        brand_of_fixtures: $("#brand_of_fixtures").val(),
        supervisor: $("#supervisor").val(),
        rough_type: $("#rough_type").val(),
        fixtures: [],
        update: "true",
        others: [],
    };
    let fixtures = getFixtures(true);
    // Validacion de laundry pan drain en Extra Fixtures
    // Seccion para la validacion de todos los items que necesitan un select activado
    // Variables para validar
    let masterShower = "",
        masterTub = "",
        tubShowers = "",
        guestShower = "",
        waterHeater = "",
        dogShower = "",
        laundry = "";
    fixtures.forEach(function (element) {
        if (element.id_fixture == 5) {
            masterShower = element;
        }
        if (element.id_fixture == 6) {
            masterTub = element;
        }
        if (element.id_fixture == 7 && element.future_bath == 0) {
            tubShowers = element;
        }
        if (element.id_fixture == 8 && element.future_bath == 0) {
            guestShower = element;
        }
        if (element.id_fixture == 11) {
            waterHeater = element;
        }
        if (element.id_fixture == 31) {
            dogShower = element;
        }
        if (element.id_fixture == 17) {
            laundry = element;
        }
    });
    // Validacion del Master Shower
    if (masterShower.quantity != "") {
        const contenido = masterShower.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Master Shower!");
            return false;
        }
    }
    // Validacion del Master Tub
    if (masterTub.quantity != "") {
        const contenido = masterTub.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Master Tub!");
            return false;
        }
    }
    // Validacion del Tub Showers
    if (tubShowers.quantity != "") {
        const contenido = tubShowers.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Tub Showers!");
            return false;
        }
    }
    // Validacion del Guest Shower
    if (guestShower.quantity != "") {
        const contenido = guestShower.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Guest Shower!");
            return false;
        }
    }
    // Validacion del Water Heater
    if (waterHeater.quantity != "") {
        const contenido = waterHeater.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "" ||
            validacion.sub_pregunta_1 == "Select"
        ) {
            util.alertError("Error", "Please select Water Heater!");
            return false;
        }
    }
    // Validacion de Dog Shower
    if (dogShower.quantity != "") {
        const contenido = dogShower.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "Select" ||
            validacion.sub_pregunta_1 == ""
        ) {
            util.alertError("Error", "Please select Dog Shower!");
            return false;
        }
    }
    // Validacion de laundry pan drain en Extra Fixtures
    if (laundry.quantity != "") {
        const contenido = laundry.items;
        const [validacion] = contenido;
        if (
            validacion.sub_pregunta_1 == "Select" ||
            validacion.sub_pregunta_1 == ""
        ) {
            util.alertError("Error", "Please select laundry pan drain!");
            return false;
        }
    }
    // Validacion para que el campo de others no se envie vacio
    let informacion = $("#othersInfo").val();
    let contenido = $("#textAreaOther").val();
    if (informacion == "Yes") {
        if (contenido == "") {
            util.alertError("Error", "You must complete the description in others");
            return false;
        }
        data.others = {
            id_fixture: 18,
            quantity: informacion,
            future_bath: 0,
            extra: 2,
            items: contenido,
        };
    } else {
        data.others = {
            id_fixture: 18,
            quantity: informacion,
            future_bath: 0,
            extra: 2,
            items: "",
        };
    }
    data.fixtures = fixtures;
    console.log(data);
    $(this).html("Loading...");
    $.ajax({
        url: "views/ajax/gestorRoughForm.php",
        type: "POST",
        dataType: "JSON",
        data: data,
    })
        .done((res) => {
            console.log(res);
            if (res.status == "error") {
                util.alertError("Error", res.mensaje);
            } else {
                util.alertSuccess("Success", "Updated!", false, true);
            }
            $(this).html("UPDATE");
        })
        .fail((error) => {
            console.log(error);
            $(this).html("UPDATE");
        });
});

$("#filtrarRoughForms").on("click", function () {
    filter_rough($("#generalSearch").val());
});

$(".removeFilterRoughForm").on("click", function () {
    filter_rough("");
});

$("#guardar_plantilla").on("change", function () {
    if ($(this).is(":checked")) {
        $(`#content_nombre_plantilla`).removeClass("hidden");
    } else {
        $(`#content_nombre_plantilla`).addClass("hidden");
    }
});

$("#plantillas").on("change", function () {
    let val = $(this).val();
    let url = window.location.href;
    debugger;
    if (val != "") {
        let partes = url.split("_");
        if (partes.length > 2) {
            let new_url = `${partes[0]}_${partes[1]}_${val}`;
            window.location.href = new_url;
        } else {
            window.location.href = url + "_" + val;
        }
    } else {
        let partes = url.split("_");
        let new_url = `${partes[0]}_${partes[1]}`;
        window.location.href = new_url;
    }
});

$(".desplegar").click(function () {
    var tractual = $(this).attr("data-field");
    var abierto = $(this).data("dis");
    if (abierto == 1) {
        $(`.esclon[data-id="${tractual}"]`).remove();
        $(this).data("dis", 0);
        $(this).removeClass("fa-arrow-up");
        $(this).addClass("fa-arrow-right");
    } else {
        $(this).data("dis", 1);
        $(this).removeClass("fa-arrow-right");
        $(this).addClass("fa-arrow-up");
        var cantidad = parseInt($("#quantity_" + tractual).val());
        for (let index = 0; index < cantidad - 1; index++) {
            $trNew = $("#tr_" + tractual)
                .clone()
                .attr("class", "esclon")
                .removeAttr("id");
            $trNew.find("td:nth-child(1)").html("");
            $trNew.find("td:nth-child(2)").html("");
            $("#tr_" + tractual).after($trNew);
        }
    }
});

$(".fDesplegar").click(function () {
    var tractual = $(this).attr("data-field");
    var abierto = $(this).data("dis");
    if (abierto == 1) {
        $(`.esclon2[data-id="${tractual}"]`).remove();
        $(this).data("dis", 0);
        $(this).removeClass("fa-arrow-up");
        $(this).addClass("fa-arrow-right");
    } else {
        $(this).data("dis", 1);
        $(this).removeClass("fa-arrow-right");
        $(this).addClass("fa-arrow-up");
        var cantidad = parseInt($("#fQuantity_" + tractual).val());
        for (let index = 0; index < cantidad - 1; index++) {
            $trNew = $("#ftr_" + tractual)
                .clone()
                .attr("class", "esclon2")
                .removeAttr("id");
            $trNew.find("td:nth-child(1)").html("");
            $trNew.find("td:nth-child(2)").html("");
            $("#ftr_" + tractual).after($trNew);
        }
    }
});
/* ==================================================== 
FUNCION PARA MOSTRAR LA OPCION DE OTHERS
==================================================== */
let valor = $("#othersInfo").val();
if (valor == "No") {
    $("#textAreaOther").addClass("hidden");
} else {
    $("#textAreaOther").removeClass("hidden");
}
$("#othersInfo").on("change", function () {
    let info = $(this).val();
    if (info == 'No') {
        $("#textAreaOther").addClass("hidden");
    } else {
        $("#textAreaOther").removeClass("hidden");
    }
});

$(".trigger_show").on("change", function () {
    var field = $(this).attr("data-field");
    var val = $(this).val();
    let id_fixture = $(this).attr("data-id");
    if (!isNaN(val) && id_fixture != "") {
        let numero = Number(val);
        if (numero > 1) {
            if ($("#desplegar_" + id_fixture).length) {
                $("#desplegar_" + id_fixture).css("visibility", "visible");
            }
        } else {
            if ($("#desplegar_" + id_fixture).length) {
                $("#desplegar_" + id_fixture).css("visibility", "hidden");
            }
        }
    }
    if (val == "" || val == "No") {
        $(`.${field}`).addClass("hidden");
    } else {
        $(`.${field}`).removeClass("hidden");
    }
    providers_text();
});
$(".fTrigger_show").on("change", function () {
    var field = $(this).attr("data-field");
    var val = $(this).val();
    let id_fixture = $(this).attr("data-id");
    if (!isNaN(val) && id_fixture != "") {
        let numero = Number(val);
        if (numero > 1) {
            if ($("#fDesplegar_" + id_fixture).length) {
                $("#fDesplegar_" + id_fixture).css("visibility", "visible");
            }
        } else {
            if ($("#fDesplegar_" + id_fixture).length) {
                $("#fDesplegar_" + id_fixture).css("visibility", "hidden");
            }
        }
    }
    if (val == "" || val == "No") {
        $(`.${field}`).addClass("hidden");
    } else {
        $(`.${field}`).removeClass("hidden");
    }
    providers_text();
});

$(".mains").on("change", function () {
    calcular_total();
    calcular_valves();
    let id_fixture = $(this).attr("data-id");
    let cantidad = $(this).val();
    $("#valves_" + id_fixture).val(cantidad);
});

$("#future_bath_bonus").on("change", function () {
    var val = $(this).val();
    if (val == "Yes") {
        $(".next_future_bath").removeClass("hidden");
    } else {
        $(".next_future_bath input").val("");
        $(".next_future_bath").addClass("hidden");
    }
    calcular_valves();
    calcular_total();
});

$("#builders").on("change", function () {
    var val = $("#builders option:selected").text();
    //console.log(val);
    if (val == "Chafin") {
        $('input[name="quantity_9"]').val(2);
    } else {
        $('input[name="quantity_9"]').val("");
    }
});

$("#subdivision").on("change", function () {
    buscar_lote();
});

$("#lote").on("change", function () {
    buscar_lote();
});

function providers_text() {
    var ids = [5, 6, 7, 8];
    var show_text = false;
    $.each(ids, (i, item) => {
        var $input = $(`input[name="quantity_${item}"]`);
        if ($input.val() != "") {
            show_text = true;
        }
    });
    if (show_text) {
        $(`.others`).removeClass("hidden");
    } else {
        $(`.others`).addClass("hidden");
    }
}

$(".mains").keyup(function () {
    calcular_valves();
    calcular_total();
});

$(".valves").change(function () {
    calcular_valves();
    calcular_total();
});

$(".valvulaselect").change(function () {
    calcular_valves();
    calcular_total();
});

function getFixtures(withDbId = false) {
    let fixtures = [];
    let $trs = $(".tr_fixture");
    let $future_trs = $(".next_future_bath");
    let $extra_fixture_trs = $(".extra_fixture");

    $.each($trs, (i, item) => {
        let id_fixture = $(item).attr("data-id");
        let fixture = {
            id_fixture: id_fixture,
            quantity: $(`#quantity_${id_fixture}`).val(),
            future_bath: 0,
            extra: 0,
            items: [],
        };

        if (withDbId) {
            fixture.id = $(`#tr_${id_fixture}`).attr("data-dbid");
        }

        let first_item = {};
        let subpreguntas = false;
        if ($(item).find(".sub_pregunta_1").length) {
            first_item.sub_pregunta_1 = $(item).find(".sub_pregunta_1").val();
            subpreguntas = true;
        }

        if ($(item).find(".sub_pregunta_2").length) {
            first_item.sub_pregunta_2 = $(item).find(".sub_pregunta_2").val();
            subpreguntas = true;
        }

        if ($(item).find(".sub_pregunta_3").length) {
            first_item.sub_pregunta_3 = $(item).find(".sub_pregunta_3").val();
            subpreguntas = true;
        }

        if (subpreguntas) {
            fixture.items.push(first_item);
        }

        let clones = $(`.esclon[data-id="${id_fixture}"]`);

        $.each(clones, (j, clone) => {
            let item_clone = {};
            if ($(clone).find(".sub_pregunta_1").length) {
                item_clone.sub_pregunta_1 = $(clone).find(".sub_pregunta_1").val();
            }

            if ($(clone).find(".sub_pregunta_2").length) {
                item_clone.sub_pregunta_2 = $(clone).find(".sub_pregunta_2").val();
            }

            if ($(clone).find(".sub_pregunta_3").length) {
                item_clone.sub_pregunta_3 = $(clone).find(".sub_pregunta_3").val();
            }
            fixture.items.push(item_clone);
        });

        fixtures.push(fixture);
    });

    $.each($future_trs, (i, item) => {
        let id_fixture = $(item).attr("data-id");
        let fixture = {
            id_fixture: id_fixture,
            quantity: $(`#fQuantity_${id_fixture}`).val(),
            future_bath: 1,
            extra: 0,
            items: [],
        };

        if (withDbId) {
            fixture.id = $(`#ftr_${id_fixture}`).attr("data-dbid");
        }

        let first_item = {};
        let subpreguntas = false;
        if ($(item).find(".sub_pregunta_1").length) {
            first_item.sub_pregunta_1 = $(item).find(".sub_pregunta_1").val();
            subpreguntas = true;
        }

        if ($(item).find(".sub_pregunta_2").length) {
            first_item.sub_pregunta_2 = $(item).find(".sub_pregunta_2").val();
            subpreguntas = true;
        }

        if ($(item).find(".sub_pregunta_3").length) {
            first_item.sub_pregunta_3 = $(item).find(".sub_pregunta_3").val();
            subpreguntas = true;
        }

        if (subpreguntas) {
            fixture.items.push(first_item);
        }

        let clones = $(`.esclon2[data-id="${id_fixture}"]`);

        $.each(clones, (j, clone) => {
            let item_clone = {};
            if ($(clone).find(".sub_pregunta_1").length) {
                item_clone.sub_pregunta_1 = $(clone).find(".sub_pregunta_1").val();
            }

            if ($(clone).find(".sub_pregunta_2").length) {
                item_clone.sub_pregunta_2 = $(clone).find(".sub_pregunta_2").val();
            }

            if ($(clone).find(".sub_pregunta_3").length) {
                item_clone.sub_pregunta_3 = $(clone).find(".sub_pregunta_3").val();
            }
            fixture.items.push(item_clone);
        });

        fixtures.push(fixture);
    });

    $.each($extra_fixture_trs, (i, item) => {
        let id_fixture = $(item).attr("data-id");
        let fixture = {
            id_fixture: id_fixture,
            quantity: $(`#quantity_${id_fixture}`).val(),
            future_bath: 0,
            extra: 1,
            items: [],
        };

        if (withDbId) {
            fixture.id = $(item).attr("data-dbid");
        }

        let first_item = {};
        let subpreguntas = false;
        if ($(item).find(".sub_pregunta_1").length) {
            first_item.sub_pregunta_1 = $(item).find(".sub_pregunta_1").val();
            subpreguntas = true;
        }

        if ($(item).find(".sub_pregunta_2").length) {
            first_item.sub_pregunta_2 = $(item).find(".sub_pregunta_2").val();
            subpreguntas = true;
        }

        if ($(item).find(".sub_pregunta_3").length) {
            first_item.sub_pregunta_3 = $(item).find(".sub_pregunta_3").val();
            subpreguntas = true;
        }

        if (subpreguntas) {
            fixture.items.push(first_item);
        }

        fixtures.push(fixture);
    });

    return fixtures;
}

function calcular_valves() {
    var $inputs = $(".count_valve");
    var total = 0;

    $.each($inputs, (i, item) => {
        if (!isNaN($(item).val())) {
            let por_dos = $(`.valvulaselect[data-id="${$(item).attr("data-id")}"]`);
            let n = Number($(item).val());
            if (
                por_dos.length &&
                por_dos.val() == "Yes" &&
                !$(item).hasClass("fTrigger_show")
            ) {
                n = n * 2;
            }

            total += n;
        }
    });

    let extra = Number($("#extra_valves").val());
    // console.log(extra)
    total += extra;
    // console.log(total)

    $(".total_valves").html(total);
}

function calcular_total() {
    var $inputs = $(".mains");
    var total = 0;

    $.each($inputs, (i, item) => {
        if (!isNaN($(item).val())) {
            total += Number($(item).val());
        }
    });

    let extra = Number($("#extra_valves").val());
    total += extra;

    $(".total_mains").html(total);
}

function filter_rough(val) {
    var data = {
        filtrar: true,
        search: val,
    };

    ajax.peticion("normal", data, "views/ajax/gestorRoughForm.php").then(
        (res) => {
            console.log(res);
            window.location.reload();
        },
        (error) => {
            console.log(error);
        }
    );
}

function buscar_lote() {
    var subdivision = $("#subdivision").val();
    var lote = $("#lote").val();

    if (subdivision != "" && lote != "") {
        $.ajax({
            url: "views/ajax/gestorRoughForm.php",
            type: "POST",
            dataType: "JSON",
            data: {
                buscar_lote: true,
                subdivision: subdivision,
                lote: lote,
            },
        })
            .done((data) => {
                // console.log(data)
                if (data.info) {
                    $("#address").val(data.info.address);
                }
            })
            .fail((fail) => {
                console.log(fail);
            });
    }
}

calcular_total();
calcular_valves();
providers_text();
