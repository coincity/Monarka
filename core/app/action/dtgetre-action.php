<?php
$res = ReData::getRes();
$json_data = array(
    "data" => $res
);
echo json_encode($json_data);
