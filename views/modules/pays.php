<?php 
    if(!isset($_SESSION["usuario_validado"])){
    
        echo "<script> window.location.href = 'login' </script>";
    
        exit();
    }

    if ($_SESSION["tipo"] != 0) {
        GestorConfigController::verficar_usuario("searchSheets");
    }   

    $workers = GestorWorkersController::roughWorkers();
    $constructoras = GestorConstructoraController::constructoras();
    $subdivisiones = GestorSubdivisionesController::subdivisiones();

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Search sheets
                </h3>

                <span class="kt-subheader__separator kt-subheader__separator--v"
                ></span>
    
            </div>
            
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div
        class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body kt-portlet__body--fit p-2">
                <!--begin::Form-->
                <div class="kt-portlet__body">

                    <form id="form_search_sheet">
                        <div class="row">
                            <div class="form-group mb-2 col-md-4">
                                <label for="sheet" >Sheets searcher</label>
                                <select name="sheet" class="form-control" id="sheet">
                                    <option value="rough">Rough</option>
                                    <option value="final">Final</option>
                                </select>
                            </div>
                          
                        </div>
                        <div class="row">
                            <div class="form-group mb-2 col-md-4">
                                <label for="builder" >Builder</label>
                                <select name="builder" class="form-control" id="builder">
                                    <?php
                                        foreach ($constructoras as $builder) {
                                            echo "<option value='" . $builder['id'] . "'>" . $builder['nombre'] . "</option>";
                                        }
                                    
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-2 col-md-4">
                                <label for="worker" >Installer</label>
                                <select name="worker" class="form-control" id="worker">
                                    <?php
                                        foreach ($workers as $worker) {
                                            echo "<option value='" . $worker['id'] . "'>" . $worker['nombre'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group mb-2 col-md-4">
                                <label for="subdivision_pays" >Subdivision</label>
                                <select name="subdivision" class="form-control" id="subdivision_pays">
                                    <?php
                                        foreach ($subdivisiones as $subdivision) {
                                            echo "<option value='" . $subdivision['id'] . "'>" . $subdivision['nombre'] . "</option>";
                                        }
                                    
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mb-2 col-md-4">
                                <label for="lot" >Lot</label>
                                <input type="text" class="form-control" id="lot" name="lot">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 mb-2">
                                <label for="kt_daterangepicker_pays">
                                    Date range
                                </label>
                                <input type='text' class="form-control" id="kt_daterangepicker_pays" placeholder="Select dates" type="text"  name="date_range" />
                            </div>
                            <div class="form-group col-md-4 mb-2 pt-4">
                                <button  class="btn btn-primary mb-2" id="confirm">Find</button>
                            </div>
                        </div>
                      
                    </form>
                    <br>
                    <div class="div_tabla" >
                        <table class="table table-bordered" style="font-size: 14px;">
                            <thead>
                                <tr>
                                    <th>Neighborhood</th>
                                    <th>Lot number</th>
                                    <th>Day</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody id="table_body">
                            </tbody>
                        </table> 
                    </div>
                </div>

                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                       
                    </div>
                </div>
            

                <!--end::Form-->
            </div>
        </div>
        <!--end::Portlet-->

        
    </div>
    <!-- end:: Content -->
</div>

<script>

    window.addEventListener('load',function(){

        $('#kt_daterangepicker_pays').daterangepicker({
            buttonClasses: ' btn',
            cancelClass: 'btn-secondary',
            locale: {
                format: 'MM/DD/YYYY'
            }
        });


        $("#confirm").on("click",function(e){
            e.preventDefault()
            var data = new FormData(document.getElementById('form_search_sheet'))
            data.append('pagos', true)

            $.ajax({
                url: "views/ajax/gestorRoughForm.php",
                type: "POST",
                dataType: "JSON",
                data: data,
                processData: false,
                contentType: false,
                cache:false
            })
            .done((data)=>{
                console.log(data)

                $("#table_body").html("")

                var total = 0

                $.each(data.pagos,(i,item)=>{
                    $("#table_body").append(`
                        <tr>
                            <td>${item.neighborhood}</td>
                            <td>${item.lot}</td>
                            <td>${item.fecha}</td>
                            <td>$${numberWithCommas(item.total)}</td>
                        </tr>
                    `)

                    total += item.total
                })

                $("#table_body").append(`
                    <tr>
                        <td>Total</td>
                        <td></td>
                        <td></td>
                        <td>$${numberWithCommas(total)}</td>
                    </tr>
                `)

   
               
            })
            .fail((fail)=>{
                console.log(fail);
            })
        })

    },false)

    function numberWithCommas(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        return parts.join(",");
    }
</script>