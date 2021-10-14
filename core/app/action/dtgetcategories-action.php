<?php
$categories = CategoryData::getAll();
$json_data = array(
    "data" => $categories
);
echo json_encode($json_data);
