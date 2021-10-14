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
                    <h2>MODIFICACIÓN DE USUARIO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./?view=users">Usuarios <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Editar Usuario</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="edituser" enctype="multipart/form-data" action="index.php?action=updateuser" role="form">
                                        <div class="form-group" style="display:none">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="user_id" name="user_id" value="<?php echo $user->id;?>" placeholder="00" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Foto</label>
                                            <div class="col-md-7">
                                                <div class="input prepend-small-btn">
                                                    <div class="file-button">
                                                        Browse
                                                        <input class="btn btn-success" type="file" id="image" name="image" onchange="document.getElementById('prepend-small-btn').value = this.value;">
                                                    </div>
                                                    <input class="form-control" type="text" id="prepend-small-btn" placeholder="No se eligió archivo">
                                                    <?php if($user->image!=""):?>
                                                        <img alt="Foto" src="storage/profiles/<?php echo $user->image;?>" class="thumbnail img-responsive" style="max-width:150px;max-height:150px;margin-bottom:4px;margin-top:8px;">
                                                    <?php endif;?>
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
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $user->name;?>" placeholder="Nombre">
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
                                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $user->lastname;?>" placeholder="Apellido">
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
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $user->email;?>" placeholder="Correo Electrónico">
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
                                                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user->username;?>" placeholder="Usuario">
                                                </div>
                                            </div>
                                        </div>
										 <div class="form-group">
                                            <label class="col-md-4 control-label">Cambiar Clave</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-unlock-alt"></i></span>
													</span>
                                                    <input type="password" class="form-control" id="password" name="password" placeholder="Clave">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Repetir Nueva Clave</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-unlock-alt"></i></span>
													</span>
                                                    <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Repetir Clave">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Rol</label>
                                            <div class="col-md-7">
                                                <select id="kind" name="kind" class="form-control">
                                                    <option value="">--SELECCIONE--</option>
                                                    <option value="1" <?php if($user->kind ==  1) echo "selected"; ?>>Administrador</option>
                                                    <option value="2" <?php if($user->kind ==  2) echo "selected"; ?>>Supervisor</option>
                                                    <option value="3" <?php if($user->kind ==  3) echo "selected"; ?>>Cajero</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./?view=users" type="button" class="btn btn-default">Cancelar</a>
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
        $("#edituser").validate({
            rules: {
                name: "required",
                lastname: "required",
                username: {
                    required: true,
                    minlength: 4
                },
                password: {
                    required: false,
                    minlength: 6
                },
                repeat_password: {
                    required: false,
                    minlength: 6,
                    equalTo: "#password"
                },
                email: {
                //    required: true,
                    email: true
                },
                kind: "required"
            },
            messages: {
                name: "Especifique el Nombre",
                lastname: "Especifique el Apellido",
                username: {
                    required: "Especifique el Usuario",
                    minlength: "Debe contener al menos 4 caracteres"
                },
                password: {
                    required: "Especifique Clave",
                    minlength: "La Clave debe tener 6 caracteres como Mínimo"
                },
                repeat_password: {
                    required: "Especifique Clave",
                    minlength: "La Clave debe tener 6 caracteres como Mínimo",
                    equalTo: "Las Claves son Diferentes"
                },
                email: "Especifique un Correo Válido",
                kind: "Especifique el Rol"
            }
        });
    });
</script>