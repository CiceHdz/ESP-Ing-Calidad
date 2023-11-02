<?php
    function obtener_todos_registros(){
        include("../../model/conexion.php");
        $stmt = $conexion->prepare("SELECT * FROM ROLES_MODULO");
        $stmt->execute();
        $resultado = $stmt->fetchAll(); 
        return $stmt->rowCount();       
    }