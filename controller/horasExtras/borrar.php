<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_horEx"]))
{

	$stmt = $conexion->prepare(
		"EXECUTE PROC_DE_HORAEX :id_horEx"
	);
	$resultado = $stmt->execute(
		array(
			':id_horEx'	=>	$_POST["id_horEx"]
		)
	);
	
	if(!empty($resultado))
	{
		echo 'Registro borrado';
	}
}



?>