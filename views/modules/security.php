<?php 
    if(!isset($_SESSION["usuario_validado"])){
        echo "<script> window.location.href = 'login' </script>";
        exit();
    }

    if ($_SESSION["tipo"] != 0) {
        GestorConfigController::verficar_usuario("security");
    }   

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Security
                </h3>

                <span class="kt-subheader__separator kt-subheader__separator--v"
                ></span>
    
            </div>
            <div class="kt-subheader__toolbar">


            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div
        class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid"
    >
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body kt-portlet__body--fit p-2">

                
                
                <div id="seccionA" >
                    <!--begin::Portlet-->
                    <div class="kt-pricing-1 kt-pricing-1--fixed">
                        <div class="kt-pricing-1__items row">
                            <div class="kt-pricing-1__item col-md-12 p-3">
                                <h4 class="title">Allowed modules Normal users</h4>
                                
                                    
                                <?php $groups = GestorConfigController::ver_paginas_permitidas("admin") ?>

                                <div class="accordion accordion-light  accordion-toggle-arrow" id="accordionExample6">

                                    <?php foreach ($groups as $group): ?>

                                        <div class="card">
                                            <div class="card-header" id="heading<?php echo $group["bName"] ?>">
                                                <div class="card-title collapsed" data-toggle="collapse" data-target="#<?php echo $group["bName"] ?>" aria-expanded="false" aria-controls="<?php echo $group["bName"] ?>">
                                                    <?php echo $group["bName"] ?>
                                                </div>
                                            </div>
                                            <div id="<?php echo $group["bName"] ?>" class="collapse" aria-labelledby="heading<?php echo $group["bName"] ?>" data-parent="#accordionExample6">
                                                <div class="">
                                                    <div class="kt-notification-v2">
                                                        <?php  GestorConfigController::list_items($group["list"],"users") ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--end::Portlet-->

        
    </div>
    <!-- end:: Content -->
</div>
