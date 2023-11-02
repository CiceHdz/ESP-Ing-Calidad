<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_usuario"]))
{

	$stmt = $conexion->prepare(
		"EXECUTE PROC_DEL_USUARIO :id_usuario"
	);
	$resultado = $stmt->execute(
		array(
			':id_usuario'	=>	$_POST["id_usuario"]
		)
	);
	
	if(!empty($resultado))
	{
		echo 'Registro borrado';
	}
}



?>