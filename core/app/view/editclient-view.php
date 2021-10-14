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
                    <h2>MODIFICACIÓN DE CLIENTE</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=clients">Clientes <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Editar Cliente</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editclient" enctype="multipart/form-data" action="index.php?action=updateclient" role="form">
                                        <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon">
                                                            <i class="fa fa-key"></i>
                                                        </span>
                                                    </span>
                                                    <input type="text" class="form-control" id="client_id" name="client_id" value="<?php echo $client->id;?>" placeholder="00" readonly />
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
                                                    <?php if($client->image!=""):?>
                                                        <img alt="Foto" src="storage/clients/<?php echo $client->image;?>" class="img-responsive" style="max-width:150px;max-height:150px;">
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Cédula</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-id-card-o"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="no" name="no" value="<?php echo $client->no;?>" placeholder="Cédula">
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
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $client->name;?>" placeholder="Nombre">
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
                                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $client->lastname;?>" placeholder="Apellido">
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Direccion</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-map-marker"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $client->address;?>" placeholder="Direccion">
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Telefono</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="ffa fa fa-phone"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $client->phone;?>" placeholder="Telefono">
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
                                                    <input type="text" class="form-control" id="cell" name="cell" value="<?php echo $client->cell;?>" placeholder="Celular" />
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
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $client->email;?>" placeholder="Correo Electrónico">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Tipo Cliente</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="client_type_id" class="form-control">
														<option value="">-- SELECCIONA --</option>
                                                         <?php foreach(TipoCliData::getAllActive() as $c):?>
														<option value="<?php echo $c->id;?>" <?php if($c->id == $client->client_type_id) echo "selected"; ?>><?php echo $c->description;?></option>
														<?php endforeach;?>
													</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Comentarios</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <textarea rows="3" class="form-control" spellcheck="false" id="observations" name="observations"><?php echo $comments->observation;?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=clients" type="button" class="btn btn-default">Cancelar</a>
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
                    <span>© 2017 OSM Solutions.</span>
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
        $("#editclient").validate({
            rules: {
                no: {
                    required: true,
                    minlength: 6,
                    maxlength: 12
                },
                name: "required",
                lastname: "required",
                phone: "required",
                email: {
                    email: true
                }
            },
            messages: {
                no: "Especifique la Cédula o RNC (Valor entre 6 y 12 caracteres)",
                name: "Especifique el Nombre",
                lastname: "Especifique el Apellido",
                phone: "Especifique el Telefono",
                username: {
                    required: "Especifique el Usuario",
                    minlength: "Debe contener al menos 3 caracteres"
                },
                email: "Especifique un Correo Válido"
            }
        });
    });
</script>