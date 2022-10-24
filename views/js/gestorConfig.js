
$(".addType").on("click",function(e){
    e.preventDefault()

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: {access: true}
    })
    .done((res)=>{
        if (res.newType != undefined) {
            $("#m_new_type").modal("show")
        }else{
            util.alertError("Error", "You don't have access to this action")
        }
    })
    .fail((error)=>{
        console.log(error);
    })
    
})

$(".editType").on("click",function(e){

    var id = $(this).attr("data-id")


    ajax.peticion("normal", {type: true, id: id}, "views/ajax/gestorConfig.php")
        .then((res)=>{
            console.log(res);
            if (res.access.editType == undefined) {
                util.alertError("Error", "You don't have access to this action")

                return false
            }

            $("#check_ordered_edit").removeAttr("checked")
            $("#check_marked_edit").removeAttr("checked")
            $("#check_attach_edit").removeAttr("checked")
            $("#check_billiable_edit").removeAttr("checked")
            $("#check_archive_edit").removeAttr("checked")
            $("#check_warning_edit").removeAttr("checked")
            $("#default_billiable_edit").removeAttr("checked")

            document.getElementById("defaul_color_edit").jscolor.fromString(res.settings.defaul_color_)
            document.getElementById("color_ordered_edit").jscolor.fromString(res.settings.color_ordered)
            document.getElementById("color_marked_edit").jscolor.fromString(res.settings.color_marked)
            $("#name_type_edit").val(res.nombre)
            $("#id_type_edit").val(res.id)

            if(res.settings.ordered){
                $("#check_ordered_edit").attr("checked", "true")
            }
            if(res.settings.marked){
                $("#check_marked_edit").attr("checked", "true")
            }
            if(res.settings.attachment){
                $("#check_attach_edit").attr("checked", "true")
            }
            if(res.settings.billiable){
                $("#check_billiable_edit").attr("checked", "true")
            }
            if(res.settings.default_billiable){
                $("#default_billiable_edit").attr("checked", "true")
            }
            if(res.settings.archive){
                $("#check_archive_edit").attr("checked", "true")
            }
            if(res.settings.warning){
                $("#check_warning_edit").attr("checked", "true")
            }
            
            console.log(res.settings.textos);
            $('#textos_edit_type option').remove().trigger('change');
            
            if (res.settings.textos != undefined) {
                for(var i = 0; i < res.settings.textos.length; i++){
                    var newOption = new Option(res.settings.textos[i], res.settings.textos[i], true, true);
                    $('#textos_edit_type').append(newOption)
                }
                
                $('#textos_edit_type').trigger('change');
            }
                        
            $("#m_edit_type").modal("show")
        }, (fail)=>{
            console.log(fail);
        })

})

$("#seveType").on("click",function(e){
    e.preventDefault()

    var data = new FormData(document.getElementById("form_edit_type"))

    data.append("updateType", "true")

    $(this).html("Loading...")
    ajax.peticion("FormData", data, "views/ajax/gestorConfig.php")
        .then((res)=>{
            console.log(res);
            if (res.status == false) {
                util.alertError("Error", res.message)
            }else{
                util.alertSuccess("Success", "Updated!", false, true)
            }
            $(this).html("UPDATE")
        },(fail)=>{
            console.log(fail);
        })
})

$(".deleteType").on("click",function(e){
    e.preventDefault()

    var id = $(this).attr("data-id")

    util.alertConfirm("Do you want to delete this type?")
        .then((s)=>{
            $(this).html("Loading...")
            ajax.peticion("normal", {deleteType: true, id: id},  "views/ajax/gestorConfig.php")
                .then((res)=>{
                    console.log(res);
                    if (res.status == false) {
                        $(this).html("Delete")
                        util.alertError("Error", res.message)
                    }else{
                        util.alertSuccess("Success", "Deleted!", false, true)
                    }
                },(fail)=>{
                    console.log(fail);
                })

        }, (f) => console.log("no"))

})

$("#createType").on("click",function(e){
    e.preventDefault()


    var data = new FormData(document.getElementById("form_type"))

    data.append("newType", "true")

    $(this).html("Loading...")
    ajax.peticion("FormData", data, "views/ajax/gestorConfig.php")
        .then((res)=>{
            console.log(res);
            if (res.status == false) {
                util.alertError("Error", res.message)
            }else{
                util.alertSuccess("Success", "Added!", false, true)
            }
            $(this).html("CREATE")
        },(fail)=>{
            console.log(fail);
        })
})

$(".addMail").on("click",function(e){
    e.preventDefault()

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: {access: true}
    })
    .done((res)=>{
        if (res.newType != undefined) {
            $("#modalDailyMail").modal("show")
        }else{
            util.alertError("Error", "You don't have access to this action")
        }
    })
    .fail((error)=>{
        console.log(error);
    })
    
})

