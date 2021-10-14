<?php

$config = [];
include "config.database.php";
include "config.application.php";

$debug = $config["Development"]["Debug"];
if($debug){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

?>