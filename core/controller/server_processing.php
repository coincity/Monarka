<?php
include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";
include "core/app/model/PersonData.php";

$users = PersonData::getPersonByAll("Paciente");
$json_data = array(
    "data" => $users
);
echo json_encode($json_data);
