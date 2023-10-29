<?php

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear") {
    /*$imagen = '';
    if ($_FILES["imagen_usuario"]["name"] != '') {
        $imagen = subir_imagen();
    }*/
    $stmt = $conexion->prepare("INSERT INTO ausencias(fecha, id_empleado, comentario, tipo) VALUES (CONVERT(datetime, :fecha, 103), :id_empleado, :comentario, :tipo)");

    $resultado = $stmt->execute(
        array(    
            ':fecha'    => $_POST["fecha"],
            ':id_empleado'    => $_POST["id_empleado"],
            ':comentario'    => $_POST["comentario"],
            ':tipo'    => $_POST["tipo"]
        )
    );

    if (!empty($resultado)) {
        echo 'Registro creado';
    }
}


if ($_POST["operacion"] == "Editar") {
    /*$imagen = '';
    if ($_FILES["imagen_usuario"]["name"] != '') {
        $imagen = subir_imagen();
    }else{
        $imagen = $_POST["imagen_usuario_oculta"];
    }*/

    $stmt = $conexion->prepare("UPDATE ausencias SET fecha=CONVERT(datetime, :fecha, 103), id_empleado=:id_empleado, comentario=:comentario, tipo=:tipo WHERE id = :id_ausencia");

    $resultado = $stmt->execute(
        array(
            ':fecha'    => $_POST["fecha"],
            ':id_empleado'    => $_POST["id_empleado"],
            ':comentario'    => $_POST["comentario"],
            ':tipo'    => $_POST["tipo"],
            ':id_ausencia'    => $_POST["id_ausencia"],
        )
    );

    if (!empty($resultado)) {
        echo 'Registro actualizado';
    }
}