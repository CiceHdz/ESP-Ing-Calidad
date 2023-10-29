<?php
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION["Titulo"] = "Usuarios";
    }

    require_once("../master/head_mtto.php")
?>
        <div class="table-responsive">
            <table id="datos_usuario" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th></th>    
                        <th>Id</th>
                        <th>Nombres</th>
                        <th>Apellidos</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Fecha Creación</th>
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
        <h5 class="modal-title" id="exampleModalLabel">Crear Usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
        <form method="POST" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-body">
                    <label for="nombres">Ingrese el nombre</label>
                    <input type="text" name="nombres" id="nombres" class="form-control">
                    <br />

                    <label for="apellidos">Ingrese los apellidos</label>
                    <input type="text" name="apellidos" id="apellidos" class="form-control">
                    <br />

                    <label for="email">Ingrese el email</label>
                    <input type="email" name="email" id="email" class="form-control">
                    <br />

                    <label for="password">Ingrese el password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <br />

                    <!--<label for="id_rol">Id del rol</label>
                    <input type="text" name="id_rol" id="id_rol" class="form-control">
                    <br />-->

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

                    <label for="id_empleado">Seleccione el empleado:&nbsp;</label>
                    <select class="form-select" name="id_empleado" id="id_empleado">
                        <option value="0">Elija un empleado...</option>
                        <?php 
                        include_once("../../model/conexion.php");
                        $query = "SELECT id_empleado, nombres FROM empleados where estado = 'A' ";
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

                    <label for="imagen">Seleccione una imagen</label>
                    <input type="file" name="imagen_usuario" id="imagen_usuario" class="form-control">
                    <span id="imagen_subida"></span>
                    <br />
                </div>

                <div class="modal-footer">
                    <input type="hidden" name="id_usuario" id="id_usuario">
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
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel" aria-hidden="true"></i> Excel',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 6 ]
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="fa fa-file-csv" aria-hidden="true"></i> CSV',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 6 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf" aria-hidden="true"></i> PDF',
                        className: 'btn btn-primary btn-sm',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4, 5, 6 ]
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
                        $(".modal-title").text("Crear Usuario");
                        $("#action").val("Crear");
                        $("#operacion").val("Crear");
                        $("#imagen_subida").html("");
                    });
                },
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url: "../../controller/usuarios/obtener_registros.php",
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
            var nombres = $('#nombres').val();
            var apellidos = $('#apellidos').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var id_rol = $('#id_rol').val();
            var id_empleado = $('#id_empleado').val();            
            var extension = $('#imagen_usuario').val().split('.').pop().toLowerCase();
            if(extension != '')
            {
                if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
                {
                    alert("Fomato de imagen inválido");
                    $('#imagen_usuario').val('');
                    return false;
                }
            }	
		    if(nombres != '' && apellidos != '' && email != '' && password != '')
                {
                    $.ajax({
                        url:"../../controller/usuarios/crear.php",
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
            var id_usuario = $(this).attr("id");		
            $.ajax({
                url:"../../controller/usuarios/obtener_registro.php",
                method:"POST",
                data:{id_usuario:id_usuario},
                dataType:"json",
                success:function(data)
                    {
                        console.log(data);				
                        $('#modalUsuario').modal('show');
                        $('#nombres').val(data.nombres);
                        $('#apellidos').val(data.apellidos);
                        $('#email').val(data.email);
                        $('#password').val(data.password);
                        $('#id_rol').val(data.id_rol);
                        $('#id_empleado').val(data.id_empleado);
                        $('.modal-title').text("Editar Usuario");
                        $('#id_usuario').val(id_usuario);
                        $('#imagen_subida').html(data.imagen_usuario);
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
                var id_usuario = $(this).attr("id");
                if(confirm("Esta seguro de borrar este registro:" + id_usuario))
                {
                    $.ajax({
                        url:"../../controller/usuarios/borrar.php",
                        method:"POST",
                        data:{id_usuario:id_usuario},
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
            $('#tab_usuarios').addClass('active');
        });         
    </script>
    
<?php
    require_once("../master/footer.php")
?>