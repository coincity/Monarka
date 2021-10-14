<?php
header('Content-type: application/json');
date_default_timezone_set('America/La_Paz');
if(isset($_POST["cash"])) {
	$payment = new PaymentData();
	$payment->sell_id = $_POST["sell_id"];
	if($_POST["cash"] >= $_POST["credit"]) $payment->val = $_POST["credit"];
    else  $payment->val = $_POST["cash"];
	$payment->person_id = $_POST["person_id"];
    $payment->saldada = 1;
	$payment->user_id = $_SESSION["user_id"];
    $s = $payment->add_payment();

    echo json_encode($s[1]);
}
?>