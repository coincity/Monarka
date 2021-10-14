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
    $products = ProductData::getActive();
    $provider = PersonData::getProvidersActive();
    $currency = ConfigurationData::getByPreffix("currency")->val;
    $iva = ConfigurationData::getByPreffix("imp-val")->val;
    $totalTaxes = 0;
    $subTotal = 0;
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
                    <h2>NUEVA COMPRA</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li>
                            <a href="./index.php?view=home">
                                Inicio
                                <i class="zmdi zmdi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="active-page">Compra</li>
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
                        <h3>GESTIONAR ABASTECIMIENTO DE INVENTARIO</h3>
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
                                                    <select id="product_code" name="product_code" class="form-control select5" style="width: 100%" required>
                                                        <option value=""></option>
                                                        <?php foreach($products as $p):?>
                                                            <option value="<?php echo $p->id; ?>"><?php echo $p->barcode." - ".$p->name; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 unit">
                                                <label class="label"></label>
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <button type="submit" class="btn btn-info primary-btn">
                                                            <i class="fa fa-search"></i> Buscar
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

                                            $.get("./?action=searchproduct_com", $("#searchp").serialize(), function (data) {
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
                                if(isset($_SESSION["reabastecer"])):
                                    $total = 0;
                                    $iva_name = ConfigurationData::getByPreffix("imp-name")->val;
                                    $iva_val = ConfigurationData::getByPreffix("imp-val")->val;
                                ?>
                               <div class="divider gap-bottom-25"></div>
                               <div class="w-section-header"><h3>RESUMEN DE ABASTECIMIENTO</h3></div>
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
                                                                <th>Sub Total</th>
                                                                <th>Impuestos</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        
                                                         <?php foreach($_SESSION["reabastecer"] as $p):

                                                                   if($p["product_id"] != "") {
                                                                       $product = ProductData::getById($p["product_id"]);
                                                                       $p["subTotal"] = $p["price_in"] * $p["q"];
                                                                       $p["taxes"] = $product->itbis == "1" ?  $p["subTotal"] * ($iva/100) : 0;
                                                                       $p["total"] = $p["subTotal"] + $p["taxes"];
                                                         ?>
                                                        <tbody>
                                                         <tr>
                                                             <td><?php echo $product->barcode; ?></td>
                                                             <td><?php echo $product->name; ?></td>
                                                             <td><?php echo $p["q"]; ?></td>                                       
                                                             <td><b><?php echo Utils::moneyFormat($currency, $p["price_in"]); ?></b></td>

                                                             <td><b><?php echo Utils::moneyFormat($currency, $p["subTotal"]); ?></b></td>
                                                             <td><b><?php echo Utils::moneyFormat($currency, $p["taxes"]); ?></b></td>

                                                             <td><b><?php echo Utils::moneyFormat($currency, $p["total"]); ?></b></td>
                                                             <td style="width:30px;"><a href="index.php?action=clearre&product_id=<?php echo $product->id; ?>&price=<?php echo $p["price_in"]; ?>" class="btn btn-danger"><i class="fa fa-times"></i> Eliminar</a></td>
                                                         </tr>
                                                        <?php   
                                                                    $totalTaxes = $totalTaxes + $p["taxes"];
                                                                    $subTotal = $subTotal + $p["total"];
                                                                    print($subTotal);
                                                                
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
                               <div class="divider gap-bottom-25"></div>
                               <form method="post" class="form-horizontal" id="processre" action="index.php?action=processre">
                                   <div class="row">
                                   <div class="col-12 w-section-header"><h3>RESUMEN FACTURA</h3></div>
                                   </div>
                                   
                                    <div class="row justify-content-end">
                                        
                                        
                                        <div class="col-lg-4 col-md-5 col-sm-6 col-12 float-right">
                                        <div >
                                                        <table class="table table-bordered">
                                                            <tr class="bg-light">
                                                                <td>
                                                                    <b>Proveedor:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">
                                                                        <select id="provider_id" name="provider_id" class="form-control select6" style="width: 100%">
                                                                            <option value=""></option><?php foreach($provider as $c):?>
                                                                            <option value="<?php echo $c->id; ?>"><?php echo  $c->name. " " . $c->lastname . " (" . $c->company . ")"; ?></option><?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr class="bg-light">
                                                                <td>
                                                                    <b>Estado Compra:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">
                                                                        <select id="p_id" name="p_id" class="form-control" style="width: 100%">
                                                                            <option value="1">PAGADO</option>
                                                                            <option value="2">CREDITO</option>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                            </tr>
															<tr class="bg-light">
                                                                <td>
                                                                    <b>NCF:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">
                                                                        <input class="form-control" type="text" id="ncf" name="ncf"/>
                                                                    </div>
                                                                </td>
                                                            </tr>
															<tr class="bg-light">
                                                                <td>
                                                                <b>% ITBIS:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">
                                                                        <input class="form-control" type="text" id="itbis" name="itbis" value="<?php echo $iva_val; ?>" min="1" max="9999" readonly/>
                                                                    </div>
                                                                </td>
                                                            </tr>
															<tr class="bg-light">
                                                                <td>
                                                                <b>Total ITBIS:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">
                                                                        <input class="form-control" type="text" id="Titbis" name="Titbis" value="<?php echo $totalTaxes; ?>" min="1" max="9999" disabled/>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <!-- <tr class="bg-light">
                                                                <td>
                                                                <b>No. Factura:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">
                                                                        <input class="form-control" type="number" id="bill_id" name="bill_id" min="1" max="9999" />
                                                                    </div>
                                                                </td>
                                                            </tr> -->
                                                            <input type="hidden" id="bill_id" name="bill_id" min="1" max="9999" />
                                                            <tr class="info">
                                                                <td>
                                                                <b>Total Factura:</b>
                                                                </td>
                                                                <td>
                                                                    <div class="input">     
                                                                        <input type="hidden" id="subTotal" name="subTotal" value="<?php echo $subTotal; ?>" />                                                                
                                                                        <input type="hidden" id="total" name="total"  value="<?php echo $subTotal; ?>" />
                                                                    </div>
                                                                    <h4>
                                                                        <strong id="facturar"><?php echo Utils::moneyFormat($currency, $subTotal); ?></strong>
                                                                    </h4>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                        </div>
                                    </div>
                                    <div class="divider gap-bottom-25"></div>
                                    <div class="row">
                                        <div class="col-md-offset-9 col-md-3 form-footer" style="padding-top:15px;">
                                            <button type="submit" class="btn btn-success primary-btn"><i class="fa fa-download"></i> Guardar Compra</button>
                                            <a href="index.php?action=clearre" class="btn btn-danger secondary-btn"><i class="fa fa-times"></i> Cancelar</a>
                                        </div>
                                    </div>
                                </form>
                               <?php endif; ?>
                            </div>
                        </div>
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
<script src="../../../theme/js/lib/jquery.ui.js"></script>
<script type="text/javascript">

    var linkClick = false;
    document.onclick = function (e) {
        linkClick = true;
        var elemntTagName = e.target.tagName;
        if (elemntTagName == 'A' || ($(e.target).parent().get(0).tagName == 'A')) {

            var url = "";

            if (elemntTagName == 'A') url = e.target.getAttribute("href");
            else if ($(e.target).parent().get(0).tagName == 'A') url = $(e.target).parent().get(0).getAttribute("href");

            var detail = url.indexOf("view=detailre");
            var clear = url.indexOf("action=clearre");
            var menu = url.indexOf("menu");
            var email_tab = url.indexOf("email_tab");
            var task_tab = url.indexOf("task_tab");
            var support_tab = url.indexOf("support_tab");
            if (url == "index.php?action=clearre") {
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
            else if (support_tab > 0) {
                return true;
            }
            else if (document.getElementById("pselled")) {
                return true;
            }
            else {
                if (confirm('Al Salir se perderá todo el progreso, ¿Salir de Todos Modos?')) {
                    $.post("./index.php?action=clearre", function (data) {

                    });
                    return true;
                }
                else {
                    return false;
                }
            }
        }
    }




    $(document).ready(function () {

        $("#processre").validate({
            rules: {
                provider_id: "required",
                total: "required"
            },
            messages: {
                provider_id: "Especifique el Proveedor",
                total: "Especifique el Total Factura"
            }
        });
    });

    $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>