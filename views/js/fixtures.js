var $check1 = $("[d-check=true]").bootstrapSwitch();
var reload_w = true


//mostrar modal 
$("#addFixture").on("click",function(){
    $("#new_fixture").modal("show")
})

$("#createFixture").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formNewFixture"))

    // console.log(datos)
    datos.append("crear", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorFixture.php")
        .then((res)=>{
            console.log(res)  
            if (res.status) {
                util.alertSuccess("Success", "Created!", false, true)
            } else{                
                util.alertError("Error", res.message)
            }
            $(this).html("CREATE")
        },(error)=>{
            $(this).html("CREATE")
            console.log(error)
        })

})

$("#updateFixture").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formEditFixture"))

    // console.log(datos)
    datos.append("update", "true")
    $(this).html("Loading...")

    ajax.peticion("FormData", datos,"views/ajax/gestorFixture.php")
        .then((res)=>{
            console.log(res)  
            if (res.status) {
                util.alertSuccess("Success", "Updated!", false, true)
            } else{            
                util.alertError("Error", res.message)
            }
            $(this).html("UPDATE")
        },(error)=>{
            console.log(error)
            $(this).html("UPDATE")
        })

})


$("#filtrarFixtures").on("click",function(e){
    e.preventDefault()

    filter_fixture($("#generalSearch").val())
    
})
$(".removeFilterFixture").on("click",function(){

    filter_fixture("")
    
})

function filter_fixture(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorFixture.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}