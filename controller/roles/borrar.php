<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_rol"]))
{

	$stmt = $conexion->prepare(
		"EXECUTE PROC_DEL_ROL :id_rol"
	);
	$resultado = $stmt->execute(
		array(
			':id_rol'	=>	$_POST["id_rol"]
		)
	);
	
	if(!empty($resultado))
	{
		echo 'Registro borrado';
	}
}



?>