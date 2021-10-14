<?php
$users = UserData::getAll();
$json_data = array(
    "data" => $users
);
echo json_encode($json_data);
