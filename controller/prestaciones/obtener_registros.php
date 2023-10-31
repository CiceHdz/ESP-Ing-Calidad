<?php

    include("../../model/conexion.php");
    include("funciones.php");

    $query = "";
    $salida = array();
    $query = "SELECT id_prestacion, nombre, porcentaje, monto, techo_inferior, techo_superior, CASE estado WHEN 'A' THEN 'Activo' ELSE 'Inactivo' END estado FROM PRESTACIONES pre";

    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $datos = array();
    $filtered_rows = $stmt->rowCount();
    foreach($resultado as $fila){

        $sub_array = array();
        $sub_array[] = $fila["id_prestacion"];
        $sub_array[] = $fila["nombre"];
        $sub_array[] = number_format($fila["porcentaje"], 2) . "%";
        $sub_array[] = "$".number_format(round($fila["monto"], 2), 2);
        $sub_array[] = "$".number_format(round($fila["techo_inferior"], 2), 2);
        $sub_array[] = "$".number_format(round($fila["techo_superior"], 2), 2);
        $sub_array[] = $fila["estado"];
        $sub_array[] = '<button type="button" name="editar" id="'.$fila["id_prestacion"].'" class="btn btn-primary btn-sm editar"><i class="fa-regular fa-pen-to-square"></i> Editar</button>';
        $sub_array[] = '<button type="button" name="borrar" id="'.$fila["id_prestacion"].'" class="btn btn-danger btn-sm borrar"><i class="fa-regular fa-trash-can"></i> Borrar</button>';
        $datos[] = $sub_array;
    }

    $salida = array(
        "draw"               => intval($_POST["draw"]),
        "recordsTotal"       => $filtered_rows,
        "recordsFiltered"    => obtener_todos_registros(),
        "data"               => $datos
    );

    echo json_encode($salida);