<?php 
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo (isset($_SESSION["Titulo"])) ? $_SESSION["Titulo"] . " - RRHH IBD115-D" : "Mantenimiento"; ?></title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/styles.css" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker3.min.css" integrity="sha512-rxThY3LYIfYsVCWPCW9dB0k+e3RZB39f23ylUYTEuZMDrN/vRqLdaCBo/FbvVT6uC2r0ObfPzotsfKF9Qc5W5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" />
</head>
<body>
    <div class="container-fluid" style="background-color: black; height: 50px;">
        <img src="../assets/header.jpg" class="img-fluid" alt="Responsive image" style="height: 50px;" >
    </div>    

    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <?/*php include("menu_vertical.php") */?>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            
            <!-- Top navigation-->
           
                
                    <!--<button class="btn btn-primary" id="sidebarToggle">Mostrar/Ocultar Menu</button>-->
                    <!--<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>-->
                    
                        <ul class="nav nav-tabs justify-content-end bg-light">
                            <!--<li class="nav-item active"><a class="nav-link" href="../home/index.php">Inicio</a></li>-->
                            <li class="nav-item"><a class="nav-link active" href="#!">¡Bienvenido, <?php echo (isset($_SESSION) && isset($_SESSION['nombres'])) ? $_SESSION['nombres'] : 'usuario'; ?>!</a>

                            <?php /*
                                include("../../model/conexion.php");
                                include("../../controller/usuarios/funciones.php");

                                if (isset($_SESSION["id_usuario"])) {
                                    $salida = array();
                                    $stmt = $conexion->prepare("SELECT id_usuario, nombres, apellidos, imagen, id_rol, email, password, fecha_creacion, id_empleado FROM usuarios WHERE id_usuario = '".$_SESSION["id_usuario"]."' LIMIT 1");
                                    $stmt->execute();
                                    $resultado = $stmt->fetchAll();
                                    foreach($resultado as $fila){
                                        if ($fila["imagen"] != "") {
                                            echo '<img src="../../controller/usuarios/img/' . $fila["imagen"] . '"  class="img-thumbnail" width="30" height="10" /><input type="hidden" name="imagen_usuario_oculta" value="'.$fila["imagen"].'" />';
                                        }else{
                                            echo '<input type="hidden" name="imagen_usuario_oculta" value="" />';
                                        }
                                        $salida["id_empleado"] = $fila["id_empleado"];
                                    }
                                }*/
                            ?>
                            </li>

                            
                            <?php
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2' || $_SESSION['id_rol'] == '3')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_planilla" href="../planilla/index.php">Planilla</a></li>';
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_costeo" href="../costeo/index.php">Costos</a></li>';
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_ausencias" href="../ausencias/index.php">Ausencias</a></li>';
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '3' || $_SESSION['id_rol'] == '4')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_deptos" href="../departamentos/index.php">Áreas</a></li>';
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '3' || $_SESSION['id_rol'] == '4')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_empleados" href="../empleados/index.php">Empleados</a></li>';
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '4')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_usuarios" href="../usuarios/index.php">Usuarios</a></li>
                                    <li class="nav-item"><a class="nav-link" id="tab_roles" href="../roles/index.php">Permisos</a></li>'
                                    ;
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2' || $_SESSION['id_rol'] == '3')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_indem" href="../planilla/indemnizaciones.php">Indemnizaciones</a></li>';
                                }

                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2' || $_SESSION['id_rol'] == '3')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_horExtra" href="../horasExtras/index.php">Horas Extras</a></li>';
                                }
                                if (isset($_SESSION) && ($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2' || $_SESSION['id_rol'] == '3')) {
                                    echo '<li class="nav-item"><a class="nav-link" id="tab_permisos" href="../permisos/index.php">Asignación de Permisos</a></li>';
                                }
                            ?>

                            <?php 
                            include_once("../../model/conexion.php");
                            if (isset($_SESSION) && ($_SESSION['id_rol'] == '1')) {
                                $query = "SELECT nombre, ruta, tab_name FROM MODULOS m ORDER BY id asc";
                            } else {
                                $query = "SELECT nombre, ruta, tab_name FROM MODULOS m WHERE m.id in (select r.ID_MODULO from ROLES_MODULO r where ID_ROL = ".$_SESSION['id_rol']." and PUEDE_CONSULTAR = 'S') ORDER BY id asc";
                            }
                            
                            $stmt = $conexion->prepare($query);
                            $stmt->execute();
                            $resultado = $stmt->fetchAll();
                            $datos = array();
                            $filtered_rows = $stmt->rowCount();
                            foreach($resultado as $fila){
                                echo '<li class="nav-item"><a class="nav-link" id="'.$fila["tab_name"].'" href="'.$fila["ruta"].'">'.$fila["nombre"].'</a></li>';
                            }
                            ?>
                            <li class="nav-item"><a class="nav-link" href="../../logout.php">Cerrar Sesión</a></li>
                        </ul>
                    
                
            
            <!-- Page content-->

            <!-- Page content-->
            <div class="container-fluid fondo">
                <h3 class="mt-4 bg-dark text-light">&nbsp;<i class="fa-brands fa-uncharted"></i>&nbsp;<?php echo (isset($_SESSION["Titulo"])) ? $_SESSION["Titulo"] : "Mantenimiento"; ?></h3>
                