<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
    $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_AUSENCIAS :id_ausencia, :fecha, :id_empleado, :comentario, :tipo");

    $resultado = $stmt->execute(
        array(    
            ':id_ausencia'    => $_POST["id_ausencia"],
            ':fecha'    => $_POST["fecha"],
            ':id_empleado'    => $_POST["id_empleado"],
            ':comentario'    => $_POST["comentario"],
            ':tipo'    => $_POST["tipo"]
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
