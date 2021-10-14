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
	$ncf = NcfData::getById($_GET["id"]);
    $tipo_doc = TipoDocData::getAllActive();
    $tipo = TipoCliData::getAllActive();
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
                    <h2>EDITAR SECUENCIA</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=ncf">Comprobantes Fiscales <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Editar Secuencia </h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editncf" action="index.php?action=updatencf" role="form">
                                         <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="ncf_id" name="ncf_id" value="<?php echo $ncf->id;?>" placeholder="00" readonly />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Tipo Documento</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="tipo_doc" class="form-control">
														<option value="">-- SELECCIONA --</option>
                                                        <?php foreach($tipo_doc as $t):?>
														<option value="<?php echo $t->id;?>" <?php if($t->id == $ncf->tipodoc) echo "selected"; ?>><?php echo $t->description;?></option>
														<?php endforeach;?>
													</select>
                                                </div>
                                            </div>
                                        </div>
                                         <div class="form-group">
                                            <label class="col-md-4 control-label">Tipo Cliente</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="tipo" class="form-control">
														<option value="">-- SELECCIONA --</option>
                                                         <?php foreach($tipo as $c):?>
														<option value="<?php echo $c->id;?>" <?php if($c->id == $ncf->tipo) echo "selected"; ?>><?php echo $c->description;?></option>
														<?php endforeach;?>
													</select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Fecha Inicio Vigencia</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-calendar-check-o"></i></span>
													</span>
													<input type="text" value="<?php if($ncf->fecinivig != "0000-00-00") echo date("d/m/Y",strtotime($ncf->fecinivig));?>" class="form-control date_at" id="fecinivig" name="fecinivig" placeholder="Fecha Inicio"/>
                                                </div>
                                            </div>
                                        </div>
                                       <div class="form-group">
                                            <label class="col-md-4 control-label">Fecha Final Vigencia</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-calendar-check-o"></i></span>
													</span>
													<input type="text" value="<?php if($ncf->fecfinvig != "0000-00-00") echo date("d/m/Y",strtotime($ncf->fecfinvig));?>" class="form-control date_at" id="fecfinvig" name="fecfinvig" placeholder="Fecha Fin"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Secuencia Inicio</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="number" value="<?php echo $ncf->secuenciaini; ?>" class="form-control" id="secuenciaini" min="1" name="secuenciaini" placeholder="Secuencia Inicio" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Secuencia Actual</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="number" value="<?php echo $ncf->secuenciaactual; ?>" class="form-control" id="secuenciaactual" min="1" name="secuenciaactual" placeholder="Secuencia Actual" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Secuencia Final</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="number" value="<?php echo $ncf->secuenciafin; ?>" class="form-control" id="secuenciafin" min="1" name="secuenciafin" placeholder="Secuencia Final" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=ncf" type="button" class="btn btn-default">Cancelar</a>
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

        $("#editncf").validate({
            rules: {
                tipo_doc: "required",
                tipo: "required",
                fecinivig: "required",
                fecfinvig: "required",
                secuenciaini: "required",
                secuenciafin: "required"
            },
            messages: {
                tipo_doc: "Especifique el Tipo de Documento",
                tipo: "Especifique el Tipo de Cliente",
                fecinivig: "Especifique la Fecha de Inicio Vigencia",
                fecfinvig: "Especifique la Fecha de Fin Vigencia",
                secuenciaini: "Especifique el Inicio de la Secuencia",
                secuenciafin: "Especifique el Final de la Secuencia"
            }
        });
		
		$("#editncf").submit(function (e) {
            e.preventDefault();
            $.post("./index.php?action=updatencf", $("#editncf").serialize(), function (data) {

                if (data.status == 'success') {
                    window.location.replace("./index.php?view=ncf");
                }
                else if (data.status == 'fail') {
                    swal({
                        title: data.message,
                        text: "",
                        type: "error",
                        confirmButtonColor: "#4caf50"
                    }); 
                }
                else if (data.status == 'novalid') { }
            });
        });
    });
</script>