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
	$product = ProductData::getById($_GET["id"]);
	$categories = CategoryData::getByType(1);
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
                    <h2>MODIFICACIÓN DE PRODUCTO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=products">Productos <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Editar</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Editar Producto</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editproduct" enctype="multipart/form-data" action="index.php?action=updateproduct" role="form">                                
										<div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="id" name="id" value="<?php echo $product->id;?>" placeholder="00" readonly />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Imagen</label>
                                            <div class="col-md-7">
												<?php if($product->image!=""):?>
												    <img alt="Foto" src="storage/products/<?php echo $product->image;?>" class="img-responsive" style="max-width:150px;max-height:150px;">
												<?php endif;?>
                                                <div class="input prepend-small-btn">
                                                    <div class="file-button">
                                                        Cambiar
                                                        <input class="btn btn-success" type="file" accept="image/jpeg, image/png" id="image" name="image" onchange="document.getElementById('prepend-small-btn').value = this.value;">
                                                    </div>
                                                    <input class="form-control" type="text" id="prepend-small-btn" readonly="" placeholder="No se eligió archivo">
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Categoria</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="category_id" class="form-control">
														<option value="">-- NINGUNA --</option>
														<?php foreach($categories as $category):?>
														<option value="<?php echo $category->id;?>" <?php if($product->category_id!=null&& $product->category_id==$category->id){ echo "selected";}?>><?php echo $category->description;?></option>
														<?php endforeach;?>
													</select>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Nombre</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $product->name;?>" placeholder="Nombre" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Descripción</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="description" name="description" value="<?php echo $product->description;?>" placeholder="Descripción" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Precio Compra</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-money"></i></span>
                                                    </span>
                                                    <input type="number" class="form-control" value="<?php echo $product->price_in;?>" id="price_in" name="price_in" placeholder="Precio Compra" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Precio Minimo</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-money"></i></span>
                                                    </span>
                                                    <input type="number" class="form-control" value="<?php echo $product->min_price;?>" id="min_price" name="min_price" placeholder="Precio Minimo" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Precio Maximo</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-money"></i></span>
                                                    </span>
                                                    <input type="number" class="form-control" value="<?php echo $product->max_price;?>" id="max_price" name="max_price" placeholder="Precio Maximo" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">¿ITBIS?</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="itbis" id="itbis" <?php if($product->itbis == 1) echo "checked"; ?>>
                                                        <i></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Limite Garantia</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-calendar-check-o"></i></span>
                                                    </span>
                                                    <input type="text" class="form-control date_at" value="<?php if($product->warranty_at != "0000-00-00") echo date("d/m/Y",strtotime($product->warranty_at));?>" id="warranty_at" name="warranty_at" placeholder="Limite Garantia" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Condiciones Garantia</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <textarea rows="3" class="form-control"  spellcheck="false" id="observations" name="observations"><?php echo $product->observations;?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                  <!--<div class="form-group">
                                            <label class="col-md-4 control-label">Documento de Garantia</label>
                                            <div class="col-md-7">
                                                <div class="input prepend-small-btn">
                                                    <div class="file-button">
                                                        Elegir
                                                        <input class="btn btn-success" type="file" accept=".pdf,.doc" id="warranty" name="warranty" onchange="document.getElementById('prepend-small-warranty').value = this.value;" />
                                                    </div>
                                                    <input class="form-control" type="text" id="prepend-small-warranty" readonly="" value="<?php echo $product->warranty_file;?>" placeholder="" />
                                                </div>
                                            </div>
                                        </div>-->
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=products" type="button" class="btn btn-default">Cancelar</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
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
<script type="text/javascript">
    $(document).ready(function() {
		$(".date_at").datepicker({
                dateFormat: 'dd/mm/yy',
                prevText: '<i class="fa fa-caret-left"></i>',
                nextText: '<i class="fa fa-caret-right"></i>'
         });
        $("#editproduct").validate({
            rules: {
                category_id: "required",
                name: "required",
                price_in: "required",
                min_price: "required",
                max_price: "required"
            },
            messages: {
                category_id: "Especifique la Categoría",
                name: "Especifique el Nombre",
                price_in: "Especifique el Precio de Compra",
                min_price: "Especifique el Precio Minimo de Venta",
                max_price: "Especifique el Precio Maximo de Venta"
            }
        });


        var file = $('#image');
        var msg = $('#prepend-small-btn');
     //   var warranty = $('#warranty');
     //   var msg2 = $('#prepend-small-warranty');
        file.on('change', function (e) {
            var reader = new FileReader();

            reader.onload = function () {
                var data = reader.result;
                if (data.match(/^data:image\//)) {
                    $('#thumbnail').attr('src', data);
                } else {
                    console.error('Not an image');
                    alert("Formato de archivo no permitido. Seleccione solo (jpg,png)");
                    file.val('');
                    msg.val('');
                }
            };

            reader.readAsDataURL(file.prop('files')[0]);
        });

      /*  warranty.on('change', function () {
            myfile = $(this).val();
            var ext = myfile.split('.').pop();
            if (ext == "pdf" || ext == "docx" || ext == "doc") {

            } else {
                alert("Formato de archivo no permitido. Seleccione solo (pdf)");
                warranty.val('');
                msg2.val('');
            }
        });*/
    });
</script>