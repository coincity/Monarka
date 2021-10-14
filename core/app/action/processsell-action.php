<?php
header('Content-type: application/json');
date_default_timezone_set('America/La_Paz');
$currency = ConfigurationData::getByPreffix("currency")->val;
$client_type = ConfigurationData::getByPreffix("client_type")->val;
$cashDesk = CashDeskData::getOpenCashDeskByUserId($_SESSION['user_id']);


if(isset($_SESSION["cart"])){
	$cart = $_SESSION["cart"];
	if(count($cart)>0){
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){
			$q = OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id);
			$product = ProductData::getProductServiceById($c["product_id"]);
			if($c["q"]<=$q || $product->tipo == "Servicio"){
				if(isset($_POST["is_oficial"])){
                    $qyf =OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id); /// son los productos que puedo facturar
                    if($c["q"]<=$qyf || $product->tipo == "Servicio"){
                        $num_succ++;
                    }else{
                        $error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto para facturar en inventario.");
                        $errors[] = $error;
                    }
				}else{
					// si llegue hasta aqui y no voy a facturar, entonces continuo ...
					$num_succ++;
				}
			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
				$errors[] = $error;
			}

		}
		if($num_succ==count($cart)){
			$process = true;
        }
        
        if($_POST["ncf_type"] != "") {
            $ncfAvailable = NcfData::getNCF($_POST["ncf_type"],PersonData::getById($_POST["client_id"])->client_type_id, date('Y/m/d', time()));
            if($ncfAvailable == null) {
                $process = false;
                $errors[] = "No hay secuencias disponibles para el tipo de comprobante indicado.";
            }
        }

        if($cashDesk == null) {
            $process = false;
            $errors[] = "La caja no esta abierta, abrela antes de facturar.";
        }

		if($process==false){
            $_SESSION["errors"] = $errors;
		}
            
