<?php

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("../../model/conexion.php");
include("funciones.php");

if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {

    $imagen = '';
    if ($_FILES["imagen_usuario"]["name"] != '') {
        $imagen = subir_imagen();
    } else {
        $imagen = NULL;
    }

    $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_USUARIOS :id_usuario, :nombres, :apellidos, :email, :password, :id_rol, :imagen, :id_empleado");

    $resultado = $stmt->execute(
        array(
            ':id_usuario'    => $_POST["id_usuario"],
            ':nombres'    => $_POST["nombres"],
            ':apellidos'    => $_POST["apellidos"],
            ':email'    => $_POST["email"],
            ':password'    => $_POST["password"],
            ':id_rol'    => $_POST["id_rol"],
            ':imagen'    => $imagen,            
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
