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
	$currency = ConfigurationData::getByPreffix("currency")->val;
	
	$mes = date('Y/m/1', time());
	$año = date('Y-01-01', time());
	$end = date('Y/m/d', time());
	$hour = strtotime('-1 hour' , strtotime($end));
	$hour = date('Y/m/d', $hour);	
	
	$earnHour = SellData::getAllSellByDate($end,$end);
	$earnDay = SellData::getAllSellByDate($end,$end);
	$earnMoth = SellData::getAllSellByDate($mes,$end);
	$earnYear = SellData::getAllSellByDate($año,$end);
	
	$qDay = OperationData::GetAllQ($end,$end);
	$qMoth = OperationData::GetAllQ($mes,$end);
	$qYear = OperationData::GetAllQ($año,$end);
	
}


?>
<!--Page Container Start Here-->
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <h2>Dashboard</h2>
                    <p>Sistema de Administración: Facturación e Inventario</p>
                </div>
                <div class="col-md-6 col-sm-6">
                    <ul class="list-page-breadcrumb">
                        <li>
                            <a href="./index.php?view=home">
                                Inicio
                                <i class="zmdi zmdi-chevron-right"></i>
                            </a>
                        </li>
                        <li class="active-page">Dashboard</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-4">
                <div class="stats-widget stats-widget">
                    <div class="widget-header">
                        <h3>Estadísticas Diarias</h3>
                        <p>Total de Ventas y Ganancias del Día</p>
                    </div>
                    <div class="widget-stats-list">
                        <ul>
							<li>
                                <label>Facturado:</label>
								<?php								
								
								$tot = 0;
								foreach($earnDay as 
								$earn): 
								$tot += $earn->facturado;
								endforeach;
								echo $currency." ".number_format($tot,2,".",",");
								
								?>
                            </li>
                            <li>
                                <label>Ganancias:</label>
								<?php								
								
								$tot = 0;
								foreach($earnDay as 
								$earn): 
								$tot += $earn->pagado;
								endforeach;
								echo $currency." ".number_format($tot,2,".",",");
								
								?>
                            </li>
                            <li>
                                <label>Productos Vendidos:</label>
								<?php
								$q = $qDay->suma;
								if (empty($q)){
									$q = 0;
								}
								echo $q; 
								?>
                            </li>
                        </ul>
                    </div>
                    <div class="w_bg_teal stats-chart-container">
                        <div id="widget-stats-chart" class="stats-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="widget-wrap stats-widget">
                    <div class="widget-header">
                        <h3>Estadísticas Mensuales</h3>
                        <p>Total de Ventas y Ganancias del Mes</p>
                    </div>
                    <div class="widget-stats-list">
                        <ul>
                            <li>
                                <label>Facturado:</label>
								<?php								
								
								$tot = 0;
								foreach($earnMoth as 
								$earn): 
								$tot += $earn->facturado;
								endforeach;
								echo $currency." ".number_format($tot,2,".",",");
								
								?>
                            </li>
                            <li>
                                <label>Ganancias:</label>
								<?php								
								
								$tot = 0;
								foreach($earnMoth as 
								$earn): 
								$tot += $earn->pagado;
								endforeach;
								echo $currency." ".number_format($tot,2,".",",");
								
								?>
                            </li>
                            <li>
                                <label>Productos Vendidos:</label>
								<?php
								$q = $qMoth->suma;
								if (empty($q)){
									$q = 0;
								}
								echo $q; 
								?>
                            </li>
                        </ul>
                    </div>
                    <div class="w_bg_deep_purple stats-chart-container">
                        <div id="widget-monthly-chart" class="stats-chart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-4">
                <div class="widget-wrap stats-widget">
                    <div class="widget-header">
                        <h3>Estadísticas Anuales</h3>
                        <p>Total de Ventas y Ganancias</p>
                    </div>
                    <div class="widget-stats-list">
                        <ul>
                            <li>
                                <label>Facturado:</label>
								<?php								
								
								$tot = 0;
								foreach($earnYear as 
								$earn): 
								$tot += $earn->facturado;
								endforeach;
								echo $currency." ".number_format($tot,2,".",",");
								
								?>
                            </li>
                            <li>
                                <label>Ganancias:</label>
								<?php								
								
								$tot = 0;
								foreach($earnYear as 
								$earn): 
								$tot += $earn->pagado;
								endforeach;
								echo $currency." ".number_format($tot,2,".",",");
								
								?>
                            </li>
                            <li>
                                <label>Productos Vendidos:</label>
								<?php
								$q = $qYear->suma;
								if (empty($q)){
									$q = 0;
								}
								echo $q; 
								?>
                            </li>
                        </ul>
                    </div>
                    <div class="w_bg_cyan stats-chart-container">
                        <div id="widget-alltime-chart" class="stats-chart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Notificaciones</h3>
                            <p>Alertas para Productos de Inventario</p>
                        </div>
                    </div>
                    <?php

                    $products = ProductData::getActive();

                     if(count($products) > 0){
                        ?>
                        <div class="widget-container">
                            <div class="widget-content">
                                <?php
                                foreach($products as $p) {
                                    $q= OperationData::getQByStock($p->id);
                                    if($q==0){
                                    ?>
                                        <div class="alert alert-danger alert-dismissible fade in iconic-alert clearfix " role="alert">
                                            <div class="alerts-icon">
                                                <i class="fa fa-exclamation-triangle"></i>
                                            </div>
                                            <div class="alert-details">
                                                <strong>No Hay Existencias del Producto </strong>
                                                <a class="alert-link" style="text-decoration: none;">
                                                    <?php  echo $p->name; ?>
                                                </a>en el Inventario.
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    else if(($q > 0) && ($q <=10)){
                                        ?>
                                        <div class="alert alert-warning alert-dismissible fade in iconic-alert clearfix " role="alert">
                                            <div class="alerts-icon">
                                                <i class="fa fa-exclamation-circle"></i>
                                            </div>
                                            <div class="alert-details">
                                                <strong>
                                                    Quedan muy pocas Existencias  (<?php echo $q; ?>) del Producto
                                                </strong>
                                                <a class="alert-link" style="text-decoration: none;">
                                                    <?php  echo $p->name; ?>
                                                </a>
                                                en el Inventario.
                                            </div>
                                        </div>
                                        <?php
                                    } else{
                                        ?>
                                        <div class="alert alert-info alert-dismissible fade in iconic-alert clearfix " role="alert">
                                            <div class="alerts-icon">
                                                <i class="fa fa-check-square-o"></i>
                                            </div>
                                            <div class="alert-details">
                                                <strong>
                                                    Todos los Productos Cuenta con Suficientes Existencias
                                                </strong>
                                                en el Inventario.
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                        ?>
                            </div>
                        </div>
                        <?php
                        }
                    ?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="widget-wrap">
                    <div class="widget-header block-header margin-bottom-0 clearfix">
                        <div class="pull-left">
                            <h3>Todo List</h3>
                            <p>Listado de Tareas Pendientes</p>
                        </div>
                    </div>
                    <div class="widget-container margin-top-0">
                        <div class="widget-content">
                            <div class="task-widget">
                                <input type="hidden" id="user_id" value="<?php echo $_SESSION["user_id"]?>" name="user_id" />
                                <div class="widget-task-list todo-tasklist"></div>
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
    var invoker;

    var loadToDolist = function (id) {
        var url = 'core/app/action/gettodolist-action.php';
        var obj;
        var dataTosend = 'user=' + $("#user_id").val();
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data: dataTosend,
            dataType: "json",
            success: function (res) {
                obj = res;
            },
            error: function (xhr, ajaxOptions, thrownError) {
                
            },
            async: false
        });

        return obj;
    }

    function fillToDoList() {
        $(".todo-tasklist").html("");
        var obj = loadToDolist();
        $.each(obj, function (key, val) {

            //var time = "";
            //var label = "";
            //var decoration = "none";
            //var checked = "";
            //var tools = "";


            //if ($("#user_id").val() == val.user_from) {
            //    tools = "<div class='tools'><i id='edt-" + val.id + "' onclick='GetInvoker(this)' data-toggle='modal' data-target='#myModal4' class='fa fa-edit'></i><i id='del-" + val.id + "' onclick='deleteTask(this);' class='fa fa-trash-o'></i></div>";
            //}

            //date_future = new Date();
            //date_now = new Date(val.created_at);

            //seconds = Math.floor((date_future - (date_now)) / 1000);
            //minutes = Math.floor(seconds / 60);
            //hours = Math.floor(minutes / 60);
            //days = Math.floor(hours / 24);

            ///*	alert(date_future - (date_now);
            //	alert(seconds);
            //	alert(minutes);
            //	alert(hours);
            //	alert(days);*/

            //if (val.is_completed == 1) {
            //    decoration = "line-through";
            //    checked = "checked";
            //}

            //if (seconds < 59) {
            //    time = seconds + " s";
            //    label = "label label-success";
            //}
            //else {
            //    if ((minutes > 0) && (minutes < 59)) {
            //        if (minutes == 1) time = minutes + " min";
            //        else time = minutes + " mins";
            //        label = "label label-primary";
            //    }
            //    else {
            //        if ((hours > 0) && (hours < 24)) {
            //            if (hours == 1) time = hours + " hora";
            //            else time = hours + " horas";
            //            label = "label label-warning";
            //        }
            //        else {
            //            if (days == 1) time = days + " dia";
            //            else time = days + " dias";
            //            label = "label label-danger";
            //        }
            //    }
            //}
            var txtlevel = "";
            var fulldate = "";
            if (val.level == 1) txtlevel = "Alta Importancia";
            else if (val.level == 2) txtlevel = "Baja Importancia";

            if (val.created_at != "") {
                var duedate = new Date(val.created_at);
                var dd = duedate.getDate();
                var mm = duedate.getMonth() + 1;
                if (dd < 10) { dd = '0' + dd };
                if (mm < 10) { mm = '0' + mm };
                var seconds = duedate.getSeconds();
                var minutes = duedate.getMinutes();
                var hour = duedate.getHours();
                fulldate = dd + "/" + mm + "/" + duedate.getFullYear() + " " + tConvert(hour + ':' + minutes + ":" + seconds);
            }

            $(".todo-tasklist").append("<div class='task-entry'>" +
                "<div class='task-intro'>" +
                "<div class='task-action'>" +
                "<input class='task-i-check' type='checkbox' />" +
                "</div>" +
                "<div class='task-title'>" + val.description + "</div>" +
                "</div>" +
                "<div class='task-details'>" +
                "<p>ASIGNADO POR - " + val.name + " " + val.lastname + "</p>" +
                "<label class='label label-danger'>" + txtlevel + "</label>" +
                "<div class='todo-date'>" +
                "<div class='todo-due-date'>" +
                "<i class='fa fa-clock-o'></i>Asignado el: " + fulldate + "" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>");

        });
    }

    // alert(JSON.stringify(loadToDolist()));
    fillToDoList();

    function tConvert(time) {
        // Check correct time format and split into components
        time = time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

        if (time.length > 1) { // If time format correct
            time = time.slice(1);  // Remove full string match value
            time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
            time[0] = +time[0] % 12 || 12; // Adjust hours
        }
        return time.join(''); // return adjusted time or original string
    }
</script>