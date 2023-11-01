<?php

include("../../model/conexion.php");
include("funciones.php");

if(isset($_POST["id_ausencia"]))
{
	/*$imagen = obtener_nombre_imagen($_POST["id_usuario"]);
	if($imagen != '')
	{
		unlink("img/" . $imagen);
	}*/
	$stmt = $conexion->prepare(
		"EXECUTE PROC_DEL_AUSENCIAS :id_ausencia"
	);
	$resultado = $stmt->execute(
		array(
			':id_ausencia'	=>	$_POST["id_ausencia"]
		)
	);
	
	if(!empty($resultado))
	{
		echo 'Registro borrado';
	}
}
