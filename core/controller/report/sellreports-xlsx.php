<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/SellData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SummaryData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/DData.php";
include "../core/app/model/PData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$products = array();
if($_GET["user_id"]==""){
	$products = SellData::getAllByDateOp($_GET["sd"],$_GET["ed"],2);
}
else{
	$products = SellData::getAllByDateBCOp($_GET["user_id"],$_GET["sd"],$_GET["ed"],2);
} 

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

$sheet->setCellValue('A1', 'Reporte de Ventas - SySpa')
->setCellValue('A2', 'Factura')
->setCellValue('B2', 'Fecha')
->setCellValue('C2', 'Paciente')
->setCellValue('D2', 'Facturado')
->setCellValue('E2', 'Pagado')
->setCellValue('F2', 'Pendiente')
->setCellValue('G2', 'Vendedor');

$start = 3;
foreach($products as $product){
$val = 0;
if($product->p_id == 1){
   $val = SummaryData::sumByClientId($product->id)->total;	
}
else if($product->p_id == 2){
	$val = PaymentData::sumPaymentByClientId($product->id)->total;
}
else if($product->p_id == 4){
	$val = PaymentData::sumPaymentByClientId($product->id)->total;
}

if($product->person_id!="") $credit=-1*(PaymentData::sumByClientBySellId($product->person_id,$product->id)->total);
if($credit=="") $credit = 0;

$c= $product->getPerson();
$d= $product->getUser();
$sheet->setCellValue('A'.$start, $product->id)
->setCellValue('B'.$start, $product->created_at)
->setCellValue('C'.$start, $c->name." ".$c->lastname)
->setCellValue('D'.$start, $product->total-$product->discount)
->setCellValue('E'.$start, $val)
->setCellValue('F'.$start, $credit)
->setCellValue('G'.$start, $d->name." ".$d->lastname);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sellsreport-'.time().'.xlsx"');
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
