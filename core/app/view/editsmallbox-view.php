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
	$smallbox = SavingData::getById($_GET["id"]);
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
                    <h2>MODIFICACIÓN DE OPERACIONES</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=smallbox">Caja Chica <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Editar Operacion</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editsmallbox" enctype="multipart/form-data" action="index.php?action=updatesmallbox" role="form">
                                        <div class="form-group" style="display:none;">
											<label class="col-md-4 control-label">ID</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
													<input type="text" class="form-control" id="smallbox_id" name="smallbox_id" value="<?php echo $smallbox->id;?>" placeholder="00" readonly />
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Tipo</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="kind" class="form-control">
                                                        <option value=""> -- SELECCIONE --</option>
                                                        <option value="1" <?php if($smallbox->kind == 1) echo "selected"; ?>> Entrada</option>
                                                        <option value="2" <?php if($smallbox->kind == 2) echo "selected"; ?>> Salida</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="col-md-4 control-label">Fecha</label>
											<div class="col-md-7">
												<div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-calendar-o"></i></span>
													</span>
													<input type="text" name="date_at" class="form-control date_at" id="date_at" value="<?php echo date("d/m/Y",strtotime($smallbox->date_at));?>" placeholder="Fecha de Operacion" required>
												</div>
											</div>
										</div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Concepto</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <select name="concept_id" class="form-control">
                                                        <option value="">-- SELECCIONE --</option><?php foreach($concept as $c):?>
                                                        <option value="<?php echo $c->id; ?>" <?php if($c->id == $smallbox->concept_id) echo "selected"; ?>><?php echo $c->description; ?></option><?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Descripción</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-edit"></i></span>
                                                    </span>
                                                    <input type="text" class="form-control" value="<?php echo $smallbox->description;?>" id="description" name="description" placeholder="Descripción" />
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
                                                    <input type="number" class="form-control" value="<?php echo $smallbox->amount;?>" id="amount" name="amount" placeholder="Monto" />
                                                </div>
                                            </div>
                                        </div>
										<div class="form-group">
											<label class="col-md-4 control-label">&nbsp;</label>
											<div class="col-md-7">
												<div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
													<a href="./index.php?view=smallbox" type="button" class="btn btn-default">Cancelar</a>
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
        $("#editsmallbox").validate({
            rules: {
                kind: "required",
                date_at: "required",
                concept_id: "required",
                amount: "required"
            },
            messages: {
                kind: "Especifique el Tipo",
                date_at: "Especifique la Fecha",
                concept_id: "Especifique el Concepto",
                amount: "Especifique el Monto"
            }
        });
    });
</script>