<?php

if(count($_POST)>0){

	$employee = PersonData::getById($_POST["employee_id"]);
	$employee->no = $_POST["no"];
	$employee->name = strtoupper($_POST["name"]);
	$employee->lastname = strtoupper($_POST["lastname"]);
    $employee->gender = $_POST["gender"];
	$employee->birthdate = $_POST["birthdate"];
	$employee->marital_status = $_POST["marital_status"];
	$employee->address = $_POST["address"];
	$employee->phone = $_POST["phone"];
    $employee->cell = $_POST["cell"];
	$employee->email = $_POST["email"];
    $employee->user_id = $_SESSION['user_id'];

	$employee->update_employee();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$image->file_new_name_body = $user->no."_".date("Y-m-d H:i:s");
			$image->Process("storage/employees/");
			if($image->processed){
				$employee->image = $image->file_dst_name;
				$employee->update_image();
			}
		}else {
			if(($employee->image == "man.jpg") || ($employee->image == "woman.jpg")){
				if($_POST["gender"] == "M") $employee->image = 'man.jpg';
				else $employee->image = 'woman.jpg';
				$employee->update_image();
			}
		}
	}

}
print "<script>window.location='index.php?view=employees';</script>";

?>