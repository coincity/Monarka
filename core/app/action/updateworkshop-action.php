<?php

if(count($_POST)>0){
    $workshop = WorkshopData::getById($_POST["workshop_id"]);
	$workshop->description = strtoupper($_POST["description"]);
	$workshop->client_id = $_POST['client_id'];
	$workshop->brand = strtoupper($_POST["brand"]);
	$workshop->model = strtoupper($_POST["model"]);
	$workshop->serie = strtoupper($_POST["serie"]);
	$workshop->date_in = DateTime::createFromFormat('d/m/Y', $_POST["date_in"])->format('Y-m-d');
	$workshop->date_out = DateTime::createFromFormat('d/m/Y', $_POST["date_out"])->format('Y-m-d');

	if(isset($_POST["returned"])) {
		$workshop->returned = 1;
		$workshop->status = 2;
	} else { 
		$workshop->returned = 0;
		$workshop->status = 1;
	}

    $workshop->observation = strtoupper($_POST["observation"]);
	$workshop->user_id = $_SESSION['user_id'];
	$workshop->update();
}

print "<script>window.location='index.php?view=workshop';</script>";


?>