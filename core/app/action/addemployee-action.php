<?php

if(count($_POST)>0){

	$employee = new PersonData();
	$employee->no = $_POST["no"];
	$employee->name = strtoupper($_POST["name"]);
	$employee->lastname = strtoupper($_POST["lastname"]);
    $employee->gender = $_POST["gender"];
	$employee->birthdate = $_POST["birthdate"];
    $employee->hiredate = $_POST["hiredate"];
	$employee->marital_status = $_POST["marital_status"];
	$employee->address = $_POST["address"];
	$employee->phone = $_POST["phone"];
    $employee->cell = $_POST["cell"];
	$employee->email = $_POST["email"];
    $employee->user_id = $_SESSION['user_id'];

    if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$image->file_new_name_body = $user->no."_".date("Y-m-d H:i:s");
			$image->Process("storage/employees/");
			if($image->processed){
				$employee->image = $image->file_dst_name;
				$prod = $employee->add_employee();
			}
		}else{
			if($_POST["gender"] == "M") $employee->image = 'man.jpg';
			else $employee->image = 'woman.jpg';
			$prod = $employee->add_employee();
		}
	}
}


print "<script>window.location='index.php?view=employees';</script>";


?>