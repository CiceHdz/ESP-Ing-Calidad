<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_horEx"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT id, fecha, tipo, cantidad, id_empleado FROM FN_OBT_HORASEXTRA (".$_POST["id_horEx"].") ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["fecha"] = $fila["fecha"];
        $salida["tipo"] = $fila["tipo"];
        $salida["cantidad"] = $fila["cantidad"];
        $salida["id_empleado"] = $fila["id_empleado"];
    }

    echo json_encode($salida);
} 