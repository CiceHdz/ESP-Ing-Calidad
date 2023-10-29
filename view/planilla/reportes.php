<?php

    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION["Titulo"] = "Reportes";
    }

    require_once("../master/head_mtto.php")
?>
    <?php
    if (($_SESSION['id_rol'] == '1' || $_SESSION['id_rol'] == '2' || $_SESSION['id_rol'] == '3') || ($_SESSION['id_rol'] == '5' && isset($_SESSION['id_empleado']) && $_SESSION['id_empleado'] != '0')) {
        
        if (isset($_SESSION['totalplanilla'])) {
            echo "<center>El total de la planilla este mes es de: <b>" . $_SESSION['totalplanilla'] . "</b></center>";
        }
    } else {
        echo 'No tiene permisos para ver información de planillas.<br/>Solicite que su usuario sea asociado con un empleado para ver la información correspondiente.';
    }
    
    ?>

    <form id="indemnizaciones">
        <label for="id_empleado">Seleccione el empleado:&nbsp;</label>
        <select class="form-select" name="id_empleado" id="id_empleado">
            <option value="0">Elija un empleado...</option>
            <?php 
            include_once("../../model/conexion.php");
            $query = "SELECT id_empleado, nombres FROM empleados where estado = 'I' ";
            $stmt = $conexion->prepare($query);
            $stmt->execute();
            $resultado = $stmt->fetchAll();
            $datos = array();
            $filtered_rows = $stmt->rowCount();
            foreach($resultado as $fila){
                $id_empleado = $fila["id_empleado"];
                $nombres = $fila["nombres"];

                echo "<option value='".$id_empleado."' >".$nombres."</option>";
            }
            ?>
        </select>
        <br />
        <input type="submit" name="action" id="action" class="btn btn-success" value="Generar Reporte" onclick="return genIndemnizaciones(0);">
    </form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<!--<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>-->
<!-- Bootstrap Date-Picker Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js" integrity="sha512-5pjEAV8mgR98bRTcqwZ3An0MYSOleV04mwwYj2yw+7PBhFVf/0KcE+NEox0XrFiU5+x5t5qidmo5MgBkDD9hEw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>-->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <script type="text/javascript">
        function genBoleta(id) {
            window.open('http://localhost/RRHH.IBD115-D/controller/planilla/planilla-pdf.php?id='+id,'_blank');
        };

        function genIndemnizaciones(id) {
            window.open('http://localhost/RRHH.IBD115-D/controller/planilla/indemnizaciones-pdf.php?id='+id,'_blank');
        };
        
        $(document).ready(function(){
            var dataTable = $('#datos_costeo').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-plus-circle" aria-hidden="true"></i> Copiar',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Excel',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-csv" aria-hidden="true"></i> CSV',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> PDF',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
                        }
                    }
                ],
                initComplete: function() { 
                    var btns = $('.dt-button');
                    btns.removeClass('dt-button');
                },
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url: "../../controller/planilla/obtener_registros.php",
                    type: "POST"
                },
                "columnsDefs":[
                    {
                    "targets":[0, 3, 4],
                    "orderable":false,
                    },
                ],
                "language": {
                "decimal": "",
                "emptyTable": "No hay registros",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
                "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                "infoPostFix": "",
                "thousands": ",",
                "lengthMenu": "Mostrar _MENU_ Entradas",
                "loadingRecords": "Cargando...",
                "processing": "Procesando...",
                "search": "Buscar:",
                "zeroRecords": "Sin resultados encontrados",
                "paginate": {
                    "first": "Primero",
                    "last": "Ultimo",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
            });

            var navs = $('.nav-link');
            navs.removeClass('active');
            $('#tab_reportes').addClass('active');
        });         
    </script>
    
<?php
    require_once("../master/footer.php")
?>