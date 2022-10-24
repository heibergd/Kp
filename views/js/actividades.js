var alert_act = false
var founded_slab = false
var settings_t = false
var one = false
var lot_duplicated = 0
var intentos = 0
var wait_ = false


if ($(".content_act_list").length != 0) {
    
    var showen = []

    var flag = true
    var flag_t = true
    var page = 1
    var page_t = 0
    var less_data = true
    var less_data_t = true
    var init_ = false
    var init_t = false
    var f_group = ""
    var f_group_t = ""
    var wrapper = $("#kt_wrapper")
    var break_ = false
    var p = Number(wrapper.css("padding-top").replace("px", ""))
    
    var h = window.innerHeight
   
    $(".content_act_list").css("height", h)

    ajax.peticion("normal",{list_act: true, page: page, direc: ">=", init: true},"views/ajax/gestorActividades.php")
        .then((res)=>{
            console.log("init");
            console.log(res);

            $("#list_activities").html("")

            for(var i = 0; i < res.length; i++){

                var item = res[i]
                var typ = item.tipo
                var textos = ""
                var title_date = (item.fecha_group.today) ? "TODAY" : item.fecha_group.format

                var passed = (item.fecha_group.diff) ? `<span class='badge badge-danger'>-${item.fecha_group.diff} day/s</span>` : ""

                if (item.estado == 2) {
                    passed = ""
                }
                
                showen.push(item.id)

                if (!init_) {

                    f_group = item.fecha_group.f
                    init_ = true

                    $("#list_activities").append(`
                        <tr class="fc-list-heading _gders" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                    
                }

                if (item.fecha_group.f != f_group) {
                    f_group = item.fecha_group.f
                    $("#list_activities").append(`
                        <tr class="fc-list-heading _gders" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                }

                var bg = ""
                var status = ""

                if(typ.settings.ordered && typ.settings.marked){
                    if (item.estado == 3) {                       
                        bg = typ.settings.color_marked
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }else{
                    if (item.estado == 3 || item.estado == 2) {                       
                        bg = typ.settings.color_marked
                    }else if (item.estado == 1) {                       
                        bg = typ.settings.color_ordered
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }

            
                if (typ.settings.ordered && item.estado == 1) {
                    status = `<div>Ordered</div>`
                }else if (typ.settings.marked && item.estado == 2) {
                    status = `<div>Marked</div>`
                }else if (typ.settings.marked && typ.settings.ordered &&  item.estado == 3) {
                    status = `<div>Ordered</div><div>Marked</div>`
                }
        
                var l = item.lote != "" ? `
                    <a href="#" class="set_filter_lot" data-sub="${item.subdivision.id}" data-lot="${item.lote}">
                            <b>Lot :  ${item.lote} </b>
                    </a>` : ""
                var inst = item.worker.nombre != "" ? `<b>Installer : ${item.worker.nombre}</b>` : ""
                var str_ = `
                    <span> <b>${item.subdivision.nombre} </b></span> 
                    <div>
                        ${l}
                    </div>                              
                    <div>${inst}</div> 
                `

                if (item.textos != undefined && typ.settings.textos.length) {
                    var selecteds = item.textos.filter((item_x) => item_x.valor == "1")
                    var aux_ = []
                
                    for(var x = 0; x < selecteds.length; x++){
                       aux_.push(selecteds[x].texto)
                    }

                    if (aux_.length != 0) {
                        textos =  "(" + aux_.toString() + ")"
                    }

                    
                }

                var aux_str = (textos != "") ? `<b>${textos}</b>` : `${item.tipo.nombre} <b>${textos}</b>`

                if(item.can_form_rough){
                    if(item.have_form_rough > 0){
                        var dot = `<a href="editRoughForm_${item.id_form_rough}" class="fc-event-dot bg_primary" target="_blank"></a>`
                    }else{
                        var dot = `<a href="createRoughForm_${item.id}" class="fc-event-dot" target="_blank"></a>`
                    }
                }else{
                    var dot = ``
                }

                $("#list_activities").append(`
                    <tr class="fc-list-item position-relative item_d_list" data-d="${item.fecha_group.f}">
                        <td class="td_color" style="background: #${bg};"></td>
                        <td class="fc-list-item-time fc-widget-content">
                            ${str_}  
                            <div>By : ${item.creado_por}</div>       
                        </td>
                        <td class="fc-list-item-marker fc-widget-content">
                            ${dot}
                        </td>
                        <td class="fc-list-item-title fc-widget-content">
                            <a href="#" class="editActivity" data-id="${item.id}">
                                <div><b>Type : ${aux_str}</b></div>  
                            </a>
                            ${status}      
                            <div class="fc-description">
                                ${item.descripcion}
                            </div>
                        </td>
                    </tr>
                `)
               
            }
            
            if (!res.length) {
                resultsError(res)                                        
            }

            var thereScroll = $("#list_activities")[0].offsetHeight - $(".content_act_list")[0].offsetHeight
      
            if (thereScroll < 0) {
                ajax.peticion("normal",{list_act: true, page: page + 1, direc: ">=", init : false},"views/ajax/gestorActividades.php")
                    .then((res)=>{
                        if (res.length) {
                            $(".loadMoreBottom").removeClass("hidden")
                        }
                    },(fail)=>{
                        console.log(fail);
                    })
                ajax.peticion("normal",{list_act: true, page: page_t + 1, direc: "<", init : false},"views/ajax/gestorActividades.php")
                    .then((res)=>{
                        if (res.length) {
                            $(".loadMoreTop").removeClass("hidden")
                        }
                    },(fail)=>{
                        console.log(fail);
                    })
            }

            bind_()           

        },(fail)=>{
            console.log(fail);
        })

    var last_aux = 0

    $(".content_act_list").on("scroll",function(){
    
        var top = $(this).scrollTop()
        var that = $(this)[0]
    
        var scrollY = that.scrollHeight - that.scrollTop
        var height = that.offsetHeight
        var offset = height - scrollY

        // console.log(offset);

        if(offset == 0 || offset == 1 || offset > -1){
            // console.log(showen);
            if(flag && less_data){

                loadMoreBottom()                 
                
            }
                            
        }else if(top < 150 &&  top < last_aux){
            if(flag_t && less_data_t){
                loadMoreTop()                  
            }
        }

        // if (top == 0 && !break_) {
        //     break_ = true
        //     if(flag_t && less_data_t){
        //         loadMoreTop()                  
        //     }
        // }

        last_aux = top

    })

    $(".loadMoreTop").on("click", ()=> loadMoreTop())
    $(".loadMoreBottom").on("click", ()=> loadMoreBottom())
  

    $(".add_filter").on("change",function(e){
        // console.log(e);
        var val = e.currentTarget.checked
        if (val) {
            $(".filters").removeClass("hidden")
        }else{
            $(".filters").addClass("hidden")
        }
    })

    $(".set_filter_list").on("change",function(){

        var key = $(this).attr("data-key")
        var val = $(this).val()

        if (key == "filter_billi") {

            val = $("#fil_billiable")[0].checked

            val = (val) ? "on" : ""
        }

        var data = {
            set_filter_list: true,
            key: key,
            val: val
        }
        console.log(data);
        
        ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
            .then((res)=>{

                console.log(res);
                window.location.reload()
            },(fail)=>{

                console.log(fail);
            })

       

    })

    $(".applyAllFilters").on("click",function(){

        apply_all_filters()

    })

    $("#lotFilter_").keypress(function(e) {
        if(e.which == 13) {
          e.preventDefault();
          apply_all_filters();
        }
      });


    function apply_all_filters(){
        var data =  new FormData(document.getElementById("form_filters_a"))
      
      
        data.append("set_filters", "true")
  

        $(this).addClass("kt-spinner kt-spinner--left kt-spinner--sm kt-spinner--light")

        $.ajax({
            url: "views/ajax/gestorActividades.php",
            type: "POST",
            dataType: "JSON",
            cache:  false,
			contentType:  false,
			processData:  false,
            data: data
        })
        .done((res)=>{
            console.log(res);

            window.location.reload()
        })
        .fail((fail)=>{
            console.log(fail);
        })
    }

    // calendario

    var int_c = setInterval(()=>{

        // console.log("intentando...");
        if ($("td[data-date]").length) {
            if($("#specific_d_border").length){
                var _date_border = $("#specific_d_border").val()
            
                $(`td[data-date="${_date_border}"]`).addClass("border_specific_date")                        
            }
        
          
            $("td[data-date]").unbind().bind("click",function(e){
    
                var date = e.currentTarget.dataset.date
        
                var key = "specific_date"
                var val = date
        
                var data = {
                    set_filter_list: true,
                    key: key,
                    val: val
                }
                console.log(data);
                
                ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
                    .then((res)=>{
        
                        console.log(res);
                        window.location.reload()
                    },(fail)=>{
                        console.log(fail);
                    })
        
            })

            $(".fc-content-skeleton table tbody").remove()

            setTimeout(()=>{
                $(".fc-day-grid-container").css("height", "400px")
            },500)

            $(".fc-day-grid-container").bind("DOMSubtreeModified",function(){
                $("td[data-date]").unbind().bind("click",function(e){
        
                    var date = e.currentTarget.dataset.date
            
                    var key = "specific_date"
                    var val = date
            
                    var data = {
                        set_filter_list: true,
                        key: key,
                        val: val
                    }
                    console.log(data);
                    
                    ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
                        .then((res)=>{
            
                            console.log(res);
                            window.location.reload()
                        },(fail)=>{
                            console.log(fail);
                        })
            
                })

                if($("#specific_d_border").length){
                    var _date_border = $("#specific_d_border").val()
                
                    $(`td[data-date="${_date_border}"]`).addClass("border_specific_date")                        
                }

                $(".fc-content-skeleton table tbody").remove()

                setTimeout(()=>{
                    $(".fc-day-grid-container").css("height", "400px")
                },800)
                
            })

            clearInterval(int_c)
         
        }
    },1000)

    
    
}

if ($(".content_act_list_lot").length != 0) {
    
    let flag = true
    let page = 1
    let less_data = true
    let init_ = false
    let f_group = ""
    var sub_lot = $("#sub_lot").val()
    var lot_ = $("#lot_").val()
    var data_l = {
        act_by_lot: true,
        subdivision: sub_lot,
        lote: lot_
    }

    ajax.peticion("normal",data_l,"views/ajax/gestorActividades.php")
        .then((res)=>{
            console.log(res);

            $("#list_activities_lot").html("")

            for(var i = 0; i < res.length; i++){

                var item = res[i]
                var typ = item.tipo
                var textos = ""
                var title_date = (item.fecha_group.today) ? "TODAY" : item.fecha_group.format

                var passed = (item.fecha_group.diff) ? `<span class='badge badge-danger'>-${item.fecha_group.diff} day/s</span>` : ""

                if (item.estado == 2) {
                    passed = ""
                }
                
                if (!init_) {

                    f_group = item.fecha_group.f
                    init_ = true

                    $("#list_activities_lot").append(`
                        <tr class="fc-list-heading" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                    
                }

                if (item.fecha_group.f != f_group) {
                    f_group = item.fecha_group.f
                    $("#list_activities_lot").append(`
                        <tr class="fc-list-heading" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                }

                var bg = ""
                var status = ""

                if(typ.settings.ordered && typ.settings.marked){
                    if (item.estado == 3) {                       
                        bg = typ.settings.color_marked
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }else{
                    if (item.estado == 3 || item.estado == 2) {                       
                        bg = typ.settings.color_marked
                    }else if (item.estado == 1) {                       
                        bg = typ.settings.color_ordered
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }

            
                if (typ.settings.ordered && item.estado == 1) {
                    status = `<div>Ordered</div>`
                }else if (typ.settings.marked && item.estado == 2) {
                    status = `<div>Marked</div>`
                }else if (typ.settings.marked && typ.settings.ordered &&  item.estado == 3) {
                    status = `<div>Ordered</div><div>Marked</div>`
                }

              
                var l = item.lote != "" ? `<b>Lot :  ${item.lote} </b>` : ""
                var inst = item.worker.nombre != "" ? `<b>Installer : ${item.worker.nombre}</b>` : ""
                var str_ = `
                    <span> <b>${item.subdivision.nombre} </b></span> 
                    <div>${l}</div>                              
                    <div>${inst}</div> 
                `

                if (item.textos != undefined && typ.settings.textos.length) {
                    var selecteds = item.textos.filter((item_x) => item_x.valor == "1")
                    var aux_ = []
                
                    for(var x = 0; x < selecteds.length; x++){
                       aux_.push(selecteds[x].texto)
                    }

                    if (aux_.length != 0) {
                        textos =  "(" + aux_.toString() + ")"
                    }

                    
                }

                var aux_str = (textos != "") ? `<b>${textos}</b>` : `${item.tipo.nombre} <b>${textos}</b>`

                if(item.can_form_rough){
                    if(item.have_form_rough > 0){
                        var dot = `<a href="editRoughForm_${item.id_form_rough}" class="fc-event-dot bg_primary" target="_blank"></a>`
                    }else{
                        var dot = `<a href="createRoughForm_${item.id}" class="fc-event-dot" target="_blank"></a>`
                    }
                }else{
                    var dot = ``
                }

                $("#list_activities_lot").append(`
                    <tr class="fc-list-item position-relative">
                        <td class="td_color" style="background: #${bg};"></td>
                        <td class="fc-list-item-time fc-widget-content">
                            ${str_}
                            <div>By : ${item.creado_por}</div>       
                        </td>
                        <td class="fc-list-item-marker fc-widget-content">
                            ${dot}
                        </td>
                        <td class="fc-list-item-title fc-widget-content">
                            <a href="#" class="editActivity" data-id="${item.id}">
                                <div><b>Type : ${aux_str}</b></div>  
                            </a>
                            ${status}       
                            <div class="fc-description">
                                ${item.descripcion}
                            </div>
                        </td>
                    </tr>
                `)
               
            }
            
            if (!res.length) {
                $("#list_activities_lot").html(`
                    <div class="alert alert-info">No records found</div>
                `)
            }

            bind_()           

        },(fail)=>{
            console.log(fail);
        })

 


}

$(".addActivity").on("click",function(){

    var lot = $(this).attr("data-lot")
    var sub = $(this).attr("data-sub")

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: {access: true}
    })
    .done((res)=>{
        // console.log(res);
        if (res.newActivitie != undefined) {
            if (lot != undefined && sub != undefined) {
                $("#formNewActivity select[name='subdivision']").val(sub)
                $("#formNewActivity input[name='lote']").val(lot)
                lot_list_("list_lot1")
                
            }
        
            $("#m_new_activity").modal("show")
        }else{
            util.alertError("Error", "You don't have access to this action")
        }
    })
    .fail((error)=>{
        console.log(error);
    })

    
})

$("#crearActivity").unbind().bind("click",function(e){

    e.preventDefault()
    
    var datos = new FormData(document.getElementById("formNewActivity"))
    
   

    var val = $("#billiable_new")[0].checked

    val = (val) ?   "1" : "0"

    datos.append("billiable_", val)

    if (!settings_t) {
        util.alertError("Select Type")
    }else if (alert_act) {
        util.alertConfirm("You are creating an activity in a Lot that has no Slab. Do you want to continue?")
            .then(()=>{
                create_(datos)
            },()=>{
                console.log("NO");
            })
    }else{
        create_(datos)
    }
    console.log("datos:");
    console.log(datos);
})

$("#editActivity").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formEditActivity"))

    var val = $("#billiable_edit")[0].checked

    val = (val) ? "1" : "0"

    // console.log($("#kt_datepicker_2").val());

    // return false

    datos.append("billiable_", val)

    datos.append("actualizar", "true")

    $(this).html("Loading...")
    ajax.mostrarProgreso((p)=>{
        // console.log(p);
        p = p * 100
        $(".progress_edit").html(`
            <div class="progress progress-sm">
                <div class="progress-bar kt-bg-primary" role="progressbar" style="width: ${p}%;" aria-valuenow="${p}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        `)
    })
    ajax.peticion("FormData", datos,"views/ajax/gestorActividades.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
                
            } else{
                util.alertSuccess("Success", "Updated!", false, true)

            }
            $(".progress_edit").html("")
            $(this).html("UPDATE")
        },(error)=>{
            console.log(error)
            $(this).html("Error, Try Again!")
            if (error.readyState == 0) {
                // Network error (i.e. connection refused, access denied due to CORS, etc.)
                util.alertError("Network error", "Connection error")
            }
        })

})

