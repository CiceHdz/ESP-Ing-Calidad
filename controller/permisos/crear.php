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
    $query = "SELECT r.nombre as rol, m.nombre as modulo
                FROM roles r
                    INNER JOIN ROLES_MODULO rm ON rm.ID_ROL=r.ID_ROL
                    INNER JOIN MODULOS m on rm.ID_MODULO=m.id
                WHERE rm.id = 1";

    $stmt = $conexion->prepare($query);
    $stmt->execute();
    $resultado = $stmt->fetchAll();
    $datos = array();
    $filtered_rows = $stmt->rowCount();
    foreach($resultado as $fila){
        $nombreRol = $fila["rol"];
        $nombreModulo = $fila["modulo"];

        echo "El permiso ya existe para el rol '".$nombreRol."' y modulo '".$nombreModulo."'. Asigne un permiso para un rol y modulo diferente.";
        //echo "<option value='".$id_rol."' >".$nombre."</option>";
    }
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