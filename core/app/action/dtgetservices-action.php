<?php
$services = ServiceData::getAll();
$json_data = array(
    "data" => $services
);
echo json_encode($json_data);
