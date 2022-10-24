var $check1 = $("[d-check=true]").bootstrapSwitch();
var reload_w = true

$("#createWorker").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formNewWorker"))

    // console.log(datos)
    datos.append("crear", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorWorkers.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                $(".mensajeRespuesta").html(`
                    <span class="badge badge-danger">${res.mensaje}</span>            
                `)
            } else{
                $(".mensajeRespuesta").html(`
                    <span class="badge badge-success">Added!</span>                
                `)

                setTimeout(()=>{
                    $(".mensajeRespuesta").html("")
                },1500)
            }
            $(this).html("CREATE")
        },(error)=>{
            console.log(error)
            $(this).html("CREATE")
        })

})

$(".n_worker").on("click",function(e){
    e.preventDefault()

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: {access: true}
    })
    .done((res)=>{
        console.log(res);
        if (res.newWorker != undefined) {

            if ($(this).attr("data-reload")) {
                reload_w = false
            }else{
                reload_w = true
            }

            $("#new_worker").modal("show")
        }else{
            util.alertError("Error", "You don't have access to this action")
        }
    })
    .fail((error)=>{
        console.log(error);
    })

   
})

$("#add_worker").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formadd_Worker"))

    // console.log(datos)
    datos.append("crear", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorWorkers.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
            } else{
                util.alertSuccess("Success", "Added!", false, reload_w)

                $("#select_new_worker").append(`
                    <option value="${res.id}">
                        ${res.name}
                    </option>
                `)
            
            }
            $(this).html("CREATE")
        },(error)=>{
            console.log(error)
            $(this).html("CREATE")
        })

})


$("#updateWorker").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formEditWorker"))

    // console.log(datos)
    datos.append("update", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorWorkers.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
            } else{                
                util.alertSuccess("Success", "Updated!", false, true)
            }
            $(this).html("UPDATE")
        },(error)=>{
            console.log(error)
            $(this).html("UPDATE")
        })

})


//mostrar modal 
$("#cambiarImagenPerfil").on("click",function(){
    $("#myModal").modal("show")
})
//guardar imagen
$("#cambiarImagen").on("click",function(){
    
    var data = new FormData(document.getElementById("formImagenPerfil"))
    data.append("cambiarImagenPerfil", "true")
    $(this).html("...")
    ajax.peticion("FormData", data, "views/ajax/gestorUsuarios.php")
        .then((res)=>{
            console.log(res)

            if (res.status != "ok") {
                $(".mensajeModal").html(res.mensaje)   
            }else{
                swal.fire({
                    title: "¡Actualizado!",
                    text:
                        "¡Imagen de perfil actualizada!",
                    type: "success",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    confirmButtonClass:
                        "btn btn-sm btn-bold btn-brand"
                }).then(()=>{
                    window.location.reload()
                })
            }
            $(this).html("guardar")
            
        },(error)=>{
            console.log(error)
            $(this).html("guardar")
        })
})

$("#filtrarWorkers").on("click",function(){

    filter($("#generalSearch").val())
    
})
$(".removeFilterWorker").on("click",function(){

    filter("")
    
})

$(".update_config_user").on("change", function(e){
    
    var data = {
        key: $(this).attr("data-key"),
        value: $(this).val(),
        update_config_user: true
    }

   chageConfig(data)

})


function filter(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorWorkers.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}