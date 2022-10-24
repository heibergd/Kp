var $check1 = $("[d-check=true]").bootstrapSwitch();
var reload_w = true


//mostrar modal 
$("#addBuilder").on("click",function(){
    $("#new_builder").modal("show")
})

$("#createBuilder").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formNewBuilder"))

    // console.log(datos)
    datos.append("crear", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorConstructora.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
            } else{                
                util.alertSuccess("Success", "Created!", false, true)
            }
            $(this).html("CREATE")
        },(error)=>{
            $(this).html("CREATE")
            console.log(error)
        })

})

$("#updateBuilder").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formEditBuilder"))

    // console.log(datos)
    datos.append("update", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorConstructora.php")
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


$("#filtrarConstructoras").on("click",function(){

    filter_constructora($("#generalSearch").val())
    
})
$(".removeFilterConstructora").on("click",function(){

    filter_constructora("")
    
})

function filter_constructora(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorConstructora.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}