$('#m_edit_activity').on('hidden.bs.modal', function (e) {
    // do something...
    // console.log("OCULADO");

    one = false
    $("#attach_list_2").html("")
    $("#attach_video_2").html("")
    $(".attachment_list_2").addClass("hidden")                    
    $(".attachment_video_2").addClass("hidden")                    

    $("#lote_edit_act").val("").css("border-color", "#e2e5ec")
    $("#editActivity").removeAttr("disabled")
    $("#cloneActivity").html("Prepare Duplicate")
    $("#exampleModalLabel_6").html("Edit Activity")
})

$("#cloneActivity").on("click",function(e){
    e.preventDefault()

    if (!one) {
        lot_duplicated = $("#lote_edit_act").val()
        $("#lote_edit_act").val("").css("border-color", "red").focus()
        $(this).html("Duplicate!")
        util.alertInfo("Message", "Enter the new Lot for the Duplicate")
        $("#exampleModalLabel_6").html("Doubling")
        $("#editActivity").attr("disabled", "true")
        one = true
        return false
    }else{
        $("#lote_edit_act").css("border-color", "#e2e5ec")
    }

    
    var datos = new FormData(document.getElementById("formEditActivity"))

    var val = $("#billiable_edit")[0].checked

    val = (val) ? "1" : "0"

    datos.append("billiable_", val)

    datos.append("crear", "true")
    
    $(this).html("Doubling...")
    ajax.mostrarProgreso((p)=>{
        // console.log(p);
        p = p * 100
        $(".progress_edit").html(`
            <div class="progress progress-sm">
                <div class="progress-bar kt-bg-primary" role="progressbar" style="width: ${p}%;" aria-valuenow="${p}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        `)
    })
    ajax.peticion("FormData", datos,"views/ajax/gestorActividades.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
                
            } else{
                util.alertSuccess("Success", "Duplicated!", false, false)

                one = false
                $("#lote_edit_act").val("").css("border-color", "#e2e5ec")
                // $("#editActivity").removeAttr("disabled")
                $("#cloneActivity").html("Prepare Duplicate")
                $("#exampleModalLabel_6").html("Edit Activity")
                $("#lote_edit_act").val(lot_duplicated)

            }
            $(".progress_edit").html("")
            // $(this).html("Duplicate!")
        },(error)=>{
            console.log(error)
            $(this).html("Error, Try Again!")
            if (error.readyState == 0) {
                // Network error (i.e. connection refused, access denied due to CORS, etc.)
                util.alertError("Network error", "Connection error")
            }
        })

})

