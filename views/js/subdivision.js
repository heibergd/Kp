
$(".addSubdivision").on("click",function(e){
    e.preventDefault()

    $.ajax({
        url: "views/ajax/gestorConfig.php",
        type: "POST",
        dataType: "JSON",
        data: {access: true}
    })
    .done((res)=>{
        console.log(res);
        if (res.newSubdivision != undefined) {
            if (using != "new") {
                mapa = undefined
                markerDireccion = undefined    
                using = "new"
            }
            
            $("#m_new_subdivision").modal("show")
        
            mapa_sub("mapa_add_sub", "latlng")
        }else{
            util.alertError("Error", "You don't have access to this action")
        }
    })
    .fail((error)=>{
        console.log(error);
    })

    
})

$("#crearSubdivision").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formNewSubdivision"))

    datos.append("crear", "true")

    $(this).html("Loading...")
    ajax.peticion("FormData", datos,"views/ajax/gestorSubdivision.php")
        .then((res)=>{
            console.log(res)  
            if (res.status == "error") {
                util.alertError("Error", res.mensaje)
                
            } else{
                util.alertSuccess("Success", "Added!", false, true)

            }
            $(this).html("CREATE")
        },(error)=>{
            console.log(error)
            $(this).html("CREATE")
        })

})

$("#updateSubdivision").on("click",function(e){
    e.preventDefault()
    var datos = new FormData(document.getElementById("formEditSubdivision"))
    datos.append("actualizar", "true")
    $(this).html("Loading...")
    ajax.peticion("FormData", datos,"views/ajax/gestorSubdivision.php")
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

$("#filtrarSubs").on("click",function(){

    filtrarSubs($("#generalSearch").val())
    
})
$(".removeFilterSubs").on("click",function(){

    filtrarSubs("")
    
})

var mapa
let markerDireccion
let using 

function mapa_sub(id_map, id){
  
    console.log("edit");

    var p__ = {
        coords: {
            latitude: "10.6222200",
            longitude: "-66.5735300"
        }
    }
   
    showPosition(p__)
	

	function showPosition(position) {
        
        console.log("POSITON");

        var __tkoen = "pk.eyJ1IjoiamVzdXNtYXAxOSIsImEiOiJjazZ0cHZqeHQwMG5uM2xteXRnOXJjcm01In0.81SWLAQS_tTVT4YzbsWRRQ"

        var coors = $("#"+id).val()
        var latitude
        var longitude
        if (coors != "") {

            coors = coors.split(",")
            console.log(coors);
            latitude = coors[0]
            longitude = coors[1]
        }else{
            latitude = position.coords.latitude
            longitude = position.coords.longitude
        }

        if (mapa == undefined) {
            console.log("CREANDO MAPA");
            mapa = L.map(id_map).setView([latitude, longitude], 7);
            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mapa);
        }
         
        if (coors != "") {
            if (markerDireccion != undefined) {
                mapa.removeLayer(markerDireccion)
            }
    
            markerDireccion = L.marker(coors);
    
            mapa.addLayer(markerDireccion) 
            $("#"+id).val(latitude + "," + longitude)

            mapa.setView([latitude, longitude],8)
        }

    
        document.getElementById(id_map).style.display = 'block';
        
        setTimeout(function() {
            mapa.invalidateSize();
        }, 1500);
        
        mapa.on("click",function(e){
            console.log("click sobre el mapa");
            console.log(e);

            if (markerDireccion != undefined) {
                mapa.removeLayer(markerDireccion)
            }

            markerDireccion = L.marker(e.latlng);

            mapa.addLayer(markerDireccion) 
            $("#"+id).val(e.latlng.lat + "," + e.latlng.lng)
          
        })
	
        
        $(".findInMap").on("click",function(){

            var d = $("#direccion").val()
            console.log(d);
            $.ajax({
                url: `https://api.mapbox.com/geocoding/v5/mapbox.places/${d}.json?access_token=${__tkoen}`,
                type: "GET",
                dataType: "JSON"
            })
            .done((res)=>{
                console.log(res);
            
                if (markerDireccion != undefined) {
                    mapa.removeLayer(markerDireccion)
                }

                if (res.features) {
                    var coors = [
                        res.features[0].geometry.coordinates[1],
                        res.features[0].geometry.coordinates[0] 
                    ]
                    markerDireccion = L.marker(coors);
                    mapa.addLayer(markerDireccion) 

                    mapa.setView(coors,15)

                    $("#latlng").val(res.features[0].geometry.coordinates[1] + "," + res.features[0].geometry.coordinates[0] )
                }
                

                
                
            })
            .fail((error) =>{
                console.log(error);
            })
        })
    }
    
}

function filtrarSubs(val){
    var data ={
        filtrar: true,
        search: val
    }

    ajax.peticion("normal", data, "views/ajax/gestorSubdivision.php")
        .then((res)=>{
            console.log(res)
            window.location.reload()            
        },(error)=>{
            console.log(error)
        })
}

   

$( document ).ready(function() {
    

$.ajax({
  url: 'views/ajax/gestorConstructora.php',
  type: 'POST',
  async: true,
  data: 'listar',
  dataType : 'json',
  success: function(abuilder){
    console.log(abuilder);
    if (abuilder != undefined) {
    for(var i = 0; i < abuilder.length; i++){
        $('.select_2_b').append(`<option value='${abuilder[i].id}'>${abuilder[i].nombre}</option>`);
    }
                
}
  },
  error: function(xhr, status) {
        console.log('problema al cargar constructoras');
    },
});


});

