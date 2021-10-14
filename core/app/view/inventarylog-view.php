<?php
if(!isset($_SERVER['HTTP_REFERER'])){
    print "<script>window.location='index.php?view=home';</script>";
    exit;
}
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > ConfigurationData::getByPreffix("session")->val))
{
    session_destroy();
    session_unset();
	Core::alert("¡Su sesión ha expirado!");
	print "<script>window.location='index.php?view=login';</script>";
}
else {
	$_SESSION['start'] = time();
}
?>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>Movimientos de Productos</h2>
                    <p>Entrada y Salida de Productos</p>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li>
                            <a href="./index.php?view=home">
                                Inicio
                                <i class="zmdi zmdi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="active-page">Movimientos de Productos</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header clearfix">
                        <h3>CONSULTA DE MOVIMIENTOS</h3>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <table id="example" class="table data-tbl">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descripción</th>
                                        <th>Tipo Operación</th>
                                        <th>Cantidad</th>
                                        <th>Fecha</th>
										<th>Estado</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Footer Start Here -->
        <footer class="footer-container">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="footer-left">
                            <span>© 2021 A&K Global Services</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--Footer End Here -->
</section>
<!--Page Container End Here-->

<script>
    $(document).ready(function(){
        if ($.fn.dataTable) {
            $('.data-tbl').DataTable({
                responsive: true,
                "columnDefs": [ { "targets": [1, 2], "orderable": false }],
                "oLanguage": {
                    "sLengthMenu": '<select class="tbl-data-select">' +
                    '<option value="10">10</option>' +
                    '<option value="20">20</option>' +
                    '<option value="30">30</option>' +
                    '<option value="40">40</option>' +
                    '<option value="50">50</option>' +
                    '<option value="-1">All</option>' +
                    '</select>' + '<span class="r-label">Registros</span>',
                    "sZeroRecords": "No se encontraron resultados",
                    "sEmptyTable": "Ningún dato disponible en esta tabla",
                    "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                    "sSearch": "Buscar:",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst": "Primero",
                        "sLast": "Último",
                        "sNext": "Siguiente",
                        "sPrevious": "Anterior"
                    }
                },
                "processing": false,
                "serverSide": false,
                "bPaginate": false,
                "bFilter": false, 
                "bInfo": false,
                "ajax":{
                    url :"./?action=dtgetinventarylog", // json datasource
                    type: "post"
                },
                "columns": [
                    { "data": "barcode" },
                    { "data": "name" },
                    { "data": "opname" },
                    { "data": "q" },
                    { "data": "created_at" },
                    {
                        "data": "status",
                        "render" : function ( data, type, full) {

                            if(data == 1){
                                return '<label class="label label-primary">ACTIVO</label>';
                            }else if(data == 2){
                                return '<label class="label label-danger">INACTIVO</label>';
                            }
                            else if (data == 5) {
                                return '<label class="label label-danger">CANCELADO</label>';
                            }
                            else {
                                return '';
                            }

                        }
                    }
                ],
                "dom": '<"row" <"col-md-6"l><"col-md-6"f>><"row" <"col-md-12"<"td-content"rt>>><"row" <"col-md-6"i><"col-md-6"p>>'
            });
        }
    });

    $(document).ajaxComplete(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>