<?php

if(count($_POST)>0){

	$client = PersonData::getById($_POST["client_id"]);
	$client->no = $_POST["no"];
	$client->name = strtoupper($_POST["name"]);
	$client->lastname = strtoupper($_POST["lastname"]);
	$client->address = $_POST["address"];
	$client->phone = $_POST["phone"];
    $client->cell = $_POST["cell"];
	$client->email = $_POST["email"];
	$client->user_id = $_SESSION['user_id'];
	$client->client_type_id = $_POST['client_type_id'];
	

	

	$client->update_client();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$image->file_new_name_body = $client->no."_".date("Y-m-d H:i:s");
			$image->Process("storage/clients/");
			if($image->processed){
				$client->image = $image->file_dst_name;
				$client->update_image();
			}
		}
	}

    if(isset($_POST['observations'])) {
		$observations = isset($_POST["observations"])?$_POST["observations"]:"";
		$client->update_comment($observations);
	}

}
print "<script>window.location='index.php?view=clients';</script>";

?>