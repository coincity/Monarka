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
	$client = PersonData::getById($_GET["id"]);
    $comments = PersonData::getComments($_GET["id"]);
}
?>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>DETALLE DE CLIENTE</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=clients">Clientes <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Detalle de Cliente</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal">
                                        <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon">
                                                            <i class="fa fa-key"></i>
                                                        </span>
                                                    </span>
                                                    <input type="text" class="form-control" value="<?php echo $client->id;?>" placeholder="00" disabled />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="col-md-4 control-label">Foto</label>
											<div class="col-md-7">
												<?php if($client->image!=""):?>
												    <img alt="Foto" src="storage/clients/<?php echo $client->image;?>" class="img-responsive" style="max-width:150px;max-height:150px;">
												 <?php endif;?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Cédula</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-id-card-o"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $client->no;?>" placeholder="No." disabled />
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
                                                    <input type="text" class="form-control" value="<?php echo $client->name;?>" placeholder="Nombre" disabled />
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Apellido</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $client->lastname;?>" placeholder="Apellido" disabled />
												</div>
											</div>
										</div>
										<!--<div class="form-group">
											<label class="col-md-4 control-label">Empresa</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $client->company;?>" placeholder="Empresa" disabled />
												</div>
											</div>
										</div>-->
										<div class="form-group">
											<label class="col-md-4 control-label">Direccion</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-map-marker"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $client->address;?>" placeholder="Direccion" disabled />
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Telefono</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa fa-phone"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $client->phone;?>" placeholder="Telefono" disabled />
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Celular</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon">
                                                            <i class="fa fa-mobile"></i>
                                                        </span>
                                                    </span>
                                                    <input type="text" class="form-control" id="cell" name="cell" value="<?php echo $client->cell;?>" placeholder="Celular" disabled />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="col-md-4 control-label">Correo Electrónico</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-envelope"></i></span>
													</span>
                                                    <input type="text" class="form-control" value="<?php echo $client->email;?>" placeholder="Correo" disabled />
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Comentarios</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <textarea rows="3" class="form-control" spellcheck="false" id="observations" name="observations" disabled><?php echo $comments->observation;?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
                                                    <a href="./index.php?view=clients" type="button" class="btn btn-default">Volver a la Consulta</a>
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