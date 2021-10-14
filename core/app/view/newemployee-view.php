<?php
if(!isset($_SERVER['HTTP_REFERER'])){
    print "<script>window.location='./?view=home';</script>";
    exit;
}
if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > ConfigurationData::getByPreffix("session")->val)) 
{   
    session_destroy();   
    session_unset();
	Core::alert("¡Su sesión ha expirado!");
	print "<script>window.location='./?view=login';</script>";
}
else {
	$_SESSION['start'] = time();
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
                    <h2>AGREGAR NUEVO EMPLEADO</h2>
                </div>
                <div class="col-md-6">
                    <ul class="list-page-breadcrumb">
                        <li><a href="./?view=home">Inicio <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li><a href="./?view=employees">Empleados <i class="zmdi zmdi-chevron-right"></i></a></li>
                        <li class="active-page"> Nuevo Empleado</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Nuevo Empleado</h3>
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
                                                        <div class="input prepend-small-btn">
                                                            <div class="file-button">
                                                                Buscar
                                                                <input class="btn btn-success" type="file" id="image" name="image" onchange="document.getElementById('prepend-small-btn').value = this.value;" />
                                                            </div>
                                                            <input class="form-control" type="text" id="prepend-small-btn" readonly="" placeholder="No se eligió archivo" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Nombre</label>
                                                    <div class="col-md-8">
                                                        <div class="input-group iconic-input">
                                                            <span class="input-group-addon">
                                                                <span class="input-icon">
                                                                    <i class="fa fa-edit"></i>
                                                                </span>
                                                            </span>
                                                            <input type="text" class="form-control" id="name" name="name" placeholder="Nombre" />
                                                        </div>
                                                    </div>
                                                </div>                                               
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Sexo</label>
                                                    <div class="col-md-5">
                                                        <select id="gender" name="gender" class="form-control">
                                                            <option value="">--SELECCIONE--</option>
                                                            <option value="M">MASCULINO</option>
                                                            <option value="F">FEMENINO</option>
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
                                                            <input type="date" class="form-control" id="birthdate" name="birthdate" placeholder="Fecha Nacimiento" />
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
                                                            <input type="text" class="form-control" id="address" name="address" placeholder="Direccion" />
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
                                                            <input type="text" class="form-control phone_us-mask1" id="phone" name="phone" placeholder="Telefono" />
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
                                                            <input type="text" class="form-control id-mask1" id="no" name="no" placeholder="Cédula" />
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
                                                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Apellido" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Estado Civil</label>
                                                    <div class="col-md-5">
                                                        <select id="marital_status" name="marital_status" class="form-control">
                                                            <option value="">--SELECCIONE--</option><?php foreach($marital_status as $m):?>
                                                            <option value="<?php echo $m->id;?>"><?php echo $m->description;?></option><?php endforeach;?>
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
                                                            <input type="date" class="form-control" id="hiredate" name="hiredate" placeholder="Fecha Ingreso" />
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
                                                            <input type="text" class="form-control" id="email" name="email" placeholder="Correo Electrónico" />
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
                                                            <input type="text" class="form-control phone_us-mask1" id="cell" name="cell" placeholder="Celular" />
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
<script type="text/javascript">
    $(document).ready(function () {

        $('.phone_us-mask1').mask('(000) 000-0000');

        $('.id-mask1').mask('000-0000000-0');

        $("#addemployee").validate({
            rules: {
                no: {
                    required: true,
                    ziprange: true
                },
                name: "required",
                lastname: "required",
				address: "required",
                phone: {
                    required: true,
                    phoneUS: true
                },
                cell: {
                    phoneUS: true
                },
                email: {
                    email: true
                },
                gender: "required",
                birthdate: "required",
                hiredate: "required",
                marital_status: "required"
            },
            messages: {
                no: {
                    required: "Especifique la Cédula",
                    ziprange: "Especifique una Cédula Válida"
                },
                name: "Especifique el Nombre",
                lastname: "Especifique el Apellido",
				address: "Especifique la Direccion",
                phone: {
                    required: "Especifique el Telefono",
                    phoneUS: "Especifique un Telefono Válido"
                },
                cell: {
                    phoneUS: "Especifique un Celular Válido"
                },
                email: {
                    email: "Especifique un Correo Válido"
                },
				gender: "Especifique el Sexo",
                birthdate: "Especifique la Fecha de Nacimiento",
                hiredate: "Especifique la Fecha de Ingreso",
				marital_status: "Especifique el Estado Civil"
            }
        });
    });
</script>