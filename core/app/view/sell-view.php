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
    $products = ProductData::getAllProductService();
    $clients = PersonData::getClientsActive();
    $currency = ConfigurationData::getByPreffix("currency")->val;
    $iva = ConfigurationData::getByPreffix("imp-val")->val;
    $totalTaxes = 0;
}
?>
<style>
    .error {
        color: #ef5350 !important;
        font-size: 14px !important;
        font-family: 'Roboto', sans-serif !important;
        font-weight: 400 !important;
    }
</style>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>Facturación</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li>
                            <a href="./index.php?view=home">
                                Inicio
                                <i class="zmdi zmdi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="active-page">Facturación</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="widget-wrap">
                <div class="widget-header block-header clearfix">
                    <div class="pull-left">
                        <h3>Venta de Productos y/o Servicios</h3>
                    </div>
                </div>
                <div class="widget-container">
                    <div class="widget-content">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="searchp" class="j-forms" novalidate>
                                    <div class="form-content">
                                        <div class="row">
                                            <div class="col-md-5 unit">
                                                <label class="label"></label>
                                                <div class="input">
                                                    <select id="product_code" name="product_code" class="form-control select3" style="width: 100%" required>
                                                        <option value=""></option><?php foreach($products as $p):?>
                                                        <option value="<?php echo $p->id; ?>"><?php echo $p->barcode." - ".$p->name; ?></option><?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 unit">
                                                <label class="label"></label>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-info primary-btn">
                                                            <i class="fa fa-search"></i>Buscar
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="divider gap-bottom-25"></div>
                                    </div>
                                </form>
                                <div id="show_search_results"></div>
                                <script>
                                    $(document).ready(function () {
                                        $("#searchp").on("submit", function (e) {
                                            e.preventDefault();

                                            $.get("./?action=searchproduct", $("#searchp").serialize(), function (data) {
                                                $("#show_search_results").html(data);
                                            });
						                    $("#product_code").val('').trigger('change');

                                        });
                                    });
                                </script>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-12">
                                <?php
                                if(isset($_SESSION["errors"])) {
                                    foreach($_SESSION["errors"] as $c){

                                        if($c["product_id"] != null){
                                            echo $c["message"];  
                                        }else {
                                            $p = ProductData::getById($c["product_id"]);
                                            $q = OperationData::getQByStock($c["product_id"]);
                                            echo "<p class='alert alert-danger'>El Producto <b>".$p->barcode." - ".$p->name."</b>, no tiene suficientes existencias en el Inventario ($q)</p>";   
                                        }
                                    }  
                                    unset($_SESSION["errors"]);
                                }

                                if(isset($_SESSION["cart"])):
                                    $total = 0;
                                    $iva_name = ConfigurationData::getByPreffix("imp-name")->val;
                                    $iva_val = ConfigurationData::getByPreffix("imp-val")->val;
                                ?>
                               <div class="divider gap-bottom-25"></div>
                               <div class="w-section-header"><h3>CARRITO DE COMPRA</h3></div>
                               <div class="unit check cake-size">
                                  <div class="row">
                                      <div class="col-md-12">
                                      <table id="example" class="table foo-data-table">
                                                        <thead>
                                                            <tr>
                                                                <th>Descripción</th>
                                                                <th>Precio Unit.</th>
                                                                <th>Cantidad</th>
                                                                <th>Impuestos</th>
                                                                <th>Precio Total</th>
                                                            </tr>
                                                        </thead>
                                                         <?php 
                                    $subTotal = 0;
                                    $discount = 0;
                                    $cot_id = 0;
                                    foreach($_SESSION["cart"] as $productReference):

                                        if($productReference["product_id"] != "") {
                                            $product = ProductData::getProductServiceById($productReference["product_id"]);                           
                                            $productReference["subTotal"] = $productReference["price"] * $productReference["q"];
                                            $productReference["taxes"] = $product->itbis == "1" ?  $productReference["subTotal"] * ($iva/100) : 0;
                                            $productReference["total"] = $productReference["subTotal"] + $productReference["taxes"];
                                                                       
                                            if(isset($productReference["discount"]) && $productReference["discount"] != "") $discount = $productReference["discount"];
                                            if(isset($productReference["cot_id"]) && $productReference["cot_id"] != "") $cot_id = $productReference["cot_id"];                                         
                                                         ?>
                                            <tbody>
                                                   <tr>
                                                   <td><?php echo $product->barcode." - ".$product->name; ?></td>
                                                                             <td><?php echo Utils::moneyFormat($currency, $productReference["price"]); ?></td>
                                                                             <td><?php echo $productReference["q"]; ?></td>         
                                                                             <td><?php echo Utils::moneyFormat($currency, $productReference["taxes"]) ?></td>                                
                                                                             <td><b><?php echo Utils::moneyFormat($currency, $productReference["subTotal"]) ?></b></td>
                                                                             <td style="width:30px;"><a href="index.php?action=clearcart&product_id=<?php echo $product->id; ?>&price=<?php echo $productReference["price"]; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Eliminar</a></td>
                                                   </tr>
                                        <?php 
                                            $subTotal = $subTotal + $productReference["subTotal"];
                                            $totalTaxes = $totalTaxes + $productReference["taxes"];
                                        }
                                    endforeach; 
                                        ?>
                                               </tbody>
                                             </table>
                                      </div>
                                    </div>
                                 </div>
                              </div>

                            </div>
                        </div>
                        <form method="post" class="form-horizontal" id="processsell"> 
                        <div class="row justify-content-end">
                                        <!--<div class="col-md-offset-6 col-md-6 w-section-header"><h3>RESUMEN FACTURA</h3></div>-->
                                        <div class="col-md-6">
                                            <div class="widget-container">
                                                <div class="widget-content">
                                                    <div class="">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td colspan="4">
                                                                    <div class="form-group">
                                                                        <label for="note" class="control-label">Comentario</label>
                                                                        <textarea name="note" id="note" maxlength="499" class="form-control" cols="30" rows="3"></textarea>
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                           <tr class="warning">
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <h5>
                                                                        <strong>Cliente:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" colspan="2">
                                                                     <div class="input">
                                                                        <select id="client_id" name="client_id" class="form-control select4" style="width: 100%">
                                                                            <option value=""></option><?php foreach($clients as $c):?>
                                                                            <option value="<?php echo $c->id; ?>" <?php if(isset($productReference["client"]) && $productReference["client"] != "") {if($productReference["client"] == $c->id) echo "selected";} ?>><?php echo $c->name." ".$c->lastname; ?></option><?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr> 
                                                            <tr class="warning">
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <h5>
                                                                        <strong>Tipo Pago:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" colspan="2">
                                                                     <div class="input">
                                                                        <select id="payment_method" name="payment_method" class="form-control" style="width: 100%">
                                                                            <option selected value="EFECTIVO">Efectivo</option>
                                                                            <option selected value="TARJETA">Tarjeta de Credito/Debito</option>
                                                                            <option selected value="TRANSFERENCIA">Transferencia</option>
                                                                            <option selected value="CHEQUE">Cheque</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr> 
                                                            
                                                            <tr class="warning">
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <h5>
                                                                        <strong>Tipo NCF:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" colspan="2">
                                                                     <div class="input">
                                                                        <select id="ncf_type" name="ncf_type" class="form-control" style="width: 100%">
                                                                            <option selected value="">No aplica.</option>
                                                                            <?php foreach(TipoDocData::getAllActive() as $t):?>
                                                                            <option value="<?php echo $t->id;?>"><?php echo $t->description;?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr> 
                                                            
                                                            <tr class="warning">
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <h5>
                                                                        <strong>% Descuento:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <div class="input">
                                                                        <input class="form-control" type="number" id="discountPercent" name="discountPercent" min="0" max="100" value="<?php echo isset($_SESSION["baseCotization"]) ? $_SESSION["baseCotization"]->discount : 0; ?>" />
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="warning">
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <h5>
                                                                        <strong>Tipo Venta:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" colspan="2">
                                                                    <div class="input">
                                                                        <select id="p_id" name="p_id" class="form-control" style="width: 100%">
                                                                            <option value="1">AL CONTADO</option>
                                                                            <option value="2">CREDITO</option>
                                                                            <option value="3">ABONO</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr> 
                                                            <tr class="warning">
                                                                <td style="max-width:100px;">
                                                                    <h5>
                                                                        <strong>Sub-Total:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td colspan="3" style="max-width:100px;" class="text-right">
                                                                    <h5><?php echo Utils::moneyFormat($currency, $subTotal) ; ?>
                                                                    </h5>
                                                                </td>
                                                            </tr>
                                                            <tr class="warning">
                                                                <td style="max-width:100px;">
                                                                    <h5>
                                                                        <strong>Descuento <b id="discountPercentReference"></b>:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" class="text-right">
                                                                    <h5><b id="discountReference"></b></h5>
                                                                </td>
                                                                <td style="max-width:100px;">
                                                                    <h5>
                                                                        <strong>Impuestos (<?php echo $iva; ?>%):</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" class="text-right">
                                                                    <h5 id="taxesReference"></h5>
                                                                </td>
                                                            </tr>
                                                            <tr class="warning">
                                                                <td style="max-width:100px;">
                                                                    <h5>
                                                                        <strong>Balance Pendiente:</strong>
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;">
                                                                    <h5 style="text-align:center;" class="text-right">--</h5>
                                                                </td>
                                                                <td style="max-width:100px;">
                                                                    <h5>
                                                                        Total Facturar
                                                                    </h5>
                                                                </td>
                                                                <td style="max-width:100px;" class="text-right">
                                                                   <h5>
                                                                        <strong id="totalReference"></strong>
                                                                    </h5>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <input type="hidden" id="subtotal" name="subtotal" value="<?php echo $subTotal; ?>">
                                                        <input type="hidden" id="total" name="total" value="<?php echo $subTotal; ?>">
                                                        <input type="hidden" id="discount" name="discount">
                                                        <input type="hidden" id="taxesPercent" name="taxesPercent" value="<?php echo $iva; ?>">
                                                        <input type="hidden" id="taxes" name="taxes" value="<?php echo $totalTaxes; ?>">
                                                        <input type="hidden" id="iva_name" name="iva_name" value="<?php echo $iva_name; ?>">
                                                        <input type="hidden" id="iva_val" name="iva_val" value="<?php echo $iva; ?>">
                                                        <input type="hidden" id="cot_id" name="cot_id" value="<?php echo $cot_id; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                        <div class="row justify-content-end">
                            <div class="col-md-6" style="padding-top:15px;">
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Monto Recibido</label>
                                    <div class="col-md-5">
                                        <div class="input-group iconic-input">
                                            <span class="input-group-addon">
                                                <span class="input-icon"><i class="fa fa-money"></i></span>
                                            </span>
                                            <input type="number" class="form-control" min="1" max="9999999" id="cash" name="cash" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-6 form-footer text-right" style="padding-top:15px;">
                                <button type="submit" class="btn btn-success primary-btn"><i class="fa fa-download"></i> Finalizar Venta</button>
                                <a href="index.php?action=clearcart" class="btn btn-danger secondary-btn"><i class="fa fa-times"></i> Cancelar</a>
                            </div>
                        </div>
                                                                            </form>

                               <?php endif; ?>
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
                        <span>© 2017 OSM Solutions.</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--Footer End Here -->
