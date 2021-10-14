<?php
$employees = PersonData::getPersonByAll("Empleado");
$json_data = array(
    "data" => $employees
);
echo json_encode($json_data);
