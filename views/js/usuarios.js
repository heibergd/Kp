var $check1 = $("[d-check=true]").bootstrapSwitch();

$("#crearUsuario").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formNuevoUsuario"))

    console.log(datos)
    datos.append("crear", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorUsuarios.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                $(".mensajeRespuesta").html(`

                    <span class="badge badge-danger">${res.mensaje}</span>            
                `)
            } else{
                $(".mensajeRespuesta").html(`
                    <span class="badge badge-success">Added admin</span>                
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

//mostrar modal 
$("#cambiarImagenPerfil").on("click",function(){
    $("#myModal").modal("show")
})
//guardar imagen
$("#cambiarImagen").on("click",function(){
    
    var data = new FormData(document.getElementById("formImagenPerfil"))
    data.append("cambiarImagenPerfil", "true")
    $(this).html("Loading...")
    ajax.peticion("FormData", data, "views/ajax/gestorUsuarios.php")
        .then((res)=>{
            console.log(res)

            if (res.status != "ok") {
                $(".mensajeModal").html(res.mensaje)   
            }else{
                swal.fire({
                    title: "Success!",
                    text:
                        "Image Updated!",
                    type: "success",
                    buttonsStyling: !1,
                    confirmButtonText: "OK",
                    confirmButtonClass:
                        "btn btn-sm btn-bold btn-brand"
                }).then(()=>{
                    window.location.reload()
                })
            }
            $(this).html("SAVE")
            
        },(error)=>{
            console.log(error)
            $(this).html("SAVE")
        })
})

$("#filtrarUsers").on("click",function(){

    filter($("#generalSearch").val())
    
})
$(".removeFilter").on("click",function(){

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

$check1.bootstrapSwitch.defaults.onSwitchChange = function(e, state){
    
    if (e.target.dataset.key != undefined) {
        var data = {
            update_config_user: true,
            value: state,
            key: e.target.dataset.key
        }
    
        chageConfig(data)
    }
    
}

function chageConfig(data){
    console.log(data);

    ajax.peticion("normal", data, "views/ajax/gestorConfig.php")
        .then((res)=>{
            console.log(res);
        }, (error)=>{
            console.log(error);
        })
}


function filter(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorUsuarios.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}