<?php

if(count($_POST)>0){

    $ins = SavingData::SumByKind(1);
	$outs = SavingData::SumByKind(2);
	$avaiable = $ins->s-$outs->s;
	if($_POST["kind"]==2&& $avaiable<$_POST["amount"]){
		Core::alert("Error, no se cuenta con el monto solicitado!");
	}else {
        $smallbox = SavingData::getById($_POST["smallbox_id"]);
        $smallbox->concept_id = $_POST["concept_id"];
        $smallbox->date_at = DateTime::createFromFormat('d/m/Y', $_POST["date_at"])->format('Y-m-d');
        $smallbox->description = strtoupper($_POST["description"]);
        $smallbox->amount = $_POST["amount"];
        $smallbox->kind = strtoupper($_POST["kind"]);
        $smallbox->user_id = $_SESSION['user_id'];
        $smallbox->update();
    }
}

print "<script>window.location='index.php?view=smallbox';</script>";


?>