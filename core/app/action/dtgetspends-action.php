<?php
$spends = SpendData::getAll();
$json_data = array(
    "data" => $spends
);
echo json_encode($json_data);
