<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
    $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_ROLES :id_rol, :nombre, :estado");

    $resultado = $stmt->execute(
        array(
            ':id_rol'    => $_POST["id_rol"],
            ':nombre'    => $_POST["nombre"],
            ':estado'    => $_POST["estado"]
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
