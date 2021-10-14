<?php
$clients = PersonData::getPersonByAll("Cliente");
$json_data = array(
    "data" => $clients
);
echo json_encode($json_data);
