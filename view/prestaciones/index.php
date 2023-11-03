<?php
    if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
        session_start();
        $_SESSION["Titulo"] = "Prestaciones";
    }

    require_once("../master/head_mtto.php")
?>
        <div class="table-responsive">
            <table id="datos_usuario" class="table table-bordered table-striped table-sm">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Porcentaje</th>
                        <th>Monto Fijo</th>
                        <th>Techo Inferior</th>
                        <th>Techo Superior</th>
                        <th>Estado</th>
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
        <h5 class="modal-title" id="exampleModalLabel">Crear Prestaciones</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
     
        <form method="POST" id="formulario" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-body">
                <label for="nombre">Ingrese el nombre</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required>
                    <br />

                    <label for="porcentaje">Ingrese el porcentaje</label>
                    <input type="number" step="any" min="0.01" max="100" name="porcentaje" id="porcentaje" class="form-control" required>
                    <br />

                    <label for="monto">Ingrese el monto fijo:</label>
                    <input type="number" step="any" min="0.00" max="9999.99" name="monto" id="monto" class="form-control" required>
                    <br />

                    <label for="techo_inferior">Ingrese el techo inferior:</label>
                    <input type="number" step="any" min="0.01" max="9999.99" name="techo_inferior" id="techo_inferior" class="form-control" required>
                    <br />
                    
                    <label for="techo_superior">Ingrese el techo superior:</label>
                    <input type="number" step="any" min="0.01" max="99999.99" name="techo_superior" id="techo_superior" class="form-control" required>
                    <br />

                    <label for="estado">Estado de prestación:&nbsp;</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="estado" id="estadoA" value="A" checked>
                            <label class="form-check-label" for="estado1">Activo</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="estado" id="estadoI" value="I" >
                            <label class="form-check-label" for="estado2">Inactivo</label>
                        </div>
                    <br />

                <div class="modal-footer">
                    <input type="hidden" name="id_prestacion" id="id_prestacion">
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
                $(".modal-title").text("Crear Prestación");
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
                        $(".modal-title").text("Crear Prestación");
                        $("#action").val("Crear");
                        $("#operacion").val("Crear");
                        $("#imagen_subida").html("");
                    });
                },
                "processing":true,
                "serverSide":true,
                "order":[],
                "ajax":{
                    url: "../../controller/prestaciones/obtener_registros.php",
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
            $(':disabled').each(function(event) {
                $(this).removeAttr('disabled');
            });
            event.preventDefault();
            var nombre = $('#nombre').val();
            var porcentaje = $('#porcentaje').val();
            var monto = $('#monto').val();
            var techo_inferior = $('#techo_inferior').val();
            var techo_superior = $('#techo_superior').val();
            var estado = $('#estado').val();
		    if(nombre != '' && porcentaje != '' && monto != '' && techo_inferior != '' && techo_superior != '' && estado != '')
                {
                    $.ajax({
                        url:"../../controller/prestaciones/crear.php",
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
                    alert("Todos los campos son obligatorios");
                }
	        });

            //Funcionalidad de editar
            $(document).on('click', '.editar', function(){		
            var id_prestacion = $(this).attr("id");		
            $.ajax({
                url:"../../controller/prestaciones/obtener_registro.php",
                method:"POST",
                data:{id_prestacion:id_prestacion},
                dataType:"json",
                success:function(data)
                    {
                        //console.log(data);				
                        $('#modalUsuario').modal('show');
                        $('#nombre').val(data.nombre);
                        $('#porcentaje').val(data.porcentaje);
                        $('#monto').val(data.monto);
                        $('#techo_inferior').val(data.techo_inferior);
                        $('#techo_superior').val(data.techo_superior);

                        if (data.estado === 'A') {
                            $('#estadoA').prop('checked', true);
                        } else {
                            $('#estadoI').prop('checked', true);
                        }

                        $('.modal-title').text("Editar Prestación");
                        $('#id_prestacion').val(id_prestacion);
                        $('#action').val("Editar");
                        $('#operacion').val("Editar");
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    }
                })
	        });

            //Funcionalidad de borrar
            $(document).on('click', '.borrar', function(){
                var id_prestacion = $(this).attr("id");
                if(confirm("Esta seguro de borrar este registro:" + id_prestacion))
                {
                    $.ajax({
                        url:"../../controller/prestaciones/borrar.php",
                        method:"POST",
                        data:{id_prestacion:id_prestacion},
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
            $('#tab_prestaciones').addClass('active');
        });         
    </script>
    
<?php
    require_once("../master/footer.php")
?>