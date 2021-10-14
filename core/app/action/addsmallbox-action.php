<?php

if(count($_POST)>0){
	$ins = SavingData::SumByKind(1);
	$outs = SavingData::SumByKind(2);
	$avaiable = $ins->s-$outs->s;
	if($_POST["kind"]==2&& $avaiable<$_POST["amount"]){
		Core::alert("Error, no se cuenta con el monto solicitado!");
	}else{

		$op = new SavingData();
		$op->concept_id = $_POST["concept_id"];
		$op->date_at = DateTime::createFromFormat('d/m/Y', $_POST["date_at"])->format('Y-m-d');
		$op->description = strtoupper($_POST["description"]);
		$op->amount = $_POST["amount"];
		$op->kind = $_POST["kind"];
        $op->user_id = $_SESSION['user_id'];
		$op->add();
	}
}

print "<script>window.location='index.php?view=smallbox';</script>";

?>