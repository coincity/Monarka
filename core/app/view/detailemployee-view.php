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
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>DETALLE DE EMPLEADO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./index.php?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./index.php?view=employees">Empleados <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Detalle</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Detalle de Empleado</h3>
                        </div>
                    </div>
                    <div class="widget-container">
                        <div class="widget-content">
                             <div class="row">
                                <div class="col-md-12">
                                    <form class="form-horizontal j-forms" id="addemployee" method="post" enctype="multipart/form-data" action="index.php?action=addemployee" role="form">
                                    <div class="row">
                                       <div class="col-md-6 unit">
                                               <div class="form-group">
                                                    <label class="col-md-3 control-label">Foto</label>
                                                    <div class="col-md-7">
                                                        <?php if($employee->image!=""):?>
												             <img alt="Foto" src="storage/employees/<?php echo $employee->image;?>" class="thumbnail img-responsive" style="max-width:150px;max-height:150px;margin-bottom:8px;">
												        <?php endif;?>
                                                    </div>
                                                </div>
                                            </div>
                                       <div class="col-md-6 unit">
                                                <div class="form-group" style="display:none">
                                                    <label class="col-md-4 control-label">ID</label>
                                                    <div class="col-md-7">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-key"></i>
                                                                </span>
                                                            </span>
                                                            <input type="text" class="form-control" value="<?php echo $employee->id;?>" placeholder="00" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                           </div>
                                    </div>
                                     <div class="row">
                                            <div class="col-md-6 unit">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Nombre</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-edit"></i>
                                                                </span>
                                                            </span>
                                                            <input type="text" class="form-control" value="<?php echo $employee->name;?>" placeholder="Nombre" disabled />
                                                        </div>
                                                    </div>
                                                </div>                                               
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Sexo</label>
                                                    <div class="col-md-5">
                                                         <select id="gender" name="gender" class="form-control" disabled>
                                                            <option value="">--SELECCIONE--</option>
                                                            <option value="M" <?php if($employee->gender=="M") echo "selected";?>>MASCULINO</option>
                                                            <option value="F" <?php if($employee->gender=="F") echo "selected";?>>FEMENINO</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Fecha Nacimiento</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon"><i class="fa fa-calendar-check-o"></i></span>
                                                            </span>
                                                            <input type="date" class="form-control" value="<?php echo $employee->birthdate;?>" id="birthdate" name="birthdate" placeholder="Fecha Nacimiento" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-3 control-label">Direccion</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-map-marker"></i>
                                                                </span>
                                                            </span>
                                                             <input type="text" class="form-control" value="<?php echo $employee->address;?>" placeholder="Direccion" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Telefono</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa fa-phone"></i>
                                                                </span>
                                                            </span>
                                                           <input type="text" class="form-control" value="<?php echo $employee->phone;?>" placeholder="Telefono" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 unit">
                                                 <div class="form-group">
                                                    <label class="col-md-3 control-label">Cédula</label>
                                                    <div class="col-md-7">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-id-card-o"></i>
                                                                </span>
                                                            </span>
                                                           <input type="text" class="form-control" value="<?php echo $employee->no;?>" placeholder="No." disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Apellido</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-edit"></i>
                                                                </span>
                                                            </span>
                                                            <input type="text" class="form-control" value="<?php echo $employee->lastname;?>" placeholder="Apellido" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Estado Civil</label>
                                                    <div class="col-md-5">
                                                          <select id="marital_status" name="marital_status" class="form-control" disabled>
                                                            <option value="">--SELECCIONE--</option><?php foreach($marital_status as $m):?>
                                                            <option value="<?php echo $m->id;?>" <?php if($m->id==$employee->marital_status) echo "selected";?>><?php echo $m->description;?></option><?php endforeach;?>
                                                          </select>
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-md-3 control-label">Fecha Ingreso</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon"><i class="fa fa-calendar-check-o"></i></span>
                                                            </span>
                                                            <input type="date" class="form-control" value="<?php echo $employee->hiredate;?>" id="hiredate" name="hiredate" placeholder="Fecha Ingreso" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Correo Electrónico</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-envelope"></i>
                                                                </span>
                                                            </span>
                                                           <input type="text" class="form-control" value="<?php echo $employee->email;?>" placeholder="Correo" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Celular</label>
                                                    <div class="col-md-5">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-mobile"></i>
                                                                </span>
                                                            </span>
                                                            <input type="text" class="form-control" id="cell" name="cell" value="<?php echo $employee->cell;?>" placeholder="Celular" disabled />
                                                        </div>
                                                    </div>
                                                </div>
                                             </div>                                     
                                            <div class="col-md-12 unit">
                                               <div class="row">
                                                   <div class="col-md-6 unit">
                                                       <div class="form-group">
                                                            <label class="col-md-3 control-label">&nbsp;</label>
                                                            <div class="col-md-7">
                                                                <div class="form-actions">
                                                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                                    <a href="./?view=employees" type="button" class="btn btn-default">Cancelar</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                   </div>
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