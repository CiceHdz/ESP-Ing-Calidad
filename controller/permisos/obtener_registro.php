<?php

include("../../model/conexion.php");
include("funciones.php");

if (isset($_POST["id_permiso"])) {
    $salida = array();
    $stmt = $conexion->prepare("SELECT id, ID_ROL, ID_MODULO, PUEDE_INSERTAR, PUEDE_ACTUALIZAR, PUEDE_ELIMINAR, PUEDE_CONSULTAR FROM FN_OBT_PERMISO (".$_POST["id_permiso"].") ");
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    foreach($resultado as $fila){
        $salida["id"] = $fila["id"];
        $salida["ID_ROL"] = $fila["ID_ROL"];
        $salida["ID_MODULO"] = $fila["ID_MODULO"];
        $salida["PUEDE_CONSULTAR"] = $fila["PUEDE_CONSULTAR"];
        $salida["PUEDE_INSERTAR"] = $fila["PUEDE_INSERTAR"];
        $salida["PUEDE_ACTUALIZAR"] = $fila["PUEDE_ACTUALIZAR"];
        $salida["PUEDE_ELIMINAR"] = $fila["PUEDE_ELIMINAR"];
    }

    echo json_encode($salida);
}