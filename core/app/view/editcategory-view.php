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
	$category = CategoryData::getById($_GET["id"]);
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
                    <h2>MODIFICACIÓN DE CATEGORÍA</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=categories">Categorías <i class="zmdi zmdi-chevron-right"></i></a></li>
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
                            <h3>Editar Categoría</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                            <div class="row">
                                <div class="col-md-7">
                                    <form class="form-horizontal j-forms" method="post" id="editcategory" role="form">
                                        <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-key"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="category_id" name="category_id" value="<?php echo $category->id;?>" placeholder="00" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Tipo</label>
                                            <div class="col-md-7">
                                                <select id="type" name="type" class="form-control">
                                                    <option value="">--SELECCIONE--</option>
                                                    <option value="1" <?php if($category->type ==  1) echo "selected"; ?>>PRODUCTO</option>
                                                    <option value="2" <?php if($category->type ==  2) echo "selected"; ?>>SERVICIO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Prefijo</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-edit"></i></span>
                                                    </span>
                                                    <input type="text" class="form-control" id="prefix" name="prefix" value="<?php echo $category->prefix;?>" placeholder="Prefijo" />
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
                                                    <input type="text" class="form-control" id="description" name="description" value="<?php echo $category->description;?>" placeholder="Descripción" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=categories" type="button" class="btn btn-default">Cancelar</a>
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
        $("#editcategory").validate({
            rules: {
				description: "required",
				prefix: "required",
                type: "required"
            },
            messages: {
				description: "Especifique la Descripción",
				prefix: "Especifique el Prefijo",
                type: "Especifique el Tipo"
            }
        });

        $("#editcategory").submit(function (e) {
            e.preventDefault();
            $.post("./index.php?action=updatecategory", $("#editcategory").serialize(), function (data) {

                if (data.status == 'success') {
                    window.location.replace("./index.php?view=categories");
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
