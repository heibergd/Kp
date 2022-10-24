"use strict";

$.ajax({
    url:"views/ajax/jsonZones.php",
    type: "POST",
    dataType: "JSON"
})
.done((res)=>{
    console.log(res);
})
.fail((error)=>{
    console.log(error);
})

var KTUserListDatatable = (function() {
    var t;
    return {
        init: function() {
            (t = $("#kt_apps_zones_list_datatable").KTDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "views/ajax/jsonZones.php"
                        }
                    },
                    pageSize: 10,
                    serverPaging: !1,
                    serverFiltering: !0,
                    serverSorting: !0
                },
                layout: { scroll: !1, footer: !1 },
                sortable: !0,
                pagination: !0,
                search: {  },
                columns: [
                    {
                        field: "id",
                        title: "#",
                        sortable: !1,
                        width: 20,
                        selector: { class: "kt-checkbox--solid" },
                        textAlign: "center"
                    },
                    {
                        field: "nombre",
                        title: "Nombre",
                        width: 112,
                        template: function(t, e) {
                            for (var a = 4 + e; a > 12; ) a -= 3;

                            return `
                                <div class="kt-user-card-v2">  
                                    <div class="kt-user-card-v2__details">
                                        <a href="#" class="kt-user-card-v2__name editarZona" data-id="${t.id}" data-nombre="${t.nombre}">
                                            ${t.nombre}
                                        </a>
                                        <span class="kt-user-card-v2__desc"></span>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        field: "Actions",
                        width: 80,
                        title: "Actions",
                        sortable: !1,
                        autoHide: !1,
                        overflow: "visible",
                        template: function(t) {
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                    <i class="flaticon-more-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link editarZona" data-id="${t.id}" data-nombre="${t.nombre}"> 
                                                <i class="kt-nav__link-icon flaticon2-contract"></i>
                                                <span class="kt-nav__link-text">Editar</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link borrarZona" data-id="${t.id}">
                                                <i class="kt-nav__link-icon flaticon2-trash"></i>
                                                <span class="kt-nav__link-text">Borrar</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            `;
                        }
                    }
                ]
            })),
                $("#kt_form_status").on("change", function() {
                    t.search(
                        $(this)
                            .val()
                            .toLowerCase(),
                        "Status"
                    );
                }),
                t.on(
                    "kt-datatable--on-check kt-datatable--on-uncheck kt-datatable--on-layout-updated",
                    function(e) {
                        var a = t.rows(".kt-datatable__row--active").nodes()
                            .length;
                        var x = t.rows(".kt-datatable__row").nodes().length
                        $("#kt_subheader_total").html(x + " Total")
                        $("#kt_subheader_group_selected_rows").html(a),
                            a > 0
                                ? ($("#kt_subheader_search").addClass(
                                      "kt-hidden"
                                  ),
                                  $("#kt_subheader_group_actions").removeClass(
                                      "kt-hidden"
                                  ))
                                : ($("#kt_subheader_search").removeClass(
                                      "kt-hidden"
                                  ),
                                  $("#kt_subheader_group_actions").addClass(
                                      "kt-hidden"
                                  ));
                    }
                ),
                $("#kt_datatable_records_fetch_modal")
                    .on("show.bs.modal", function(e) {
                        var a = new KTDialog({
                            type: "loader",
                            placement: "top center",
                            message: "Loading ..."
                        });
                        a.show(),
                            setTimeout(function() {
                                a.hide();
                            }, 1e3);
                        for (
                            var n = t
                                    .rows(".kt-datatable__row--active")
                                    .nodes()
                                    .find(
                                        '.kt-checkbox--single > [type="checkbox"]'
                                    )
                                    .map(function(t, e) {
                                        return $(e).val();
                                    }),
                                s = document.createDocumentFragment(),
                                l = 0;
                            l < n.length;
                            l++
                        ) {
                            var i = document.createElement("li");
                            i.setAttribute("data-id", n[l]),
                                (i.innerHTML = "Selected record ID: " + n[l]),
                                s.appendChild(i);
                        }
                        $(e.target)
                            .find("#kt_apps_user_fetch_records_selected")
                            .append(s);
                    })
                    .on("hide.bs.modal", function(t) {
                        $(t.target)
                            .find("#kt_apps_user_fetch_records_selected")
                            .empty();
                    }),

                // actualizar estado - sin uso
                $("#kt_subheader_group_actions_status_change").on(
                    "click",
                    "[data-toggle='status-change']",
                    function() {
                        var e = $(this)
                                .find(".kt-nav__link-text")
                                .attr("data-change")
                        var eType = $(this)
                            .find(".kt-nav__link-text")
                            .attr("data-inputT")
                        var eText = $(this)
                            .find(".kt-nav__link-text")
                            .attr("data-text")
                        var a = t
                                .rows(".kt-datatable__row--active")
                                .nodes()
                                .find(
                                    '.kt-checkbox--single > [type="checkbox"]'
                                )
                                .map(function(t, e) {
                                    return $(e).val();
                                });
                        var text = eType != undefined ? "Tipo" : "Estado"
                        
                        a.length > 0 &&
                            swal
                                .fire({
                                    buttonsStyling: !1,
                                    html:
                                        `
                                        ¿Estás seguro de actualizar ${a.length} productos/s a el ${text} de ${eText}?`,
                                    type: "info",
                                    confirmButtonText: "¡Sí, Actualizar!",
                                    confirmButtonClass:
                                        "btn btn-sm btn-bold btn-brand",
                                    showCancelButton: !0,
                                    cancelButtonText: "No, cancelar",
                                    cancelButtonClass:
                                        "btn btn-sm btn-bold btn-default"
                                })
                                .then(function(t) {

                                    if (t.value) {

                                      
                                        var datos = {
                                            actualizar_estados: true,
                                            estado: e,
                                            lista: a.toArray()
                                        }

                                        ajax.peticion("normal",datos,"views/ajax/gestorProductos.php")
                                            .then((res)=>{
                                                console.log(res)
                                                if (res.status == "ok") {
                                                    util.alertSuccess("¡Actualizado!", "¡Se ha cambiado el estado a los productos seleccionados!", "products")
                                                    
                                                }else{
                                                    util.alertError("Error", res.message)
                                                }
                                            },(error)=>{
                                                console.log(error)
                                            })

                                        
                                    }else{
                                        swal.fire({
                                            title: "Cancelado",
                                            text:
                                                "¡No se ha actualizado el estado!",
                                            type: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "OK",
                                            confirmButtonClass:
                                                "btn btn-sm btn-bold btn-brand"
                                        });
                                    }
                                          
                                });
                    }
                ),
                // Borrar selección
                $("#kt_subheader_group_actions_delete_all").on(
                    "click",
                    function() {
                        var e = t
                            .rows(".kt-datatable__row--active")
                            .nodes()
                            .find('.kt-checkbox--single > [type="checkbox"]')
                            .map(function(t, e) {
                                return $(e).val();
                            });
                        e.length > 0 &&
                            swal
                                .fire({
                                    buttonsStyling: !1,
                                    text:
                                        "¿Estás seguro de borrar " +
                                        e.length +
                                        " zona/s seleccionada/s? Esto afectará los clientes en estas zonas.",
                                    type: "error",
                                    confirmButtonText: "Sí, borrar!",
                                    confirmButtonClass:
                                        "btn btn-sm btn-bold btn-danger",
                                    showCancelButton: !0,
                                    cancelButtonText: "No, cancelar",
                                    cancelButtonClass:
                                        "btn btn-sm btn-bold btn-brand"
                                })
                                .then(function(t) {
                                    if (t.value) {

                                        var datos = {
                                            borrar_lista_zonas: true,
                                            lista: e.toArray()
                                        }

                                        ajax.peticion("normal",datos,"views/ajax/gestorZonas.php")
                                            .then((res)=>{                                                
                                                console.log(res)
                                                if (res.status == "ok") {
                                                    swal.fire({
                                                        title: "¡Borrado!",
                                                        text:
                                                            "¡Las zonas seleccionadas han sido borradas! :(",
                                                        type: "success",
                                                        buttonsStyling: !1,
                                                        confirmButtonText: "OK",
                                                        confirmButtonClass:
                                                            "btn btn-sm btn-bold btn-brand"
                                                    }).then(()=>{
                                                        window.location.reload()
                                                    })
                                                }else{
                                                    util.alertError("Error", res.message)
                                                }
                                                
                                            },(error)=>{
                                                console.log(error)
                                            })

                                        
                                    }else{
                                        swal.fire({
                                            title: "Cancelado",
                                            text:
                                                "¡Las zonas seleccionadas no han sido borradas! :)",
                                            type: "error",
                                            buttonsStyling: !1,
                                            confirmButtonText: "OK",
                                            confirmButtonClass:
                                                "btn btn-sm btn-bold btn-brand"
                                        })
                                    }
                                });
                    }
                ),
                t.on("kt-datatable--on-layout-updated", function() {
                    $(".borrarZona").unbind()
                    $(".borrarZona").bind("click",function(e){
                        e.preventDefault()
                        var idBorrar = $(this).attr("data-id")
                        swal.fire({
                                buttonsStyling: !1,
                                text:
                                    "¿Estás seguro de borrar esta zona?",
                                type: "error",
                                confirmButtonText: "Sí, borrar!",
                                confirmButtonClass:
                                    "btn btn-sm btn-bold btn-danger",
                                showCancelButton: !0,
                                cancelButtonText: "No, cancelar",
                                cancelButtonClass:
                                    "btn btn-sm btn-bold btn-brand"
                            })
                            .then(function(t) {
                                if (t.value) {
                
                                    var datos = {
                                        borrar_lista_zonas: true,
                                        lista: [idBorrar]
                                    }
                
                                    console.log(datos)
                
                                    ajax.peticion("normal",datos,"views/ajax/gestorZonas.php")
                                        .then((res)=>{
                                            console.log(res)
                                            if(res.status == "ok"){
                                                swal.fire({
                                                    title: "¡Borrado!",
                                                    text:
                                                        "¡La zona ha sido borrada! :(",
                                                    type: "success",
                                                    buttonsStyling: !1,
                                                    confirmButtonText: "OK",
                                                    confirmButtonClass:
                                                        "btn btn-sm btn-bold btn-brand"
                                                }).then(()=>{
                                                    window.location.reload()
                                                })
                                            }else{
                                                util.alertError("Error", res.message)
                                            }
                                            
                                        },(error)=>{
                                            console.log(error)
                                        })
                
                                    
                                }else{
                                    swal.fire({
                                        title: "Cancelado",
                                        text:
                                            "¡La zona no ha sido borrada! :)",
                                        type: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK",
                                        confirmButtonClass:
                                            "btn btn-sm btn-bold btn-brand"
                                    })
                                }
                            });
                    })

                    $(".editarZona").unbind().bind("click",function(e){
                        e.preventDefault()
                        var id = $(this).attr("data-id")
                        var nombre = $(this).attr("data-nombre")

                        $("#nombreZonaEditar").val(nombre)
                        $("#idZone").val(id)

                        $("#modalEditZone").modal("show")
                        
                    })


                    
                });

                
        }
    };
})();
KTUtil.ready(function() {
    KTUserListDatatable.init();
    
});

function establecerEventos() {}
