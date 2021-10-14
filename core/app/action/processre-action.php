<?php
date_default_timezone_set('America/La_Paz');
$currency = ConfigurationData::getByPreffix("currency")->val;
if(isset($_SESSION["reabastecer"])){
	$cart = $_SESSION["reabastecer"];
	if(count($cart)>0){

$process = true;
		//////////////////////////////////
		if($process==true){
			$sell = new ReData();

            $sell->user_id = $_SESSION["user_id"];
            $sell->person_id = isset($_POST["provider_id"])?$_POST["provider_id"]:0;
            $sell->ref_id = 0;
            $sell->total = isset($_POST["total"])?$_POST["total"]:0;
			$sell->ncf = isset($_POST["ncf"])?$_POST["ncf"]:0;
            $sell->ncf = ( $sell->ncf != "")?$sell->ncf:0;
			$sell->itbis = isset($_POST["itbis"])?$_POST["itbis"]:0;
            $sell->itbis = ( $sell->itbis != "")?$sell->itbis:0;
            $sell->bill_id = (isset($_POST["bill_id"]))?$_POST["bill_id"]:0;
            $sell->bill_id = ( $sell->bill_id != "")?$sell->bill_id:0;
			$sell->p_id = $_POST["p_id"];

            if($sell->p_id == 2) $sell->paid = 0;
            else $sell->paid = 1;

			$s = $sell->add();

			if($s[1] > 0){
				$sell->id = $s[1];
				$sell->ref_id = "COM".$s[1];
				$sell->update_ref_id();

                $y = new StockData();
                $yy = $y->add();

                foreach($cart as  $c){

                    $operation_type = 1;

                    $product = ProductData::getById($c["product_id"]);

                    if ($product->price_in != $c["price_in"]) {
                        $product->price_in = $c["price_in"];
                    }
                    if ($product->min_price != $c["min_price"]) {
                        $product->min_price = $c["min_price"];
                    }
                    if ($product->max_price != $c["max_price"]) {
                        $product->max_price = $c["max_price"];
                    }

                    $product->update_price();

                    $op = new OperationData();
                    $op->price_in = $product->price_in;
                    $op->min_price = $product->min_price;
                    $op->max_price = $product->max_price;
                    $op->price_out = 0;
                    $op->stock_id = $yy[1];
                    $op->product_id = $c["product_id"] ;
                    $op->operation_type_id=$operation_type; // 1 - entrada
                    $op->sell_id=$sell->ref_id;
                    $op->q= $c["q"];

                    $add = $op->add();
                }
			}

			unset($_SESSION["reabastecer"]);
			setcookie("selled","selled");
            print "<script>window.location='index.php?view=detailre&id=$s[1]';</script>";
		}
	}
}



?>