<?php

if(count($_POST)>0){
    $spend = SpendData::getById($_POST["spend_id"]);
	$spend->concept_id = $_POST["concept_id"];
	$spend->amount = $_POST["amount"];
	$spend->bill_id = $_POST["bill_id"];
	$spend->ncf = $_POST["ncf"];
	$spend->date_at = DateTime::createFromFormat('d/m/Y', $_POST["date_at"])->format('Y-m-d');
	$spend->observations = strtoupper($_POST["observations"]);
    $spend->user_id = $_SESSION['user_id'];
	$spend->update();
}

print "<script>window.location='index.php?view=spends';</script>";


?>