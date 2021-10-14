<?php
$providers = PersonData::getProviders();
$json_data = array(
    "data" => $providers
);
echo json_encode($json_data);
