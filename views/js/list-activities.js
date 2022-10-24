"use strict";

// tabla de actividades de la vista de actividades

var KTUserListDatatable = (function() {
    var t;
    return {
        init: function() {
            (t = $("#kt_apps_activities_list_datatable").KTDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "views/ajax/jsonActivities.php"
                        }
                    },
                    pageSize: 10
                },
                layout: { scroll: !1, footer: !1 },
                sortable: !0,
                search: {
                   input: $('#generalSearch2'),
                   onEnter: false,
                },
                pagination: !0,
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
                        field: "tipo",
                        title: "Type",
                        width: 215,
                        template: function(t) {
                            var bg = ""
                            var textos = ""

                            // console.log("?");

                            if(t.tipo.nombre == undefined){
                                return ""
                            }
                            if (t.tipo.settings == undefined) {
                                console.log(t);
                                return ""
                            }
                            
                            if(t.tipo.settings.ordered && t.tipo.settings.marked){
                                if (t.estado == 3) {                       
                                    bg = t.tipo.settings.color_marked
                                }else{
                                    bg = t.tipo.settings.defaul_color_
                                }
                            }else{
                                if (t.estado == 3 || t.estado == 2) {                       
                                    bg = t.tipo.settings.color_marked
                                }else if (t.estado == 1) {                       
                                    bg = t.tipo.settings.color_ordered
                                }else{
                                    bg = t.tipo.settings.defaul_color_
                                }
                            }

                            if (t.textos != undefined  && t.tipo.settings.textos.length) {
                                var selecteds = t.textos.filter((item) => item.valor == "1")
                                var aux_ = []
                            
                                for(var i = 0; i < selecteds.length; i++){
                                   aux_.push(selecteds[i].texto)
                                }

                                if (aux_.length != 0) {
                                    textos =  "(" + aux_.toString() + ")"
                                }

                                
                            }

                            

                            return `
                                <span class="p-2 d-block rounded" style="background: #${bg}; color: #000;">
                                    ${t.tipo.nombre} ${textos}
                                </span>`;
                        }
                    },  
                    {
                        field: "subdivision",
                        title: "Subdivision",
                        width: 115,
                        template: function(t, e) {
                            return t.subdivision.nombre                            
                        }
                    },
                    {
                        field: "lote",
                        title: "Lot",
                        width: 100,
                        template: function(t) {
                            return `
                                <a href="#" class="set_filter_lot" data-sub="${t.subdivision.id}" data-lot="${t.lote}">
                                   ${t.lote}
                                </a>
                            `;
                        }
                    },
                    
                    {
                        field: "trabajador",
                        title: "Installer",
                        template: function(t) {
                            return t.worker.nombre;
                        }
                    },                     
                    // {
                    //     field: "estado",
                    //     title: "Status",
                    //     width: 100,
                    //     template: function(t) {
                    //         var e = {
                    //             2: {
                    //                 title: "Concluded",
                    //                 class: " badge-g"
                    //             },
                    //             1: {
                    //                 title: "In process",
                    //                 class: " badge-p"
                    //             },
                    //             0: {
                    //                 title: "Started",
                    //                 class: " badge-ini"
                    //             }
                    //         };
                    //         return (
                    //             '<span class="btn btn-bold btn-sm btn-font-sm kt-badge--inline kt-badge--bold  ' +
                    //             e[t.estado].class +
                    //             '">' +
                    //             e[t.estado].title +
                    //             "</span>"
                    //         );
                    //     }
                    // },
                    {
                        field: "fecha",
                        title: "Date",
                        template: function(t) {
                            return t.format_us;
                        }
                    },                    
                    {
                        field: "creado_por",
                        title: "Created by",
                        template: function(t) {
                            return t.creado_por;
                        }
                    },
                    {
                        field: "descripcion",
                        title: "Description",
                        template: function(t) {
                            return t.descripcion;
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
                            // console.log("pintando acitions")
                            return `
                            <div class="dropdown">
                                <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
                                    <i class="flaticon-more-1"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <ul class="kt-nav">                                    
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link editActivity" data-id="${t.id}">
                                                <i class="kt-nav__link-icon flaticon2-contract"></i>
                                                <span class="kt-nav__link-text">Edit</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link deleteActivity" data-id="${t.id}">
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

                                  
                        a.length > 0 &&
                            swal
                                .fire({
                                    buttonsStyling: !1,
                                    html:
                                        `
                                        Do you want to update ${a.length} selected activities to ${eText}?`,
                                    type: "info",
                                    confirmButtonText: "Yes, Update!",
                                    confirmButtonClass:
                                        "btn btn-sm btn-bold btn-brand",
                                    showCancelButton: !0,
                                    cancelButtonText: "No, cancel",
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
                                        
                                        // console.log(datos);

                                        ajax.peticion("normal",datos,"views/ajax/gestorActividades.php")
                                            .then((res)=>{
                                                console.log(res)
                                                swal.fire({
                                                    title: "Success!",
                                                    text:
                                                        "Updated!",
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
                                        " selected activities?",
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
                                            borrar_lista_actividades: true,
                                            lista: e.toArray()
                                        }

                                        ajax.peticion("normal",datos,"views/ajax/gestorActividades.php")
                                            .then((res)=>{
                                                console.log(res)
                                                if (res.status == false) {
                                                    util.alertError("Error", res.message)   
                                                }else{
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
                                                }
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
                                       
                    $(".deleteActivity").unbind().bind("click",function(e){
                        e.preventDefault()
                        // console.log("BORRAR ESPECIFICO")
                        var idBorrar = $(this).attr("data-id")
                     
                        delete_a(idBorrar)
                    })

                    // modal de editar actividad en la vista de actividades
                    $(".editActivity").unbind().bind("click",function(e){
                        e.preventDefault()
                   
                        var id = $(this).attr("data-id")

                        $.ajax({
                            url: "views/ajax/gestorActividades.php",
                            type: "POST",
                            dataType: "JSON",
                            data:{info_actividad:true, id: id}
                        })
                        .done((res)=>{
                            console.log("EDITAR")
                            console.log(res);

                            if (res.access.editActivitie == undefined) {
                                util.alertError("Error", "You don't have access to this action")
            
                                return false
                            }

                            $("#formEditActivity .fecha").val(res.fecha_group.format_us)
                            $("#formEditActivity .descripcion").val(res.descripcion)
                            $("#administracion_edit").val(res.administracion)
                            if (res.subdivision != 0) {
                                $("#formEditActivity .subdivision").val(res.subdivision)    
                            }else{
                                $("#formEditActivity .subdivision").val("#")    
                            }                              
                            $("#formEditActivity .lote").val(res.lote)
                            if (res.trabajador != 0) {
                                $("#formEditActivity .worker").val(res.trabajador)    
                            }
                            if (res.tipo != 0) {
                                $("#formEditActivity .tipo").val(res.tipo)
                            }else{
                                $("#formEditActivity .tipo").val("#")
                            }
                            // Warranty o Service
                            if (res.tipo_.id == 15 || res.tipo_.id == 16) {
                                //$("#time_edit").val(res.time)
                                $("#address_edit").val(res.address)
                                $(".address_new_edit").removeClass("hidden");
                            }else{
                                $(".address_new_edit").addClass("hidden");
                                $("#time_edit").val("");
                                $("#address_edit").val("");
                                
                            }
                            
                            $("#formEditActivity .id").val(res.id)
                            
                            $(".list_texts_edit").html("")

                            var settings = res.tipo_.settings

                            if (settings == undefined) {
                                $(".content_ordened_edit").addClass("hidden")
                                $(".content_marked_edit").addClass("hidden")
                                $(".attachment_edit").addClass("hidden")
                                $(".content_archive_edit").addClass("hidden")
                                
                            }else{
                                if (settings.ordered) {
                                    $(".content_ordened_edit").removeClass("hidden")
                                }else{
                                    $(".content_ordened_edit").addClass("hidden")
                                }
                                if (settings.marked) {
                                    $(".content_marked_edit").removeClass("hidden")
                                }else{
                                    $(".content_marked_edit").addClass("hidden")
                                }
    
                                if (settings.billiable) {
                                    $(".content_billiable_edit").removeClass("hidden")
                                }else{
                                    $(".content_billiable_edit").addClass("hidden")
                                }

                                // if (settings.archive) {
                                //     $(".content_archive_edit").removeClass("hidden")
                                // }else{
                                //     $(".content_archive_edit").addClass("hidden")
                                // }
    
                                if (settings.attachment) {
                                    $(".attachment_edit").removeClass("hidden")
                                }else{
                                    $(".attachment_edit").addClass("hidden")
                                }

                                var txtSaved = res.textos
                                for(var i = 0; i < settings.textos.length; i++){
                                    var t = settings.textos[i]
                                    var check_ = ""
                                    if (txtSaved[i] != undefined) {
                                        if (txtSaved[i]["valor"] == "1") {
                                            check_ = "checked"
                                        }
                                    }
                        
                                    $(".list_texts_edit").append(
                                        `
                                            <div class="col flex_ form-group">
                                                <label class="pr-3">${t}:</label>
                                                <span class="kt-switch kt-switch--icon">
                                                    <label class="m-0">
                                                        <input type="checkbox" name="text_${i}" ${check_}> 
                                                        <span></span>
                                                    </label>
                                                </span>
                                            </div>
                                        `
                                    )
                                }
                            }
                    
                            

                            if (res.estado == "3" && settings.marked && settings.ordered) {
                                document.getElementById("ordened_edit").checked = true
                                document.getElementById("marked_edit").checked = true
                            }else if (res.estado == "2" && settings.marked) {
                                document.getElementById("marked_edit").checked = true
                                document.getElementById("ordened_edit").checked = false
                            }else if (res.estado == "1" && settings.ordered){
                                document.getElementById("ordened_edit").checked = true
                                document.getElementById("marked_edit").checked = false
                            }else{
                                document.getElementById("ordened_edit").checked = false
                                document.getElementById("marked_edit").checked = false
                            }

                            if (res.facturable == "1") {
                                document.getElementById("billiable_edit").checked = true
                            }else{
                                document.getElementById("billiable_edit").checked = false
                            }

                            // if (res.archivo == "1") {
                            //     document.getElementById("archive_edit").checked = true
                            // }else{
                            //     document.getElementById("archive_edit").checked = false
                            // }

                            // if (res.tipo_.nombre == "@Special") {
                            //     $("#formEditActivity select[name='worker']").attr("disabled", "true")
                            //     $("#formEditActivity select[name='subdivision']").attr("disabled", "true")
                            //     $("#formEditActivity input[name='lote']").attr("disabled", "true")
                            // }else{
                            //     $("#formEditActivity select[name='worker']").removeAttr("disabled")
                            //     $("#formEditActivity select[name='subdivision']").removeAttr("disabled")
                            //     $("#formEditActivity input[name='lote']").removeAttr("disabled")
                            // }


                            lot_list_("list_lot2")

                            $("#m_edit_activity").modal("show")
                        })
                        .fail((fail)=>{
                            console.log(fail);
                        })
                  
                    })

                    $(".set_filter_lot").unbind().bind("click",function(e){
                        e.preventDefault()
                        var sub = $(this).attr("data-sub")
                        var lot = $(this).attr("data-lot")
                
                        var data = {
                            set_filter_lot: true,
                            sub: sub,
                            lot: lot
                        }
                        console.log(data);
                        $.ajax({
                            url: "views/ajax/gestorActividades.php",
                            type: "POST",
                            dataType: "JSON",
                            data: data
                        })
                        .done((res)=>{
                            console.log(res);
                            window.location.href = "lot"
                        })
                        .fail((fail)=>{
                            console.log(fail);
                        })
                        
                           
                
                    })
                    
                });

                
        }
    };
})();
KTUtil.ready(function() {
    KTUserListDatatable.init();
    
});

function establecerEventos() {}
