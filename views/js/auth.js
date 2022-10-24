

$("#registrarse").on("click",function(e){
    e.preventDefault()


    var datos = new FormData(document.getElementById("form-registro"))

    datos.append("nuevo_registro", true)
    $(".message").html(`
        <div class="alert alert-primary">
            Procesando registro...
        </div> 
    `)

    ajax.peticion("FormData",datos, "views/ajax/auth.php")
        .then((res)=>{

            $(".message").html("")

            if (!res.status) {
                $(".message").html(`
                    <div class="alert alert-danger">
                        ${res.message}
                    </div> 
                `)
            }else{
                $(".message").html(`
                    <div class="alert alert-success">
                       Registrado con éxito
                    </div> 
                `)
            }

            setTimeout(()=>{
                $(".message").html("")
            },3000)
            
            console.log(res)
        },(fail)=>{
            console.log(fail)
        })

})

$("#actualizarInfo").on("click",function(e){
    e.preventDefault()


    var datos = new FormData(document.getElementById("actualizarInfo-form"))

    datos.append("actualizar_info", true)
    $(".message").html(`
        <span class="text-primary">
            Loading...
        </span> 
    `)

    ajax.peticion("FormData",datos, "views/ajax/auth.php")
        .then((res)=>{

            $(".message").html("")

            if (!res.status) {
                $(".message").html(`
                    <span class="text-danger">
                        ${res.message}
                    </span> 
                `)
            }else{
                $(".message").html(`
                    <span class="text-success">
                       Updated!
                    </span> 
                `)
            }

            setTimeout(()=>{
                $(".message").html("")
            },3000)
            
            console.log(res)
        },(fail)=>{
            console.log(fail)
        })

})

$("#updateP").on("click",function(e){
    e.preventDefault()
    var val = $("#updatePass").val()

    if (val == "false") {
        $("#updatePass").val("true")
        $(".contentPassword").removeClass("hidden")
    }else{
        $("#updatePass").val("false")
        $("#password").val("")
        $(".contentPassword").addClass("hidden")
    }
})

$("#actualizarPassword").on("click",function(e){
    e.preventDefault()


    var datos = new FormData(document.getElementById("cambiarPassword-form"))

    datos.append("actualizar_password", true)
    $(".message").html(`
        <div class="alert alert-primary">
            Procesando...
        </div> 
    `)

    ajax.peticion("FormData",datos, "views/ajax/auth.php")
        .then((res)=>{

            $(".message").html("")

            if (!res.status) {
                $(".message").html(`
                    <div class="alert alert-danger">
                        ${res.message}
                    </div> 
                `)
            }else{
                $(".message").html(`
                    <div class="alert alert-success">
                       Contraseña actualizada
                    </div> 
                `)
            }

            setTimeout(()=>{
                $(".message").html("")
            },3000)
            
            console.log(res)
        },(fail)=>{
            console.log(fail)
        })

})


$("#login").on("click",function(e){
    e.preventDefault()


    var datos = new FormData(document.getElementById("form-login"))

    datos.append("inicio_sesion", true)

    $(".message-login").html(`
        <div class="text-primary">
            Loading...
        </div> 
    `)

    ajax.peticion("FormData",datos, "views/ajax/auth.php")
        .then((res)=>{

            $(".message-login").html("")

            if (!res.status) {
                $(".message-login").html(`
                    <div class="text-danger">
                        ${res.message}
                    </div> 
                `)
            }else{
                console.log(res)
                window.location.href = "index"
            }

            setTimeout(()=>{
                $(".message-login").html("")
            },3000)
            
            console.log(res)
        },(fail)=>{
            console.log(fail)
        })

})