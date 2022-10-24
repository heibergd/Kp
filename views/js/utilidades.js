

class Utilidad{

	alertSuccess(title,mensaje, direccion, reload){
		
		swal.fire({
			title: title,
			text: mensaje,
			type: "success",
			buttonsStyling: !1,
			confirmButtonText: "OK",
			confirmButtonClass:
				"btn btn-sm btn-bold btn-brand"
		}).then(()=>{
			if (direccion) {
				window.location.href = direccion 
			}
			if (reload) {
				window.location.reload()
			}
			
		})

		setTimeout(()=>{
			swal.close()
		},3000)
	}

	alertError(title,mensaje){
		swal.fire({
			title: title,
			text: mensaje,
			type: "error",
			buttonsStyling: !1,
			confirmButtonText: "OK",
			confirmButtonClass:
				"btn btn-sm btn-bold btn-brand"
		});

		setTimeout(()=>{
			swal.close()
		},3000)
	}
	alertWarning(mensaje, direccion){
		swal({
			type: 'warning',
            title: "¡ Atención !",
            text: `¡ ${mensaje} !`
            
        })
        .then((confirm) => {
			if (direccion) {
        		if (confirm) {
					window.location = direccion
				}else{
					window.location = direccion
				}
        	}
		}) 
	}

	alertConfirm(q){
		return new Promise((resolve, reject)=>{

			swal.fire({
				buttonsStyling: !1,
				text: q,
				type: "info",
				confirmButtonText: "Yes!",
				confirmButtonClass:
					"btn btn-sm btn-bold btn-danger",
				showCancelButton: !0,
				cancelButtonText: "No",
				cancelButtonClass:
					"btn btn-sm btn-bold btn-brand"
			})
			.then(function(t) {
				if (t.value) {
					resolve()
					
				}else{
					reject()
				}
			});
        	
    	})
			
	}

	alertDanger(mensaje){

		var a = `
			
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			  	${mensaje}
			</div>
		`

		return a
	}
	alertInfo(title,mensaje, direccion, reload){

		swal.fire({
			title: title,
			text: mensaje,
			type: "info",
			buttonsStyling: !1,
			confirmButtonText: "OK",
			confirmButtonClass:
				"btn btn-sm btn-bold btn-brand"
		}).then(()=>{
			if (direccion) {
				window.location.href = direccion 
			}
			if (reload) {
				window.location.reload()
			}
			
		})

		setTimeout(()=>{
			swal.close()
		},3000)
	}
	alertTextSuccess(mensaje){

		var a = `
			
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			  	${mensaje}
			  	<span id='numAlertS'></span>
			</div>
		`

		return a
	}

	C(mensaje){
		console.log(mensaje)
	}
	c(mensaje){
		console.log(mensaje)
	}

	barraDeProgreso(id){
		var bar = new ProgressBar.Line(id, {
		  	strokeWidth: 4,
		  	easing: 'easeInOut',
		  	duration: 1400,
		  	color: '#15c',
		  	trailColor: '#eee',
		  	trailWidth: 1,
		  	svgStyle: {width: '100%', height: '100%'},
		  	text: {
		    	style: {
		      		// Text color.
		      		// Default: same as stroke color (options.color)
		      		color: '#999',
		      		position: 'absolute',
		      		right: '0',
		      		top: '20px',
		      		padding: 0,
		      		margin: 0,
		      		transform: null
		   	 	},
		    	autoStyleContainer: false
		  	},
		  	from: {color: '#15c'},
		  	to: {color: '#15c'},
		  	step: (state, bar) => {
		    	bar.setText(Math.round(bar.value() * 100) + ' %');
		  	}
		});

		return bar
	}

	obtenerMesEnEsp(mes){

		var back = "";

		switch(mes){

			case "January":
				back = "Enero";
				break;	
			case "February":
				back = "Febrero";
				break;	
			case "March":
				back = "Marzo";
				break;	
			case "April":
				back = "Abril";
				break;	
			case "May":
				back = "Mayo";
				break;	
			case "June":
				back = "Julio";
				break;	
			case "July":
				back = "Julio";
				break;	
			case "August":
				back = "Agosto";
				break;	
			case "September":
				back = "Septiembre";
				break;	
			case "October":
				back = "Octubre";
				break;	
			case "November":
				back = "Noviembre";
				break;	
			case "December":
				back = "Diciembre";
				break;
			default:

				back = "no se puede procesar el mes";	
		}

		
		return back

	}
}

let ObjetoCarrito = [];
let OptionsHoldOn = {
	theme: "sk-bounce",
	message: 'Procesando...',
	textColor: "white"
}
let VLogin = $("#verificarLogin").html();
var U = new Utilidad()
var util = new Utilidad()
let c = util.c