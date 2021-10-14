<?php

header('Content-type: application/json');

$cot = CotizationData::getById($_GET["id"]);
$operations = OperationData::getAllProductsByRefId($cot->ref_id);

StatusData::cambiarStatus("cotizations", $_GET["id"], 5);
foreach ($operations as $op) {
    StatusData::cambiarStatus("operation", $op->id, 5);
}

$response_array['status'] = 'success';
$response_array['message'] = 'Cotizacion Cancelada';
echo json_encode($response_array);

?>