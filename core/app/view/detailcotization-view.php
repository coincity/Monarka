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
    $currency = ConfigurationData::getByPreffix("currency")->val;
    $cotization = CotizationData::getById($_GET["id"]);
    $client = "";
    $operations = "";
    $user = "";
    $total = 0;


    if($cotization->ref_id!="") $operations = OperationData::getAllProductsByRefId($cotization->ref_id);
    if($cotization->person_id!="") $client = $cotization->getPerson();
    if($cotization->user_id!="") $user = $cotization->getUser();
    $iva = ConfigurationData::getByPreffix("imp-val")->val;
}
?>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-3 col-sm-5">
                    <div class="btn-ex-container">
                        <a href="./?view=cotizations" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>Volver a la Consulta
                        </a>
                    </div>
                </div>
                <div class="col-md-3 col-sm-5">
                    <div class="btn-ex-container">
                        <a href="./?action=addtosell&id=<?php echo $_GET["id"];?>" class="btn btn-info">
                            <i class="fa fa-cc-visa"></i> Convertir en Venta
                        </a>
                    </div>
                </div>
                <div class="col-md-6"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="w-section-header">
                    <h3>INFORMACION GENERAL</h3>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <form class="j-forms" id="order-forms" novalidate>
                            <div class="form-content">
                                <div class="w-section-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td>CLIENTE:</td>
                                                        <td>
                                                            <?php if($cotization->person_id!="") echo $client->name." ".$client->lastname;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ATENDIDO POR:</td>
                                                        <td>
                                                            <?php if($cotization->user_id!="") echo $user->name." ".$user->lastname;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>NO. COTIZACION</td>
                                                        <td>
                                                            <?php echo $cotization->id;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>FECHA:</td>
                                                        <td>
                                                            <?php echo date("d/m/Y h:ia",strtotime($cotization->created_at));?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                       </div>
                                   </div>                                       
                               </div>
                                <div class="divider gap-bottom-25"></div>
                                <!-- start size of the cake -->
                                <div class="w-section-header">
                                    <h3>PRODUCTOS y/o SERVICIOS</h3>
                                </div>
                                <div class="unit check cake-size">
                                            <div class="row">
                                                <div class="col-md-12">
                                                <table id="example" class="table foo-data-table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Código</th>
                                                                            <th>Descripción</th>
                                                                            <th>Precio Unit.</th>
                                                                            <th>Cantidad</th>
                                                                            <th>Precio Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php

                                                                           $total = 0;
                                                                           $supertotal = 0;
                                                                           foreach($operations as $operation){
                                                                               $product  = $operation->getProduct();
                                                                               $total = $operation->q*$operation->price_out;
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php echo $product->barcode;?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $product->name;?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo Utils::moneyFormat($currency, $operation->price_out);?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $operation->q;?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo Utils::moneyFormat($currency, $total);?>
                                                                            </td>
                                                                        </tr><?php
                                                                                $supertotal += $total;
                                                                           }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                </div>
                                            </div>
                                        </div>
                                <!-- end size of the cake -->
                                        
                                       <div class="divider gap-bottom-25"></div>
                                       <div class="w-section-header"><h3>RESUMEN FACTURA</h3></div>
                                        <div class="row">
                                            <div class="float-right col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                                <!--   <div class="widget-wrap">-->
                                                    <div class="widget-container">
                                                        <div class="widget-content">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <tr class="info">
                                                                        <td>
                                                                            <h4>
                                                                                <strong>Subtotal:</strong>
                                                                            </h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <?php echo Utils::moneyFormat($currency, $cotization->subtotal); ?>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="warning">
                                                                        <td>
                                                                            <h4>Descuento:</h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <?php echo Utils::moneyFormat($currency, $cotization->discount); ?>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="warning">
                                                                        <td>
                                                                            <h4>Impuestos:</h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <?php echo Utils::moneyFormat($currency, $cotization->taxes); ?>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="info">
                                                                        <td>
                                                                            <h4>
                                                                                <strong>Total:</strong>
                                                                            </h4>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <strong>
                                                                                    <?php echo Utils::moneyFormat($currency, $cotization->total); ?>
                                                                                </strong>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                               <!--   </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end /.content -->
                        </form>
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
    $(document).ready(function () {
        if ($.fn.dataTable) {
            $('.data-tbl').DataTable({
                responsive: true,
                "paging": false,
                "ordering": false,
                "info": false,
                "bFilter": false,
                "columnDefs": [{ "targets": [1, 2], "orderable": false }],
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
                "dom": '<"row" <"col-md-6"l><"col-md-6"f>><"row" <"col-md-12"<"td-content"rt>>><"row" <"col-md-6"i><"col-md-6"p>>'
            });
        }
    });

    $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>