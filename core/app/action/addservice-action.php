<?php

if(count($_POST)>0){
  $service = new ServiceData();

  $category_id="";

  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}

  $count = ServiceData::CountByCategory($category_id);
  $category = CategoryData::getById($category_id);

  $service->category_id=$category_id;
  $service->barcode = $category->prefix."".(sprintf("%'.05d\n", $count->s + 1));
  $service->name = strtoupper($_POST["name"]);
  $service->description = strtoupper($_POST["description"]);
  $service->price = $_POST["price"];
  if(isset($_POST["itbis"])) $service->itbis = 1;
  else $service->itbis = 0;
  $service->user_id = $_SESSION['user_id'];

  if(isset($_FILES["image"])){
    $image = new Upload($_FILES["image"]);
    if($image->uploaded){
	  $filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
	  $image->file_new_name_body = $service->barcode."_".date("Y-m-d H:i:s");
      $image->Process("storage/services/");
      if($image->processed){
        $service->image = $image->file_dst_name;
        $prod = $service->add_with_image();
      }
    }else{
		$service->image = 'nd.jpg';
		$prod = $service->add_with_image();
    }
  }
  else{
  $prod= $service->add();
  }

}

print "<script>window.location='index.php?view=services';</script>";

?>