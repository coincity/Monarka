<?php

if(count($_POST)>0){
   $product = ProductData::getById($_POST["id"]);


  $category_id="";
  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}

  $number = preg_replace("/[^0-9]/","",$product->barcode);
  $category = CategoryData::getById($category_id);

  $product->barcode =  $_POST["barcode"];
  $product->category_id=$_POST["category_id"];
  $product->name = strtoupper($_POST["name"]);
  $product->description = strtoupper($_POST["description"]);
  $product->price_in = $_POST["price_in"];
  $product->min_price = $_POST["min_price"];
  $product->max_price = $_POST["max_price"];
  if(isset($_POST["itbis"])) $product->itbis = 1;
  else $product->itbis = 0;
  if($_POST["warranty_at"] != "") $product->warranty_at = DateTime::createFromFormat('d/m/Y', $_POST["warranty_at"])->format('Y-m-d');
  else $product->warranty_at = $_POST["warranty_at"];
  $product->observations = $_POST["observations"];
  $product->user_id = $_SESSION['user_id'];
  $product->warranty_file = "";

  if(isset($_FILES["warranty"])){
      if($_FILES["warranty"] != ""){
          $warranty = new Upload($_FILES["warranty"]);
          if($warranty->uploaded){
              $filename = pathinfo($_FILES['warranty']['name'], PATHINFO_FILENAME);
              $warranty->file_new_name_body = $filename."_".date("Y-m-d H:i:s");
              $warranty->Process("storage/pdffile/");
              if($warranty->processed){
                  $product->warranty_file = $warranty->file_dst_name;
              }
          }
      }
	}

	$product->update();

	if(isset($_FILES["image"])){
        if($_FILES["image"] != ""){
            $image = new Upload($_FILES["image"]);
            if($image->uploaded){
                $filename = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
                $image->file_new_name_body = $product->barcode."_".date("Y-m-d H:i:s");
                $image->Process("storage/products/");
                if($image->processed){
                    $product->image = $image->file_dst_name;
                    $product->update_image();
                }
            }
        }
	}
}

print "<script>window.location='index.php?view=products';</script>";
?>