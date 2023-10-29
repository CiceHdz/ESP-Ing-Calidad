<?php

   
    function obtener_todos_registros(){
        include("../../model/conexion.php");
        $stmt = $conexion->prepare("SELECT id_empleado FROM empleados");
        $stmt->execute();
        $resultado = $stmt->fetchAll(); 
        return $stmt->rowCount();       
    }