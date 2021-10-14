<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/DData.php";
include "../core/app/model/PData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$sells = PaymentData::getAudit();

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

$sheet->setCellValue('A1', 'Auditoria de Recibos de Ingreso - SySpa')
->setCellValue('A2', 'Registro')
->setCellValue('B2', 'Movimiento')
->setCellValue('C2', 'Fecha')
->setCellValue('D2', 'Usuario')
->setCellValue('E2', 'Info. Usuario')
->setCellValue('F2', 'Recibo')
->setCellValue('G2', 'Factura')
->setCellValue('H2', 'Cliente')
->setCellValue('I2', 'Valor')
->setCellValue('J2', 'Fecha Recibo');

$start = 3;
foreach($sells as $sell){
$user = UserData::getById($sell->user_id);
$c= $sell->getPerson(); 
$sheet->setCellValue('A'.$start, $sell->register_id)
->setCellValue('B'.$start, $sell->movimiento)
->setCellValue('C'.$start, $sell->history_date)
->setCellValue('D'.$start, $user->name." ".$user->lastname)
->setCellValue('E'.$start, $sell->client_info)
->setCellValue('F'.$start, "#".$sell->id)
->setCellValue('G'.$start, "#".$sell->sell_id)
->setCellValue('H'.$start, $c->name." ".$c->lastname)
->setCellValue('I'.$start, $sell->val)
->setCellValue('J'.$start, $sell->created_at);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="auditincome-'.time().'.xlsx"');
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
