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
	$employee = PersonData::getById($_GET["id"]);
    $marital_status = MaritalStatusData::getAllActive();
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
                    <h2>MODIFICACIÓN DE EMPLEADO</h2>
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
                                    <form class="form-horizontal j-forms" method="post" id="editemployee" enctype="multipart/form-data" action="index.php?action=updateemployee" role="form">
                                        <div class="form-group" style="display:none;">
                                            <label class="col-md-4 control-label">ID</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon">
                                                            <i class="fa fa-key"></i>
                                                        </span>
                                                    </span>
                                                    <input type="text" class="form-control" id="employee_id" name="employee_id" value="<?php echo $employee->id;?>" placeholder="00" readonly />
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
                                                    <?php if($employee->image!=""):?>
                                                        <img alt="Foto" src="storage/employees/<?php echo $employee->image;?>" class="img-responsive" style="max-width:150px;max-height:150px;">
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
                                                    <input type="text" class="form-control" id="no" name="no" value="<?php echo $employee->no;?>" placeholder="Cédula">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Nombre</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
													<span class="input-group-addon">
														<span class="input-icon"><i class="fa fa-id-card-o"></i></span>
													</span>
                                                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $employee->name;?>" placeholder="Nombre">
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
                                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $employee->lastname;?>" placeholder="Apellido">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Sexo</label>
                                            <div class="col-md-7">
                                                <select id="gender" name="gender" class="form-control">
                                                    <option value="">--SELECCIONE--</option>
                                                    <option value="M" <?php if($employee->gender=="M") echo "selected";?>>MASCULINO</option>
                                                    <option value="F" <?php if($employee->gender=="F") echo "selected";?>>FEMENINO</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Estado Civil</label>
                                            <div class="col-md-7">
                                                <select id="marital_status" name="marital_status" class="form-control">
                                                    <option value="">--SELECCIONE--</option><?php foreach($marital_status as $m):?>
                                                    <option value="<?php echo $m->id;?>" <?php if($m->id==$employee->marital_status) echo "selected";?>><?php echo $m->description;?></option><?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Fecha Nacimiento</label>
                                            <div class="col-md-7">
                                                <div class="input-group iconic-input">
                                                    <span class="input-group-addon">
                                                        <span class="input-icon"><i class="fa fa-calendar-check-o"></i></span>
                                                    </span>
                                                    <input type="date" class="form-control" value="<?php echo $employee->birthdate;?>" id="birthdate" name="birthdate" placeholder="Fecha Nacimiento" />
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
                                                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $employee->address;?>" placeholder="Direccion">
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
                                                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $employee->phone;?>" placeholder="Telefono">
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
                                                    <input type="text" class="form-control" id="cell" name="cell" value="<?php echo $employee->cell;?>" placeholder="Celular" />
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
                                                    <input type="text" class="form-control" id="email" name="email" value="<?php echo $employee->email;?>" placeholder="Correo Electrónico">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">&nbsp;</label>
                                            <div class="col-md-7">
                                                <div class="form-actions">
													<button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                    <a href="./index.php?view=employees" type="button" class="btn btn-default">Cancelar</a>
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
        $("#editemployee").validate({
            rules: {
                no: "required",
                name: "required",
                lastname: "required",
                address: "required",
                phone: "required",
                email: {
                    required: true,
                    email: true
                },
                gender: "required",
                birthdate: "required",
                marital_status: "required"
            },
            messages: {
                no: "Especifique la Cédula",
                name: "Especifique el Nombre",
                lastname: "Especifique el Apellido",
                address: "Especifique la Direccion",
                phone: "Especifique el Telefono",
                email: "Especifique un Correo Válido",
                gender: "Especifique el Sexo",
                birthdate: "Especifique la Fecha de Nacimiento",
                marital_status: "Especifique el Estado Civil"
            }
        });
    });
</script>