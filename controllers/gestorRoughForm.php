<?php
if (!isset($_SESSION)) {
    session_start();
}
class GestorRoughFormController
{

    public static function jsonRoughForms()
    {

        $rough_forms = GestorRoughFormModel::jsonRoughForms();

        return $rough_forms;
    }

    public static function rough_forms()
    {

        $rough_forms = GestorRoughFormModel::rough_forms();

        return $rough_forms;
    }

    public static function plantillas()
    {

        $plantillas = GestorRoughFormModel::plantillas();

        return $plantillas;
    }
    /* =============================================================
    CONTROLADOR PARA ACTUALIZAR LA BASE DE DATOS (OTHERS)
    ============================================================= */
    public static function updatedatabase(){
        $query = GestorRoughFormModel::updatedatabase();
        return $query;
    }
    /* =============================================================
    CONTROLADOR PARA LA CREAR HOUSE ROUGH SHEET
    ============================================================= */
    public static function crear()
    {
        // Solicitud de validacion de datos
        $validado = GestorRoughFormController::validar($_POST);
        if ($validado["status"] == "error") {
            return $validado;
        } else {
            // Si pasa la validacion enviamos la informacion al modelo
            $arr =  GestorRoughFormModel::crear($_POST);
            return $arr;
        }
    }
    /* =============================================================
    CONTROLADOR PARA LA CREAR HOUSE ROUGH SHEET
    ============================================================= */
    public static function update()
    {
        $validado = GestorRoughFormController::validar($_POST);
        if ($validado["status"] == "error") {
            return $validado;
        } else {
            // Si pasa la validación enviamos la información al modelo
            $arr =  GestorRoughFormModel::update($_POST);
            return $arr;
        }
    }
    /* =============================================================
    CONTROLADOR PARA LA VALIDACION DE DATOS
    ============================================================= */
    public static function validar($datos)
    {
        $validado = array(
            "razon" => "",
            "mensaje" => "",
            "status" => "valido"
        );
        // Validacion por cada campo con mensaje personalizado
        // Validacion de neigborthood
        if (
            $datos["neighborhood"] == ""
        ) {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter the required Neighborhood.",
                "status" => "error"
            );
        }
        // Validacion del lote
        elseif (
            $datos["lot"] == ""
        ) {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter the required lot.",
                "status" => "error"
            );
        }
        // Validacion del Supervisor
        elseif (
            $datos["supervisor"] == ""
        ) {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter the required Supervisor.",
                "status" => "error"
            );
        }
        // Validacion del brand of fixure
        elseif (
            $datos["brand_of_fixtures"] == "Select" || $datos["brand_of_fixtures"] == ""
            || $datos["brand_of_fixtures"] == null 
        ) {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter brand of fixure!",
                "status" => "error"
            );
        }
        // Validacion del Installer
        elseif (
            $datos["payroll"] == ""
        ) {
            $validado = array(
                "razon" => "campos vacíos",
                "mensaje" => "Enter Installer!",
                "status" => "error"
            );
        }
        return $validado;
    }
    /* =================================================================
    CONTROLADOR PARA LA CARGA DE LOS DATOS DE LA SHEET EN EL REPORTE
    ================================================================== */
    public static function info_rough_form($id)
    {
        return GestorRoughFormModel::info_rough_form($id);
    }
    
    public static function buscar_lote()
    {
        return GestorRoughFormModel::buscar_lote();
    }
    public static function pagos()
    {

        return GestorRoughFormModel::pagos();
    }
    public static function borrar_lista_rough_forms()
    {
        $lista = $_POST["lista"];
        return GestorRoughFormModel::borrar_lista_rough_forms($lista);
    }
}
