<?php
date_default_timezone_set('America/La_Paz');
if(isset($_SESSION["cotization"])){
	$cart = $_SESSION["cotization"];
	if(count($cart)>0){
		$num_succ = 0;
		$process=true;

		if($process==true){
			$sell = new CotizationData();
			if(isset($_SESSION["user_id"])){
				$sell->user_id = $_SESSION["user_id"];
				$sell->person_id = isset($_POST["client_id"])?$_POST["client_id"]:0;
                if($_POST["discount"] != "") $sell->discount = $_POST["discount"];
                else $sell->discount = 0;
                $sell->ref_id = 0;
                $sell->subtotal = isset($_POST["subtotal"])?$_POST["subtotal"]:0;

                $sell->taxes = isset($_POST["taxes"])?$_POST["taxes"]:0;
                $sell->total = isset($_POST["total"])?$_POST["total"]:0;
				$s = $sell->add();

				if($s[1] > 0){
					$sell->id = $s[1];
					$sell->ref_id = "COT".$s[1];
					$sell->update_ref_id();

                    foreach($cart as  $c){
                        $operation_type = "salida";
                        if(isset($_POST["d_id"]) && $_POST["d_id"]==2){ $operation_type="salida-pendiente"; }

                        $product = ProductData::getProductServiceById($c["product_id"]);
                        if($product->tipo == "Producto") $p = ProductData::getById($product->id)->price_in;
                        else if ($product->tipo == "Servicio") $p = 0;
                        $op = new OperationData();
                        $op->product_id = $c["product_id"];
                        $op->price_in = $p;
                        $op->min_price = 0;
                        $op->max_price = 0;
                        $op->price_out = $c["price"];
                        $op->operation_type_id=OperationTypeData::getByName($operation_type)->id;
                        $op->stock_id = StockData::getPrincipal()->id;
                        $op->sell_id=$sell->ref_id;
                        $op->q= $c["q"];

                        $add = $op->add_cotization();

                        unset($_SESSION["cotization"]);
                        setcookie("selled","selled");
                    }
				}
			}

           // print "<script type='text/javascript' language='Javascript'>window.open('tickets/ticket-co.php?id=$sell->id');</script>";
            print "<script>window.location='index.php?view=cotizations';</script>";
		}
	}
}



?>