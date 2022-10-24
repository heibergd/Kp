<?php 
    if(!isset($_SESSION["usuario_validado"])){
    
        echo "<script> window.location.href = 'login' </script>";
    
        exit();
    }

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Empty Page
                </h3>

                <span class="kt-subheader__separator kt-subheader__separator--v"
                ></span>
    
            </div>
            <div class="kt-subheader__toolbar">

                <a href="/metronic/preview/demo1/custom/apps/user/add-user.html"
                    class="btn btn-label-brand btn-bold">
                    button
                </a>

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
                contenido aquí...
            </div>
        </div>
        <!--end::Portlet-->

        
    </div>
    <!-- end:: Content -->
</div>
