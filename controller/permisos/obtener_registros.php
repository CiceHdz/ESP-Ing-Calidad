<?php

    include("../../model/conexion.php");
    include("funciones.php");

    $query = "";
    $salida = array();
    $query = "SELECT rm.id as id, r.nombre as Rol, m.nombre as Modulo, CASE rm.PUEDE_CONSULTAR WHEN 'S' THEN 'Sí' ELSE 'NO' END as PUEDE_CONSULTAR,
                CASE rm.PUEDE_INSERTAR WHEN 'S' THEN 'Sí' ELSE 'NO' END as PUEDE_INSERTAR,
                CASE rm.PUEDE_ACTUALIZAR WHEN 'S' THEN 'Sí' ELSE 'NO' END as PUEDE_ACTUALIZAR,
                CASE rm.PUEDE_ELIMINAR WHEN 'S' THEN 'Sí' ELSE 'NO' END as PUEDE_ELIMINAR
                FROM roles r
                INNER JOIN ROLES_MODULO rm ON rm.ID_ROL=r.ID_ROL
                INNER JOIN MODULOS m on rm.ID_MODULO=m.id";

    /*if (isset($_POST["search"]["value"])) {
       $query .= 'WHERE nombre LIKE "%' . $_POST["search"]["value"] . '%" ';
       $query .= 'OR estado LIKE "%' . $_POST["search"]["value"] . '%" ';
    }

    if (isset($_POST["order"])) {
        $query .= 'ORDER BY ' . $_POST['order']['0']['column'] .' '.$_POST["order"][0]['dir'] . ' ';        
    }else{
        $query .= 'ORDER BY id_depto ASC ';
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
        $sub_array = array();
        $sub_array[] = $fila["id"];
        $sub_array[] = $fila["Rol"];
        $sub_array[] = $fila["Modulo"];
        $sub_array[] = $fila["PUEDE_CONSULTAR"];
        $sub_array[] = $fila["PUEDE_INSERTAR"];
        $sub_array[] = $fila["PUEDE_ACTUALIZAR"];
        $sub_array[] = $fila["PUEDE_ELIMINAR"];
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