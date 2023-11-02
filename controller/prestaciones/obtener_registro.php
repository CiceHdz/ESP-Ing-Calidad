<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_prestacion"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT id_prestacion, nombre, porcentaje, monto, techo_inferior, techo_superior, estado FROM FN_OBT_PRESTACION (".$_POST["id_prestacion"].") ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["nombre"] = $fila["nombre"];
        $salida["porcentaje"] = $fila["porcentaje"];
        $salida["monto"] = $fila["monto"];
        $salida["techo_inferior"] = $fila["techo_inferior"];
        $salida["techo_superior"] = $fila["techo_superior"];
        $salida["estado"] = $fila["estado"];
    }

    echo json_encode($salida);
} 