?>
<?php

		if($process == true) {

			$iva_val = ConfigurationData::getByPreffix("imp-val")->val;
            $pro = ProductData::getProductServiceById($c["product_id"]);
            // $imp_val = ConfigurationData::getByPreffix("imp-tarjeta")->val;
			$sell = new SellData();

            $sell->user_id = $_SESSION["user_id"];
            $sell->payment_method = $_POST["payment_method"];
            
            $sell->notes = $_POST["note"];
			//$pendiente = $_POST["pendiente"];
			$sell->p_id = $_POST["p_id"];
            if($pro->itbis == 1) $sell->iva = $iva_val;
            else $sell->iva = 0;
			//if($_POST["cash"] > 0) $sell->cash = $_POST["cash"];// - $pendiente;
            if(isset($_POST["cash"])){
                $sell->cash = $_POST["cash"]!=""?$_POST["cash"]:0;
            }
			else  $sell->cash = 0;


            $sell->subtotal = $_POST["subtotal"];
			$sell->taxes = $_POST["taxes"];
			$sell->total = $_POST["total"];
			$sell->discount = $_POST["discount"];
			$sell->person_id=$_POST["client_id"]!=""?$_POST["client_id"]:"NULL";
            $sell->created_at = "NOW()";
            //if(isset($_POST["cash2"])) {
            //    $sell->imp = $imp_val;
            //}else{
            //    $sell->imp = 0;
            //}
			$s = $sell->add();

            //$cash1 = 0;
            //$cash2 = 0;
            //if(isset($_POST["cash1"])) $cash1 =  $_POST["cash1"];
            //if(isset($_POST["cash2"])) $cash2 =  $_POST["cash2"];

            if(isset($_POST["cash"])) {
                $summary = new SummaryData();
                $summary->payment_method_id = 1;
                $summary->sell_id = $s[1];
                $summary->person_id = $_POST["client_id"]!=""?$_POST["client_id"]:"";
                $summary->reference = "";//$_POST["reference1"]!=""?$_POST["reference1"]:"";
                if($sell->p_id==1)  $summary->val = $sell->cash - ($sell->cash - ($sell->total - $sell->discount));
                else $summary->val = $sell->cash;
                $summary->add();
            }
            //if(isset($_POST["cash2"])) {
            //    $summary = new SummaryData();
            //    $summary->payment_method_id = 2;
            //    $summary->sell_id = $s[1];
            //    $summary->person_id = $_POST["client_id"]!=""?$_POST["client_id"]:"";
            //    $summary->reference = $_POST["reference2"]!=""?$_POST["reference2"]:"";
            //    $summary->val = $sell->cash - $cash1;
            //    $summary->add();
            //}

            if(($sell->p_id==2) || ($sell->p_id==3)){
                $payment = new PaymentData();
                $payment->sell_id = $s[1];
                $payment->val = -1*($sell->total - $sell->discount);
                $payment->person_id = $_POST["client_id"];
                $payment->user_id = $_SESSION["user_id"];
                $payment->add();
            }

            if($sell->p_id==3){
                $payment = new PaymentData();
                $payment->sell_id = $s[1];
                $payment->val = $sell->cash;
                $payment->person_id = $_POST["client_id"];
                $payment->user_id = $_SESSION["user_id"];
                $payment->saldada = 0;
                $payment->add_payment();
            }

            if(isset($_POST["cot_id"])){
                if(isset($_POST["cot_id"]) != ""){
                      if($_POST["cot_id"] != "0"){
                          $cot = CotizationData::getById($_POST["cot_id"]);
                          if($cot->id != ""){
                              StatusData::cambiarStatus("cotizations", $cot->id, 9);
                              StatusData::cambiarStatusByRef("operation", $cot->ref_id, 9);
                          }
                      }
                }
            }


           if($_POST["ncf_type"] != ""){
               $date = date('Y/m/d', time());
               $ncf = NcfData::getNCF($_POST["ncf_type"],PersonData::getById($_POST["client_id"])->client_type_id, date('Y/m/d', time()));
               if($ncf != null){
                   $ncf_detail = new NcfDetailData();
                   $ncf_detail->tipodoc = $ncf->tipodoc;
                   $ncf_detail->tipo = $ncf->tipo;
                   $ncf_detail->ncf = "B".$ncf->code.(sprintf("%'.08d", $ncf->secuenciaactual));
                   $ncf_detail->sell_id = $s[1];
                   $ncf_detail->user_id = $_SESSION["user_id"];
                   $ncf_detail->add();


                   $sell->ncf = $ncf_detail->ncf;
                   if($ncf->secuenciaactual >= $ncf->secuenciafin) {
                       StatusData::cambiarStatus("control_ncf", $ncf->id, 8);
                   }else {
                       $ncf->secuenciaactual++;
                       $ncf->updateSequence();
                   }
               }
           }

            //if($_POST["total"]>0){
            //    $payment2 = new PaymentData();
            //    $payment2->val = -1*$_POST["total"];
            //    $payment2->person_id = $_POST["clientid"];
            //    $payment2->add_payment();
            //}
            //}else if(isset($_POST["abono"])) {

            //    $payment = new PaymentData();
            //     $payment->sell_id = $s[1];
            //     $payment->val = -1*($_POST["total"] - $pendiente);
            //     $payment->person_id = $_POST["client_id"];
            //    $payment->user_id = $_SESSION["user_id"];
            //     $payment->add();

            //    $payment = new PaymentData();
            //     $payment->sell_id = $s[1];
            //     $payment->val = $sell->cash;
            //     $payment->person_id = $_POST["client_id"];
            //    $payment->user_id = $_SESSION["user_id"];
            //     $payment->add_payment();

            //    $sell->p_id = 2;
            //    $sell->id = $s[1];
            //      $sell->update_p();


            // if($pendiente > 0) {
            //    $payment = new PaymentData();
            //    $payment->sell_id = $_POST["oldsell_id"];
            //    $payment->val = $pendiente;
            //    $payment->person_id = $_POST["client_id"];
            //    $payment->user_id = $_SESSION["user_id"];
            //    $payment->add_payment();
            // }


            //if(isset($_POST["reservation"])) {
            //    $reservation = ReservationData::getById($_POST["reservation"]);
            //    $reservation->sell_id = $s[1];
            //    $reservation->updatePayment();
            //}

			foreach($cart as  $c){
				$operation_type = "salida";

				$product = ProductData::getProductServiceById($c["product_id"]);

				$op = new OperationData();
				$op->price_in = $product->price_in;
                $op->min_price = 0;
                $op->max_price = 0;
				$op->price_out = $c["price"];
				$op->product_id = $c["product_id"];

				$op->operation_type_id=OperationTypeData::getByName($operation_type)->id;
				$op->stock_id = StockData::getPrincipal()->id;
				$op->q= $c["q"];
				$op->sell_id=$s[1];

				$op->is_oficial = 1;

				$add = $op->add();

			}
            unset($_SESSION["cart"]);
			setcookie("selled","selled");////////////////////
			//print "<br><div id='pselle' name='pselle'><p class='alert alert-success'>Venta procesada exitosamente. <a target='_blank' href='ticket.php?id=$s[1]' class='btn-xs btn btn-info'><i class='fa fa-ticket'></i> Ver Ticket</a> <a href='index.php?view=onesell&id=$s[1]' class='btn-xs btn btn-primary'>Ver Resumen</a> </p></div>";
            //  print "<script type='text/javascript' language='Javascript'>window.open('ticket.php?id=$s[1]');</script>";
            $response_array['status'] = 'success';
            $response_array['sell_id'] = $s[1];
            $response_array['message'] = 'Venta Realizada Exitosamente';
            echo json_encode($response_array);
            //print "<iframe onload='var pdfFrame = window.frames['pdf'];pdfFrame.focus();pdfFrame.print();' id='pdf' name='pdf' src='ticket.php?id=$s[1]'></iframe>";
		} else {
            $response_array['status'] = 'fail';
            $response_array['message'] = $errors[0];
            echo json_encode($response_array);
        }
	}
}
?>