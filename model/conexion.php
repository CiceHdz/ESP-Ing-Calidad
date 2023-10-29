<?php

    /*$usuario = "root";
    $password = "";
    $conexion = new PDO("mysql:host=localhost;dbname=rrhh", $usuario, $password);*/
	
    // Definir el DSN con los datos del servidor y la base de datos
    $dsn = "sqlsrv:Server=localhost;Database=rrhh";
    // Definir el usuario y la contraseña
    $usuario = "sa";
    $password = "123456";
    // Crear una instancia de PDO con el DSN, el usuario y la contraseña
    $conexion = new PDO($dsn, $usuario, $password);
    // Si hay algún error, lanzar una excepción
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);