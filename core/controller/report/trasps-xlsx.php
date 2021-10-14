<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/SellData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/StockData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/DData.php";
include "../core/app/model/PData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
//$products = null;
/*if(isset($_SESSION["user_id"])){
if(Core::$user->kind==3){
$products = SellData::getAllBySQL(" where user_id=Core::$user->id and operation_type_id=6 and is_draft=0 order by created_at desc");

}
else if(Core::$user->kind==2){
$products = SellData::getAllBySQL(" where operation_type_id=6 and is_draft=0 and stock_to_id=Core::$user->stock_id order by created_at desc");
}
else{*/
$products = SellData::getAllBySQL(" where operation_type_id=6");
/*
}
}else if(isset($_SESSION["client_id"])){
$products = SellData::getAllBySQL(" where person_id=$_SESSION[client_id] and operation_type_id=6 and is_draft=0 order by created_at desc");	
}*/


// Set document properties
$objPHPExcel->getProperties()->setCreator("SySpa 3.0")
							 ->setLastModifiedBy("SySpa 3.0")
							 ->setTitle("SySpa 3.0")
							 ->setSubject("SySpa 3.0")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Traspasos - SySpa')
->setCellValue('A2', 'Factura')
->setCellValue('B2', 'Vendedor')
->setCellValue('C2', 'Origen')
->setCellValue('D2', 'Destino')
->setCellValue('E2', 'Fecha');

$start = 3;
foreach($products as $sell){
if($sell->user_id!=null){
	$c= $sell->getUser();
}
$sheet->setCellValue('A'.$start, $sell->id)
->setCellValue('B'.$start, $c->name." ".$c->lastname)
->setCellValue('C'.$start, $sell->getStockFrom()->name)
->setCellValue('D'.$start, $sell->getStockTo()->name)
->setCellValue('E'.$start, $sell->created_at);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="traps-'.time().'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
