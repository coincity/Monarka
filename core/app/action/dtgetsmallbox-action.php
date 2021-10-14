<?php
$smallbox = SavingData::getAll();
$json_data = array(
    "data" => $smallbox
);
echo json_encode($json_data);
