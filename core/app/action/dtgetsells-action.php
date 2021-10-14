<?php
$sells = SellData::getAll();
$json_data = array(
    "data" => $sells
);
echo json_encode($json_data);
