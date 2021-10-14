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

$user = UserData::getById($_GET["user_id"]);
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

if($user->kind==3){
 $sells = SellData::getAllBySQL(" where user_id=".$user->id." and operation_type_id=2 and d_id in (1,2,3,4) and is_draft=0 order by created_at desc");
}
else if($user->kind==2){
 $sells = SellData::getAllBySQL(" where operation_type_id=2 and user_id=".$user->id." and d_id in (1,2,3,4) and is_draft=0 order by created_at desc");
}
else{
 $sells = SellData::getSells();
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
->setCellValue('F2', 'Saldo Pendiente')
->setCellValue('G2', 'Vendedor');

$start = 3;
foreach($sells as $sell){

if($sell->person_id!=""){
$c= $sell->getPerson();
$credit=-1*(PaymentData::sumByClientBySellId($sell->person_id,$sell->id)->total);
}
$d= $sell->getUser();
$operations = OperationData::getAllProductsBySellId($sell->id);
$val = 0;
if($sell->p_id == 1){
    $val = SummaryData::sumByClientId($sell->id)->total;	
}
else if($sell->p_id == 2){
	$val = PaymentData::sumPaymentByClientId($sell->id)->total;
}
else if($sell->p_id == 4){
	$val = PaymentData::sumPaymentByClientId($sell->id)->total;
}


if($credit!="") {
	$sheet->setCellValue('A'.$start, $sell->id)
	->setCellValue('B'.$start, $sell->created_at)
	->setCellValue('C'.$start, $c->name." ".$c->lastname)
	->setCellValue('D'.$start, $sell->total)
	->setCellValue('E'.$start, $val)
	->setCellValue('F'.$start, $credit)
	->setCellValue('G'.$start, $d->name." ".$d->lastname);
	$start++;
	}
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="sells-'.time().'.xlsx"');
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
