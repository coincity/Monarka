<?php
header('Content-type: application/json');

if(($_POST["type"]!="") && ($_POST["prefix"]!="") && ($_POST["description"]!="")){

	$rx = CategoryData::getRepeated(strtoupper($_POST["prefix"]));

	if($rx==null){
	  $category = new CategoryData();
	  $category->type = strtoupper($_POST["type"]);
	  $category->prefix = strtoupper($_POST["prefix"]);
      $category->description = strtoupper($_POST["description"]);
      $category->user_id = $_SESSION['user_id'];
      $category->add();

	  $response_array['status'] = 'success';
	  $response_array['message'] = 'Categoria Agregada Exitosamente';
	  echo json_encode($response_array);
	}
	else {
		$response_array['status'] = 'fail';
		$response_array['message'] = 'Este Prefijo ya existe, especifique uno nuevo.';
		echo json_encode($response_array);
	}
}
else {
    $response_array['status'] = 'novalid';
    $response_array['message'] = '';
    echo json_encode($response_array);
}

//print "<script>window.location='index.php?view=categories';</script>";

?>