<?php

if(count($_POST)>0){
	$provider = PersonData::getById($_POST["provider_id"]);
	$provider->no = $_POST["no"];
	$provider->name = strtoupper($_POST["name"]);
	$provider->lastname = strtoupper($_POST["lastname"]);
    $provider->company = strtoupper($_POST["company"]);
	$provider->address = $_POST["address"];
	$provider->email = $_POST["email"];
	$provider->phone = $_POST["phone"];
    $provider->cell = $_POST["cell"];
    $provider->user_id = $_SESSION['user_id'];
	$provider->update_provider();

}
print "<script>window.location='index.php?view=providers';</script>";

?>