<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear") {
    $stmt = $conexion->prepare("INSERT INTO empleados(nombres, apellidos, cargo, fecha_ingreso, fecha_salida, salario, estado, id_depto, tipo_salida, estado_indem, id_tipo_contrato) VALUES (:nombres, :apellidos, :cargo, CONVERT(datetime, :fecha_ingreso, 103), CONVERT(datetime, :fecha_salida, 103), :salario, :estado, :id_depto, :tipo_salida, :estado_indem, :id_tipo_contrato)");

    $resultado = $stmt->execute(
        array(
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
        echo 'Registro creado';
    }
}


if ($_POST["operacion"] == "Editar") {
    $stmt = $conexion->prepare("UPDATE empleados SET nombres=:nombres, apellidos=:apellidos, cargo=:cargo, fecha_ingreso=CONVERT(datetime, :fecha_ingreso, 103), fecha_salida=CONVERT(datetime, :fecha_salida, 103), salario=:salario, estado=:estado, id_depto=:id_depto, tipo_salida=:tipo_salida, estado_indem=:estado_indem, id_tipo_contrato=:id_tipo_contrato  WHERE id_empleado = :id_empleado");

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
        echo 'Registro actualizado';
    }
}
