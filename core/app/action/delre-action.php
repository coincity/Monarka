<?php


$re = ReData::getById($_GET["id"]);
$operations = OperationData::getAllProductsByRefId($re->ref_id);

StatusData::cambiarStatus("re", $_GET["id"], 5);
foreach ($operations as $op) {
    StatusData::cambiarStatus("operation", $op->id, 5);
}

$response_array['status'] = 'success';
$response_array['message'] = 'Compra Cancelada';
print "<script>window.location='./?view=paymentres';</script>";

?>