<?php

    if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > ConfigurationData::getByPreffix("session")->val))
    {
        session_destroy();
        session_unset();
        Core::alert("¡Su sesión ha expirado!");
        print "<script>window.location = 'index.php?view=login';</script>";
    }
    else {
        $_SESSION['start'] = time();
    }
    $currency = ConfigurationData::getByPreffix("currency")->val;
    $clients = PersonData::getClientsActive();
?>

<section class="main-container">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md">
                            <div class="form-group">
                                    <label for="client_id" class="control-label">Cliente</label>
                                    <select id="client_id" name="client_id" class="form-control select4"
                                        style="width: 100%">
                                        <option value=""></option>
                                        <?php foreach($clients as $c):?>
                                        <option value="<?php echo $c->id; ?>">
                                            <?php echo $c->name." ".$c->lastname; ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ncfType">Tipo de comprobante</label>
                                    <select id="ncfType" name="ncfType" class="form-control" style="width: 100%">
                                                                            <option selected value="">No aplica.</option>
                                                                            <?php foreach(TipoDocData::getAllActive() as $t):?>
                                                                            <option value="<?php echo $t->id;?>"><?php echo $t->description;?></option>
                                                                            <?php endforeach;?>
                                                                        </select>
                                                                        </div>
                                                                        <div class="form-group">
                                    <label for="paymentTypeId">Tipo de venta</label>
                                    <select id="paymentTypeId" name="paymentTypeId" class="form-control" style="width: 100%">
                                                                            <option value="1">AL CONTADO</option>
                                                                            <option value="2">CREDITO</option>
                                                                            <option value="3">ABONO</option>
                                                                        </select>
                                </div>
                            </div>
                            <div class="col-12 col-md">
                                <div class="row">
                                <div class="col">
                                                      
                                <div class="form-group">
                                    <label for="paymentMethod">Metodo de pago</label>
                                    <select id="paymentMethod" name="paymentMethod" class="form-control" style="width: 100%">
                                                                            <option selected value="EFECTIVO">Efectivo</option>
                                                                            <option value="TARJETA">Tarjeta de Credito/Debito</option>
                                                                            <option value="TRANSFERENCIA">Transferencia</option>
                                                                            <option value="CHEQUE">Cheque</option>
                                                                        </select>
                                    
                                </div>
                                </div>
                                <div class="col cashOnly">
                                    <div class="form-group">
                                    <label for="cashAmount" class="control-label">Cant. Efectivo</label>
                                    <input type="number" class="form-control" name="cashAmount" id="cashAmount" placeholder="Efectivo recibido.">
                                    </div>
                                </div>
                                </div>      

                                
                                
                                    
                                
                                <div class="form-group">
                                    <label for="note" class="control-label">Comentario</label>
                                    <textarea name="note" id="note" maxlength="499" class="form-control" cols="30"
                                        rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        <div class="col">
        <div class="card mt-4">
        <div class="card-body">
        <div class="row">
        <div class="col">
        <div class="form-group">
                                    <label for="productSearch">Nombre del Producto / Codigo de Barras</label>
                                    <input list="productsList" autofocus type="text" name="productSearch" class="form-control" id="productSearch">
                                    <datalist id="productsList">
                                    </datalist>
                                    <small id="productSuggestion"></small>
                                    <small class="text-muted">Si tienes un codigo de barras enfoca este campo, y empieza
                                        a escanear.</small>
                                </div>
        
        </div></div></div></div></div>
        </div>
        <div class="row">
            <div class="col">
                <table id="productsTable" class="table table-bordered table-striped bg-white">
                    <thead>
                        <tr>
                            <th>Descripción</th>
                            <th class="text-right">Precio Unit.</th>
                            <th>Cantidad</th>

                            <th class="text-right">Sub-Total</th>
                            <th class="text-right">Impuestos</th>
                            <th class="text-right">Precio Total</th>
                        </tr>
                    </thead>

                    <tbody>

                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr>
                            <td class="text-right" colspan="5">Sub-total</td>
                            <td class="text-right" id="subTotal">0.00</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Impuestos</td>
                            <td class="text-right" id="taxes">0.00</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Total</td>
                            <td class="text-right" id="total">0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row">
        <div class="col">
            <div class="alert" id="message" hidden>
            </div>
        </div>
        <div class="col text-right">
                <button id="send" class="btn btn-primary">Procesar Factura</button>
        </div>
        </div>
    </div>
</section>

