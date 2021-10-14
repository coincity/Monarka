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
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
         <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>DETALLE DEL SERVICIO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=services">Servicios<i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Detalle</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Detalle del Producto</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms">
										<div class="form-group">
											<label class="col-md-4 control-label">Imagen</label>
											<div class="col-md-7">
												<?php if($service->image!=""):?>
												    <img alt="Foto" src="storage/services/<?php echo $service->image;?>" class="img-responsive" style="max-width:150px;max-height:150px;">
												 <?php endif;?>
											</div>
										</div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Categoria</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="category_id" class="form-control" disabled>
                                                        <option value="">-- SELECCIONA --</option><?php foreach($categories as $category):?>
                                                        <option value="<?php echo $category->id;?>" <?php if($service->category_id ==  $category->id) echo "selected"; ?>><?php echo $category->description;?></option><?php endforeach;?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="col-md-4 control-label">Codigo</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $service->barcode;?>" placeholder="Codigo" disabled />
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
                                                    <input type="text" class="form-control" value="<?php echo $service->name;?>" placeholder="Nombre" disabled />
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
                                                    <input type="text" class="form-control" value="<?php echo $service->description;?>" placeholder="Descripción" disabled />
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
                                                    <input type="text" class="form-control" value="<?php echo $service->price;?>" placeholder="Precio" disabled />
												</div>
											</div>
										</div>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label">¿ITBIS?</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="itbis" id="itbis" <?php if($service->itbis == 1) echo "checked"; ?>  disabled>
                                                        <i></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
                                                    <a href="./index.php?view=services" type="button" class="btn btn-default">Volver a la Consulta</a>
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
    });
</script>