<?php
$res = ReData::getPaymentRes();
$json_data = array(
    "data" => $res
);
echo json_encode($json_data);
