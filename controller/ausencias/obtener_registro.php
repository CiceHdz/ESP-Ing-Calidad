<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_ausencia"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT id, fecha, tipo, comentario, id_empleado FROM FN_OBT_AUSENCIA(".$_POST["id_ausencia"].") ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["fecha"] = $fila["fecha"];
        $salida["tipo"] = $fila["tipo"];
        $salida["comentario"] = $fila["comentario"];
        $salida["id_empleado"] = $fila["id_empleado"];
    }

    echo json_encode($salida);
}