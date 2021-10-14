<?php
$settings = ConfigurationData::getAll();
$logo = "theme/images/logo-dark.png";
foreach($settings as $cat):
    if($cat->short == "logo") $logo = $cat->val;
endforeach;
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="" />
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title>POS - Punto de Venta</title>
    <link type="text/css" rel="stylesheet" href="theme/css/font-awesome.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/material-design-iconic-font.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/bootstrap.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/animate.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/layout.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/components.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/widgets.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/plugins.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/pages.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/bootstrap-extend.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/common.css" />
    <link type="text/css" rel="stylesheet" href="theme/css/style.css" />
    <script src="theme/js/lib/jquery.js"></script>
    <script>
    </script>
    <style>
        .error {
            color: #ef5350 !important;
            font-size: 14px !important;
            font-family: 'Roboto', sans-serif !important;
            font-weight: 400 !important;
        }
    </style>
</head>

<body>


    <?php if(isset($_SESSION["user_id"])):?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
  <a class="navbar-brand" href="#">Monarka<b>ERP</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto" id="menu">
      
    </ul>
    <ul class="navbar-nav my-2 my-lg-0">

      <li class="nav-item">
      <a class="nav-link"  href="./?view=editprofile&id=<?php echo Core::$user->id?>">
                    <i class="zmdi zmdi-account"></i>
                </a>
      </li>

      <li class="nav-item">
      <a class="nav-link" href="./logout.php">
                    <i class="zmdi zmdi-power"></i>
                </a>
       <li class="nav-item">
        <a class="nav-link disabled" href="#"><?php echo Core::$user->name?></a>
      </li>
      </li>
    </ul>
  </div>
