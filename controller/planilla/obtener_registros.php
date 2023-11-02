<?php
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION["Titulo"] = "Planilla";
    }
    
    include("../../model/conexion.php");
    include("funciones.php");

    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $query = "";

    $salida = array();
    $query = "SELECT Id_Empleado, Nombre, Fecha_Contratacion, Tiempo_Laborado, Salario, AFP, ISSS, Renta, Salario_Neto
                FROM FN_OBT_PLANILLA() ";
    
    /*if (($_SESSION['id_rol'] == '4' || $_SESSION['id_rol'] == '5') && isset($_SESSION['id_empleado'])) {
        $query .= 'AND id_empleado = ' . $_SESSION['id_empleado'] . ' ';
    }
    
    if (isset($_POST["search"]["value"])) {
        $query .= 'AND CONCAT(apellidos,\' \', nombres) LIKE "%' . $_POST["search"]["value"] . '%" ';
        
    }

    if (isset($_POST["order"])) {
        $query .= 'ORDER BY ' . $_POST['order']['0']['column'] .' '.$_POST["order"][0]['dir'] . ' ';        
    }else{
        $query .= 'ORDER BY nombres ASC ';
    }

    if($_POST["length"] != -1){
        $query .= 'LIMIT ' . $_POST["start"] . ','. $_POST["length"];
    }*/

    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $datos = array();
    $filtered_rows = $stmt->rowCount();
    $totalPlanilla = 0;

    foreach($resultado as $fila){
        $sub_array = array();
        $sub_array[] = $fila["Nombre"];
        $sub_array[] = $fila["Fecha_Contratacion"];
        $sub_array[] = $fila["Tiempo_Laborado"];
        $sub_array[] = "$".number_format(round($fila["Salario"], 2), 2);
        $sub_array[] = "$".number_format(round($fila["AFP"], 2), 2);
        $sub_array[] = "$".number_format(round($fila["ISSS"], 2), 2);
        $sub_array[] = "$".number_format(round($fila["Renta"], 2), 2);
        $sub_array[] = "$".number_format(round($fila["Salario_Neto"], 2), 2);

        //$sub_array[] = "$".number_format(round($salarioNeto, 2), 2);

        $sub_array[] = '<button class="btn btn-success btn-sm" onclick="return genBoleta('.$fila["Id_Empleado"].');"><i class="fa fa-file-pdf" aria-hidden="true"></i> Descargar</button>';

        $totalPlanilla = $totalPlanilla + $fila["Salario"];

        $datos[] = $sub_array;
    }

    if (isset($_SESSION)) {
        $_SESSION['totalplanilla'] = "$".number_format(round($totalPlanilla, 2), 2);
    }

    $salida = array(
        "draw"               => intval($_POST["draw"]),
        "recordsTotal"       => $filtered_rows,
        "recordsFiltered"    => obtener_todos_registros(),
        "data"               => $datos
    );

    echo json_encode($salida);