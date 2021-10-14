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
    $actualCashDesk = CashDeskData::getOpenCashDeskByUserId($_SESSION['user_id']);
    $currency = ConfigurationData::getByPreffix("currency")->val;
?>
<section class="main-container">
    <div class="container-fluid">
        <div class="page-header filled full-block light">
            <div class="row">
                <div class="col-md-6">
                    <h2>Apertura/Cierre de Caja</h2>
                    <p>Apertura y Cierre de Cajas Contables</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <?php if($actualCashDesk != null) { ?>
                    
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                
                                    <h5 class="card-title">
                                        Caja #<?php echo $actualCashDesk->id ?>  <?php echo $actualCashDesk->close_time == null ? "<span class='btn btn-sm bg-primary text-white'> Abierta </span>" :  "<span class='btn bg-danger text-white'> Cerrada </span>" ?>
                                    </h5>
                                </div>
                                
                                <div class="col"><input type="text" class="form-control float-right" id="note" placeholder="Nota de cierre..."></div>
                                <div class="col"><input class="form-control float-right" type="number" name="unbalanced" value="" id="unbalanced" placeholder="Monto de descuadre..."> </div>
                                <div class="col-2">
                                    <button id="close" class="btn btn-danger btn-block float-right">Cerrar Caja</button>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                <ul class="list-group">
                                        <li class="list-group-item"><b>Usuario</b> <?php echo $actualCashDesk->user->name . ' ' . $actualCashDesk->user->lastname ?> (<?php echo $actualCashDesk->user->username ?>)</li>
                                        <li class="list-group-item"><b>Monto Inicial</b> <?php echo Utils::moneyFormat($currency, $actualCashDesk->start_amount) ?></li>
                                        <li class="list-group-item"><b>Monto Acumulado</b> <?php echo Utils::moneyFormat($currency, $actualCashDesk->end_amount) ?></li>
                              
                                </ul>
                                </div>
                                <div class="col">
                                <ul class="list-group">
                                        <li class="list-group-item"><b>Fecha de Apertura</b> <?php echo $actualCashDesk->opening_time ?></li>
                                        <li class="list-group-item"><b>Fecha de Cierre</b> <?php echo $actualCashDesk->close_time == null ? "-" : $actualCashDesk->end_time ?></li>
                                </ul>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <h4>Transacciones</h4>
                                    <div class="table">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Cliente</th>
                                                    <th>Tipo de Pago</th>
                                                    
                                                    <th>Tipo de Pago</th>
                                                    <th class="text-right">Valor</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                $subTotal = 0;
                                                foreach ($actualCashDesk->transactions as $key => $transaction) {
                                                    
                                                ?>
                                                <tr class="transactions">
                                                    <td>
                                                        <a href="index.php?view=detailsell&id=<?php echo $transaction->id ?>"> #<?php echo $transaction->id ?> </a>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction->name . ' ' . $transaction->lastname  ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction->tipo ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $transaction->payment_method ?>
                                                    </td>
                                                    <td class="text-right">

                                                      <?php echo Utils::moneyFormat($currency,$transaction->facturado) ?>

                                                    </td>
                                                </tr>
                                                <?php 
                                                $subTotal = $subTotal + $transaction->facturado;
                                                } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">Sub-Total</td>
                                                    <td class="text-right font-weight-bold"><?php echo Utils::moneyFormat($currency,$subTotal) ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">Monto de apertura</td>
                                                    <td class="text-right font-weight-bold"><?php echo Utils::moneyFormat($currency,$actualCashDesk->start_amount) ?></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-right font-weight-bold">Total de cierre</td>
                                                    <td class="text-right font-weight-bold"><?php echo Utils::moneyFormat($currency,$subTotal + $actualCashDesk->start_amount) ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        (function() {
                            
                                document.querySelector("#close").onclick = function() {
                                    var content = this.innerHTML;
                                    this.disabled = true;
                                    this.innerHTML = "Cerrando caja..."
                                    fetch("index.php?action=cashdesk&operation=close&note=" +document.querySelector("#note").value + "&unbalanced=" +document.querySelector("#unbalanced").value).then(function() {
                                        location.reload();
                                    })
                                }

                            var closeTime = "<?php echo $actualCashDesk->close_time ?>";
                            if(closeTime.trim() == "") {
                                setInterval(x =>function() {
                                    fetch("index.php?action=cashdesk&operation=actual").then(async function(response) {
                                        var cashDesk = await response.json();
                                        console.log(cashDesk)
                                        if(document.querySelectorAll(".transactions").length != cashDesk.transactions.length) {
                                            location.reload()
                                        }
                                    });
                                }, 3000);
                            }

                        })();
                    </script>
                <?php } else {  ?>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                Caja Cerrada 
                            </h5>
                            <p>Abre la caja para poder facturar</p>
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="amount" class="control-label font-weight-bold">Monto Inicial de Apertura</label>
                                        <input class="form-control" type="number" name="amount" value="0" id="amount"> 
                                    </div>
                                </div>
                                <div class="col">
                                    <br>
                                    <button class="btn btn-primary mt-2" id="open">Abrir Caja</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script>
                    document.querySelector("#open").addEventListener("click", function() {
                        var content = this.innerHTML;
                        this.disabled = true;
                        this.innerHTML = "Abriendo caja..."
                        fetch("index.php?action=cashdesk&operation=open&amount="+document.querySelector("#amount").value).then(function() {
                            location.reload();
                        })
                    });
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
