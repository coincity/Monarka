<?php
$cotizations = CotizationData::getActive();
$json_data = array(
    "data" => $cotizations
);
echo json_encode($json_data);
