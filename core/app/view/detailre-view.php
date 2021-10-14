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
    $re = ReData::getById($_GET["id"]);
    $provider = "";
    $operations = "";
    $user = "";
    $total = 0;

    if($re->ref_id!="") $operations = OperationData::getAllProductsByRefId($re->ref_id);
    if($re->person_id!="") $provider = $re->getPerson();
    if($re->user_id!="") $user = $re->getUser();

}
?>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-2">
                    <div class="btn-ex-container">
                        <a href="./?view=res" class="btn btn-default">
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
                    <h3>INFORMACION GENERAL</h3>
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
                                                        <td>PROVEEDOR:</td>
                                                        <td>
                                                            <?php if($re->person_id!="") echo $provider->company;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>REGISTRADO POR:</td>
                                                        <td>
                                                            <?php if($re->user_id!="") echo $user->name." ".$user->lastname;?>
                                                        </td>
                                                    </tr>
                                                    <?php if($re->ncf != null || $this->ncf != "") { ?>
													<tr>
                                                        <td>NCF</td>
                                                        <td>
                                                            <?php echo $re->ncf;?>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
													<tr>
                                                        <td>ITBIS</td>
                                                        <td>
                                                            <?php echo $re->itbis;?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>NO. COMPRA</td>
                                                        <td>
                                                            <?php echo $re->id;?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($re->bill_id > 0) {?>
                                                    <tr>
                                                        <td>NO. FACTURA</td>
                                                        <td><?php echo $re->bill_id;?>
                                                        </td>
                                                    </tr>
                                                    <?php }?>
                                                    <tr>
                                                        <td>FECHA:</td>
                                                        <td>
                                                            <?php echo date("d/m/Y h:ia",strtotime($re->created_at));?>
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
                                    <h3>PRODUCTOS</h3>
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
                                                                            <th>Precio Unitario</th>
                                                                            <th>Total Sin ITBIS</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                           foreach($operations as $operation){
                                                                               $product  = $operation->getProduct();
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
                                                                                <?php echo $currency." ".number_format($operation->price_in,2,".",",");?>
                                                                            </td>
                                                                            <td>
                                                                                <?php echo $currency." ".number_format($operation->price_in * $operation->q,2,".",",");?>
                                                                            </td>
                                                                        </tr><?php
                                                                               $total+=$operation->q*$operation->price_in;
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
                                       <div class="w-section-header"><h3>RESUMEN FACTURA TOTAL</h3></div>
                                        <div class="row justify-content-end">
                                            <div class="col-md-5">
                                                <?php if($re->paid == 1){ ?>
                                                <!--   <div class="widget-wrap">-->
                                                    <div class="">
                                                                <table class="table table-bordered">

                                                                <tr class="info">
                                                                        <td>
                                                                        <strong>Sub-Total:</strong>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <strong>
                                                                                    <?php  echo Utils::moneyFormat($currency,$re->total); ?>
                                                                                </strong>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="info">
                                                                        <td>
                                                                        <strong>ITBIS :</strong>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <strong>
                                                                                    <?php echo Utils::moneyFormat($currency,($re->total * ($re->itbis / 100))); ?>
                                                                                </strong>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="info">
                                                                        <td>
                                                                        <strong>Total:</strong>
                                                                        </td>
                                                                        <td>
                                                                            <h4>
                                                                                <strong>
                                                                                    <?php  echo Utils::moneyFormat($currency,$re->total + ($re->total * ($re->itbis / 100))); ?>
                                                                                </strong>
                                                                            </h4>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                               <!--   </div>-->
                                                <?php } ?>

                                                <?php if($re->paid == 0){ ?>
                                                <div class="widget-container">
                                                    <div class="widget-content">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered">
                                                                <tr class="danger">
                                                                    <td>
                                                                        <h4>Monto Pendiente:</h4>
                                                                    </td>
                                                                    <td>
                                                                        <h4>
                                                                            <?php echo $currency." ".number_format($re->total + ($re->total * ($re->itbis / 100)) ,2,'.',','); ?>
                                                                        </h4>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <br />
                                                <form method="post" id="payre">
                                                    <div class="form-group">
                                                        <label class="col-md-4 control-label"></label>
                                                        <div class="col-md-4">
                                                            <div class="input-group iconic-input">
                                                                <input type="hidden" id="sell_id" name="sell_id" value="<?php echo $re->id; ?>" />
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if($re->status == 1) {
                                                            ?> 
                                                                <div class="col-md-4">
                                                                    <a class="btn btn-danger" href="index.php?action=delre&id=<?php echo $re->id; ?>">
                                                                        <i class="fa fa-download"></i> Saldar Deuda
                                                                    </a>
                                                                </div>

                                                        <?php 
                                                        }
                                                        ?>
                                                    </div>
                                                </form>
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

    $("#payre").submit(function (e) {

       e.preventDefault();
       $.post("./index.php?action=payre", $("#payre").serialize(), function (data) {
            location.reload();
       });
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