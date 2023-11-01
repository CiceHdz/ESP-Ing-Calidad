<?php

    function obtener_todos_registros(){
        include("../../model/conexion.php");
        $stmt = $conexion->prepare("SELECT * FROM AUSENCIAS");
        $stmt->execute();
        $resultado = $stmt->fetchAll(); 
        return $stmt->rowCount();       
    }