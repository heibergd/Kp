
 

bind_()

var id_u = $("#user_id_global").val()
var tag_on_signal = $("#tag_on_signal").val()
var audio = new Audio("views/audio/notificacion.mp3")


console.log(id_u);


OneSignal.push(function() {
    /* These examples are all valid */
    var tag_user = `user_${id_u}`

    if (id_u != undefined) {
        var isPushSupported = OneSignal.isPushNotificationsSupported();
        if (isPushSupported) {

            OneSignal.isPushNotificationsEnabled(function(isEnabled) {
                console.log(isEnabled);
                if (isEnabled){
                    console.log("Push notifications are enabled!");

                    OneSignal.getTags(function(tags) {
                        console.log(tags);

                        if(tags.tag_n == undefined){
                            OneSignal.sendTag("tag_n", tag_user, function(tagsSent) {
                                guargarTag()
                            });	
                        }else if (tags.tag_n != tag_user) { 
                            OneSignal.sendTag("tag_n", tag_user, function(tagsSent) {
                                guargarTag()
                            });	                        
                        }
                        
                    });


                }else{
                    console.log("Push notifications are not enabled yet. :("); 
            
                    OneSignal.showSlidedownPrompt().then(() => {
                        
                        OneSignal.sendTag("tag_n", tag_user, function(tagsSent) {
                            guargarTag()
                        });	 

                    }).catch((err) => {
                        console.log(err);
                    });
                   
                }
            }).catch((err) => {
                console.log(err);
            });
            
        } else {
            // Push notifications are not supported
            console.log("No Soportado");
        }

        OneSignal.on('notificationDisplay', function(event) {        
            audio.play();
        
            console.log(event);
            var data = event.data

            var n1 = ($(".b-notific").html() != undefined) ? Number($(".b-notific").html()) : 0
     
            n1 = n1 + 1
            $(".b-notific").html(n1)
            $(".n_badge").html(`${n1} Nuevas`)

            if (n1 == 1) {

                $(".b-notific-container").html(`
                    <span class="badge badge-primary b-notific">${n1}</span>
                `)

                $(".n_badge_container").html(`
                    <span class="btn btn-success btn-sm btn-bold btn-font-md n_badge">${n1} Nuevas</span>
                `)

                $(".sinRevisar .kt-notification").html(`
                    <a href="#" class="kt-notification__item notificacion" 
                        data-link="${data.link_short}"
                        data-id="${data.id}">
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title">
                                ${data.mensaje}
                            </div>
                            <div class="kt-notification__item-time">
                                ${data.fecha}

                                <span class="spinn_n_${data.id}"></span>
                            </div>
                        </div>
                    </a>
                
                `)
            }else{
                $(".sinRevisar .kt-notification").prepend(`
                    <a href="#" class="kt-notification__item notificacion" 
                        data-link="${data.link_short}"
                        data-id="${data.id}">
                        <div class="kt-notification__item-details">
                            <div class="kt-notification__item-title">
                                ${data.mensaje}
                            </div>
                            <div class="kt-notification__item-time">
                                ${data.fecha}
                            </div>
                        </div>
                    </a>
                
                `)
            }

            

            
            bind_()
        });

    }

    

});


$(".mobile-noty").on("click",function(e){
    $(".Npty").removeClass("hidden")
    $("._body_").addClass("overflow-hidden")
})
$(".close-noty").on("click",function(e){
    $(".Npty").addClass("hidden")
    $("._body_").removeClass("overflow-hidden")
})

$(".borrarRevisadas").on("click",function(e){
    e.preventDefault()

    util.alertConfirm("¿Quieres borrar todas las notificaciones revisadas?")
        .then((res)=>{
            console.log("ok");

            $.ajax({
                url: "views/ajax/gestorNotificaciones.php",
                type: "POST",
                dataType: "JSON",
                data: {
                    borrar_revisadas: true
                }
            })
            .done((res)=>{
                console.log(res);
                if (res.status == "ok") {
                    $(".nt-revisadas").html(`
                    <div class="kt-grid kt-grid--ver" style="min-height: 200px;">
                        <div class="kt-grid kt-grid--hor kt-grid__item kt-grid__item--fluid kt-grid__item--middle">
                            <div class="kt-grid__item kt-grid__item--middle kt-align-center">
                                
                                <br>No hay notificaciones.
                            </div>
                        </div>
                    </div>
                    
                    `)
                }
            })
            .fail((error)=>{
                console.log(error);
            })

        },()=> console.log("no"))
})

function bind_(){
    $(".notificacion").unbind().bind("click",function(e){
        e.preventDefault()
    
        var link = $(this).attr("data-link")
        var id = $(this).attr("data-id")
    
        $(`.spinn_n_${id}`).html(`
            <div class="spinner-grow spinner-grow-sm text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        `)

        $.ajax({
            url: "views/ajax/gestorNotificaciones.php",
            type: "POST",
            dataType: "JSON",
            data: {
                notificacion_vista: true,
                id: id
            }
        })
        .done((res)=>{
            console.log(res);
            $(`.spinn_n_${id}`).html("")
            if (res.status == "ok") {
                window.location.href = link
            }
        })
        .fail((error)=>{
            console.log(error);
        })
    
    })
}


function guargarTag(){

    console.log("guardando tag");
    $.ajax({
        url: "views/ajax/gestorNotificaciones.php",
        type: "POST",
        dataType: "JSON",
        data: {
            guardar_tag: true
        }
    })
    .done((res)=>{
        console.log(res);
    })
    .fail((error)=>{
        console.log(error);
    })


}