$("#filtrarActivities").on("click",function(){

    filter_a($("#generalSearch").val())
    
})

$("#kt_subheader_search_form").on("submit",function(e){
    e.preventDefault()

    filter_a($("#generalSearch").val())
})

$(".removeFilterActivities").on("click",function(){

    filter_a("")
    
})

$(".removeSpecificDate").on("click",function(){

        var key = "specific_date"
        var val = ""

        var data = {
            set_filter_list: true,
            key: key,
            val: val
        }
        // console.log(data);
        
        ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
            .then((res)=>{
                console.log(res);
                window.location.reload()
            },(fail)=>{
                console.log(fail);
            })
    
})
$(".removeAllFilters").on("click",function(){

    var data = {
        removeAllFilters: true
    }
    // console.log(data);
    
    ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
        .then((res)=>{
            // console.log(res);
            window.location.reload()
        },(fail)=>{
            console.log(fail);
        })

})

$("#b_actividad").on("click",function(){

    var id = $("#act_id_Edit").val()

    // console.log(id);
   
    delete_a(id)
})

$(".icon_toggle_calendar").on("click",function(){

    var key = "toggle_calendar"
    var val = $(this).attr("data-val")

    var data = {
        set_filter_list: true,
        key: key,
        val: val
    }
    // console.log(data);

    if (val == "true") {
        $(".calendar_content").addClass("hidden")
        $(".activity_content").removeClass("col-md-7").addClass("col-md-12")
        $(".hide_calendar").addClass("hidden")
        $(".show_calendar").removeClass("hidden")
    }else{
        $(".calendar_content").removeClass("hidden")
        $(".activity_content").removeClass("col-md-12").addClass("col-md-7")
        $(".hide_calendar").removeClass("hidden")
        $(".show_calendar").addClass("hidden")
    }
    
    ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
        .then((res)=>{
            console.log(res);
        },(fail)=>{
            console.log(fail);
        })

})

