<?php
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION["Titulo"] = "Asignación de permisos";
    }

    require_once("../master/head_mtto.php")
?>
        <div class="table-responsive">
            <table id="datos_usuario" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rol</th>
                        <th>Módulo</th>
                        <th>Consulta</th>
                        <th>Inserción</th>
                        <th>Actualización</th>
                        <th>Eliminación</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
   


<!-- Modal -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Crear permisos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
        <form method="POST" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-body">

                    <label for="id_rol">Seleccione el rol:&nbsp;</label>
                    <select class="form-select" name="id_rol" id="id_rol">
                        <option value="0">Elija un rol...</option>
                        <?php 
                        include_once("../../model/conexion.php");
                        $query = "SELECT id_rol, nombre FROM roles where estado = 'A' ";
                        $stmt = $conexion->prepare($query);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();
                        $datos = array();
                        $filtered_rows = $stmt->rowCount();
                        foreach($resultado as $fila){
                            $id_rol = $fila["id_rol"];
                            $nombre = $fila["nombre"];

                            echo "<option value='".$id_rol."' >".$nombre."</option>";
                        }
                        ?>
                    </select>
       

                    <br />
                    <label for="id_modulo">Seleccione el modulo:&nbsp;</label>
                    <select class="form-select" name="id_modulo" id="id_modulo">
                        <option value="0">Elija un modulo...</option>
                        <?php 
                        include_once("../../model/conexion.php");
                        $query = "SELECT id, nombre FROM modulos where estado = 'A' ";
                        $stmt = $conexion->prepare($query);
                        $stmt->execute();
                        $resultado = $stmt->fetchAll();
                        $datos = array();
                        $filtered_rows = $stmt->rowCount();
                        foreach($resultado as $fila){
                            $id_modulo = $fila["id"];
                            $nombre = $fila["nombre"];

                            echo "<option value='".$id_modulo."' >".$nombre."</option>";
                        }
                        ?>
                    </select>

                    <br />
                    <label for="estado">Permisos para visualizar:&nbsp;</label>
                    <!--<input type="text" name="estado" id="estado" class="form-control">-->

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_r" id="estado_rs" value="S" checked>
                        <label class="form-check-label" for="estado1">Si</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_r" id="estado_rn" value="N">
                        <label class="form-check-label" for="estado2">No</label>
                    </div>

                    <br />

                    <label for="estado">Permisos para insertar:&nbsp;</label>
                    <!--<input type="text" name="estado" id="estado" class="form-control">-->

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_c" id="estado_cs" value="S" checked>
                        <label class="form-check-label" for="estado3">Si</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_c" id="estado_cn" value="N">
                        <label class="form-check-label" for="estado4">No</label>
                    </div>

                    <br />
                    <label for="estado">Permisos para actualizar:&nbsp;</label>
                    <!--<input type="text" name="estado" id="estado" class="form-control">-->

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_u" id="estado_us" value="S" checked>
                        <label class="form-check-label" for="estado5">Si</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_u" id="estado_un" value="N">
                        <label class="form-check-label" for="estado6">No</label>
                    </div>

                    <br />
                    <label for="estado">Permisos para eliminar:&nbsp;</label>
                    <!--<input type="text" name="estado" id="estado" class="form-control">-->

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_d" id="estado_ds" value="S" checked>
                        <label class="form-check-label" for="estado7">Si</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="estado_d" id="estado_dn" value="N">
                        <label class="form-check-label" for="estado8">No</label>
                    </div>
                    <br />

                </div>

                <div class="modal-footer">
                    <!-- <input type="hidden" name="id_rol" id="id_rol">
                    <input type="hidden" name="id_modulo" id="id_modulo"> -->
                    <input type="hidden" name="id_permiso" id="id_permiso">
                    <input type="hidden" name="operacion" id="operacion">             
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Crear">
                </div>
            </div>
        </form>
      </div>     
  </div>
