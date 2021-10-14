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
                    <h2>AGREGAR NUEVA SECUENCIA</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=ncf">Comprobantes Fiscales <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Nueva Secuencia</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Nueva Secuencia de Comprobante Fiscal</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" id="addncf" method="post" action="index.php?action=addncf" role="form">
                                      
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Tipo Documento</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="tipo_doc" class="form-control">
														<option value="">-- SELECCIONA --</option>
                                                        <?php foreach($tipo_doc as $t):?>
														<option value="<?php echo $t->id;?>"><?php echo $t->description;?></option>
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
														<option value="<?php echo $c->id;?>"><?php echo $c->description;?></option>
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
													<input type="text" class="form-control date_at" id="fecinivig" name="fecinivig" placeholder="Fecha Inicio"/>
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
													<input type="text" class="form-control date_at" id="fecfinvig" name="fecfinvig" placeholder="Fecha Final"/>
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
                                                    <input type="number" class="form-control" id="secuenciaini" min="1" name="secuenciaini" placeholder="Secuencia Inicio" />
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
                                                    <input type="number" class="form-control" id="secuenciafin" min="1" name="secuenciafin" placeholder="Secuencia Final" />
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

        $("#addncf").validate({
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
		
		$("#addncf").submit(function (e) {
            e.preventDefault();
            $.post("./index.php?action=addncf", $("#addncf").serialize(), function (data) {

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