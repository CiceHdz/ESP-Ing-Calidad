<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_permiso"]))
{
	$stmt = $conexion->prepare(
		"DELETE FROM ROLES_MODULO WHERE id = :id_permiso"
	);
	$resultado = $stmt->execute(
		array(
			':id_permiso'	=>	$_POST["id_permiso"]
		)
	);
	
	if(!empty($resultado))
	{
		echo 'Registro borrado';
	}
}

?>