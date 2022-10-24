<?php 
    if(!isset($_SESSION["usuario_validado"])){
        echo "<script> window.location.href = 'login' </script>";
        exit();
    }

    $id = $_GET["id"];

    if ($_SESSION["tipo"] != 0 && $_SESSION["id_usuario"] != $id) {
        GestorConfigController::verficar_usuario("editUser");
    }

    $disabled = "";

    //si el usuario no es Master o no tiene permiso solo puede editar su propia información
    if($id != $_SESSION["id_usuario"]){

        if ($_SESSION["tipo"] != 0) {
           $resp = GestorConfigController::verficar_usuario("listUsers");
        } 

    }

    if($id == $_SESSION["id_usuario"] && $_SESSION["tipo"] != 0){
        $disabled = "disabled";
    }

    $info = GestorUsuariosController::ver_info_usuario($id);

    if (count($info) == 0) {
        echo "<script> window.location.href = 'listUsers' </script>";
    }

    $select1 = ($info["tipo"] == '0') ? "selected" : "";
    $select2 = ($info["tipo"] == '1') ? "selected" : "";
    $select3 = ($info["tipo"] == '2') ? "selected" : "";

    $selectE1 = ($info["estado"] == '1') ? "selected" : "";
    $selectE2 = ($info["estado"] == '0') ? "selected" : "";

    // var_dump($info);

?>

<div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
    <!-- begin:: Content Head -->
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">

                   

                    <?php if ($id == $_SESSION["id_usuario"]): ?>
                        Edit profile
                    <?php else: ?>
                        Edit admin #<?php echo $id;  ?>
                    <?php endif; ?>
                    
                </h3>

                <span class="kt-subheader__separator kt-subheader__separator--v"
                ></span>
    
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
                <!--begin::Form-->
                <form class="kt-form" id="actualizarInfo-form">
                    <div class="kt-portlet__body">
                        <div class="kt-section kt-section--first">
                            <div class="form-group">
                                <label>Full name:</label>
                                <input type="text" class="form-control" name="nombre" value="<?php echo $info["usuario"] ?>">
                                <input type="hidden" name="id" value="<?php echo $info["id"] ?>">
                                <!-- <span class="form-text text-muted">Por favor agrega el nombre del usuario</span> -->
                            </div>
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" name="correo" value="<?php echo $info["correo"] ?>">
                                <!-- <span class="form-text text-muted">El Email debe ser único por usuario</span> -->
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control"  name="tipo" <?php echo $disabled; ?>>
                                    <?php if ($_SESSION["tipo"] == 0): ?>
                                        <option value="0" <?php echo $select1; ?>>Administrator</option>
                                    <?php endif; ?>
                                    <option value="1" <?php echo $select2; ?>>User</option>
                                </select>
                            </div>
                         
                            <div class="form-group">
                                <!-- <label>Estado</label> -->
                                <select class="form-control hidden" name="estado" <?php echo $disabled; ?>>
                                    <option value="1" <?php echo $selectE1; ?>>Activo</option>
                                    <option value="0" <?php echo $selectE2; ?>>Bloqueado</option>
                                </select>
                            </div>
                            <button class="btn btn-warning" id="updateP">Change password</button>
                            <hr>
                            <div class="form-group contentPassword hidden">
                                <label>Password:</label>
                                <input type="password" class="form-control" name="password" id="password">
                                <input type="hidden" name="updatePass" value="false" id="updatePass">
                            </div>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <button class="btn btn-primary" id="actualizarInfo">UPDATE</button>

                            <span class="px-2 message"></span>
                        </div>
                    </div>
                </form>

                <!--end::Form-->
            </div>
        </div>
        <!--end::Portlet-->

        
    </div>
    <!-- end:: Content -->
</div>