</nav>
    <!--Topbar Start Here-->
    <!--Topbar End Here-->
    <!--Leftbar Start Here-->
    <!--Leftbar End Here-->
    <?php endif;?>

    <?php if(isset($_SESSION["user_id"])):?>
    <div class="content-wrapper">
        <?php View::load("index");?>
    </div>
    <?php else:?>
    <?php if(isset($_GET["view"]) && $_GET["view"]=="clientaccess"):?>
    <?php else:?>
    <section class="login-container boxed-login">
        <div class="container">
            <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card">
                <div class="card-body">
                    <form action="./?action=processlogin" method="post" class="j-forms" id="forms-login" novalidate>
                        <div class="login-form-header">
                            <div class="logo pt-3">
                                <h3 class="text-primary">Monarka<b>ERP</b></h3>
                            </div>
                        </div>
                        <div class="login-form-content">
                            <!-- start login -->
                            <div class="unit">
                                <div class="input login-input">
                                    <label class="icon-left" for="login">
                                        <i class="zmdi zmdi-account"></i>
                                    </label>
                                    <input class="form-control login-frm-input" type="text" id="username" name="username" placeholder="Usuario" />
                                </div>
                            </div>
                            <!-- end login -->
                            <!-- start password -->
                            <div class="unit">
                                <div class="input login-input">
                                    <label class="icon-left" for="password">
                                        <i class="zmdi zmdi-key"></i>
                                    </label>
                                    <input class="form-control login-frm-input" type="password" id="password" name="password" placeholder="Clave" />
                                </div>
                            </div>
                            <!-- end password -->
                            <!-- start response from server -->
                            <div class="response error"></div>
                            <!-- end response from server -->
                        </div>
                        <button type="submit" class="btn-block btn btn-lg btn-primary">Acceder</button>
                    </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">© 2021 A&K Global Services</small>
                </div>
            </div>
            </div>
        </div>
    </section>
    <?php endif;?>
    <?php endif;?>

    <script src="theme/js/lib/bootstrap.js"></script>
    <script src="theme/js/lib/jquery-migrate.js"></script>
    <script src="theme/js/lib/jquery.ui.js"></script>
    <script src="theme/js/lib/jRespond.js"></script>
    <script src="theme/js/lib/nav.accordion.js"></script>
    <script src="theme/js/lib/hover.intent.js"></script>
    <script src="theme/js/lib/hammerjs.js"></script>
    <script src="theme/js/lib/jquery.hammer.js"></script>
    <script src="theme/js/lib/jquery.fitvids.js"></script>
    <script src="theme/js/lib/scrollup.js"></script>
    <script src="theme/js/lib/smoothscroll.js"></script>
    <script src="theme/js/lib/jquery.slimscroll.js"></script>
    <script src="theme/js/lib/velocity.js"></script>
    <script src="theme/js/lib/smart-resize.js"></script>
    <!--iCheck-->
    <script src="theme/js/lib/icheck.js"></script>
    <script src="theme/js/lib/jquery.switch.button.js"></script>
    <!--CHARTS-->
    <script src="theme/js/lib/chart/sparkline/jquery.sparkline.js"></script>
    <script src="theme/js/lib/chart/easypie/jquery.easypiechart.min.js"></script>
    <script src="theme/js/lib/chart/flot/excanvas.min.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.min.js"></script>
    <script src="theme/js/lib/chart/flot/curvedLines.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.time.min.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.stack.min.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.axislabels.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.resize.min.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.tooltip.min.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.spline.js"></script>
    <script src="theme/js/lib/chart/flot/jquery.flot.pie.min.js"></script>

    <!--Ui Elements-->
    <script src="theme/js/lib/sweetalert.js"></script>
    <!--Data Tables-->
    <script src="theme/js/lib/jquery.dataTables.js"></script>
    <script src="theme/js/lib/dataTables.responsive.js"></script>
    <script src="theme/js/lib/dataTables.tableTools.js"></script>
    <script src="theme/js/lib/dataTables.bootstrap.js"></script>
    <!--Forms-->
    <!--<script src="theme/js/lib/jquery.maskedinput.js"></script>-->
    <script src="theme/js/lib/jquery.validate.js"></script>
    <script src="theme/js/lib/jquery.form.js"></script>
    <script src="theme/js/lib/additional-methods.js"></script>
    <script src="theme/js/lib/jquery-cloneya.js"></script>
    <script src="theme/js/lib/j-forms.js"></script>
    <script src="theme/js/lib/jquery.loadmask.js"></script>
    <script src="theme/js/lib/theme-switcher.js"></script>
    <script src="theme/js/apps.js"></script>
    <!--Select2-->
    <script src="theme/js/lib/select2.full.js"></script>
    <!--Forms Plugins-->
    <script src="theme/js/lib/jquery.tagsinput.js"></script>
    <script src="theme/js/lib/jquery.mask.js"></script>
    <script src="theme/js/lib/jquery.bootstrap-touchspin.js"></script>
    <script src="theme/js/lib/bootstrap-filestyle.js"></script>
    <script src="theme/js/lib/selectize.js"></script>
    <script src="theme/js/lib/bootstrap-datepicker.js"></script>
    <script src="theme/js/lib/moment.js"></script>
    <script src="theme/js/lib/daterangepicker.js"></script>
    <script src="theme/js/lib/colorpicker.js"></script>
    <script src="theme/js/lib/colors.js"></script>
    <script src="theme/js/lib/jqColorPicker.js"></script>
    <script src="theme/js/lib/login-validation.js"></script>
    <script src="theme/js/lib/formatMoney.js"></script>
    

    <script>
    var userKind = <?php echo Core::$user->kind; ?>;
        var tabs = [
    {
        "group" : "Configuracion",
        "name": "Dashboard",
        "url": "?view=home",
        "icon": "fa fa-desktop",
        "kind": 3
    },
    {
        "group" : "Factura",
        "name": "Facturación",
        "url": "?view=sell",
        "icon": "fa fa-cc-visa",
        "kind": 3
    },
    {
        "group" : "Factura",
        "name": "Apertura/Cierre Cajas",
        "url": "?view=cashdesk",
        "icon": "fa fa-cc-visa",
        "kind": 3
    },
    {
        "group" : "Factura",
        "name": "Cotización",
        "url": "?view=cotizations",
        "icon": "fa fa-calculator",
        "kind": 3
    },
    {
        "name": "Taller",
        "url": "?view=workshop",
        "icon": "fa fa-wrench",
        "kind": 3
    },
    {
        "group" : "Contabilidad",
        "name": "Inventario",
        "url": "?view=inventary",
        "icon": "fa fa-cubes",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "Movimientos",
        "url": "?view=inventarylog",
        "icon": "fa fa-retweet",
        "kind": 2
    },
    {
        "group" : "Configuracion",
        "name": "Productos",
        "url": "?view=products",
        "icon": "fa fa-barcode",
        "kind": 2
    },
    {
        "group" : "Configuracion",
        "name": "Servicios",
        "url": "?view=services",
        "icon": "fa fa-tags",
        "kind": 2
    },
    {
        "group" : "Configuracion",
        "name": "Clientes",
        "url": "?view=clients",
        "icon": "fa fa-user",
        "kind": 3
    },
    {
        "group" : "Configuracion",
        "name": "Proveedores",
        "url": "?view=providers",
        "icon": "fa fa-truck",
        "kind": 3
    },
    {
        "group" : "Configuracion",
        "name": "Categorías",
        "url": "?view=categories",
        "icon": "fa fa-bookmark",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "Gastos",
        "url": "?view=concepts",
        "icon": "fa fa-thumb-tack",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "Ventas",
        "url": "?view=sells",
        "icon": "fa fa-area-chart",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "Compras",
        "url": "?view=res",
        "icon": "fa fa-cart-plus",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "C. x Cobrar",
        "url": "?view=paymentsells",
        "icon": "fa fa-file-excel-o",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "C. x Pagar",
        "url": "?view=paymentres",
        "icon": "fa fa-file-powerpoint-o",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "Caja Chica",
        "url": "?view=smallbox",
        "icon": "fa fa-fax",
        "kind": 2
    },
    {
        "group" : "Contabilidad",
        "name": "Reportar Gastos",
        "url": "?view=spends",
        "icon": "fa fa-fire",
        "kind": 2
    },
    {
        "group" : "Administracion",
        "name": "Parámetros",
        "url": "?view=settings",
        "icon": "fa fa-cogs",
        "kind": 1
    },
    {
        "group" : "Contabilidad",
        "name": "Comprobantes Fiscales",
        "url": "?view=ncf",
        "icon": "fa fa-file-text-o",
        "kind": 2
    },
    {
        "group" : "Configuracion",
        "name": "Empleados",
        "url": "?view=employees",
        "icon": "fa fa-male",
        "kind": 2
    },
    {
        "group" : "Configuracion",
        "name": "Usuarios",
        "url": "?view=users",
        "icon": "fa fa-users",
        "kind": 2
    }
]


var menuArea = document.getElementById("menu");
tabs.forEach(function(tab) {
    if(!tab.kind || userKind <= tab.kind) {

        var dropdown = document.querySelector("li.dropdown[category='" + tab.group +"']");
        if(!dropdown) {
            dropdown = document.createElement("li");
            dropdown.classList.add("nav-item", "dropdown");
            dropdown.setAttribute("category", tab.group);
            dropdown.innerHTML = `
            <a class="nav-link dropdown-toggle" href="#" id="${tab.group}dropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                ${tab.group}
            </a>
            <div class="dropdown-menu" aria-labelledby="${tab.group}dropDown"></div>`;
            menuArea.append(dropdown);
        }


        var listItem = document.createElement("a");
        listItem.classList.add("dropdown-item");
        listItem.href = tab.url;
        listItem.innerHTML =  tab.name;
        dropdown.querySelector(".dropdown-menu").append(listItem);
    }
})
    </script>

</body>
</html>