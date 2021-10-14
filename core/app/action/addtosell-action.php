<?php
if(isset($_GET["id"])){

	 $cotization = CotizationData::getById($_GET["id"]);
     if($cotization->ref_id!="") $operations = OperationData::getAllProductsByRefId($cotization->ref_id);

	 if($operations != "") {
		unset($_SESSION["cart"]);
			if(!isset($_SESSION["cart"])){
				
				$_SESSION["baseCotization"] = $cotization;
				$cart;
				$i = 0;
				foreach($operations as $op){
					$product = array("product_id"=>$op->product_id,"q"=>$op->q,"price"=>$op->price_out,"client"=>$cotization->person_id,"discount"=>$cotization->discount,"cot_id"=>$cotization->id);
					$cart[$i] = $product;
					$_SESSION["cart"] = $cart;
					$i = $i + 1;
				}

				$cart = $_SESSION["cart"];

				$num_succ = 0;
				$process=false;
				$errors = array();
				foreach($cart as $c){

					$q = OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id);
					$pro = ProductData::getProductServiceById($c["product_id"]);

					$tipo = $pro->tipo;
					if($c["q"]<=$q || $tipo=="Servicio"){
						$num_succ++;
					}else{
						$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de este producto en inventario.");
						$errors[count($errors)] = $error;
					}
				}

				if($num_succ==count($cart)){
					$process = true;
				}
				if($process==false){
					unset($_SESSION["cart"]);
					$_SESSION["errors"] = $errors;
?>	
				<script>
				//	window.location="index.php?view=sell";
				</script>
				<?php
				}
				print "<script>window.location='index.php?view=sell';</script>"; 
			}else {
				Core::alert("Session existe!");
				Core::redir("./?view=cotizations");
			}
	 }
	 else {
		Core::alert("Operacion no existe!");
		Core::redir("./?view=cotizations");
	}
}else {
	Core::alert("Valor invalido!");
	Core::redir("./?view=cotizations");
}

?>