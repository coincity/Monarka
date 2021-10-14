<?php
$ncf = NcfData::getAll();
$json_data = array(
    "data" => $ncf
);
echo json_encode($json_data);
