

var linkUrl = $(".linkUrl").html()

$(".linksGroup").removeClass("active")

if (linkUrl == "" || linkUrl == "index") {
	$(".linkImportacion").addClass("active")

	var links = $(".linkImportacion")

	for(var i = 0; i < links.length; i++){
		var parent = links[i].getAttribute("parent")
		$(`[id-parent='${parent}']`).addClass('active')
	}

}else{
	$(".link"+linkUrl).addClass("active")

	var links = $(".link"+linkUrl)

	for(var i = 0; i < links.length; i++){
		var parent = links[i].getAttribute("parent")
		$(`[id-parent='${parent}']`).addClass('active')
	}

		
}


var datos = {
	verNumLinks: true
}

ajax.peticion("normal",datos, rutaAjaxProductos)
	.then((data)=>{
		// console.log(data)
		var importList = data.importList
		var imported = data.imported
		var orders = data.orders

		$(".tagImportacion").html(`
			<span class='tag'><b>${importList}</b></span>
		`)
		$(".tagImportado").html(`
			<span class='tag'><b>${imported}</b></span>
		`)
		$(".tagPedidos").html(`
			<span class='tag'><b>${orders}</b></span>
		`)

	}, (fail)=>{
		c("fallo")
		c(fail)
	})


var lastScroll = 0;

$(window).on("scroll",function(){
	if ($(window).scrollTop() > 50) {
		$(".main_wallper").removeClass("header-show")
	}

	if ($(window).scrollTop() < lastScroll) {
		$(".main_wallper").addClass("header-show")
	}
	lastScroll = $(window).scrollTop()
})

$(".header__button").on("click",function(){
	$(".side-nav").css('transform', 'translateX(0)');

	if ($(".side-nav").hasClass("side-nav-show")) {
		$(".side-nav").removeClass("side-nav-show")
	}else{
		$(".side-nav").addClass("side-nav-show")
	}

	$(".cubierta").show()
})

$(".cubierta").on("click",function(){

	$(".side-nav").css('transform', 'translateX(-100%)');
	$(".side-nav").removeClass("side-nav-show")

	$(this).hide()
})

$(".button_nav").on("click",function(){

	var submenu = $(this).siblings().children()
	
	if (submenu.hasClass('submenu_show')) {
		submenu.removeClass('submenu_show')
	}else{
		submenu.addClass('submenu_show')
	}
})



$(".head-tabs").on("scroll",function(e){

	var scrollnow = $(this).scrollLeft()
	var wid = $(this).width()

	var scrollWidth = $(this)[0].scrollWidth

	var scrollingAll = wid + scrollnow

	if (scrollingAll == scrollWidth) {
		
		$(this).siblings('div').hide()
	}else{
		$(this).siblings('div').show()
	}

})


if (linkUrl == "listaImportacion") {
	$(window).on("resize",function(e){
		if ($(".head-tabs").length != 0) {

			if ($(".head-tabs")[0].clientWidth < 875) {
				$(".sh").show()
			}else{
				$(".sh").hide()
			}
		}
	})

	if ($(".head-tabs").length != 0) {
		if ($(".head-tabs")[0].clientWidth < 875) {
			$(".sh").show()
		}else{
			$(".sh").hide()
		}
	}
	
}


	
