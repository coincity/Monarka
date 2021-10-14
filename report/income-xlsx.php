<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/SellData.php";
include "../core/app/model/SummaryData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/DData.php";
include "../core/app/model/PData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';

$user = UserData::getById($_GET["user"]);
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

$sells = null;
$start = isset($_GET["sd"])?$_GET["sd"]:gmdate("Y-m-d", strtotime('-30 days', strtotime('now')));
$end = isset($_GET["ed"])?$_GET["ed"]:date("Y-m-d");

if($user->kind==3){
	if($start != 0 && $end!= 0){
		$sells = PaymentData::getAllByDateAndUser($start,$end,$user->id);
	}else {
		$sells = PaymentData::getAllAbonoByUser($user->id);
	}       
}
else if($user->kind==2){
	if($start != 0 && $end!= 0){
		$sells = PaymentData::getAllByDateAndUser($start,$end,$user->id);
	}else {
		$sells = PaymentData::getAllAbonoByUser($user->id);
	}       
}
else if($user->kind==1){
	if($start != 0 && $end!= 0){
		$sells = PaymentData::getAllByDate($start,$end);
	}else{
		$sells = PaymentData::getAllAbono();
	}          
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

$sheet->setCellValue('A1', 'Reporte Recibos de Ingresos - SySpa')
->setCellValue('A2', 'No. Recibo')
->setCellValue('B2', 'No. Factura')
->setCellValue('C2', 'Fecha')
->setCellValue('D2', 'Vendedor')
->setCellValue('E2', 'Cliente')
->setCellValue('F2', 'Valor');

$start = 3;
foreach($sells as $sell){
	
$c= $sell->getPerson();
$d= $sell->getUser();
$sheet->setCellValue('A'.$start, $sell->id)
->setCellValue('B'.$start, $sell->sell_id)
->setCellValue('C'.$start, $sell->created_at)
->setCellValue('D'.$start, $d->name." ".$d->lastname)
->setCellValue('E'.$start, $c->name." ".$c->lastname)
->setCellValue('F'.$start, $sell->val);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="income-'.time().'.xlsx"');
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
