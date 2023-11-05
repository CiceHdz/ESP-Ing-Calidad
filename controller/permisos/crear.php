<?php

include("../../model/conexion.php");
include("funciones.php");

try{
    if ($_POST["operacion"] == "Crear" or $_POST["operacion"] == "Editar") {
        $stmt = $conexion->prepare("EXECUTE PROC_INS_UPD_PERMISOS :id_permiso, :id_rol, :id_modulo, :estado_c, :estado_u, :estado_d, :estado_r");

        $resultado = $stmt->execute(
            array(
                ':id_permiso'    => $_POST["id_permiso"],
                ':id_rol'    => $_POST["id_rol"],
                ':id_modulo'    => $_POST["id_modulo"],
                ':estado_c'    => $_POST["estado_c"],
                ':estado_u'    => $_POST["estado_u"],
                ':estado_d'    => $_POST["estado_d"],
                ':estado_r'    => $_POST["estado_r"]
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

} catch(Exception $e){
    //echo $e -> getMessage();
    echo "Permiso existente! Asigne un permiso para un rol y módulo diferente.";
    
}


// if ($_POST["operacion"] == "Editar") {
//     $stmt = $conexion->prepare("UPDATE ROLES_MODULO SET ID_ROL=:id_rol, ID_MODULO=:id_modulo, PUEDE_INSERTAR=:estado_c, PUEDE_ACTUALIZAR=:estado_u, PUEDE_ELIMINAR=:estado_d, PUEDE_CONSULTAR=:estado_r WHERE ID_ROL = '".$_POST["id_rol"]."' AND ID_MODULO = '".$_POST["id_modulo"]."' ");

//     $resultado = $stmt->execute(
//         array(
//             ':id_rol'    => $_POST["id_rol"],
//             ':id_modulo'    => $_POST["id_modulo"],
//             ':estado_c'    => $_POST["estado_c"],
//             ':estado_u'    => $_POST["estado_u"],
//             ':estado_d'    => $_POST["estado_d"],
//             ':estado_r'    => $_POST["estado_r"]
//         )
//     );

//     if (!empty($resultado)) {
//         echo 'Registro actualizado';
//     }
// }