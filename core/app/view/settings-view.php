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
	$settings = ConfigurationData::getAll();
    $tipo = TipoCliData::getAllActive();
} 
?>
<script src="plugins/jquery.mask/jquery.mask.js"></script>
<script>
    $(function () {
        $('.phone_with_ddd').mask('(000) 000-0000');
    });
</script>
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
                    <h2>CONFIGURACION DE SISTEMA</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Configuración</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Parámetros del Sistema</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-8">
                                    <form class="form-horizontal j-forms" id="settings" method="post" enctype="multipart/form-data" action="index.php?action=updatesettings" role="form">
                                      
									  <?php
											if(count($settings)>0):
												foreach($settings as $cat):
													if($cat->short != "session"):?>
														<div class="form-group">
															<label class="col-md-4 control-label"><?php echo $cat->name; ?></label>
															<div class="col-md-7">
																<div class="input-group iconic-input">
                                                                    <?php if($cat->kind != 10 and $cat->kind != 9 and $cat->kind != 5){?>
																	    <span class="input-group-addon">
																		    <span class="input-icon"><i class="fa fa-edit"></i></span>
																	    </span>
                                                                    <?php } ?>
																	<?php if($cat->kind==2){?>
																		<input type="text" class="form-control" name="<?php echo $cat->short; ?>" value="<?php echo $cat->val;?>" />
																	<?php } ?>
																	<?php if($cat->kind==3){?>
																		<input type="password" class="form-control" name="<?php echo $cat->short; ?>" placeholder="Clave">
																	<?php } ?>
																	<?php if($cat->kind==7){?>
																		<input type="text" name="<?php echo $cat->short; ?>" id="<?php echo $cat->short; ?>" class="form-control phone_us-mask1" value="<?php echo $cat->val;?>">
																	<?php } ?>
																</div>
                                                                <?php if($cat->kind==5){?>
                                                                <?php if($cat->val!=""):?><?php $actual_link = "http://".$_SERVER["HTTP_HOST"].explode("?",$_SERVER["REQUEST_URI"])[0]; ?>
                                                                    <img src="<?php echo $actual_link . $cat->val;?>" style="width:40%;background-color:#ececec;" />
                                                                    <br />
                                                                    <br />
                                                                    <div class="input prepend-small-btn">
                                                                        <div class="file-button">
                                                                            Buscar
                                                                            <input class="btn btn-success" type="file" name="<?php echo $cat->short; ?>" onchange="document.getElementById('prepend-small-btn').value = this.value;" />
                                                                        </div>
                                                                        <input class="form-control" type="text" id="prepend-small-btn" readonly="" placeholder="No se eligió archivo" />
                                                                    </div>
                                                                <?php endif;?>
                                                                <?php } ?>
                                                                <?php if($cat->kind==9){?>
                                                                    <div class="input-group iconic-input">
                                                                        <label class="checkbox">
                                                                            <input type="hidden" name="<?php echo $cat->short; ?>"[0]"" value="off" />
                                                                            <input type="checkbox" name="<?php echo $cat->short; ?>"[0]"" <?php if($cat->val == "on") echo "checked"; ?> />
                                                                            <i></i>
                                                                        </label>
                                                                    </div>
                                                                <?php } ?>
                                                                <?php if($cat->kind==10){?>
                                                                   <select name="<?php echo $cat->short; ?>" class="form-control">
                                                                         <?php foreach($tipo as $c):?>
														                    <option value="<?php echo $c->id;?>" <?php if($c->id == $cat->val) echo "selected"; ?>><?php echo $c->description;?></option>
														                <?php endforeach;?>
													                </select>
                                                                <?php } ?>
															</div>
														</div>	
													<?php endif;
												endforeach;
											endif;?>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <!--<a href="./index.php?view=home" type="button" class="btn btn-default">Cancelar</a>-->
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
    $(document).ready(function () {
        $('.phone_us-mask1').mask('(000) 000-0000');

        $("#settings").validate({
            rules: {
                company_name: "required",
                company_address: "required",              
                company_phone: {
                    required: true,
                    phoneUS: true
                }
            },
            messages: {
                company_name: "Especifique el Nombre de la Empresa",
                company_address: "Especifique la Direccion de la Empresa",
                company_phone: {
                    required: "Especifique el Telefono",
                    phoneUS: "Especifique un Telefono Válido"
                },
            }
        });
    });
</script>