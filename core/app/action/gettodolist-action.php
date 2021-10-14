<?php

include "../model/ToDoListData.php";
include "../../controller/Executor.php";
include "../../controller/Database.php";
include "../../controller/Core.php";
include "../../controller/Model.php";

$user=$_POST['user'];

$todo = ToDoListData::getUnCompletedByUserId($user);
echo json_encode($todo);


?>