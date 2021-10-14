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
$comments = PersonData::getCommentsAudit();

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

$sheet->setCellValue('A1', 'Auditoria de Observaciones Paciente - SySpa')
->setCellValue('A2', 'Registro')
->setCellValue('B2', 'Movimiento')
->setCellValue('C2', 'Fecha')
->setCellValue('D2', 'Usuario')
->setCellValue('E2', 'Info. Usuario')
->setCellValue('F2', 'Comentarios')
->setCellValue('G2', 'Foto Antes')
->setCellValue('H2', 'Foto Despues')
->setCellValue('I2', 'Escaneo 1')
->setCellValue('J2', 'Escaneo 2')
->setCellValue('K2', 'Escaneo 3')
->setCellValue('L2', 'Fecha');

$start = 3;
foreach($comments as $comment){
$user = UserData::getById($comment->user_id);
$sheet->setCellValue('A'.$start, $comment->id)
->setCellValue('B'.$start, $comment->movimiento)
->setCellValue('C'.$start, $comment->history_date)
->setCellValue('D'.$start, $user->name." ".$user->lastname)
->setCellValue('E'.$start, $comment->client_info)
->setCellValue('F'.$start, $comment->comentarios)
->setCellValue('G'.$start, $comment->ibefore)
->setCellValue('H'.$start, $comment->iafter)
->setCellValue('I'.$start, $comment->image1)
->setCellValue('J'.$start, $comment->image2)
->setCellValue('K'.$start, $comment->image3)
->setCellValue('L'.$start, $comment->created_at);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="auditobs-'.time().'.xlsx"');
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
