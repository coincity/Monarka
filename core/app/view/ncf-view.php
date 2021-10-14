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
                    <h2>Comprobantes Fiscales</h2>
                    <p>Configuración de los Comprobantes Fiscales</p>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li>
                            <a href="./index.php?view=home">
                                Inicio
                                <i class="zmdi zmdi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="active-page">Comprobantes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header clearfix">
                        <h3>CONSULTA DE SECUENCIAS POR TIPO DE COMPROBANTE</h3>
                        <div class="data-align-right">
                            <a href="./index.php?view=newncf" class="btn add-row btn-primary">Nueva Secuencia</a>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <table id="example" class="table data-tbl2">
                                <thead>
                                    <tr>
										<th>Tipo Comprobante</th>
                                        <th>Tipo Cliente</th>
                                        <th>Inicio Secuencia</th>
                                        <th>Secuencia Actual</th>
                                        <th>Final Secuencia</th>
                                        <th></th>
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
            $('.data-tbl2').DataTable({
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
                "stateSave": true,
                "ajax":{
                    url :"./?action=dtgetncf", // json datasource
                    type: "post"
                },
                "columns": [
                    { "data": "tipodocumento" },
                    { "data": "tipocliente" },
                    { "data": "secuenciaini" },
                    { "data": "secuenciaactual" },
                    { "data": "secuenciafin" },
                    {
                        "class" : "td-center",
                        "data": "user_id",
                        "render" : function ( data, type, full) {

                            var result = '<div class="btn-toolbar" role="toolbar"><div class="btn-group" role="group">';

                            result = result + '<a href="index.php?view=editncf&id=' + full['id'] +'" class="btn btn-default btn-sm m-user-edit" data-toggle="tooltip" title="Editar"><i class="fa fa-edit"></i></a>';

                            result = result + '</div></div>';

                            return result;
                        }
                    }
                ],
                "dom": '<"row" <"col-md-6"l><"col-md-6"f>><"row" <"col-md-12"<"td-content"rt>>><"row" <"col-md-6"i><"col-md-6"p>>'
            });
        }

        $(document).on('click', '.m-user-delete', function () {
            var url = 'index.php?action=cambiarstatus&table=concept&id=' + $(this).data('id') + '&status_id=' + $(this).data('status');
            var obj;
            $.ajax({
                type: "POST",
                url: url,
                contentType: 'application/json; charset=utf-8',
                dataType: "json",
                success: function (data) {
                    if (data.status == 'success') {
                        location.reload();
                    }
                },
                error: function (data, textStatus, errorThrown) {
                    alert('message=:' + data + ', text status=:' + textStatus + ', error thrown:=' + errorThrown);
                }
            });
        });
    });

    $(document).ajaxComplete(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>