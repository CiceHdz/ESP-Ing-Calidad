<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_permiso"]))
{
	$stmt = $conexion->prepare(
		"EXECUTE PROC_DEL_PERMISO :id_permiso"
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