$("#lotFilter_").on("keyup",function(){

    var val = $(this).val()


    $.ajax({
        url: "views/ajax/gestorActividades.php",
        type: "POST",
        dataType: "JSON",
        data: {
            searchLot: true,
            val: val
        }
    })
    .done((res)=>{
        // console.log(res);   

        $("#lotes_list").html("")
        for(var i = 0; i < res.length; i++){
            
            $("#lotes_list").append(`
            
                <option>${res[i].lote}</option>
            `)
        }
    })
    .fail((error)=>{        
        console.log(error);
    })

    
})

$("#ex_pdf").on("click",function(){

    var t = $("#s_total_").html()
    // console.log(t);
    if (t > 500) {

        util.alertInfo("Error", "Exceeds the 500 activity limit")

        return false
    }
})

var timeout 

$(".lot_").on("keyup",function(){

    var content = $(this).attr("data-content")

    lot_list_(content)
    
})
$(".lot_sub").on("change",function(){

    var content = $(this).attr("data-content")

    lot_list_(content)
    
})

$("#history").on("click",function(e){
    e.preventDefault()
    if ($(".content_history_desc").hasClass("hidden")) {
        $(".content_history_desc").removeClass("hidden")
        $(this).html("History (hide)")
    }else{
        $(".content_history_desc").addClass("hidden")
        $(this).html("History (show)")
    }
})

function resultsError(init_as){
    
    ajax.peticion("normal",{list_act: true, page: page_t + 1, direc: "<", init : false},"views/ajax/gestorActividades.php")
        .then((res)=>{
            
            if (!res.length && !init_as.length) {                
                 $("#list_activities").html(`
                    <div class="alert alert-info mt-2">No records found</div>
                `)
            }else{
                loadMoreTop()   
            }
        },(fail)=>{
            console.log(fail);
        })
}

