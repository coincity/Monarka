<?php 
$request = Request::capture();
$user = UserData::getById($_SESSION['user_id']);

if(isset($request->parameters["id"])) {
    $request->ok(ProductData::getById($request->parameters["id"]));
} else {

    $request->ok(ProductData::getAll());
}
?>