</section>
<!--Page Container End Here
<script src="../../../theme/js/lib/jquery.ui.js"></script>-->
<script>

    var linkClick = false;
    document.onclick = function (e) {
        linkClick = true;
        var elemntTagName = e.target.tagName;
       // alert($(e.target).parent().get(0).tagName);
        if (elemntTagName == 'A' || ($(e.target).parent().get(0).tagName == 'A')) {

            var url = "";

            if (elemntTagName == 'A') url = e.target.getAttribute("href");
            else if ($(e.target).parent().get(0).tagName == 'A') url = $(e.target).parent().get(0).getAttribute("href");

           // alert(url);
           // alert(url.startsWith("./?view="));

            var detail = url.indexOf("view=detailsell");
            var clear = url.indexOf("action=clearcart");
            var menu = url.indexOf("menu");
            var email_tab = url.indexOf("email_tab");
            var task_tab = url.indexOf("task_tab");
            var support_tab = url.indexOf("support_tab");

         //   alert(support_tab);

            if (url == "index.php?action=clearcart") {
                return true;
            }
            else if (url == null) {
                return true;
            }
            else if (url == "#") {
                return true;
            }
            else if (detail > 0) {
                return true;
            }
            else if (clear > 0) {
                return true;
            }
            else if (menu > 0) {
                return true;
            }
            else if (email_tab > 0) {
                return true;
            }
            else if (task_tab > 0) {
                return true;
            }
            else if (url.startsWith("./?view=")) {
           //     alert("yeah");
                return true;
            }
            else if (document.getElementById("pselled")) {
                return true;
            }
            else {
             //   alert("yes");
                if (support_tab > 0) {
                    return true;
                }
                else if (confirm('Al Salir se perderá todo el progreso, ¿Salir de Todos Modos?')) {
                    $.post("./index.php?action=clearcart", function (data) {

                    });
                    return true;
                }
                else {
                    return false;
                }
            }
        }
    }

    $(document).ready(function (e) {
        var currency = "";
        currency = "RD$";
        var discount = parseInt($("#discount").val());
        function runCalculations() {
            var values = getValues();
            console.log(values);
            $("#discountReference").html(currency + " " +formatMoney(values.discountValue));
            $("#discount").val(values.discountValue);
            
            $("#discountPercentReference").html(values.discountPercent + "%");

            $("#taxesReference").html(currency + " " + formatMoney(values.totalTaxes));
            $("#taxes").val(values.totalTaxes);

            $("#totalReference").html(currency + " " + formatMoney(values.total));
            $("#total").val(values.total);
        }
        function getValues() {
            var values = {
                subTotal : parseFloat($("#subtotal").val()) || 0,
                discountPercent : parseFloat($("#discountPercent").val()) || 0,
                taxes : parseFloat($("#taxesPercent").val()) || 0,
                totalTaxes : parseFloat($("#taxes").val()) || 0
            };
            values.discountPercent = (values.discountPercent >= 0 && values.discountPercent <=100) ? values.discountPercent : 0;
            values.discountValue = values.discountPercent * values.subTotal / 100;
            //values.totalTaxes = (values.taxes) * (values.subTotal - values.discountValue) / 100;
            values.total = values.subTotal - values.discountValue + values.totalTaxes;
            return values;
        }


        $("#discountPercent").on("paste keyup", function () {
            runCalculations();
        });
        runCalculations()

        $("#p_id").on("change", function () {
            var p = $(this).val();
            if (p == 2) {
                $("#cash").prop('disabled', true);
                $("#cash").val('');
            } else {
                $("#cash").prop('disabled', false);
            }
        });

        $("#processsell").validate({
            rules: {
                client_id: "required"
            },
            messages: {
                client_id: "Especifique el Cliente"
            }
        });

        function processSale() {
            $.post("./index.php?action=processsell", $("#processsell").serialize(), function (data) {
                if (data.status == 'success') {
                    window.location.href = "./index.php?view=detailsell&id=" + data.sell_id;
                }
                else if (data.status == 'fail') {
                    swal({
                        title: data.message,
                        text: "",
                        type: "error",
                        confirmButtonColor: "#4caf50"
                    });
                }
            });
        }

        $("#processsell").submit(function (e) {
            e.preventDefault();
            if ($(this).valid()) {
                var cambio = 0;
                var p = $("#p_id").val();
                var discount = parseFloat($("#discount").val());
                var total = parseFloat($("#total").val());
                var cash = $("#cash").val();
                if (cash != "") cash = parseFloat($("#cash").val());
                total = total - discount;
                if (p == 1) cambio = cash - total;
                if (((p == 1) || (p == 3)) && (cash == "")) {
                    swal({
                        title: "Campo Monto Recibido Vacio!",
                        text: "",
                        type: "error",
                        confirmButtonColor: "#4caf50"
                    });
                }
                else if ((p == 1) && (cambio < 0)) {
                    swal({
                        title: "Monto Recibido Insuficiente!",
                        text: "",
                        type: "error",
                        confirmButtonColor: "#4caf50"
                    });
                }
                else if ((p == 2)) {
                    processSale();
                }
                else if ((p == 3) && (cash >= total)) {
                    swal({
                        title: "Cuenta con Monto Recibido Suficiente, Realice Venta Pagada!",
                        text: "",
                        type: "error",
                        confirmButtonColor: "#4caf50"
                    });
                }
                else if ((p == 3) && (cash < total)) {
                    e.preventDefault();
                    processSale();
                }
                else if ((p == 1) && (cambio >= 0)) {
                    swal({
                        title: "CAMBIO: RD$" + cambio,
                        text: "",
                        type: "info",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "OK",
                        cancelButtonText: "",
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            processSale();
                        }
                    });
                }

            }
        });


    });
    $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>