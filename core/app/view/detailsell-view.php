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
    $sell = SellData::getById($_GET["id"]);
    $client = "";
    $operations = "";
    $user = "";
    $total = 0;

    if($sell->id!="") $operations = OperationData::getAllProductsBySellId($sell->id);
    if($sell->person_id!="") $client = $sell->getPerson();
    if($sell->user_id!="") $user = $sell->getUser();

    $credit = -1*(PaymentData::sumByClientBySellId($client->id,$sell->id)->total);

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
                        <a href="./?view=sells" class="btn btn-default">
                            <i class="fa fa-arrow-left"></i>Volver a la Consulta
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
                    <h3>INFORMACION GENERAL  <a  class="btn btn-primary btn-sm" href="./tickets/Sale.php?id=<?php echo $_GET["id"]?>" target="_blank">Impresion Pequeña</a>
                    <a class="btn btn-primary btn-sm" href="./tickets/Sale.php?id=<?php echo $_GET["id"]?>&largePrint=1" target="_blank">Impresion Grande</a>
                    </h3>
                   
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                            <div class="form-content">
                                <div class="w-section-header">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td>CLIENTE:</td>
                                                        <td>
                                                            <?php if($sell->person_id!="") echo $client->name." ".$client->lastname;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ATENDIDO POR:</td>
                                                        <td>
                                                            <?php if($sell->user_id!="") echo $user->name." ".$user->lastname;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>NO. FACTURA</td>
                                                        <td>
                                                            <?php echo $sell->id;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>FECHA:</td>
                                                        <td>
                                                            <?php echo date("d/m/Y h:ia",strtotime($sell->created_at));?>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <table>
                                                <tbody>
                                                    
                                                    <tr>
                                                        <td class="font-weight-bold">
                                                            Tipo de pago :
                                                        </td>
                                                        <td><?php echo $sell->payment_method ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td> 
                                                            <b>Comentario</b>
                                                            <br>    
                                                        <?php echo trim( $sell->note) == "" ? "Sin comentario." : $sell->note ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
                                            <div class="widget-wrap">
                                                <div class="widget-container">
                                                    <div class="widget-content">
                                                        <table id="example" class="table foo-data-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Código</th>
                                                                    <th>Descripción</th>
                                                                    <th>Cantidad</th>
                                                                    <th>Precio</th>
                                                                    <th>ITBIS</th>
                                                                    <th>Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php

                                                                $total = 0;
                                                                $supertotal = 0;
                                                                foreach($operations as $operation){
                                                                    $product  = $operation->getProduct();
                                                                    $total = $operation->q*$operation->price_out;
                                                                    $itbis = 0;
                                                                    if($product->itbis == 1) $itbis = ($total *($iva/100));
                                                                    $total = $total + $itbis;
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $product->barcode;?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $product->name;?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $operation->q;?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $currency." ".number_format($operation->price_out,2,".",",");?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $currency." ".number_format($itbis,2,'.',','); ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php echo $currency." ".number_format($total,2,".",",");?>
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
                                        </div>
                                    </div>
                                </div>
                                <!-- end size of the cake -->

                                <div class="divider gap-bottom-25"></div>
                                <div class="w-section-header">
                                    <h3>RESUMEN FACTURA</h3>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
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
                                                                    <?php echo $currency." ".number_format($supertotal,2,'.',','); ?>
                                                                </h4>
                                                            </td>
                                                        </tr>
                                                        <tr class="warning">
                                                            <td>
                                                                <h4>Descuento:</h4>
                                                            </td>
                                                            <td>
                                                                <h4>
                                                                    <?php echo $currency." ".number_format($sell->discount,2,'.',','); ?>
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
                                                                        <?php echo $currency." ".number_format(ceil(($supertotal-$sell->discount)),2,'.',','); ?>
                                                                    </strong>
                                                                </h4>
                                                            </td>
                                                        </tr>
                                                        <tr class="warning">
                                                            <td>
                                                                <h4>Recibido:</h4>
                                                            </td>
                                                            <td>
                                                                <h4>
                                                                    <?php echo $currency." ".number_format(ceil($sell->cash),2,'.',','); ?>
                                                                </h4>
                                                            </td>
                                                        </tr>
                                                        <tr class="warning">
                                                            <td>
                                                                <h4>Cambio:</h4>
                                                            </td>
                                                            <td>
                                                                <h4>
                                                                    <?php
                                                                    if($sell->p_id == 1) echo $currency." ".number_format(ceil($sell->cash) - ceil(($supertotal-$sell->discount)) ,2,'.',',');
                                                                    else echo $currency." ".number_format(0 ,2,'.',',');
                                                                    ?>
                                                                </h4>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--   </div>-->


                                        <?php if($credit!=""){ ?>
                                        <div class="widget-container">
                                            <div class="widget-content">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr class="danger">
                                                            <td>
                                                                <h4>Balance Pendiente:</h4>
                                                            </td>
                                                            <td>
                                                                <h4>
                                                                    <?php echo $currency." ".number_format($credit ,2,'.',','); ?>
                                                                </h4>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <br />
                                        <!--<form method="post" id="processabono">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Monto Recibido:</label>
                                                <div class="col-md-4">
                                                    <div class="input-group iconic-input">
                                                        <span class="input-group-addon">
                                                            <span class="input-icon">
                                                                <i class="fa fa-money"></i>
                                                            </span>
                                                        </span>
                                                        <input type="number" class="form-control" min="1" max="9999999" id="cash" name="cash" />
                                                        <input type="hidden" id="credit" name="credit" value="<?php echo $credit; ?>" >
                                                        <input type="hidden" id="sell_id" name="sell_id" value="<?php echo $sell->id; ?>" />
                                                        <input type="hidden" id="person_id" name="person_id" value="<?php echo $sell->person_id; ?>" />
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-success primary-btn">
                                                        <i class="fa fa-download"></i> Aplicar Abono
                                                    </button>
                                                </div>
                                            </div>
                                        </form>-->
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <!-- end /.content -->
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

    $("#processabono").submit(function (e) {

        var cambio = 0;
        var credit = parseFloat($("#credit").val());
        var cash = $("#cash").val();
        if (cash != "") cash = parseFloat($("#cash").val());
        cambio = cash - credit;
        if ($("#cash").val() == "") {
            e.preventDefault();
            swal({
                title: "Campo Monto Recibido Vacio!",
                text: "",
                type: "error",
                confirmButtonColor: "#4caf50"
            });
        }
        else {
            if (cash >= credit) {
                e.preventDefault();
                swal({
                    title: "CAMBIO: RD$" + cambio,
                    text: "",
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK",
                    cancelButtonText: "",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                    function (isConfirm) {
                        if (isConfirm) {
                            e.preventDefault();
                            $.post("./index.php?action=processabono", $("#processabono").serialize(), function (data) {
                               // window.open('ticket-in.php?id=' + data);
                                window.location = 'index.php?view=paymentsells';;
                            });
                        }
                    });
            } else {
                e.preventDefault();
                swal({
                    title: "ABONO: RD$" + cash,
                    text: "",
                    type: "info",
                    showCancelButton: false,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "OK",
                    cancelButtonText: "",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                    function (isConfirm) {
                        if (isConfirm) {
                            e.preventDefault();
                            $.post("./index.php?action=processabono", $("#processabono").serialize(), function (data) {
                                // window.open('ticket-in.php?id=' + data);
                                window.location = 'index.php?view=paymentsells';;
                            });
                        }
                    });
            }
        }
    });

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