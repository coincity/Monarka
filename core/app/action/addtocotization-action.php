<?php

$tipo = $_POST["tipo_id"];

if(!isset($_SESSION["cotization"])){

	$product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"price"=>$_POST["product_price"]);
	$_SESSION["cotization"] = array($product);

	$cart = $_SESSION["cotization"];

    $num_succ = 0;
    $process=false;
    $errors = array();
    foreach($cart as $c){
        $q = OperationData::getQByStock($c["product_id"]);
        if($c["q"]<=$q || $tipo=="Servicio"){
            $num_succ++;


        }else{
            $error = array("product_id"=>$c["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
            $errors[count($errors)] = $error;
        }

    }
    if($num_succ==count($cart)){
        $process = true;
    }
    if($process==false){
        unset($_SESSION["cotization"]);
        $_SESSION["errors"] = $errors;
?>
<script>
    window.location = "index.php?view=newcotization";
</script>
<?php
    }
}else {

    $found = false;
    $cart = $_SESSION["cotization"];
    $index=0;

    $q = OperationData::getQByStock($_POST["product_id"]);

    $can = true;
    if($_POST["q"]<=$q || $tipo == "Servicio"){
    }else{
        $error = array("product_id"=>$_POST["product_id"],"message"=>"No hay suficiente cantidad de producto en inventario.");
        $errors[count($errors)] = $error;
        $can=false;
    }

    if($can==false){
        $_SESSION["errors"] = $errors;
?>
<script>
    window.location = "index.php?view=newcotization";
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
            $_SESSION["cotization"] = $cart;
        }

        if($found==false){
            $nc = count($cart);
            $product = array("product_id"=>$_POST["product_id"],"q"=>$_POST["q"],"price"=>$_POST["product_price"]);
            $cart[$nc] = $product;
            $_SESSION["cotization"] = $cart;
        }

    }
}
print "<script>window.location='index.php?view=newcotization';</script>";

?>