
$("#crearPedido").on("click",function(e){
    e.preventDefault()
    var datos = new FormData()

    var id_cliente = $("#tCliente").val()
    datos.append("id_cliente",id_cliente)
    datos.append("crear", "true")
    $(this).html("...")
    ajax.peticion("FormData", datos,"views/ajax/gestorPedidos.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
                
            } else{
                util.alertSuccess("Realizado", "¡El pedido se agregó correctamente!", "newOrder")

            }

            $(this).html("CREAR")
        },(error)=>{
            console.log(error)
        })

})

$(".searchCode").on("input",function(e){

    var search = $(this).val()
    var agregar = false

    //agregar o quitar del carrito
    var val = this.value;
    if($('#codes').find('option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();        
    }).length) {

        agregar = true
        var option = $('#codes').find('option').filter(function(){
            return this.value.toUpperCase() === val.toUpperCase();        
        })

        var item = {
            id: option.attr("itemId"),
            cantidad: 1
        }
        console.log(option);
        console.log(item);

        $(".searchCode").attr("readOnly","true")

        ajax.peticion("normal",{toogleItem:true, item: item}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)
                $(".searchCode").removeAttr("readOnly")
                $("#codes").html("")
                $(".searchCode").val("")
                
                mostrarTablaPedido(res)
            
            },(fail)=>{
                console.log(fail)
            })
    }
   

    if (search != "" && !agregar) {

        ajax.peticion("normal",{searchCode:true, search: search}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)

                $("#codes").html("")

                for(var i = 0; i < res.length; i++){
                    var item = res[i]

                    $("#codes").append(`
                        <option value="#${item.id}-${item.nombre}" itemId="${item.id}">
                            ${item.nombre} | ${item.codigo}  
                        </option>
                    
                    `)

                }

                $("#codes option").unbind().bind("click",function(e){
                    e.preventDefault()
                    console.log("option selected");
                })
                
            },(fail)=>{
                console.log(fail)
            })
    }


    
})

$(".searchCodeActualizar").on("input",function(e){

    var search = $(this).val()
    var agregar = false

    //agregar o quitar del pedido
    var val = this.value;
    if($('#codes').find('option').filter(function(){
        return this.value.toUpperCase() === val.toUpperCase();        
    }).length) {

        agregar = true
        var option = $('#codes').find('option').filter(function(){
            return this.value.toUpperCase() === val.toUpperCase();        
        })

        var item = {
            id: option.attr("itemId"),
            cantidad: 1,
            id_pedido: $(this).attr("data-id")
        }
        console.log(option);
        console.log(item);

        $(".searchCode").attr("readOnly","true")

        ajax.peticion("normal",{agregarAPedido:true, item: item}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)
                $(".searchCode").removeAttr("readOnly")
                $("#codes").html("")
                $(".searchCode").val("")
                
                mostrarTablaPedidoEditar(res)
            
            },(fail)=>{
                console.log(fail)
            })
    }
   

    if (search != "" && !agregar) {

        
        ajax.peticion("normal",{searchCode:true, search: search}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)

                $("#codes").html("")

                for(var i = 0; i < res.length; i++){
                    var item = res[i]

                    var inventario = (item.inventario >= 1) ? "" : " ---- AGOTADO"

                    $("#codes").append(`
                        <option value="#${item.id}-${item.nombre}${inventario}" itemId="${item.id}">
                            ${item.nombre} | ${item.codigo}  
                        </option>
                    
                    `)

                }

                $("#codes option").unbind().bind("click",function(e){
                    e.preventDefault()
                    console.log("option selected");
                })

                
            },(fail)=>{
                console.log(fail)
            })
    }


    
})

$("#vaciarPedido").on("click",function(e){
    e.preventDefault();
    var data ={
        vaciar: true
    }

    ajax.peticion("normal", data, "views/ajax/gestorPedidos.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
    
})

//no se va a usar
$("#actualizarPedido").on("click",function(e){
    e.preventDefault()
    var datos = new FormData()
    var id = $(this).attr("data-id")
    datos.append("id", "id")
    datos.append("actualizar", "true")
    ajax.peticion("FormData", datos,"views/ajax/gestorPedidos.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
                
            } else{
                util.alertSuccess("Realizado", "¡La cartera se actualizó correctamente!")

            }
        },(error)=>{
            console.log(error)
        })

})

