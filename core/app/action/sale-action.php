<?php 
$request = Request::capture();
$user = UserData::getById($_SESSION['user_id']);

$request->ok($request);



?>