<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_depto"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT nombre, estado, id_depto FROM FN_OBT_DEPARTAMENTO (".$_POST["id_depto"].") ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["nombre"] = $fila["nombre"];
        $salida["estado"] = $fila["estado"];
        $salida["id_depto"] = $fila["id_depto"];
    }

    echo json_encode($salida);
}