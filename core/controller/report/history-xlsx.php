<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/ReservationData.php";
include "../core/app/model/StatusData.php";
include "../core/app/model/HistoryData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$users = ReservationData::getAllCompleted();
$statuses = new StatusData();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SySpa 3.0")
							 ->setLastModifiedBy("SySpa 3.0")
							 ->setTitle("Reservations - SySpa 3.0")
							 ->setSubject("SySpa Reservations Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Historial de Consultas - SySpa')
->setCellValue('A2', 'Fecha')
->setCellValue('B2', 'Paciente')
->setCellValue('C2', 'Servicio')
->setCellValue('D2', 'Medico')
->setCellValue('E2', 'Procedimiento')
->setCellValue('F2', 'Medicamentos')
->setCellValue('G2', 'Observaciones')
->setCellValue('H2', 'Tratamiento en Casa');

$start = 3;
foreach($users as $user){
$history = HistoryData::getByRI($user->id);
$sheet->setCellValue('A'.$start, $user->date_at." ".$user->time_at)
->setCellValue('B'.$start, $user->pname." ".$user->plastname)
->setCellValue('C'.$start, $user->service_name)
->setCellValue('D'.$start, $user->mname." ".$user->mlastname)
->setCellValue('E'.$start, $history->proc)
->setCellValue('F'.$start, $history->medicament)
->setCellValue('G'.$start, $history->observations)
->setCellValue('H'.$start, $history->treatment);
$start++;
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="history-'.time().'.xlsx"');
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
