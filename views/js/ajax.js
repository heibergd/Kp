
class Ajax {

	constructor(){
		var progreso = false
		this.options = {
			type: "POST",
			dataType: "JSON"
		}
	}

	peticion(tipo,data,url,opciones){

		if (opciones == false) {
			opciones = undefined
		}
		if (tipo == "formData" || tipo == "FormData" || tipo == 1) {
			// console.log(url)
			this.options.url = url;
			this.options.type = (opciones != undefined) ? opciones.type : "POST";
			this.options.dataType = (opciones != undefined) ? opciones.dataType : "JSON";
			this.options.data = data;
			this.options.cache = false;
			this.options.contentType = false;
			this.options.processData = false;
			this.options.xhr = ()=>{

				var myXhr = $.ajaxSettings.xhr();
				myXhr.upload.addEventListener('progress',(prog) =>{

					var value = ~~((prog.loaded / prog.total) * 100);
					if (this.progreso) {
						this.progreso(value / 100)
					}
					
				}, false);
			
				return myXhr;
			}

		}else if (tipo == "normal" || tipo == 0) {

			//debugger;
			this.options.url = url
			this.options.type = (opciones != undefined) ? opciones.type : "POST";
			this.options.dataType = (opciones != undefined) ? opciones.dataType : "JSON";
			this.options.data = data
			this.options.xhr = ()=>{

				var myXhr = $.ajaxSettings.xhr();
				myXhr.upload.addEventListener('progress',(prog) =>{

					var value = ~~((prog.loaded / prog.total) * 100);
					if (this.progreso) {
						this.progreso(value / 100)
					}
					
				}, false);
			
				return myXhr;
			}
		}

		return new Promise((resolve, reject)=>{
        	$.ajax(this.options).done(resolve).fail(reject)
    	})
	}

	mostrarProgreso(callback){
		
		this.progreso = callback
			
	}

}

let ajax = new Ajax()
