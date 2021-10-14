<?php
$ninventary = null;
$x = 0;
$inventary = OperationData::getAllOfficial();

foreach($inventary as $i){
    $i->opname = $i->getOperationType()->name;
    $i->created_at = date("d/m/Y h:i:sa",strtotime($i->created_at));
    $x++;
}

$json_data = array(
    "data" => $inventary
);
echo json_encode($json_data);
