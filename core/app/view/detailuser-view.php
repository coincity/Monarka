<?php
if(!isset($_SERVER['HTTP_REFERER'])){
    print "<script>window.location='./?view=home';</script>";
    exit;
}
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > ConfigurationData::getByPreffix("session")->val)) 
{   
    session_destroy();   
    session_unset();
	Core::alert("¡Su sesión ha expirado!");
	print "<script>window.location='./?view=login';</script>";
}
else {
	$_SESSION['start'] = time();
	$user = UserData::getById($_GET["id"]);
} 
?>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>DETALLE DE USUARIO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./?view=users">Usuarios <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Detalle de Usuario</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal">
										<div class="form-group" style="display:none">
											<label class="col-md-4 control-label">ID</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
													<input type="text" class="form-control" value="<?php echo $user->id;?>" placeholder="00" disabled>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Foto</label>
											<div class="col-md-7">
												<?php if($user->image!=""):?>
												    <img alt="Foto" src="storage/profiles/<?php echo $user->image;?>" class="thumbnail img-responsive" style="max-width:150px;max-height:150px;margin-bottom:8px;">
												 <?php endif;?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Nombre</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
													<input type="text" class="form-control" value="<?php echo $user->name;?>" placeholder="Nombre" disabled>
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
													<input type="text" class="form-control" value="<?php echo $user->lastname;?>" placeholder="Apellido" disabled>
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
													<input type="text" class="form-control" value="<?php echo $user->email;?>" placeholder="Correo Electrónico" disabled>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="col-md-4 control-label">Usuario</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-user"></i></span>
													</span>
													<input type="text" class="form-control" value="<?php echo $user->username;?>" placeholder="Usuario" disabled>
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Rol</label>
                                            <div class="col-md-7">
                                                <select id="kind" name="kind" class="form-control" disabled>
                                                    <option value="">--SELECCIONE--</option>
                                                    <option value="1" <?php if($user->kind ==  1) echo "selected"; ?>>Administrador</option>
                                                    <option value="2" <?php if($user->kind ==  2) echo "selected"; ?>>Cajero</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
                                                    <a href="./?view=users" type="button" class="btn btn-default">Volver a la Consulta</a>
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