<?php

    include("../../model/conexion.php");
    include("funciones.php");

    $query = "";
    $salida = array();
    $query = "SELECT id, FORMAT(fecha, 'yyyy-MM-dd') fecha, (select CONCAT_WS (' ', emp.nombres, emp.apellidos) from empleados emp where emp.id_empleado = aus.id_empleado) id_empleado, comentario, case tipo when 'P' then 'Permiso' when 'I' then 'Incapacidad' when 'A' then 'Ausencia' else 'Otro' end tipo FROM ausencias aus ";

    /*if (isset($_POST["search"]["value"])) {
       $query .= 'WHERE comentario LIKE "%' . $_POST["search"]["value"] . '%" ';
    }

    if (isset($_POST["order"])) {
        $query .= 'ORDER BY ' . $_POST['order']['0']['column'] .' '.$_POST["order"][0]['dir'] . ' ';        
    }else{
        $query .= 'ORDER BY id ASC ';
    }

    if($_POST["length"] != -1){
        $query .= 'LIMIT ' . $_POST["start"] . ','. $_POST["length"];
    }*/

    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $datos = array();
    $filtered_rows = $stmt->rowCount();
    foreach($resultado as $fila){
        /*$imagen = '';
        if($fila["imagen"] != ''){
            $imagen = '<img src="../../controller/usuarios/img/' . $fila["imagen"] . '"  class="img-thumbnail" width="50" height="35" />';
        }else{
            $imagen = '';
        }*/

        $sub_array = array();
        $sub_array[] = $fila["id"];
        $sub_array[] = $fila["fecha"];
        $sub_array[] = $fila["id_empleado"];
        $sub_array[] = $fila["comentario"];
        $sub_array[] = $fila["tipo"];
        $sub_array[] = '<button type="button" name="editar" id="'.$fila["id"].'" class="btn btn-primary btn-sm editar"><i class="fa-regular fa-pen-to-square"></i> Editar</button>';
        $sub_array[] = '<button type="button" name="borrar" id="'.$fila["id"].'" class="btn btn-danger btn-sm borrar"><i class="fa-regular fa-trash-can"></i> Borrar</button>';
        $datos[] = $sub_array;
    }

    $salida = array(
        "draw"               => intval($_POST["draw"]),
        "recordsTotal"       => $filtered_rows,
        "recordsFiltered"    => obtener_todos_registros(),
        "data"               => $datos
    );

    echo json_encode($salida);