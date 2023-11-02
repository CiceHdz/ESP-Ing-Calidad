<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
    $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_HORAEXTRA :id_horEx, :fecha_horaExtra, :tipo, :cantidad, :id_empleado");

    $resultado = $stmt->execute(
        array(
            ':id_horEx'    => $_POST["id_horEx"],
            ':fecha_horaExtra'    => $_POST["fecha_horaExtra"],
            ':tipo'    => $_POST["tipo"],
            ':cantidad'    => $_POST["cantidad"],
            ':id_empleado'    => $_POST["id_empleado"]
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