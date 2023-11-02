<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_prestacion"]))
{

	$stmt = $conexion->prepare(
		"EXECUTE PROC_DEL_PRESTACION :id_prestacion"
	);
	$resultado = $stmt->execute(
		array(
			':id_prestacion'	=>	$_POST["id_prestacion"]
		)
	);
	
	if(!empty($resultado))
	{
		echo 'Registro borrado';
	}
}



?>