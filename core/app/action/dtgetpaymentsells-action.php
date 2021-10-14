<?php
$sells = SellData::getPaymentAll();
$json_data = array(
    "data" => $sells
);
echo json_encode($json_data);
