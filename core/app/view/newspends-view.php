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
	$concept = ConceptData::getAllActive();
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
                    <h2>NUEVO GASTO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=spends">Reporte de Gastos <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Nuevo Gasto</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Nuevo Gasto</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" id="addspends" method="post" action="index.php?action=addspends" role="form">
                                      
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Concepto</label>
                                            <div class="col-md-7">
												<div class="input-group iconic-input">	
													<select name="concept_id" class="form-control">
														<option value="">-- SELECCIONE --</option><?php foreach($concept as $c):?>
														<option value="<?php echo $c->id; ?>"><?php echo $c->description; ?></option><?php endforeach; ?>
													</select>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Monto</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-money"></i></span>
													</span>
                                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="Monto" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Fecha</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-calendar"></i></span>
													</span>
                                                    <input type="text" name="date_at" class="form-control date_at" id="date_at" placeholder="Fecha" required>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Factura</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="bill_id" name="bill_id" placeholder="Factura" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">NCF</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-edit"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="ncf" name="ncf" placeholder="Comprobante Fiscal" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
                                            <label class="col-md-4 control-label">Comentarios</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <textarea rows="3" class="form-control" spellcheck="false" id="observations" name="observations"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=spends" type="button" class="btn btn-default">Cancelar</a>
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
        $("#addspends").validate({
            rules: {
                concept_id: "required",
				date_at: "required",
				amount: "required"
            },
            messages: {
				concept_id: "Especifique el Tipo",
				date_at: "Especifique la Fecha",
				amount: "Especifique el Monto"
            }
        });
    });
</script>