<?php

if(count($_POST)>0){

		$op = new SpendData();
		$op->concept_id = $_POST["concept_id"];
		$op->amount = $_POST["amount"];
		$op->bill_id = isset($_POST["bill_id"])?$_POST["bill_id"]:0;
		$op->ncf = $_POST["ncf"];
		$op->date_at = DateTime::createFromFormat('d/m/Y', $_POST["date_at"])->format('Y-m-d');
		$op->observations = strtoupper($_POST["observations"]);
        $op->user_id = $_SESSION['user_id'];
		$op->add();
}

print "<script>window.location='index.php?view=spends';</script>";

?>