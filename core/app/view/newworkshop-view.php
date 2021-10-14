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
                    <h2>INGRESAR EQUIPO AL TALLER</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=workshop">Taller <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page">Ingreso de Equipo</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Nuevo Equipo</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" id="addworkshop" method="post" action="index.php?action=addworkshop" role="form">
                                    <div class="form-group">
                                            <label class="col-md-4 control-label">Cliente</label>
                                            <div class="col-md-7">
                                            <select id="client_id" name="client_id" class="form-control select4" style="width: 100%" required>
                                                <?php foreach(PersonData::getClientsActive() as $c):?>
                                                <option value="<?php echo $c->id; ?>"><?php echo $c->name." ".$c->lastname; ?></option><?php endforeach; ?>
                                            </select>                   
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Equipo</label>
                                            <div class="col-md-7">
                                                <select id="description" name="description" class="form-control">
                                                    <option value="">--SELECCIONE--</option>
                                                    <option value="LAPTOP">LAPTOP</option>
                                                    <option value="PC">PC</option>
                                                    <option value="CELULAR">CELULAR</option>
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
                                                    <input type="text" class="form-control" id="brand" name="brand" placeholder="Marca del Equipo" />
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
                                                    <input type="text" class="form-control" id="model" name="model" placeholder="Modelo del Equipo" />
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
                                                    <input type="text" class="form-control" id="serie" name="serie" placeholder="Numero de Serie del Equipo" />
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
                                                    <input type="text" name="date_in" class="form-control date_at" id="date_in" placeholder="Fecha Entrada" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Marcar Salida</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <label class="checkbox">
                                                        <input type="checkbox" name="returned" id="returned">
                                                        <i></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">Fecha de Salida</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-calendar"></i></span>
                                                    </span>
                                                    <input type="text" name="date_out" class="form-control date_at" id="date_out" placeholder="Fecha" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Comentarios</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <textarea rows="3" class="form-control" spellcheck="false" id="observation" name="observation"></textarea>
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
        $("#addworkshop").validate({
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