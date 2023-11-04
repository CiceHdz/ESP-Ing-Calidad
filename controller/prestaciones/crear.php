<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
    $techo_inferior = $_POST["techo_inferior"];
    $techo_superior = $_POST["techo_superior"];

    if ($techo_superior <= $techo_inferior) {
        echo "El valor del techo superior debe ser mayor que el techo inferior.";
    } else {

    $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_PRESTACION :id_prestacion, :nombre, :porcentaje, :monto, :techo_inferior, :techo_superior, :estado");

    $resultado = $stmt->execute(
        array(
            ':id_prestacion'    => $_POST["id_prestacion"],
            ':nombre'           => $_POST["nombre"],
            ':porcentaje'       => $_POST["porcentaje"],
            ':monto'            => $_POST["monto"],
            ':techo_inferior'   => $techo_inferior,
            ':techo_superior'   => $techo_superior,
            // ':techo_inferior'   => $_POST["techo_inferior"],
            // ':techo_superior'   => $_POST["techo_superior"],
            ':estado'           => $_POST["estado"]
        )
    );

    if (!empty($resultado)) {
        if ($_POST["operacion"] == "Crear") {
            echo 'Registro creado';
        } else {
            echo 'Registro actualizado';
        }
    }
}}