$("#seveMail").on("click",function(e){
    e.preventDefault()

    var data = {
        setMails: true,
        mails: $("#DailyMailsText").val()
    }

    $(this).html("Loading...")
    ajax.peticion("normal", data, "views/ajax/gestorConfig.php")
        .then((res)=>{
            console.log(res);
            if (res.status == false) {
                util.alertError("Error", res.message)
            }else{
                util.alertSuccess("Success", "Saved!", false, true)
            }
            $(this).html("SAVE")
        },(fail)=>{
            console.log(fail);
        })
})

$(".editMail").on("click",function(e){
    e.preventDefault()

    var id = $(this).attr("data-id")

    util.alertConfirm("Do you want to delete this Email?")
        .then((s)=>{
            $(this).html("Loading...")
            ajax.peticion("normal", {deleteMail: true, id: id},  "views/ajax/gestorConfig.php")
                .then((res)=>{
                    console.log(res);
                    if (res.status == false) {
                        $(this).html("Delete")
                        util.alertError("Error", res.message)
                    }else{
                        util.alertSuccess("Success", "Deleted!", false, true)
                    }
                },(fail)=>{
                    console.log(fail);
                })

        }, (f) => console.log("no"))

})


$("#type_new_activity").on("change",function(){

    var id = $(this).val()

    var data = {
        type: true,
        id: id
    }

    $("#ordened_new")[0].checked = false
    $("#marked_new")[0].checked = false    
    $("#billiable_new")[0].checked = true
    $("#attachment_new").val("").next(".custom-file-label").html("")
    

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: data
    })
    .done((data)=>{
        // console.log(data);
        var settings = data.settings

        if (settings == undefined) {
            $(".content_ordened_new").addClass("hidden")
            $(".content_marked_new").addClass("hidden")
            $(".attachment_new").addClass("hidden")
            return false
        }

        if (settings.ordered) {
            $(".content_ordened_new").removeClass("hidden")
        }else{
            $(".content_ordened_new").addClass("hidden")
        }
        if (settings.marked) {
            $(".content_marked_new").removeClass("hidden")
        }else{
            $(".content_marked_new").addClass("hidden")
        }
        if (settings.billiable) {
            $(".content_billiable_new").removeClass("hidden")
            //si es tipo others copiar las dirección del # lote
            if ($("#sub_new_act option:selected" ).val() == 7) {
                $("#address").val($("#lote_new_act").val());   
            }
        }else{
            $(".content_billiable_new").addClass("hidden")
            // $("#billiable_new")[0].checked = false
        }

        if (settings.default_billiable) {
            // console.log("default");
            // console.log(settings.default_billiable); 
            $("#billiable_new")[0].checked = true
        }else{
            // console.log("default 2" );
            // console.log(settings.default_billiable);
            $("#billiable_new")[0].checked = false
        }

        if (settings.archive) {
            $(".content_archive_new").removeClass("hidden")
        }else{
            $(".content_archive_new").addClass("hidden")
        }

        if (settings.attachment) {
            $(".attachment_new").removeClass("hidden")
        }else{
            $(".attachment_new").addClass("hidden")
        }

        // Warranty o Service
        if (data.id == 15 || data.id == 16) {
            $(".address_new").removeClass("hidden")
        }else{
            $(".address_new").addClass("hidden")
        }

        // if(data.nombre == "@Special"){
        //     $("#formNewActivity select[name='worker']").attr("disabled", "true")
        //     $("#formNewActivity select[name='subdivision']").attr("disabled", "true")
        //     $("#formNewActivity input[name='lote']").attr("disabled", "true")
        // }else{
        //     $("#formNewActivity select[name='worker']").removeAttr("disabled")
        //     $("#formNewActivity select[name='subdivision']").removeAttr("disabled")
        //     $("#formNewActivity input[name='lote']").removeAttr("disabled")
        // }

        $(".list_texts").html("")

        for(var i = 0; i < settings.textos.length; i++){
            var t = settings.textos[i]

            $(".list_texts").append(
                `
                    <div class="col flex_ form-group">
                        <label class="pr-3">${t}:</label>
                        <span class="kt-switch kt-switch--icon">
                            <label class="m-0">
                                <input type="checkbox" name="text_${i}"> 
                                <span></span>
                            </label>
                        </span>
                    </div>
                `
            )
        }


        validate_a(settings)
        
    })
    .fail((fail)=>{
        console.log(fail);
    })

      
    mostrar_ultima_direccion("new")
    
  
})

