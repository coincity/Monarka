<style>
    .error {
        color: #ef5350 !important;
        font-size: 14px !important;
        font-family: 'Roboto', sans-serif !important;
        font-weight: 400 !important;
    }
</style>
<?php
    if(isset($_GET["product_code"]) && $_GET["product_code"]!=""):
?>
    <?php
        $product = ProductData::getProductServiceById($_GET["product_code"]);
        $currency = ConfigurationData::getByPreffix("currency")->val;
        if($product != null){
           $q = OperationData::getQByStock($product->id);
           if($product->tipo == "Servicio") $q=1;
           if($q>0){
    ?>
        
        <form method="post" id="addtocart" class="j-forms" action="index.php?action=addtocart" novalidate>
            <div class="form-content">
                <div class="row fruits-calculation">
                    <div class="col-md-4 unit">
                        <label class="label">Descripcion</label>
                        <div class="input">
                            <input type="hidden" value="<?php echo $product->id; ?>" name="product_id">
                            <input type="hidden" name="tipo_id" value="<?php echo $product->tipo; ?>" >
                            <input class="form-control" type="text" id="product_name" value="<?php echo $product->barcode." - ".$product->name; ?>" readonly="" name="product_name" />
                        </div>
                    </div>
                    <div class="col-md-2 unit">
                        <label class="label">Cantidad</label>
                        <div class="input quantity-events">
                            <input class="form-control" type="number" id="q" name="q" min="1" max="9999" />
                        </div>
                    </div>
                    <div class="col-md-2 unit">
                        <label class="label">Precio</label>
                        <div class="input">
                            <div class="input">
                                <select id="product_price" name="product_price" class="form-control">
                                    <option value="">--Seleccione--</option>
                                    <?php 
                                       if($product->tipo == "Producto"){
                                           $p = ProductData::GetById($product->id);
                                           $op = OperationData::getByStockById($product->id);
                                    ?>
                                     <option value="<?php echo $op->min_price; ?>"><?php echo $currency." ".number_format($op->min_price,2,'.',','); ?></option>
                                     <option value="<?php echo $op->max_price; ?>"><?php echo $currency." ".number_format($op->max_price,2,'.',','); ?></option>
                                    <?php
                                       } else if($product->tipo == "Servicio"){
                                           $s = ServiceData::GetById($product->id);
                                    ?>
                                     <option value="<?php echo $s->price; ?>"><?php echo $currency." ".number_format($s->price,2,'.',','); ?></option>
                                    <?php
                                        }
                                    ?>
                                
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 unit">
                        <label class="label"></label>
                        <div class="input-group">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-plus-sign"></i>Agregar
                                </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    <?php
           }
           else{
               echo "<p class='alert alert-danger'>El Producto <b>$product->name</b> no tiene existencias en el Inventario</p>";
           }
        }
        else{
	        echo "<br><p class='alert alert-danger'>No se Encontro el Producto/Servicio</p>";
        }
    ?> 
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#addtocart").validate({
            rules: {
                product_price: "required",
                q: "required"
            },
            messages: {
                product_price: "Especifique el Precio",
                q: "Especifique la Cantidad"
            }
        });
    });


    $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
   