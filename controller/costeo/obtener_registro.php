<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_categoria"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT TOP 1 id_categoria, nombre, estado, presupuesto FROM categorias WHERE id_categoria = '".$_POST["id_categoria"]."' ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["nombre"] = $fila["nombre"];
        $salida["estado"] = $fila["estado"];
        $salida["presupuesto"] = $fila["presupuesto"];
    }

    echo json_encode($salida);
}