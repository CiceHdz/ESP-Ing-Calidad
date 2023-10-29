<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_empleado"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT id_empleado, nombres, apellidos, cargo, fecha_ingreso, fecha_salida, salario, estado, id_depto, tipo_salida, estado_indem, id_tipo_contrato FROM FN_OBT_EMPLEADO (".$_POST["id_empleado"].") ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["nombres"] = $fila["nombres"];
        $salida["apellidos"] = $fila["apellidos"];
        $salida["cargo"] = $fila["cargo"];
        $salida["fecha_ingreso"] = $fila["fecha_ingreso"];
        $salida["fecha_salida"] = $fila["fecha_salida"];
        $salida["salario"] = $fila["salario"];
        $salida["estado"] = $fila["estado"];
        $salida["id_depto"] = $fila["id_depto"];
        $salida["tipo_salida"] = $fila["tipo_salida"];
        $salida["estado_indem"] = $fila["estado_indem"];
        $salida["id_tipo_contrato"] = $fila["id_tipo_contrato"];
    }

    echo json_encode($salida);
} 