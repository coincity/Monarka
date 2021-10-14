<?php
if(isset($_POST["q"]) && !is_numeric($_POST["q"])){
Core::alert("Valor invalido!");
Core::redir("./?view=sell");
}

$allow_ncf = ConfigurationData::getByPreffix("allow_ncf")->val;
$client_type = ConfigurationData::getByPreffix("client_type")->val;

if($client_type != null){
   $date = date('Y/m/d', time());
   $ncf = NcfData::getNCF(2,$client_type,$date);
}else {
   $ncf = null;
}


if($ncf == null and $allow_ncf != null and $allow_ncf == "off"){
        $error = array("product_id"=>"ncf","message"=>"No hay Comprobantes Fiscales de Consumo para realizar esta venta.");
        $errors[1] = $error;
        unset($_SESSION["cart"]);
        $_SESSION["errors"] = $errors;
}
else {

    if(!isset($_SESSION["cart"])){
        $product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"price"=>$_POST["product_price"]);
        $_SESSION["cart"] = array($product);

        $cart = $_SESSION["cart"];

        ///////////////////////////////////////////////////////////////////
		$num_succ = 0;
		$process=false;
		$errors = array();
		foreach($cart as $c){

			///
			$q = OperationData::getQByStock($c["product_id"],StockData::getPrincipal()->id);
			$tipo = $_POST["tipo_id"];
            //			echo ">>".$q;
			if($c["q"]<=$q || $tipo=="Servicio"){
				$num_succ++;


			}else{
				$error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de este producto en inventario.");
				$errors[count($errors)] = $error;
			}

		}
        ///////////////////////////////////////////////////////////////////

        //echo $num_succ;
        if($num_succ==count($cart)){
            $process = true;
        }
        if($process==false){
            unset($_SESSION["cart"]);
            $_SESSION["errors"] = $errors;
?>
<script>
    window.location = "index.php?view=sell";
</script>
<?php
        }




    }else {
        $found = false;
        $cart = $_SESSION["cart"];
        $tipo = $_POST["tipo_id"];
        $index=0;

        $q = OperationData::getQByStock($_POST["product_id"],StockData::getPrincipal()->id);





        $can = true;
        if($_POST["q"]<=$q || $tipo=="Servicio"){
        }else{
            $error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
            $errors[count($errors)] = $error;
            $can=false;
        }

        if($can==false){
            $_SESSION["errors"] = $errors;
?>
<script>
    window.location = "index.php?view=sell";
</script>
<?php
        }
?>

<?php
        if($can==true){
            foreach($cart as $c){
                if($c["product_id"]==$_POST["product_id"]){
                    if($c["price"]==$_POST["product_price"]){
                        $found=true;
                        break;
                    }
                }
                $index++;
            }

            if($found==true){
                $q1 = $cart[$index]["q"];
                $q2 = $_POST["q"];
                $cart[$index]["q"]=$q1+$q2;
                $_SESSION["cart"] = $cart;
            }

            if($found==false){
                $nc = count($cart);
                $product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"price"=>$_POST["product_price"]);
                $cart[$nc] = $product;
                $_SESSION["cart"] = $cart;
            }

        }
}

}
 print "<script>window.location='index.php?view=sell';</script>";

?>