<?php

if(count($_POST)>0){

	$user = new UserData();
	$user->kind = $_POST["kind"];
	$user->name = strtoupper($_POST["name"]);
	$user->lastname = strtoupper($_POST["lastname"]);
	$user->username = $_POST["username"];
	$user->email = $_POST["email"];
	$user->password = sha1(md5($_POST["password"]));
    $user->user_id = $_SESSION['user_id'];

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$image->file_new_name_body = $user->name."_".date("Y-m-d H:i:s");
			$image->Process("storage/profiles/");
			if($image->processed){
				$user->image = $image->file_dst_name;
				$prod = $user->add_with_image();
			}
		}else{
            $user->image = 'user.png';
            $prod = $user->add_with_image();
        }
	}
	else{
		$prod = $user->add();
	}

}


print "<script>window.location='./?view=users';</script>";


?>