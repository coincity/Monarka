<?php
$workshop = WorkshopData::getAll();
$json_data = array(
    "data" => $workshop
);
echo json_encode($json_data);