$("#filtrarOrders").on("click",function(){

    filtrarOrders($("#generalSearch").val())
    
})
$(".removeFilterOrders").on("click",function(){

    filtrarOrders("")
    
})

$("#form-exportar-grupo").on("submit",function(e){
    e.preventDefault()

    var data = new FormData(document.getElementById("form-exportar-grupo"))

    data.append("exportarGrupo", true)

    ajax.peticion("FormData",data, "views/ajax/gestorPedidos.php")
        .then((res)=>{
            console.log(res);
            var a = $(".aHidden")

            if (res.status == "false") {
                $(".mensajeExportarGrupo").html(`
                    <div class="alert alert-danger">${res.message}</div>
                `)
            }else{
                $(".mensajeExportarGrupo").html("")
                $(".aHidden").click()
                a[0].click()
            }


        },(fail)=>{
            console.log(fail)
        })  

})

$("#procesarPedido").on("click",function(e){
    e.preventDefault()

    var id = $("#idOrder").val()
    var datos = {
        actualizar_estados: true,
        estado: "1",
        factura: $("#factura").val(),
        lista: [id]
    }
    $(this).html("...")
    console.log(datos);
    ajax.peticion("normal",datos,"views/ajax/gestorPedidos.php")
        .then((res)=>{
            console.log(res)
            if (res.status == "ok") {
                util.alertSuccess("¡Actualizado!", "¡Se ha procesado el pedido!", "orders")
                
            }else{
                util.alertError("Error", res.message)
            }
            $(this).html("Procesar")
        },(error)=>{
            console.log(error)
            $(this).html("Procesar")
        })
})

if ($("#cestaString").length != 0) {
    mostrarTablaPedido(JSON.parse($("#cestaString").val()))
}
if ($("#cestaStringEditar").length != 0) {
    mostrarTablaPedidoEditar(JSON.parse($("#cestaStringEditar").val()))
}

function filtrarOrders(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorPedidos.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}

