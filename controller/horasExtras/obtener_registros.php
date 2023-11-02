<?php

    include("../../model/conexion.php");
    include("funciones.php");

    $query = "";
    $salida = array();
    $query = "SELECT id, FORMAT(fecha, 'yyyy-MM-dd') fecha, CASE tipo WHEN 'O' THEN 'Ordinarias' ELSE 'Extra Ordinarias' END tipo, cantidad, (select (E.nombres + ' ' + E.apellidos) from empleados E where E.id_empleado = HR.id_empleado) id_empleado FROM HORAS_EXTRA HR ";

    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $datos = array();
    $filtered_rows = $stmt->rowCount();
    foreach($resultado as $fila){

        $sub_array = array();
        $sub_array[] = $fila["id"];
        $sub_array[] = $fila["id_empleado"];
        $sub_array[] = $fila["fecha"];
        $sub_array[] = $fila["tipo"];
        $sub_array[] = $fila["cantidad"];
        
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