function loadMoreTop(){
    flag_t = false;
    page_t = page_t + 1

    $(".spinner_e").removeClass("hidden")


    // console.log({
    //     pagina: page_t
    // });

    ajax.peticion("normal",{list_act: true, page: page_t, direc: "<", init: false},"views/ajax/gestorActividades.php")
        .then((res)=>{
            // console.log("top");
            // console.log(res);

            // res = res.sort(function (a, b) {
            //     if (a.fecha > b.fecha) {
            //       return 1;
            //     }
            //     if (a.fecha < b.fecha) {
            //       return -1;
            //     }
            //     // a must be equal to b
            //     return 0;
            //   });

            var temp_template = ""
            for(var i = 0; i < res.length; i++){

                var item = res[i]
                var typ = item.tipo
                var textos = ""
                var title_date = (item.fecha_group.today) ? "TODAY" : item.fecha_group.format

                var passed = (item.fecha_group.diff) ? `<span class='badge badge-danger'>-${item.fecha_group.diff} day/s</span>` : ""

                if (item.estado == 2) {
                    passed = ""
                }

                var m = showen.filter((it) => it == item.id)[0]
               
        

                if ($(`._gders[data-date="${item.fecha_group.f}"]`).length == 0) {
                    
                    $("#list_activities").prepend(`
                        <tr class="fc-list-heading _gders" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                }



                var bg = ""
                var status = ""

                if(typ.settings.ordered && typ.settings.marked){
                    if (item.estado == 3) {                       
                        bg = typ.settings.color_marked
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }else{
                    if (item.estado == 3 || item.estado == 2) {                       
                        bg = typ.settings.color_marked
                    }else if (item.estado == 1) {                       
                        bg = typ.settings.color_ordered
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }

            
                if (typ.settings.ordered && item.estado == 1) {
                    status = `<div>Ordered</div>`
                }else if (typ.settings.marked && item.estado == 2) {
                    status = `<div>Marked</div>`
                }else if (typ.settings.marked && typ.settings.ordered &&  item.estado == 3) {
                    status = `<div>Ordered</div><div>Marked</div>`
                }

                var l = item.lote != "" ? `
                <a href="#" class="set_filter_lot" data-sub="${item.subdivision.id}" data-lot="${item.lote}">
                        <b>Lot :  ${item.lote} </b>
                </a>` : ""
                var inst = item.worker.nombre != "" ? `<b>Installer : ${item.worker.nombre}</b>` : ""
                var str_ = `
                    <span> <b>${item.subdivision.nombre} </b></span> 
                    <div>${l}</div>                              
                    <div>${inst}</div> 
                `



                if (item.textos != undefined  && typ.settings.textos.length) {
                    var selecteds = item.textos.filter((item_x) => item_x.valor == "1")
                    var aux_ = []
                
                    for(var x = 0; x < selecteds.length; x++){
                       aux_.push(selecteds[x].texto)
                    }

                    if (aux_.length != 0) {
                        textos =  "(" + aux_.toString() + ")"
                    }

                    
                }
                var aux_str = (textos != "") ? `<b>${textos}</b>` : `${item.tipo.nombre} <b>${textos}</b>`

                if(item.can_form_rough){
                    if(item.have_form_rough > 0){
                        var dot = `<a href="editRoughForm_${item.id_form_rough}" class="fc-event-dot bg_primary" target="_blank"></a>`
                    }else{
                        var dot = `<a href="createRoughForm_${item.id}" class="fc-event-dot" target="_blank"></a>`
                    }
                }else{
                    var dot = ``
                }

                if ($(`.group_date_${item.fecha_group.f}`).length != 0) {
                    
                    $(`.group_date_${item.fecha_group.f}`).last().after(`
                        <tr class="fc-list-item position-relative group_date_${item.fecha_group.f}">
                            <td class="td_color" style="background: #${bg};"></td>
                            <td class="fc-list-item-time fc-widget-content">
                                ${str_}
                                <div>By : ${item.creado_por}</div>       
                            </td>
                            <td class="fc-list-item-marker fc-widget-content">
                                ${dot}
                            </td>
                            <td class="fc-list-item-title fc-widget-content">
                                <a href="#" class="editActivity" data-id="${item.id}">
                                    <div><b>Type : ${aux_str}</b></div>  
                                </a>
                                ${status}   
                                <div class="fc-description">
                                    ${item.descripcion}
                                </div>
                            </td>
                        </tr>
                    `)

                }else{
                    $(`._gders[data-date="${item.fecha_group.f}"]`).after(`
                        <tr class="fc-list-item position-relative group_date_${item.fecha_group.f}">
                            <td class="td_color" style="background: #${bg};"></td>
                            <td class="fc-list-item-time fc-widget-content">
                                ${str_}
                                <div>By : ${item.creado_por}</div>       
                            </td>
                            <td class="fc-list-item-marker fc-widget-content">
                                ${dot}
                            </td>
                            <td class="fc-list-item-title fc-widget-content">
                                <a href="#" class="editActivity" data-id="${item.id}">
                                    <div><b>Type : ${aux_str}</b></div>  
                                </a>
                                ${status}   
                                <div class="fc-description">
                                    ${item.descripcion}
                                </div>
                            </td>
                        </tr>
                    `)
                }

                
               
            }
            
            if (!res.length) {
                less_data_t = false
            }
            
            // $("#list_activities").prepend(temp_template)

            loadButtons()

            bind_()    
            flag_t = true   
            $(".spinner_e").addClass("hidden")    

            if ($(".content_act_list").scrollTop() < 200 && res.length) {
                $(".content_act_list").scrollTop(1000)
            }

        },(fail)=>{
            console.log(fail);
        })  
}

function loadMoreBottom(){
    flag = false;
    page = page + 1

    $(".spinner_i").removeClass("hidden")

    // console.log({
    //     pagina: page
    // });
    

    ajax.peticion("normal",{list_act: true, page: page, direc: ">=", init: false},"views/ajax/gestorActividades.php")
        .then((res)=>{
            // console.log(res);

            for(var i = 0; i < res.length; i++){

                var item = res[i]
                var typ = item.tipo
                var textos = ""
                var title_date = (item.fecha_group.today) ? "TODAY" : item.fecha_group.format

                var passed = (item.fecha_group.diff) ? `<span class='badge badge-danger'>-${item.fecha_group.diff} day/s</span>` : ""

                if (item.estado == 2) {
                    passed = ""
                }
                var m = showen.filter((it) => it == item.id)[0]
               
  
                if (!init_) {

                    f_group = item.fecha_group.f
                    init_ = true

                    $("#list_activities").append(`
                        <tr class="fc-list-heading" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                    
                }

                if (item.fecha_group.f != f_group) {
                    f_group = item.fecha_group.f
                    $("#list_activities").append(`
                        <tr class="fc-list-heading" data-date="${item.fecha_group.f}">
                            <td class="fc-widget-header" colspan="4">
                                <a class="fc-list-heading-main">
                                    ${item.fecha_group.day_all}
                                </a>
                                <a class="fc-list-heading-alt">
                                    ${title_date}
                                </a>
                            </td>
                        </tr>   
                    `)
                }

                var bg = ""
                var status = ""

                if(typ.settings.ordered && typ.settings.marked){
                    if (item.estado == 3) {                       
                        bg = typ.settings.color_marked
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }else{
                    if (item.estado == 3 || item.estado == 2) {                       
                        bg = typ.settings.color_marked
                    }else if (item.estado == 1) {                       
                        bg = typ.settings.color_ordered
                    }else{
                        bg = typ.settings.defaul_color_
                    }
                }

            
                if (typ.settings.ordered && item.estado == 1) {
                    status = `<div>Ordered</div>`
                }else if (typ.settings.marked && item.estado == 2) {
                    status = `<div>Marked</div>`
                }else if (typ.settings.marked && typ.settings.ordered &&  item.estado == 3) {
                    status = `<div>Ordered</div><div>Marked</div>`
                }

                var l = item.lote != "" ? `
                <a href="#" class="set_filter_lot" data-sub="${item.subdivision.id}" data-lot="${item.lote}">
                        <b>Lot :  ${item.lote} </b>
                </a>` : ""
                var inst = item.worker.nombre != "" ? `<b>Installer : ${item.worker.nombre}</b>` : ""
                var str_ = `
                    <span> <b>${item.subdivision.nombre} </b></span> 
                    <div>${l}</div>                              
                    <div>${inst}</div> 
                `

                if (item.textos != undefined && typ.settings.textos.length) {
                    var selecteds = item.textos.filter((item_x) => item_x.valor == "1")
                    var aux_ = []
                
                    for(var x = 0; x < selecteds.length; x++){
                       aux_.push(selecteds[x].texto)
                    }

                    if (aux_.length != 0) {
                        textos =  "(" + aux_.toString() + ")"
                    }

                    
                }

                var aux_str = (textos != "") ? `<b>${textos}</b>` : `${item.tipo.nombre} <b>${textos}</b>`

                if(item.can_form_rough){
                    if(item.have_form_rough > 0){
                        var dot = `<a href="editRoughForm_${item.id_form_rough}" class="fc-event-dot bg_primary" target="_blank"></a>`
                    }else{
                        var dot = `<a href="createRoughForm_${item.id}" class="fc-event-dot" target="_blank"></a>`
                    }
                }else{
                    var dot = ``
                }

                $("#list_activities").append(`
                    <tr class="fc-list-item position-relative item_d_list" data-d="${item.fecha_group.f}">
                        <td class="td_color" style="background: #${bg};"></td>
                        <td class="fc-list-item-time fc-widget-content">
                            ${str_}
                            <div>By : ${item.creado_por}</div>       
                        </td>
                        <td class="fc-list-item-marker fc-widget-content">
                            ${dot}
                        </td>
                        <td class="fc-list-item-title fc-widget-content">
                            <a href="#" class="editActivity" data-id="${item.id}">
                                <div><b>Type : ${aux_str}</b></div>  
                            </a>
                            ${status}   
                            <div class="fc-description">
                                ${item.descripcion}
                            </div>
                        </td>
                    </tr>
                `)
               
            }
            
            if (!res.length) {
                less_data = false
            }
            
            loadButtons()
            
            bind_()    
            flag = true   
            $(".spinner_i").addClass("hidden")    

        },(fail)=>{
            console.log(fail);
        })   
}

function loadButtons(){
    var thereScroll = $("#list_activities")[0].offsetHeight - $(".content_act_list")[0].offsetHeight

    if (thereScroll < 0) {
        ajax.peticion("normal",{list_act: true, page: page + 1, direc: ">=", init : false},"views/ajax/gestorActividades.php")
            .then((res)=>{
                if (res.length) {
                    $(".loadMoreBottom").removeClass("hidden")
                }else{
                    $(".loadMoreBottom").addClass("hidden")
                }
            },(fail)=>{
                console.log(fail);
            })
        ajax.peticion("normal",{list_act: true, page: page_t + 1, direc: "<", init : false},"views/ajax/gestorActividades.php")
            .then((res)=>{
                if (res.length) {
                    $(".loadMoreTop").removeClass("hidden")
                }else{
                    $(".loadMoreTop").addClass("hidden")
                }
            },(fail)=>{
                console.log(fail);
            })
    }else{
        $(".loadMoreBottom").addClass("hidden")
        $(".loadMoreTop").addClass("hidden")
    }
}

function create_(datos){
    datos.append("crear", "true")

    if (!wait_) {

        wait_ = true

        $("#crearActivity").html("Loading...")
        ajax.peticion("FormData", datos,"views/ajax/gestorActividades.php")
            .then((res)=>{
                console.log(res)  
                if (res.status == "error") {
                    util.alertError("Error", res.mensaje)                
                } else{
                    util.alertSuccess("Success", "Added!", false, true)
                }
                $("#crearActivity").html("CREATE")     
                wait_ = false       
            },(error)=>{
                console.log(error)
                $("#crearActivity").html("error")
                wait_ = false
            })
        
    }else{
        util.alertError("Error", "an activity is being created")       
    }


    
}

function delete_a(id){


    swal.fire({
        buttonsStyling: !1,
        text:
            "Do you want to delete this activity?",
        type: "info",
        confirmButtonText: "Yes, delete!",
        confirmButtonClass:
            "btn btn-sm btn-bold btn-danger",
        showCancelButton: !0,
        cancelButtonText: "No, cancel",
        cancelButtonClass:
            "btn btn-sm btn-bold btn-brand"
    })
    .then(function(t) {
        if (t.value) {

            var datos = {
                borrar_lista_actividades: true,
                lista: [id]
            }

            console.log(datos)

            ajax.peticion("normal",datos,"views/ajax/gestorActividades.php")
                .then((res)=>{
                    console.log(res)
                    if (res.status == false) {
                        util.alertError("Error", res.message)   
                    }else{
                        swal.fire({
                            title: "Deleted!",
                            text:
                                ":(",
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "OK",
                            confirmButtonClass:
                                "btn btn-sm btn-bold btn-brand"
                        }).then(()=>{
                            window.location.reload()
                        })
                    }
                    
                },(error)=>{
                    console.log(error)
                })

            
        }else{
            swal.fire({
                title: "Cancelled",
                text:
                    ":)",
                type: "error",
                buttonsStyling: !1,
                confirmButtonText: "OK",
                confirmButtonClass:
                    "btn btn-sm btn-bold btn-brand"
            })
        }
    });

}

function lot_list_(content){
    clearTimeout(timeout)

    timeout = setTimeout(()=>{
        
        if (content == "list_lot1") {
            var sub_lot = $("#sub_new_act").val()
            var lot_ = $("#lote_new_act").val()
            var m = "message_lot1"
            var attachment_content_list = "attachment_list_1"
            var attachment_content_video = "attachment_video_1"
            var attachment_list = "attach_list_1"
            var attachment_video = "attach_video_1"
            mostrar_ultima_direccion("new")
        }else{
            var sub_lot = $("#sub_edit_act").val()
            var lot_ = $("#lote_edit_act").val()
            var m = "message_lot2"
            var attachment_content_list = "attachment_list_2"
            var attachment_content_video = "attachment_video_2"
            var attachment_list = "attach_list_2"
            var attachment_video = "attach_video_2"
            mostrar_ultima_direccion("edit")
        }

        $("."+m).html(`
            <div class="kt-spinner kt-spinner--v2 kt-spinner--lg kt-spinner--dark"></div>
        `)

        if (sub_lot == "#") {
            $("."+m).html("<div class='badge badge-danger'>Select subdivision</div>")

            setTimeout(()=>{
                $("."+m).html("")
            },900)

            return false
        }else if(lot_ == ""){
            $("."+m).html("")
            return false
        }
        
        var data_l = {
            act_by_lot: true,
            subdivision: sub_lot,
            lote: lot_
        }

        var data_media = {
            archivos: true,
            sub: sub_lot,
            lote: lot_
        }

        $.ajax({
            url: "views/ajax/gestorActividades.php",
            type: "POST",
            dataType:"JSON",
            data: data_l
        })
        .done((res)=>{
            // console.log("what");
            // console.log(res);   

            $("#"+content).html("")
            $("."+m).html("")
            var found = false
            
            for(var i = 0; i < res.length; i++){
                var item = res[i]
                var tipo = item.tipo
                var settings = tipo.settings
                var bg 

                if (item.estado == 1) {                       
                    bg = settings.color_ordered
                }else if(item.estado == 2){
                    bg = settings.color_marked
                }else{
                    bg = settings.defaul_color_
                }
                


                $("#"+content).append(`                    
                    <li class="" style="background: #${bg}">
                        ${tipo.nombre}
                    </li>                    
                `)

                if (tipo.nombre == "Slab") {
                    found = true
                }                 

            }
            var aux = $("#type_new_activity")[0].selectedOptions[0].dataset.name

            if (aux != "Slab" && !found) {
                if (settings_t.warning != undefined && settings_t.warning == true) {
                    alert_act = true
                }                
            }else{
                alert_act = false
            }

            if (res.length) {
                $("#"+content).removeClass("hidden")
            }else{
                $("#"+content).addClass("hidden")
            }

        })
        .fail((fail)=>{
            console.log(fail);
        })


        // descripciones
        $.ajax({
            url: "views/ajax/gestorActividades.php",
            type: "POST",
            dataType:"JSON",
            data: {
                search_descipciones: true,
                subdivision: sub_lot,
                lote: lot_
            }
        })
        .done((res)=>{
            // console.log("what");
            console.log(res);   
            var orde =  res.sort(function(a, b) {
                if (Number(a.id) < Number(b.id)) {
                  return 1;
                }
                if (Number(a.id) > Number(b.id)) {
                  return -1;
                }
                return 0;
            });

            $("#bodyHistory").html("")
            for(var his = 0; his < orde.length; his++){

                $("#bodyHistory").prepend(`
                    <tr>
                        <td style="max-width:70px">${orde[his].tipo.nombre}</td>
                        <td>${orde[his].descripcion} <span class="use_desc" data-index="${his}">Use</span></td>
                    </tr>
                `)
            }

            // console.log("!!!!!!!!!!!");
            // console.log(orde);
            // ULTIMA DESCRIPCION DE ESTA SUBDIVISION Y LOTE ESPECIFICO
            if (orde[0] != undefined) {
                $("#descripcion_new_activity").val(orde[0].descripcion)
            }else{
                $("#descripcion_new_activity").val("")
            }

            $(".use_desc").unbind().bind("click",function(){

                var index = $(this).attr("data-index")

                if (orde[index]) {
                    $("#descripcion_new_activity").val(orde[index].descripcion)
                }
                
            })

        })
        .fail((fail)=>{
            console.log(fail);
        })


        $.ajax({
            url: "views/ajax/gestorActividades.php",
            type: "POST",
            dataType:"JSON",
            data: data_media
        })
        .done((res)=>{
         
            let videos = res.filter((item) => item.name_key == "video")
            let documentos = res.filter((item) => item.name_key != "video")

            $("#"+attachment_list).html("")
            $("#"+attachment_video).html("")

            if (documentos.length) {
                $("."+attachment_content_list).removeClass("hidden")
            }else{
                $("."+attachment_content_list).addClass("hidden")                    
            }

            if (videos.length) {
                $("."+attachment_content_video).removeClass("hidden")
            }else{
                $("."+attachment_content_video).addClass("hidden")                    
            }

            for(let i = 0; i < documentos.length; i++){
                let item = documentos[i]
    
                $("#"+attachment_list).append(`                    
                    <li id="li_list_${item.id_media}">
                        <button class="btn btn-sm btn-danger _p deleteAttachment" data-id="${item.id_media}">
                            <i class="fas fa-times"></i>
                        </button>
                        <a href="${item.path_media}" target="_blank">
                            ${item.name} (${item.name_key})
                        </a> 
                    </li>                    
                `)
            }

            for(let i = 0; i < videos.length; i++){
                let item = videos[i]
    
                $("#"+attachment_video).append(`                    
                    <li id="li_list_${item.id_media}">
                        <button class="btn btn-sm btn-danger _p deleteAttachment" data-id="${item.id_media}">
                            <i class="fas fa-times"></i>
                        </button>
                        <a href="${item.path_media}" target="_blank">
                            ${item.name} (${item.name_key})
                        </a> 
                    </li>                    
                `)
            }


            $(".deleteAttachment").unbind().bind("click",function(e){
                e.preventDefault()
                var id = $(this).attr("data-id")
    
                console.log(id);

                util.alertConfirm("Do you want to delete this attachment file?")
                    .then((res)=>{
                        
                        $.ajax({
                            url: "views/ajax/gestorActividades.php",
                            type: "POST",
                            dataType: "JSON",
                            data: {
                                deleteAttachment: true,
                                id: id
                            }
                        })
                        .done((res)=>{
                            console.log(res);
                            if (res.status == "ok") {
                                let parent = $(`#li_list_${id}`).parent()
                                $(`#li_list_${id}`).remove()

                                if(!parent.find("li").length){
                                    parent.parent().addClass("hidden")
                                }

                                util.alertSuccess("Success", "Deleted!")
                            }
                        })
                        .fail((error)=>{
                            console.log(error);
                        })
                    },(f)=> console.log("no"))

            })

        })
        .fail((fail)=>{
            console.log(fail);
        })
            

    },1000)
}
function validate_a(settings){
    var aux = $("#type_new_activity")[0].selectedOptions[0].dataset.name
    settings_t = settings
    var sub_lot = $("#sub_new_act").val()
    var lot_ = $("#lote_new_act").val()

    var data_l = {
        act_by_lot: true,
        subdivision: sub_lot,
        lote: lot_
    }


    $.ajax({
        url: "views/ajax/gestorActividades.php",
        type: "POST",
        dataType:"JSON",
        data: data_l
    })
    .done((res)=>{
        var found = false
        // console.log("==");
        // console.log(settings);
        
        for(var i = 0; i < res.length; i++){
            var tipo = res[i].tipo
            if (tipo.nombre == "Slab") {
                found = true
            }                 
        }
        var aux = $("#type_new_activity")[0].selectedOptions[0].dataset.name

        if (aux != "Slab" && !found && settings.warning) {
            alert_act = true
        }else{
            alert_act = false
        }

   
    })
    .fail((fail)=>{
        console.log(fail);
    })


}

function bind_(){
    
    $(".editActivity").unbind().bind("click",function(e){
        e.preventDefault()
   
        var id = $(this).attr("data-id")

        $.ajax({
            url: "views/ajax/gestorActividades.php",
            type: "POST",
            dataType: "JSON",
            data: {info_actividad:true, id: id}
        })
        .done((res)=>{
            console.log("aqui")
             console.log(res);
              
            if (res.access.editActivitie == undefined) {
                util.alertError("Error", "You don't have access to this action")

                return false
            }
          
            if (res.have_form_rough) {
                var id_form_rough = res.id_form_rough
                $('#link_rough_sheet').removeClass('hidden')
                $('#link_rough_sheet').attr('href', `editRoughForm_${res.id_form_rough}`)
                $('#formSummaryPayPrint input[name="id"]').val(id_form_rough)
                $('#formSummaryBillPrint input[name="id"]').val(id_form_rough)
                $('#formSummaryPayPrint input[type="submit"]').removeAttr('disabled')
                $('#formSummaryBillPrint input[type="submit"]').removeAttr('disabled')
            }else{
                if(res.can_form_rough){
                    $('#link_rough_sheet').attr('href', `createRoughForm_${res.id}`)
                    $('#link_rough_sheet').removeClass('hidden')
                }else{
                    $('#link_rough_sheet').addClass('hidden')
                }
                $('#formSummaryPayPrint input[type="submit"]').attr('disabled','true')
                $('#formSummaryBillPrint input[type="submit"]').attr('disabled','true')
            }

            $("#formEditActivity .fecha").val(res.fecha_group.format_us)
            $("#formEditActivity .descripcion").val(res.descripcion)
            $("#administracion_edit").val(res.administracion)
            
            if (res.subdivision != 0) {
                $("#formEditActivity .subdivision").val(res.subdivision)    
            }else{
                $("#formEditActivity .subdivision").val("#")    
            }  
            $("#formEditActivity .lote").val(res.lote)
            if (res.trabajador != 0) {
                $("#formEditActivity .worker").val(res.trabajador)    
            }
            $("#formEditActivity .tipo").val(res.tipo)
            $("#formEditActivity .id").val(res.id)

            var settings = res.tipo_.settings

            if (settings == undefined) {
                $(".content_ordened_edit").addClass("hidden")
                $(".content_marked_edit").addClass("hidden")
                $(".attachment_edit").addClass("hidden")
                $(".content_archive_edit").addClass("hidden")
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
            if (settings.billiable) {
                $(".content_billiable_edit").removeClass("hidden")
            }else{
                $(".content_billiable_edit").addClass("hidden")
            }

             // Warranty o Service
             if (res.tipo_.id == 15 || res.tipo_.id == 16) {
                $("#time_edit").val(res.time)
                $("#address_edit").val(res.address)
                $(".address_new_edit").removeClass("hidden")
            }else{
                $(".address_new_edit").addClass("hidden")
                $("#time_edit").val("")
                $("#address_edit").val("")
            }

            // if (settings.archive) {
            //     $(".content_archive_edit").removeClass("hidden")
            // }else{
            //     $(".content_archive_edit").addClass("hidden")
            // }

            if (settings.attachment) {
                $(".attachment_edit").removeClass("hidden")
            }else{
                $(".attachment_edit").addClass("hidden")
            }

            if (res.estado == "3" && settings.marked && settings.ordered) {
                document.getElementById("ordened_edit").checked = true
                document.getElementById("marked_edit").checked = true
            }else if (res.estado == "2" && settings.marked) {
                document.getElementById("marked_edit").checked = true
                document.getElementById("ordened_edit").checked = false
            }else if (res.estado == "1" && settings.ordered){
                document.getElementById("ordened_edit").checked = true
                document.getElementById("marked_edit").checked = false
            }else{
                document.getElementById("ordened_edit").checked = false
                document.getElementById("marked_edit").checked = false
            }

            
            

            if (res.facturable == "1") {
                document.getElementById("billiable_edit").checked = true
            }else{
                document.getElementById("billiable_edit").checked = false
            }
            // if (res.archivo == "1") {
            //     document.getElementById("archive_edit").checked = true
            // }else{
            //     document.getElementById("archive_edit").checked = false
            // }

            // if (res.tipo_.nombre == "@Special") {
            //     $("#formEditActivity select[name='worker']").attr("disabled", "true")
            //     $("#formEditActivity select[name='subdivision']").attr("disabled", "true")
            //     $("#formEditActivity input[name='lote']").attr("disabled", "true")
            // }else{
            //     $("#formEditActivity select[name='worker']").removeAttr("disabled")
            //     $("#formEditActivity select[name='subdivision']").removeAttr("disabled")
            //     $("#formEditActivity input[name='lote']").removeAttr("disabled")
            // }
            
            lot_list_("list_lot2")

            $(".list_texts_edit").html("")
            var txtSaved = res.textos
            for(var i = 0; i < settings.textos.length; i++){
                var t = settings.textos[i]
                var check_ = ""
                if (txtSaved[i] != undefined) {
                    if (txtSaved[i]["valor"] == "1") {
                        check_ = "checked"
                    }
                }
    
                $(".list_texts_edit").append(
                    `
                        <div class="col flex_ form-group">
                            <label class="pr-3">${t}:</label>
                            <span class="kt-switch kt-switch--icon">
                                <label class="m-0">
                                    <input type="checkbox" name="text_${i}" ${check_}> 
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    `
                )
            }

            $("#m_edit_activity").modal("show")
        })
        .fail((fail)=>{
            console.log(fail);
        })
        

    })

    //copiar dirección de lote si es others

    $("#lote_new_act").change(function() {
        var esteLote = $("#lote_new_act").val();
        $("#address_edit").val(esteLote);
        $("#address").val(esteLote);
      });

    $(".set_filter_lot").unbind().bind("click",function(e){
        e.preventDefault()
        var sub = $(this).attr("data-sub")
        var lot = $(this).attr("data-lot")

        var data = {
            set_filter_lot: true,
            sub: sub,
            lot: lot
        }
        // console.log(data);
        
        ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
            .then((res)=>{

                console.log(res);
                window.location.href = "lot"
            },(fail)=>{

                console.log(fail);
            })

       

    })
}

function filter_a(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorActividades.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}

function mostrar_ultima_direccion(modal){

    if(modal == "edit"){
        var tipo = $("#type_edit_activity").val()
        var subdivision = $("#sub_edit_act").val()
        var lote = $("#lote_edit_act").val()
    }else{
        var tipo = $("#type_new_activity").val()
        var subdivision = $("#sub_new_act").val()
        var lote = $("#lote_new_act").val()
    }
 

    $.ajax({
        url: "views/ajax/gestorActividades.php",
        type: "POST",
        dataType: "JSON",
        data: {
            verUltimaDireccion: true,
            tipo: tipo,
            subdivision: subdivision,
            lote: lote
        }
    })
    .done((data)=>{
         console.log("abc")
         console.log(data.info)
         console.log("def")
         
        if(data.info){
            if (modal == "new") {
                $("#address").val(data.info.address)
                //$("#time").val(data.info.time)
            }else{
                $("#address_edit").val(data.info.address)
                //$("#time_edit").val("data.info.time")
            }
        }else{
            //$("#address").val("") 
            $("#address_edit").val("")
        }
    })
    .fail((fail)=>{
        console.log(fail);
    })
}