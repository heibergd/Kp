"use strict";
$.ajax({
    url:"views/ajax/ajax.php",
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
            (t = $("#kt_apps_user_list_datatable").KTDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "views/ajax/ajax.php"
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
                search: { },
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
                        field: "CompanyAgent",
                        title: "Full name",
                        width: 80,
                        // autoHide: !1,
                        template: function(t, e) {
                            for (var a = 4 + e; a > 12; ) a -= 3;

                            return `
                            <div class="kt-user-card-v2">
                                <div class="kt-user-card-v2__pic"> </div>
                                <div class="kt-user-card-v2__details">
                                    <a href="#" class="kt-user-card-v2__name"> 
                                        ${t.CompanyAgent}
                                    </a>
                                    <span class="kt-user-card-v2__desc"></span>
                                </div>
                            </div>
                            `;
                        }
                    },
                    {
                        field: "imagen",
                        title: "Image",
                        width: 115,
                        template: function(t, e) {
                            if(t.imagen != ""){
                                return `
                                    <div class="kt-user-card-v2">  
                                        <img src="${t.imagen}" alt="photo">
                                    </div>
                                `;
                            }else{
                                return `
                                    <div class="kt-badge kt-badge--xl kt-badge--${
                                        [
                                            "success",
                                            "brand",
                                            "danger",
                                            "success",
                                            "warning",
                                            "primary",
                                            "info"
                                        ][KTUtil.getRandomInt(0, 6)]}">
                                        ${t.CompanyAgent.substring(0, 1)}
                                    </div>
                                `
                            }
                            
                        }
                    },
                    {
                        field: "correo",
                        title: "Email",
                        template: function(t) {
                            return t.correo;
                        }
                    },
                    
                    {
                        width: 110,
                        field: "tipo",
                        title: "Type",
                        
                        template: function(t) {
                            var e = {
                                0: { title: "Administrator", state: "primary" },
                                1: { title: "User", state: "success" }
                            };
                            return (
                                '<span class="kt-badge kt-badge--' +
                                e[t.tipo].state +
                                ' kt-badge--dot"></span>&nbsp;<span class="kt-font-bold kt-font-' +
                                e[t.tipo].state +
                                '">' +
                                e[t.tipo].title +
                                "</span>"
                            );
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
                            console.log("pintando acitions")
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                    <i class="flaticon-more-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">                                      
                                        <li class="kt-nav__item">
                                            <a href="editAdmin_${t.id}" class="kt-nav__link">
                                                <i class="kt-nav__link-icon flaticon2-contract"></i>
                                                <span class="kt-nav__link-text">Edit</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link borrarUsuario" data-id="${t.id}">
                                                <i class="kt-nav__link-icon flaticon2-trash"></i>
                                                <span class="kt-nav__link-text">Delete</span>
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

                // actualizar estado
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
                                        ¿Estás seguro de actualizar ${a.length} usuario/s a el ${text} de ${eText}?`,
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

                                        if (eType != undefined) {
                                            var datos = {
                                                actualizar_tipos: true,
                                                tipo: e,
                                                lista: a.toArray()
                                            }
                                        }else{
                                            var datos = {
                                                actualizar_estados: true,
                                                estado: e,
                                                lista: a.toArray()
                                            }
                                        }

                                        

                                        ajax.peticion("normal",datos,"views/ajax/gestorUsuarios.php")
                                            .then((res)=>{
                                                console.log(res)
                                                swal.fire({
                                                    title: "¡Actualizado!",
                                                    text:
                                                        "¡Se ha cambiado el estado a los usuarios seleccionados!",
                                                    type: "success",
                                                    buttonsStyling: !1,
                                                    confirmButtonText: "OK",
                                                    confirmButtonClass:
                                                        "btn btn-sm btn-bold btn-brand"
                                                }).then(()=>{
                                                    window.location.reload()
                                                })
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
                                        "Do you want to delete " +
                                        e.length +
                                        " selected admins?",
                                    type: "info",
                                    confirmButtonText: "Yes, delete!",
                                    confirmButtonClass:
                                        "btn btn-sm btn-bold btn-danger",
                                    showCancelButton: !0,
                                    cancelButtonText: "No, cancel",
                                    cancelButtonClass:
                                        "btn btn-sm btn-bold btn-brand"
                                })
                                .then(function(t) {
                                    if (t.value) {

                                        var datos = {
                                            borrar_lista_usuarios: true,
                                            lista: e.toArray()
                                        }

                                        ajax.peticion("normal",datos,"views/ajax/gestorUsuarios.php")
                                            .then((res)=>{
                                                console.log(res)
                                                swal.fire({
                                                    title: "Deleted!",
                                                    text:
                                                        ":(",
                                                    type: "success",
                                                    buttonsStyling: !1,
                                                    confirmButtonText: "OK",
                                                    confirmButtonClass:
                                                        "btn btn-sm btn-bold btn-brand"
                                                }).then(()=>{
                                                    window.location.reload()
                                                })
                                            },(error)=>{
                                                console.log(error)
                                            })

                                        
                                    }else{
                                        swal.fire({
                                            title: "Cancelled",
                                            text:
                                                ":)",
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
                    console.log("EVENTO");                    
                    $(".borrarUsuario").unbind().bind("click",function(e){
                        e.preventDefault()
                        // console.log("BORRAR ESPECIFICO")
                        var idBorrar = $(this).attr("data-id")
                        swal.fire({
                                buttonsStyling: !1,
                                text:
                                    "Do you want to delete this admin?",
                                type: "info",
                                confirmButtonText: "Yes, delete!",
                                confirmButtonClass:
                                    "btn btn-sm btn-bold btn-danger",
                                showCancelButton: !0,
                                cancelButtonText: "No, cancel",
                                cancelButtonClass:
                                    "btn btn-sm btn-bold btn-brand"
                            })
                            .then(function(t) {
                                if (t.value) {
                
                                    var datos = {
                                        borrar_lista_usuarios: true,
                                        lista: [idBorrar]
                                    }
                
                                    console.log(datos)
                
                                    ajax.peticion("normal",datos,"views/ajax/gestorUsuarios.php")
                                        .then((res)=>{
                                            console.log(res)
                                            swal.fire({
                                                title: "Deleted!",
                                                text:
                                                    ":(",
                                                type: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText: "OK",
                                                confirmButtonClass:
                                                    "btn btn-sm btn-bold btn-brand"
                                            }).then(()=>{
                                                window.location.reload()
                                            })
                                        },(error)=>{
                                            console.log(error)
                                        })
                
                                    
                                }else{
                                    swal.fire({
                                        title: "Cancelled",
                                        text:
                                            ":)",
                                        type: "error",
                                        buttonsStyling: !1,
                                        confirmButtonText: "OK",
                                        confirmButtonClass:
                                            "btn btn-sm btn-bold btn-brand"
                                    })
                                }
                            });
                    })
                });

                
        }
    };
})();
KTUtil.ready(function() {
    KTUserListDatatable.init();
    
});

function establecerEventos() {}
