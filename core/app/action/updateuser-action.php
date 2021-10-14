<?php

if(count($_POST)>0){
	$user = UserData::getById($_POST["user_id"]);

	$user->name = strtoupper($_POST["name"]);
	$user->lastname = strtoupper($_POST["lastname"]);
	$user->username = $_POST["username"];
	$user->email = $_POST["email"];
	$user->status = isset($_POST["status"])?1:0;
	$user->kind = $_POST["kind"];
	$prod = $user->update();
	
	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$image->file_new_name_body = $user->name."_".date("Y-m-d H:i:s");
			$image->Process("storage/profiles/");
			if($image->processed){
				$user->image = $image->file_dst_name;
				$user->update_image();
			}
		}
	}

	if($_POST["password"]!=""){
		$user->password = sha1(md5($_POST["password"]));
		$user->update_passwd();
		print "<script>window.location='./?view=users';</script>";	

	}
	else{
		print "<script>window.location='./?view=users';</script>";
	}

    print "<script>window.location='./?view=users';</script>";
}


?>