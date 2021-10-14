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
    $workshop = WorkshopData::getById($_GET["id"]);
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
                    <h2>MODIFICACIÓN DE EQUIPOS</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=workshop">Taller <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Editar Equipo</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editworkshop" enctype="multipart/form-data" action="index.php?action=updateworkshop" role="form">
                                        <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="workshop_id" name="workshop_id" value="<?php echo $workshop->id;?>" placeholder="00" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Cliente</label>
                                            <div class="col-md-7">
                                            <select id="client_id" name="client_id" class="form-control select4" style="width: 100%" required>
                                                
                                                <?php foreach(PersonData::getClientsActive() as $c):?>
                                                <option value="<?php echo $c->id; ?>" <?php if($workshop->client_id == $c->id) echo "selected";?>><?php echo $c->name." ".$c->lastname; ?></option><?php endforeach; ?>
                                            </select>                   
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Equipo</label>
                                            <div class="col-md-7">
                                                <select id="description" name="description" class="form-control">
                                                    <option value="">--SELECCIONE--</option>
                                                    <option value="LAPTOP" <?php if($workshop->description == "LAPTOP") echo "selected"; ?>>LAPTOP</option>
                                                    <option value="PC" <?php if($workshop->description == "PC") echo "selected"; ?>>PC</option>
                                                    <option value="CELULAR" <?php if($workshop->description == "CELULAR") echo "selected"; ?>>CELULAR</option>
                                                </select>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Marca</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $workshop->brand;?>" placeholder="Marca" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Modelo</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="model" name="model" value="<?php echo $workshop->model;?>" placeholder="Modelo" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Numero de Serie</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="serie" name="serie" value="<?php echo $workshop->serie;?>" placeholder="Numero de Serie" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Fecha Entrada</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-calendar"></i></span>
													</span>
                                                    <input type="text" name="date_in" class="form-control date_at" id="date_in" value="<?php echo date("d/m/Y",strtotime($workshop->date_in));?>" placeholder="Fecha Entrada">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Marcar Salida</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="returned" id="returned" <?php if($workshop->returned == 1) echo "checked"; ?>>
                                                        <i></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Fecha Salida</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-calendar"></i></span>
													</span>
                                                    <input type="text" name="date_out" class="form-control date_at" id="date_out" value="<?php if($workshop->date_out != "0000-00-00") echo date("d/m/Y",strtotime($workshop->date_out));?>" placeholder="Salida">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Comentarios</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <textarea rows="3" class="form-control" spellcheck="false" id="observation" name="observation"><?php echo $workshop->observation;?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=workshop" type="button" class="btn btn-default">Cancelar</a>
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
        $(".date_at").datepicker({
            dateFormat: 'dd/mm/yy',
            prevText: '<i class="fa fa-caret-left"></i>',
            nextText: '<i class="fa fa-caret-right"></i>'
        });
        $("#editworkshop").validate({
            rules: {
                description: "required",
				brand: "required",
				model: "required",
				serie: "required",
				date_in: "required",
				date_out: "required"
            },
            messages: {
                description: "Especifique la Descripción",
				brand: "Especifique la Marca de Equipo",
				model: "Especifique el Modelo",
				serie: "Especifique el Numero de Serie",
                date_in: "Especifique la Fecha de Ingreso",
				date_out: "Especifique la Fecha de Salida"
            }
        });
    });
</script>