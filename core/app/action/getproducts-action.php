<?php
$products = ProductData::getAllProductService();
$json_data = array(
    "data" => $products
);
echo json_encode($json_data);
