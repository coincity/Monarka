<?php

header('Content-type: application/json');

$table = $_GET["table"];
$persona_id = $_GET["id"];
$status_id = $_GET["status_id"];

StatusData::cambiarStatus($table, $persona_id, $status_id);

$response_array['status'] = 'success';
$response_array['message'] = 'Estado Actualizado';
echo json_encode($response_array);
?>