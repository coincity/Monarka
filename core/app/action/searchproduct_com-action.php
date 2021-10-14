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
        $product = ProductData::getById($_GET["product_code"]);
        $currency = ConfigurationData::getByPreffix("currency")->val;

        if($product != null){
           $q = OperationData::getQByStock($product->id);
    ?>
        
        <form method="post" id="addtocart" class="j-forms" action="index.php?action=addtore" novalidate>
            <div class="form-content">
                <div class="row fruits-calculation">
                    <div class="col-md-3 unit">
                        <label class="label">Descripcion</label>
                        <div class="input">
                            <input type="hidden" value="<?php echo $product->id; ?>" name="product_id">
                            <input type="hidden" name="tipo_id" value="Producto" >
                            <input class="form-control" type="text" id="product_name" value="<?php echo $product->name; ?>" readonly="" name="product_name" />
                        </div>
                    </div>
                    <div class="col-md-1 unit">
                        <label class="label">Disponible</label>
                        <div class="input quantity-events">
                            <input class="form-control" type="number" value="<?php echo $q;?>" readonly="" />
                        </div>
                    </div>
                    <div class="col-md-1 unit">
                        <label class="label">Cantidad</label>
                        <div class="input quantity-events">
                            <input class="form-control" type="number" id="q" name="q" min="1" max="9999" />
                        </div>
                    </div>
                    <div class="col-md-2 unit">
                        <label class="label">Precio Compra</label>
                        <div class="input">
                            <div class="input">
                                <input class="form-control" type="number" id="price_in" name="price_in" value="<?php echo $product->price_in;?>" min="1" max="999999999" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 unit">
                        <label class="label">Precio Minimo</label>
                        <div class="input">
                            <div class="input">
                                <input class="form-control" type="number" id="min_price" name="min_price" value="<?php echo $product->min_price;?>" min="1" max="999999999" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 unit">
                        <label class="label">Precio Maximo</label>
                        <div class="input">
                            <div class="input">
                                <input class="form-control" type="number" id="max_price" name="max_price" value="<?php echo $product->max_price;?>" min="1" max="999999999" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 unit">
                        <label class="col-md-10 label"></label>
                        <div class="col-md-2 input-group">
                            <span class="input-group-btn">
                                <button type="submit" class="btn btn-primary">
                                    <i class="glyphicon glyphicon-plus-sign"></i> Agregar
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
	        echo "<br><p class='alert alert-danger'>No se Encontro el Producto/Servicio</p>";
        }
    ?> 
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#addtocart").validate({
            rules: {
                price_in: "required",
                min_price: "required",
                max_price: "required",
                q: "required"
            },
            messages: {
                price_in: "Especifique el Precio",
                min_price: "Especifique el Precio Minimo",
                max_price: "Especifique el Precio Maximo",
                q: "Especifique la Cantidad"
            }
        });
    });


    $(document).ajaxComplete(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
   