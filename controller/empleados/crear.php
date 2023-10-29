<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
    $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_EMPLEADO :id_empleado, :nombres, :apellidos, :cargo, :fecha_ingreso, :fecha_salida, :salario, :estado, :id_depto, :tipo_salida, :estado_indem, :id_tipo_contrato");

    $resultado = $stmt->execute(
        array(
            ':id_empleado'    => $_POST["id_empleado"],
            ':nombres'    => $_POST["nombres"],
            ':apellidos'    => $_POST["apellidos"],
            ':cargo'    => $_POST["cargo"],
            ':fecha_ingreso'    => $_POST["fecha_ingreso"],
            ':fecha_salida'    => $_POST["fecha_salida"],
            ':salario'    => $_POST["salario"],
            ':estado'    => $_POST["estado"],
            ':id_depto'    => $_POST["id_depto"],
            ':tipo_salida'    => $_POST["tipo_salida"],
            ':estado_indem'    => $_POST["estado_indem"],
            ':id_tipo_contrato'    => $_POST["id_tipo_contrato"]
        )
    );

    if (!empty($resultado)) {
        if ($_POST["operacion"] == "Crear") {
            echo 'Registro creado';
        } else {
            echo 'Registro actualizado';
        }
    }
}