var store_local_time = ""
var store_local_address = ""
$("#type_edit_activity").on("change",function(){

    var id = $(this).val()

    var data = {
        type: true,
        id: id
    }

    $("#ordened_edit")[0].checked = false
    $("#marked_edit")[0].checked = false
    $("#attachment_edit").val("").next(".custom-file-label").html("")

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: data
    })
    .done((data)=>{
        console.log(data);
        var settings = data.settings

        if (settings == undefined) {
            $(".content_ordened_edit").addClass("hidden")
            $(".content_marked_edit").addClass("hidden")
            $(".attachment_edit").addClass("hidden")
            return false
        }

        if (settings.ordered) {
            $(".content_ordened_edit").removeClass("hidden")
        }else{
            $(".content_ordened_edit").addClass("hidden")
        }
        if (settings.marked) {
            $(".content_marked_edit").removeClass("hidden")
        }else{
            $(".content_marked_edit").addClass("hidden")
        }
        if (settings.attachment) {
            $(".attachment_edit").removeClass("hidden")
        }else{
            $(".attachment_edit").addClass("hidden")
        }
        if (settings.billiable) {
            $(".content_billiable_edit").removeClass("hidden")
        }else{
            $(".content_billiable_edit").addClass("hidden")
        }
        if (settings.archive) {
            $(".content_archive_edit").removeClass("hidden")
        }else{
            $(".content_archive_edit").addClass("hidden")
        }

        // Warranty o Service
        if (data.id == 15 || data.id == 16) {
            $(".address_new_edit").removeClass("hidden")
            //$("#time_edit").val(store_local_time)
            $("#address_edit").val(store_local_address)
           
        }else{
            $(".address_new_edit").addClass("hidden")
            store_local_time =  $("#time_edit").val()
            store_local_address =  $("#address_edit").val()
            $("#time_edit").val("")
            $("#address_edit").val("")
        }


        // if(data.nombre == "@Special"){
        //     $("#formEditActivity select[name='worker']").attr("disabled", "true")
        //     $("#formEditActivity select[name='subdivision']").attr("disabled", "true")
        //     $("#formEditActivity input[name='lote']").attr("disabled", "true")
        // }else{
        //     $("#formEditActivity select[name='worker']").removeAttr("disabled")
        //     $("#formEditActivity select[name='subdivision']").removeAttr("disabled")
        //     $("#formEditActivity input[name='lote']").removeAttr("disabled")
        // }


        $(".list_texts_edit").html("")

        for(var i = 0; i < settings.textos.length; i++){
            var t = settings.textos[i]

            $(".list_texts_edit").append(
                `
                    <div class="col flex_ form-group">
                        <label class="pr-3">${t}:</label>
                        <span class="kt-switch kt-switch--icon">
                            <label class="m-0">
                                <input type="checkbox" name="text_${i}"> 
                                <span></span>
                            </label>
                        </span>
                    </div>
                `
            )
        }
        
    })
    .fail((fail)=>{
        console.log(fail);
    })

    mostrar_ultima_direccion("edit")
 
})


$(".actualizar_config").on("blur",function(){

    var key = $(this).attr("data-key")
    var value = $(this).val()

    var data = {
        update_field_config: true,
        key: key,
        value: value
    }
  
    ajax.peticion("normal", data, "views/ajax/gestorConfig.php")
        .then((data)=>{

                if (data.status == "ok") {
                    util.alertSuccess("Success", "Updated!", false, true)
                }else{
                    
                    util.alertError("Error", data.error)
                }

            },(fail)=>{
                console.log("fallo");
                console.log(fail);
            })

})

$(".actualizar_config_seguridad").on("click",function(){



    var id = $(this).attr("data-id")
    var key = $(this).attr("data-key")
    var to = $(this).attr("data-to")
    var value = $(this)[0].checked

    console.log($(this))

    var data = {
        update_field_config_seg: true,
        key: key,
        value: value,
        id_p: id,
        to: to
    } 

    console.log(data)

    ajax.peticion("normal",data, "views/ajax/gestorConfig.php")
        .then((data)=>{

            if (data.status == "ok") {
                util.alertSuccess("Updated!")
            }else{
                
                util.alertError(data.error)
            }

        },(fail)=>{
            console.log("fallo");
            console.log(fail);
        })
        




})


$(".agregarPaginaRestriccion").on("click",function(){


    var nombre = $("#p-name").val()


    if (nombre != "") {
        OptionsAjax.data = {
            addPageRes: true,
            nombre: nombre
        }
        OptionsAjax.url = "views/ajax/gestorConfig.php"
        ajax.setData(OptionsAjax)
        ajax.ejecutar()
        .then((data)=>{
    
                if (data.status == "ok") {
                    util.alertSuccess("agregado")
                    $("#p-name").val("")
                }else{
                    
                    util.alertError(data.error)
                }
    
            },(fail)=>{
                console.log("fallo");
                console.log(fail);
            })
    
    }
    



})