function mostrarTablaPedido(items){
    

    //bodyTablaPedidos
    var trs = ""
    var totalPedido = 0
    for(var i = 0; i < items.length; i++){
        var item = items[i]
        var opts = ""
        var total = Number(item.cantidad) * Number(item.precio)
        totalPedido += total 
        for(var j = 0; j < item.limite; j++){
            var iter = j + 1
            var selected = (item.cantidad == iter) ? "selected" : ""
            opts += `<option ${selected}>${iter}</option>`
        }

        // <select class="form-control changeStock" data-id='${item.id}'>${opts}</select>

        trs += `
            <tr>
                <td>
                    <span class="deleteFromCart cursor" data-id='${item.id}'>
                        <i class="kt-nav__link-icon flaticon2-delete"></i> 
                    </span>       
                    <a href="detailsProduct_${item.id}" class="" data-id='${item.id}'>${item.nombre}</a>  
                    
                </td>
                <td>${item.precio}</td>
                <td>
                    <span class="kt-badge kt-badge--unified-primary kt-badge--inline kt-badge--bold cursor setStock" data-id='${item.id}' data-c="${item.cantidad}"> stock ( ${item.cantidad} )</span>
                </td>
                <td>${total.toFixed(2)}</td>
            </tr>

        `
    }
    $("#bodyTablaPedidos").html(trs)
    $("#totalPedido").html(totalPedido.toFixed(2))

    $(".changeStock").unbind().bind("change",function(){
        var c = $(this).val()
        var _id = $(this).attr("data-id")
      
        
        var item = {
            id: _id,
            cantidad: c 
        }

        $.ajax({
            url: "views/ajax/gestorPedidos.php",
            type: "POST",
            dataType: "JSON",
            data:{toogleItem:true, item: item, reemplazar: true}
        })
        .done((res)=>{
            console.log(res)
            mostrarTablaPedido(res)
        })
        .fail((fail)=>{
            console.log(fail)
        })

    })

    $(".deleteFromCart").unbind().bind("click",function(){
 
        var _id = $(this).attr("data-id")
        var item = {
            id: _id,
            cantidad: 1
        }

        ajax.peticion("normal",{toogleItem:true, item: item}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)
                

                mostrarTablaPedido(res)
            
            },(fail)=>{
                console.log(fail)
            })
    })

    $(".setStock").unbind().bind("click",function(){
 
        var _id = $(this).attr("data-id")
        var c = $(this).attr("data-c")
        ajax.peticion("normal",{peticiones:true, id_producto: _id}, "views/ajax/gestorUsuarios.php")
            .then((res)=>{

                console.log(res)
                if (res.status) {
                    util.alertError("Error", res.message)
                }else{
                    $("#realizarPeticionStock").attr("data-id", res.producto.id)
                    $("#modalSetStock").modal("show")
                    var peticiones = res.peticiones
                    var producto = res.producto
                    var str_peticiones = ""
                    var limitStock = producto.limite_compra
                    var peticionesUsar = []
                    
                    for(var i = 0; i < peticiones.length; i++){
                        var peticion = peticiones[i]
                        str_peticiones += `
                            <div class='kt-notification-v2__item'>
                                <div class='kt-notification-v2__item-icon'>
                                    <label class='kt-checkbox kt-checkbox--brand'>
                                        <input type='checkbox' data-id='${peticion.id}' data-stock='${peticion.num_stock}' class='usePeticion' data-v='false'>
                                        <span class='center-checkbox'></span>
                                    </label>
                                </div>
                                <div class='kt-notification-v2__itek-wrapper text-left'>
                                    <div class='kt-notification-v2__item-title'>
                                        #${peticion.id} Stock: ${peticion.num_stock}
                                    </div>
                                </div>
                            </div>
                        
                        `
                    }

                    $(".body__").html(`
                        <p>Peticiones aceptadas (${peticiones.length}):</p>
                        <p class="text-center text-muted-2 mb-0">
                            ¡Una vez que utilices una petición de stock no podrás usarla nuevamente!
                        </p>

                        <div class="kt-notification-v2">
                            ${str_peticiones}
                        </div>
                        <div class="divider"></div>
                        <div>
                            <div class="row">
                                <div class="col-md-6">
                                    Inventario: 
                                    <span class="kt-badge kt-badge--unified-primary kt-badge--inline kt-badge--bold">${producto.inventario}</span>
                                </div>
                                <div class="col-md-6">
                                    Límite de compra: 
                                    <span class="kt-badge kt-badge--unified-primary kt-badge--inline kt-badge--bold stockLimite">${limitStock}</span>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 mt-1">
                                    <input type="text" id="stockPedido_" class="form-control" placeholder="Cantidad del producto" value="${c}">
                                </div>
                                <div class="col-md-6 mt-1">
                                    <button  class="btn btn-label-brand btn-bold" id="guardarStockPedido" data-id-p="${producto.id}">
                                        guardar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `)


                    $(".usePeticion").unbind().bind("click",function(e){

                        console.log($(this).attr("data-v"));
                        var val = ($(this).attr("data-v") == "false") ? true : false
                 
                        $(this).attr("data-v", val)              
                        var id_peticion = $(this).attr("data-id")
                        var stock = $(this).attr("data-stock")

                        var data = {
                            valor: val,
                            id_peticion: id_peticion,
                            stock: stock
                        }

                        if (val) {
                            limitStock = Number(limitStock) + Number(stock)
                            peticionesUsar.push(data)
                        }else{
                            peticionesUsar = peticionesUsar.filter((item) => item.id_peticion != id_peticion)
                            limitStock = Number(limitStock) - Number(stock)
                        }

                        $(".stockLimite").html(limitStock)
                        console.log(peticionesUsar);

                    })

                    $("#guardarStockPedido").unbind().bind("click",function(e){

                        
                        var cantidad = $("#stockPedido_").val()
                        var id_producto = $(this).attr("data-id-p")

                        var item = {
                            id: id_producto,
                            cantidad: cantidad 
                        }

                        var data = {
                            toogleItem:true, 
                            item: item, 
                            reemplazar: true,
                            peticionesUsar: peticionesUsar
                        }
                

                        $.ajax({
                            url: "views/ajax/gestorPedidos.php",
                            type: "POST",
                            dataType: "JSON",
                            data: data
                        })
                        .done((res)=>{
                            console.log(res)
                            if (res.status == false) {
                                util.alertError("Error", res.message)
                            }else{
                                util.alertSuccess("Realizado", "")
                                mostrarTablaPedido(res)
                            }
                            
                        })
                        .fail((fail)=>{
                            console.log(fail)
                        })

                    })


                }
               
                    
            },(fail)=>{
                console.log(fail)
            })
    })
    $("#realizarPeticionStock").unbind().bind("click",function(){
 
        var _id = $(this).attr("data-id")
        var num = $("#pStock").val()
        $(this).html("...")
        ajax.peticion("normal",{crear_peticion:true, id_producto: _id, num_stock: num}, "views/ajax/gestorUsuarios.php")
            .then((res)=>{

                console.log(res)
                if (res.status == false) {
                    util.alertError("Error", res.message)
                }else{
                    // $("#modalSetStock").modal("show")
                    util.alertSuccess("Realizado", "Se ha mandado la petición, por favor espere la notificación de confirmación.")
                }
                $(this).html("Realizar")
                    
            },(fail)=>{
                console.log(fail)
                $(this).html("Realizar")
            })
    })


}

