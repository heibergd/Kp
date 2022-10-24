<?php 
    if(!isset($_SESSION["usuario_validado"])){
        echo "<script> window.location.href = 'login' </script>";
        exit();
    }

    $full = explode(" ",$_SESSION["usuario"]);

    $iniciales = (count($full) >= 2) ? substr($full[0], 0,1) . substr($full[1], 0,1) : substr($full[0], 0,1);
    $tipo = "";
    if ($_SESSION["tipo"] == 0) {
        $tipo = "Administrator";
    }else if ($_SESSION["tipo"] == 1) {
        $tipo = "User";
    }
    
    $tipos = GestorConfigController::types();
    $daily_mails = GestorConfigController::daily_mails();
    $color = GestorConfigController::get_config("sidebar_color");
    $color = isset($color["config_value"]) ? $color["config_value"] : "light";
    $access = GestorConfigModel::verficar_usuario();

    

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Profile
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
        class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <!--begin::Portlet-->
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body kt-portlet__body--fit p-2">
               
                <div class="kt-widget kt-widget--user-profile-3 p-3">
                    <div class="kt-widget__top row">
                        <?php if ($_SESSION["imagen"] != ""): ?>
                            <div class="kt-widget__media kt-hidden- col-md-3 text-center">
                                <img src="<?php echo $_SESSION["imagen"]; ?>" alt="image" class="img-profile">
                            </div>
                        <?php else: ?>
                            <div class="col-md-3 text-center mb-4">
                                <div class="kt-widget__pic kt-widget__pic--danger kt-font-danger kt-font-boldest kt-font-light kt-hidden- inline-flex">
                                    <?php echo $iniciales; ?>
                                </div>
                            </div>
                            
                        <?php endif; ?>
                        
                        
                        
                        <div class="kt-widget__content col-md-9">
                            <div class="kt-widget__head">
                                <a href="#" class="kt-widget__username">
                                    <?php echo $_SESSION["usuario"] ?>
                                    <i class="flaticon2-correct kt-font-success"></i>                       
                                </a>

                                <div class="kt-widget__action">
                                    
                                    <button type="button" class="btn btn-brand btn-sm btn-upper" id="cambiarImagenPerfil">Image</button>
                                </div>
                            </div>

                            <div class="kt-widget__subhead">
                                <a href="#">
                                    <i class="flaticon2-new-email"></i>
                                    <?php echo $_SESSION["correo"] ?>
                                </a>
                            </div>

                            <div class="kt-widget__subhead">
                                <div class="">
                                    
                                    <a href="#">
                                        <i class="flaticon2-calendar-3"></i>
                                        <?php echo $tipo ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="kt-widget__bottom">

                                        
                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon-pie-chart"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">My accout</span>
                                <a href="editAdmin_<?php echo $_SESSION["id_usuario"]?>" 
                                    class="kt-widget__value kt-font-brand">
                                    Edit
                                </a>
                            </div>
                        </div>
                        <?php if ($_SESSION["tipo"] == 0 || isset($access->security)): ?>
                            <div class="kt-widget__item">
                                <div class="kt-widget__icon">
                                    <i class="flaticon-lock"></i>
                                </div>
                                <div class="kt-widget__details">
                                    <span class="kt-widget__title">Security</span>
                                    <a href="security" 
                                    class="kt-widget__value kt-font-brand">
                                        Manage
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <!-- <i class="flaticon-diagram"></i> -->
                            </div>
                            <div class="kt-widget__details">
                                <!-- <span class="kt-widget__title">Ventas</span>
                                <span class="kt-widget__value">
                                    <span>$</span>
                                    
                                </span> -->
                            </div>
                        </div>

                      
                      


                        <!-- <div class="kt-widget__item">
                            <div class="kt-widget__icon">
                                <i class="flaticon-chat-1"></i>
                            </div>
                            <div class="kt-widget__details">
                                <span class="kt-widget__title">648 Comments</span>
                                <a href="#" class="kt-widget__value kt-font-brand">View</a>
                            </div>
                        </div> -->

                    </div>


                </div>
                                  
            </div>
        </div>
        <!--end::Portlet-->


        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body kt-portlet__body--fit p-2">
                <div class="p-3">
                    <h3>Side bar</h3>

                    <form class="kt-form kt-form--label-right">
                        <div class="kt-portlet__body">
                            
                            <div class="form-group row">
                                <label class="col-form-label col-lg-3 col-sm-12">Side bar color</label>
                                <div class="col-lg-4 col-md-8 col-sm-12">
                                    <select class="form-control actualizar_config" id="kt_notify_icon" data-key="sidebar_color">
                                        <option value="light" <?php echo ($color == "light") ? "selected" : ""; ?>>
                                            Light
                                        </option>
                                        <option value="dark" <?php echo ($color == "dark") ? "selected" : ""; ?>>
                                            Dark
                                        </option>
                                    </select>
                                </div>
                                
                                <div class="col-md-1">
                                    <i class="kt-nav__link-icon flaticon-questions-circular-button" data-toggle="kt-tooltip" title="Side bar Theme." data-placement="top"></i>
                                </div>
                            </div>
                        
                        </div>
                        <div class="kt-portlet__foot"></div>
                    </form>

                    <?php if (isset($access->types)) { ?>

                        <div class="row">
                            <div class="col-md-3">
                            <h5>
                                Types 
                                <i class="kt-nav__link-icon flaticon-questions-circular-button" data-toggle="kt-tooltip" title="Selectable types when creating an activity." data-placement="top"></i>
                            </h5>
                                <button class="btn btn-primary addType">Add type</button>
                            </div>
                            <div class="col-md-9">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($tipos as $item) { ?>
                                            <tr>
                                                <td><?php echo $item["nombre"] ?></td>
                                                <td>
                                                    <button class="btn btn-primary editType" data-id="<?php echo $item['id'] ?>">Edit</button>
                                                    <button class="btn btn-danger deleteType" data-id="<?php echo $item['id'] ?>">Delete</button>
                                                    
                                                </td>
                                            </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    <?php } ?>

                    <hr>

                    <?php if (isset($access->mail)) { ?>

                        <div class="row">
                            <div class="col-md-3">
                            <h5>
                                Emails 
                                <i class="kt-nav__link-icon flaticon-questions-circular-button" data-toggle="kt-tooltip" title="Send daily activity reports." data-placement="top"></i>
                            </h5>
                                <button class="btn btn-primary addMail">Add email</button>
                            </div>
                            <div class="col-md-9">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($daily_mails as $item) { ?>
                                            <tr>
                                                <td><?php echo $item["mail"] ?></td>
                                                <td>
                                                    
                                                    <button class="btn btn-danger editMail" data-id="<?php echo $item['id'] ?>">Delete</button>
                                                    
                                                </td>
                                            </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    <?php } ?>
                    

                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
</div>


<!--begin::Modal-->
<div
    class="modal fade"
    id="myModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Select an image
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div
                    class="kt-scroll"
                    data-scroll="true"
                    data-height="200">
                    <form id="formImagenPerfil">
                        <input type="file" name="imageUser">
                    </form>
                    

                    <div class="mensajeModal"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-primary"
                    id="cambiarImagen">
                    SAVE
                </button>
                <button
                    type="button"
                    class="btn btn-brand"
                    data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade"
    id="modalDailyMail"
    tabindex="-1"
    role="dialog"
    aria-labelledby="modalDailyMail"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDailyMail">
                    New Email
                </h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                <div
                    class="kt-scroll"
                    data-scroll="true"
                    data-height="200">
                    
                    <p>
                        You can use commas (,) to save several at once.
                    </p>
                    <input type="text" class="form-control" placeholder="email" id="DailyMailsText">
                
                    <div class="modalDailyMailMensaje"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-primary"
                    id="seveMail">
                    SAVE
                </button>
                <button
                    type="button"
                    class="btn btn-brand"
                    data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<!--end::Modal-->

