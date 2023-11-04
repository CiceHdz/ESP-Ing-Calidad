<?php

$k = "SQLSTATE[23000]: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Instrucción UPDATE en conflicto con la restricción CHECK 'CHK_NOCTURNA'. El conflicto ha aparecido en la base de datos 'rrhh', tabla 'dbo.HORAS_EXTRA'.";
$w = "SQLSTATE[23000]: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Instrucción INSERT en conflicto con la restricción CHECK 'CHK_NOCTURNA'. El conflicto ha aparecido en la base de datos 'rrhh', tabla 'dbo.HORAS_EXTRA'.";

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

try{
    if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
        $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_HORAEXTRA :id_horEx, :fecha_horaExtra, :tipo, :cantidad, :id_empleado");
    
        $resultado = $stmt->execute(
            array(
                ':id_horEx'    => $_POST["id_horEx"],
                ':fecha_horaExtra'    => $_POST["fecha_horaExtra"],
                ':tipo'    => $_POST["tipo"],
                ':cantidad'    => $_POST["cantidad"],
                ':id_empleado'    => $_POST["id_empleado"]
            )
        );
    
        if (!empty($resultado)) {
            if ($_POST["operacion"] == "Crear") {
                echo 'Registro creado';
            } else {
                echo 'Registro actualizado';
            }
        }
    }
}catch (Exception $e){
    if($e -> getMessage() == $k){
        echo 'El numero de horas extras nocturnas no debe ser mayor a 11 horas';
    }
    else if($e -> getMessage() == $w){
        echo 'El numero de horas extras nocturnas no debe ser mayor a 11 horas';
    }
    else{
        echo 'El numero de horas diurnas no debe ser mayor a 13 horas';
    }
}