function mostrarTablaPedidoEditar(items){
    

    //bodyTablaPedidos
    var trs = ""
    var totalPedido = 0
    for(var i = 0; i < items.length; i++){
        var item = items[i]
        console.log(item);
        var opts = ""
        var total = Number(item.cantidad) * Number(item.precio)
        totalPedido += total 
      

        trs += `
            <tr>
                <td>
                    <span class="deleteFromCart cursor" data-id='${item.id}'>
                        <i class="kt-nav__link-icon flaticon2-delete"></i> 
                    </span>                    
                    <a href="detailsProduct_${item.id_producto}">${item.nombre}</a>
                </td>
                <td>${item.precio}</td>
                <td>
                    <input type="text" class="form-control changeStock mw-60" data-id="${item.id}" value="${item.cantidad}" /> 
                </td>
                <td>${total.toFixed(2)}</td>
            </tr>

        `
    }
    $("#bodyTablaPedidos").html(trs)
    $("#totalPedido").html(totalPedido.toFixed(2))

    $(".changeStock").unbind().bind("blur",function(){
        var c = $(this).val()
        var _id = $(this).attr("data-id")
        var item = {
            id: _id,
            cantidad: c,
            id_pedido: $(".searchCodeActualizar").attr("data-id")
        }

        console.log(item);
        ajax.peticion("normal",{cambiarStockItemPedido:true, item: item}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)
                
                mostrarTablaPedidoEditar(res)
            
            },(fail)=>{
                console.log(fail)
            })
    })

    $(".deleteFromCart").unbind().bind("click",function(){
 
        var _id = $(this).attr("data-id")

        ajax.peticion("normal",{deleteFromCartAct:true, id: _id}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)            
                mostrarTablaPedidoEditar(res)
            
            },(fail)=>{
                console.log(fail)
            })
    })

    $("#tCliente").unbind().bind("change",function(){
 
        var _id = $(this).val()
        var _id_pedido = $(this).attr("data-id")

        if (_id != '0') {
            ajax.peticion("normal",{cambiarClientePedido:true, id: _id, id_pedido: _id_pedido}, "views/ajax/gestorPedidos.php")
            .then((res)=>{

                console.log(res)            
                if (res.status == true) {
                    util.alertSuccess("Realizado", res.message)
                }else{
                    util.alertError("Error", res.message)
                }
                    
            },(fail)=>{
                console.log(fail)
            })
        }else{
            util.alertError("Error", "Selecciona un cliente")
        }

        
    })
    
}