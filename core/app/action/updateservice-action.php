<?php

if(count($_POST)>0){
  $service = ServiceData::getById($_POST["service_id"]);

  $category_id="";
  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}

  $number = preg_replace("/[^0-9]/","",$service->barcode);
  $category = CategoryData::getById($category_id);

  $service->barcode = $category->prefix."".(sprintf("%'.05d\n", $number));
  $service->category_id=$_POST["category_id"];
  $service->name = strtoupper($_POST["name"]);
  $service->description = strtoupper($_POST["description"]);
  $service->price = $_POST["price"];
  if(isset($_POST["itbis"])) $service->itbis = 1;
  else $service->itbis = 0;
  $service->user_id = $_SESSION['user_id'];
  $service->update();

	if(isset($_FILES["image"])){
		$image = new Upload($_FILES["image"]);
		if($image->uploaded){
			$filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
			$image->file_new_name_body = $service->barcode."_".date("Y-m-d H:i:s");
			$image->Process("storage/services/");
			if($image->processed){
				$service->image = $image->file_dst_name;
				$service->update_image();
			}
		}
	}
}

print "<script>window.location='index.php?view=services';</script>";
?>