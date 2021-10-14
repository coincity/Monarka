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
	$service = ServiceData::getById($_GET["id"]);
	$categories = CategoryData::getByType(2);
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
                    <h2>MODIFICACIÓN DE SERVICIO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=services">Servicios <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page">Editar </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Editar Categoría</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editservice" enctype="multipart/form-data" action="index.php?action=updateservice" role="form">
                                      <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="service_id" name="service_id" value="<?php echo $service->id;?>" placeholder="00" readonly />
                                                </div>
                                            </div>
                                        </div>
									  <div class="form-group">
                                            <label class="col-md-4 control-label">Imagen</label>
                                            <div class="col-md-7">
												<?php if($service->image!=""):?>
												    <img alt="Foto" src="storage/services/<?php echo $service->image;?>" class="img-responsive" style="max-width:150px;max-height:150px;">
												<?php endif;?>
                                                <div class="input prepend-small-btn">
                                                    <div class="file-button">
                                                        Cambiar
                                                        <input class="btn btn-success" type="file" id="image" name="image" onchange="document.getElementById('prepend-small-btn').value = this.value;">
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
                                                        <option value="">-- SELECCIONA --</option><?php foreach($categories as $category):?>
                                                        <option value="<?php echo $category->id;?>" <?php if($service->category_id ==  $category->id) echo "selected"; ?>><?php echo $category->description;?></option><?php endforeach;?>
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
                                                    <input type="text" class="form-control" name="name" value="<?php echo $service->name;?>" placeholder="Nombre" />
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
                                                    <input type="text" class="form-control" name="description" value="<?php echo $service->description;?>" placeholder="Descripción" />
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Precio</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-money"></i></span>
													</span>
                                                    <input type="text" class="form-control" name="price" value="<?php echo $service->price;?>" placeholder="Precio" />
												</div>
											</div>
										</div>
                                         <div class="form-group">
                                            <label class="col-md-4 control-label">¿ITBIS?</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="itbis" id="itbis" <?php if($service->itbis == 1) echo "checked"; ?>>
                                                        <i></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=services" type="button" class="btn btn-default">Cancelar</a>
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
        $("#editservice").validate({
            rules: {
				barcode: "required",
				name: "required",
                category_id: "required",
                type: "required",
				price: "required"
            },
            messages: {
				barcode: "Especifique el Codigo",
				name: "Especifique el Nombre",
                category_id: "Especifique la Categoria",
                type: "Especifique el Tipo",
				price:"Especifique el Precio"
            }
        });
    });
</script>