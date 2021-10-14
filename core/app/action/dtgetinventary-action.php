<?php
$ninventary = null;
$x = 0;
$inventary = ProductData::getAll();

foreach($inventary as $i){
    $i->q = OperationData::getQByStock($i->id);
    $ninventary[$x] = $i;
    $x++;
}

$json_data = array(
    "data" => $ninventary
);
echo json_encode($json_data);
