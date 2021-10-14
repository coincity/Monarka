<?php
if(isset($_GET["product_id"])){
	$cart=$_SESSION["cotization"];
	if(count($cart)==1){
	 unset($_SESSION["cotization"]);
	}else{
		$ncart = null;
		$nx=0;
		foreach($cart as $c){
			if($c["product_id"]!=$_GET["product_id"]){// && ($c["price"]==$_GET["price"])){
                $ncart[$nx]= $c;
			}
            else if(($c["product_id"]==$_GET["product_id"]) && ($c["price"]!=$_GET["price"])){
                $ncart[$nx]= $c;
            }
			$nx++;
		}
		$_SESSION["cotization"] = $ncart;
	}

}else{
 unset($_SESSION["cotization"]);
}

print "<script>window.location='index.php?view=newcotization';</script>";

?>