"use strict";

$.ajax({
    url:"views/ajax/jsonSubdivisions.php",
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
            (t = $("#kt_apps_subdivisions_list_datatable").KTDatatable({
                data: {
                    type: "remote",
                    source: {
                        read: {
                            url: "views/ajax/jsonSubdivisions.php"
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
                        title: "Name",
                        width: 140,
                        template: function(t, e) {                            
                            return `
                                <div class="kt-user-card-v2">  
                                    <div class="kt-user-card-v2__details">
                                        <a href="#" class="kt-user-card-v2__name">
                                            ${t.nombre}
                                        </a>
                                        <span class="kt-user-card-v2__desc"></span>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        field: "grupoconst",
                        title: "Builders",
                        width: 200,
                        template: function(t) {
                            return t.grupoconst;
                        }
                    },
                    {
                        field: "is_sewer",
                        title: "Sewer",
                        width: 150,
                        template: function(t) {
                            return t.is_sewer;
                        }
                    },
                     {
                        field: "forsyth_county",
                        title: "Forsyth County",
                        width: 150,
                        template: function(t) {
                            return t.forsyth_county;
                        }
                    },
                    {
                        field: "direccion",
                        title: "Address",
                        width: 200,
                        template: function(t) {
                            return t.direccion;
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
                                            <a href="#" class="kt-nav__link edit_sub" data-id="${t.id}">
                                                <i class="kt-nav__link-icon flaticon2-contract"></i>
                                                <span class="kt-nav__link-text">Edit</span>
                                            </a>
                                        </li>
                                        <li class="kt-nav__item">
                                            <a href="#" class="kt-nav__link borrarSubs" data-id="${t.id}">
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
        
                // Borrar selecci??n
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
                                        " selected subdivisions?",
                                    type: "info",
                                    confirmButtonText: "Yes, Delete!",
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
                                            borrar_lista_subdivisiones: true,
                                            lista: e.toArray()
                                        }

                                        ajax.peticion("normal",datos,"views/ajax/gestorSubdivision.php")
                                            .then((res)=>{                                                
                                                console.log(res)
                                                if (res.status == "ok") {
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
                                                }else{
                                                    util.alertError("Error", res.message)
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
                    
                    $(".borrarSubs").unbind().bind("click",function(e){
                        e.preventDefault()
                        var idBorrar = $(this).attr("data-id")
                        swal.fire({
                                buttonsStyling: !1,
                                text:
                                    "Do you want to delete this subdivision?",
                                type: "info",
                                confirmButtonText: "Yes, Delete!",
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
                                        borrar_lista_subdivisiones: true,
                                        lista: [idBorrar]
                                    }
                
                                    console.log(datos)
                
                                    ajax.peticion("normal",datos,"views/ajax/gestorSubdivision.php")
                                        .then((res)=>{
                                            console.log(res)
                                            if(res.status == "ok"){
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
                                            }else{
                                                util.alertError("Error", res.message)
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
                    })


                    $(".edit_sub").unbind().bind("click",function(e){
                        e.preventDefault()
                        var id = $(this).attr("data-id")

                        $.ajax({
                            url: "views/ajax/gestorSubdivision.php",
                            type: "POST",
                            dataType: "JSON",
                            data: {info_sub: true, id: id}
                        })
                        .done((res)=>{
                            console.log("res###");
                            console.log(res);
                            if (res.access.editSubdivision == undefined) {
                                util.alertError("Error", "You don't have access to this action")
            
                                return false
                            }

                            if (res.forsyth_county == "Yes") {
                                $("#forsyth option[value='Yes']").prop('selected', true);
                            } else if(res.forsyth_county == "No") {
                                $("#forsyth option[value='No']").prop('selected', true);
                            } 

                            $("#sewer option[value='" + res.sewer + "']").prop('selected', true);    

                            $("#formEditSubdivision .nombre").val(res.nombre)
                            $("#formEditSubdivision .direccion").val(res.direccion)
                            $("#formEditSubdivision .lotes").val(res.lotes)
                            $("#formEditSubdivision .direccion").val(res.direccion)
                            $("#formEditSubdivision .latlng").val(res.lat_lng)
                            $("#formEditSubdivision .id").val(id)
                            var myArray =  res.constructoras.replace(/['"]+/g, '');
                            myArray = myArray.replace(/[\[\]']+/g,'');
                            var arr = myArray.split(','); 
                  
                            $("#builders_select_edit option:selected").prop("selected", false); //desselecciono todos los options 

                            arr.forEach(function(elemento, indice, array) {
                                $("#builders_select_edit option[value='"+ arr[indice] +"']").prop('selected', true);
                                console.log(arr[indice]);
                                 
                            })
                            
                            //$("#builders_select_edit option[value='5']").prop('selected', false);
                            $('#builders_select_edit').trigger('change');
                           
                            


                            if (using != "edit") {
                                mapa = undefined
                                markerDireccion = undefined    
                                using = "edit"
                            }

                            $("#m_edit_subdivision").modal("show")

                            mapa_sub("mapa_edit_sub", "latlng_edit")
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