<script>

    (async function () {
        var generalValues = {
            discount: 0,
            subTotal: 0,
            taxes: 0,
            total: 0
        }
        var products = await (await fetch("index.php?action=product")).json();
        var productSearchInput = document.querySelector("#productSearch");
        var productsList = document.querySelector("#productsList")
        var productsTable = document.querySelector("#productsTable");
        var productsTableBody = productsTable.querySelector("tbody");
        var productSuggestion = document.querySelector("#productSuggestion");
        var productsTableFooter = productsTable.querySelector("tfoot");
        var taxesPercent = <?php echo ConfigurationData:: getByPreffix("imp-val") -> val;?>;
        var currency = "<?php echo $currency;?> ";
        var saleItems = [];


        products.forEach(product => productsList.innerHTML += `<option value="${product.name}">`);


        function renderTableAndCalculate() {
            generalValues.subTotal = generalValues.taxes = generalValues.total = 0;
            productsTableBody.innerHTML = "";
            saleItems.filter(x => !x.quantity || x.quantity <= 0).forEach(x => {
                saleItems.splice(saleItems.indexOf(x), 1);
            })



            if (!saleItems.length) {
                productsTableBody.innerHTML += `
                    <tr>
                        <td class="text-center text-muted" colspan="6">No hay productos en esta factura...</td>
                    </tr>
                `;
            }


            for (var item of saleItems) {
                generalValues.subTotal += item.subTotal = parseFloat(item.max_price) * item.quantity;
                generalValues.taxes += item.taxes = parseInt(item.itbis) ? item.subTotal * (taxesPercent / 100) : 0;
                generalValues.total += item.total = item.subTotal + item.taxes;


                productsTableBody.innerHTML += `
                    <tr data-product-id="${item.id}">
                        <td><b>${item.name}</b></td>
                        <td class="text-right">${currency + formatMoney(item.max_price)}</td>
                        <td class="productQuantity" contentEditable>${item.quantity}</td>
                        <td class="text-right">${currency + formatMoney(item.subTotal)}</td>
                        <td class="text-right">${currency + formatMoney(item.taxes)}</td>
                        <td class="text-right">${currency + formatMoney(item.total)}</td>
                    </tr>
                `;


            }
            document.querySelectorAll("tr[data-product-id] .productQuantity").forEach(quantityInput => {
                function changeAndCalculate() {
                    var productId = this.parentNode.dataset.productId;
                    var item = saleItems.filter(x => x.id == productId)[0];
                    if(item) {
                        try {
                            item.quantity = parseInt(quantityInput.innerText);
                            renderTableAndCalculate();
                        } catch(e) {

                        }
                    }
                }

                quantityInput.addEventListener("blur", changeAndCalculate);
                
                quantityInput.addEventListener("keyup", event => {
                    var key = event.keyCode || event.which;
                    if(key == 13 || key.code == "Enter") {
                        quantityInput.blur();
                        quantityInput.dispatchEvent(new Event("blur"));
                        event.preventDefault();
                        event.stopPropagation();
                        return false;
                    }
                });
            })

            productsTableFooter.querySelector("#subTotal").innerHTML = currency + formatMoney(generalValues.subTotal)
            productsTableFooter.querySelector("#taxes").innerHTML = currency + formatMoney(generalValues.taxes)
            productsTableFooter.querySelector("#total").innerHTML = currency + formatMoney(generalValues.total)
        }



        function addProduct(productSelected) {
                    var existingItem = saleItems.filter(x => x.id == productSelected.id)[0];

                    if (existingItem) {
                        existingItem.quantity++;
                    } else {
                        var item = JSON.parse(JSON.stringify(productSelected));
                        item.quantity = 1;
                        item.subTotal = 0;
                        item.taxes = 0;
                        item.total = 0;
                        saleItems.push(item);
                    }


                    renderTableAndCalculate();
        }

        productSearchInput.addEventListener("keyup", function (event) {
            var key = event.keyCode || event.which;
            var searchValue = this.value.trim();
            var productsFound = products.filter(x => (x.name).toUpperCase().indexOf(searchValue.toUpperCase()) > -1 || (x.description).toUpperCase().indexOf(searchValue.toUpperCase()) > -1  || x.barcode.toUpperCase() == searchValue.toUpperCase());
            
            if (key == 13) {
                if (productsFound.length) {
                    addProduct(productsFound[0]);
                }
                
                this.value = "";
                return;
            }
        })


        productSearchInput.addEventListener("change", function() {
            var searchValue = this.value.trim();
            var productsFound = products.filter(x => (x.name).toUpperCase().indexOf(searchValue.toUpperCase()) > -1);
            
            if (productsFound.length) {
                    addProduct(productsFound[0]);
                    this.value = "";
                }
        })



        function displayMessage(type, message) {
            var alertElement = document.querySelector("#message");
            alertElement.innerHTML = message;
            alertElement.classList.add("alert-" + type);
            alertElement.hidden = false;
            setTimeout(function(alertElement) {
                alertElement.hidden = true;
                alertElement.classList.remove("alert-" + type);
            }, 3000, alertElement)
        }


        function validate(saleInformation) {
            if(!saleInformation.products.length) {
                displayMessage("danger", "No puedes realizar una factura sin productos.")
                return false;
            }

            if(!saleInformation.customerId) {
                displayMessage("danger", "Debes seleccionar un cliente para continuar.")
                return false;
            }

            if(saleInformation.paymentMethod === "EFECTIVO" && !saleInformation.cashAmount || saleInformation.cashAmount < saleInformation.values.total) {
                displayMessage("danger", "Debes ingresar una cantidad valida de efectivo, por lo menos mayor al total de la factura.")
                return false;
            }

            if(saleInformation.paymentMethod !== "EFECTIVO" && saleInformation.comment.trim().length < 5) {
                displayMessage("danger", "Si el pago no es con efectivo, debes ingresar un comentario con la informacion del metodo de pago.")
                return false;
            }
            return true;
        }

        document.querySelector("#paymentMethod").addEventListener("change", function() {
            document.querySelectorAll(".cashOnly").forEach(x => x.hidden = this.value != "EFECTIVO");
        })




        document.querySelector("#send").addEventListener("click", function() {
            renderTableAndCalculate();
            var saleInformation = {
                customerId : document.querySelector("#client_id").value,
                paymentMethod : document.querySelector("#paymentMethod").value,
                paymentTypeId : document.querySelector("#paymentTypeId").value,
                comment : document.querySelector("#note").value,
                values : generalValues,
                products : saleItems
            }

            if(saleInformation.paymentMethod === "EFECTIVO") {
                saleInformation.cashAmount = document.querySelector("#cashAmount").value;
            }

            if(validate(saleInformation)) {
                fetch("index.php?action=sale", {
                    method : "POST",
                    headers : {
                        "Content-Type" : "application/json"
                    },
                    body : JSON.stringify(saleInformation)
                });
            }
        })
        renderTableAndCalculate();

    })();

</script>