</div>

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
        $(document).ready(function(){
            $("#botonCrear").click(function(){
                $("#formulario")[0].reset();
                $(".modal-title").text("Crear Permisos");
                $("#action").val("Crear");
                $("#operacion").val("Crear");
            });
            
            var dataTable = $('#datos_usuario').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: '<i class="fa fa-plus-circle" aria-hidden="true"></i> Crear',
                        action: function () {
                            $('#modalUsuario').modal('show');
                        },
                        className: 'btn btn-success btn-sm my-text-button'
                    },
                    {
                        extend: 'copyHtml5',
                        text: '<i class="fa fa-plus-circle" aria-hidden="true"></i> Copiar',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Excel',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-csv" aria-hidden="true"></i> CSV',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> PDF',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    }
                ],
                initComplete: function() { 
                    var btns = $('.dt-button');
                    btns.removeClass('dt-button');

                    $('.my-text-button') 
                        .attr('data-toggle', 'modal')
                        .attr('data-target', '#modalUsuario')
                        .attr("id", "botonCrear");

                    $("#botonCrear").click(function(){
                        $("#formulario")[0].reset();
                        $(".modal-title").text("Crear Permisos");
                        $("#action").val("Crear");
                        $("#operacion").val("Crear");
                        $("#imagen_subida").html("");
                    });
                },
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url: "../../controller/permisos/obtener_registros.php",
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
            
            //Aquí código inserción
            $(document).on('submit', '#formulario', function(event){
            event.preventDefault();	
            var id_rol = $('#id_rol').val();
            var id_modulo = $('#id_modulo').val();
            var estado_r = $('#estado_r').val();
            var estado_c = $('#estado_c').val();
            var estado_u = $('#estado_u').val();
            var estado_d = $('#estado_d').val();
            
		    if(id_rol != 0 && id_modulo != 0 && estado_r != '' && estado_c != '' && estado_u != '' && estado_d != '')
                {
                    $.ajax({
                        url:"../../controller/permisos/crear.php",
                        method:'POST',
                        data:new FormData(this),
                        contentType:false,
                        processData:false,
                        success:function(data)
                        {
                            alert(data);
                            $('#formulario')[0].reset();
                            $('#modalUsuario').modal('hide');
                            dataTable.ajax.reload();
                        }
                    });
                }
                else
                {
                    alert("Algunos campos son obligatorios");
                }
	        });

            //Funcionalidad de editar
            $(document).on('click', '.editar', function(){		
            var id_permiso = $(this).attr("id");		
            $.ajax({
                url:"../../controller/permisos/obtener_registro.php",
                method:"POST",
                data:{id_permiso:id_permiso},
                dataType:"json",
                success:function(data)
                    {
                        //alert(data);
                        console.log(data)
                        console.log(data.ID_ROL)
                        $('#modalUsuario').modal('show');
                        $('#id_rol option[value="'+data.ID_ROL+'"]').attr("selected", "selected");
                        $('#id_modulo option[value="'+data.ID_MODULO+'"]').attr("selected", "selected");

                        if (data.PUEDE_CONSULTAR === 'S') {
                            $('#estado_rs').prop('checked', true);
                        } else {
                            $('#estado_rn').prop('checked', true);
                        }
                        if (data.PUEDE_INSERTAR === 'S') {
                            $('#estado_cs').prop('checked', true);
                        } else {
                            $('#estado_cn').prop('checked', true);
                        }
                        if (data.PUEDE_ACTUALIZAR === 'S') {
                            $('#estado_us').prop('checked', true);
                        } else {
                            $('#estado_un').prop('checked', true);
                        }
                        if (data.PUEDE_ELIMINAR === 'S') {
                            $('#estado_ds').prop('checked', true);
                        } else {
                            $('#estado_dn').prop('checked', true);
                        }

                        //console.log(data);
                        $('#id_permiso').val(id_permiso);				
                        $('.modal-title').text("Editar Permisos");                        
                        $('#action').val("Editar");
                        $('#operacion').val("Editar");

                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    }
                })
	        });

            //Funcionalida de borrar
            $(document).on('click', '.borrar', function(){
                var id_permiso = $(this).attr("id");
                if(confirm("Esta seguro de borrar este registro:" + id_permiso))
                {
                    $.ajax({
                        url:"../../controller/permisos/borrar.php",
                        method:"POST",
                        data:{id_permiso:id_permiso},
                        success:function(data)
                        {
                            alert(data);
                            dataTable.ajax.reload();
                        }
                    });
                }
                else
                {
                    return false;	
                }
            });

            var navs = $('.nav-link');
            navs.removeClass('active');
            $('#tab_permisos').addClass('active');
        });         
    </script>
    
<?php
    require_once("../master/footer.php")
?>