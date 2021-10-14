<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/ReservationData.php";
include "../core/app/model/StatusData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$users = ReservationData::getOld();
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

$sheet->setCellValue('A1', 'Reporte Historial de Citas - SySpa')
->setCellValue('A2', 'Fecha')
->setCellValue('B2', 'Servicio')
->setCellValue('C2', 'Medico')
->setCellValue('D2', 'Paciente')
->setCellValue('E2', 'Estado');

$start = 3;
foreach($users as $user){
$sdata  = StatusData::getById($user->status);
$sheet->setCellValue('A'.$start, $user->date_at." ".$user->time_at)
->setCellValue('B'.$start, $user->service_name)
->setCellValue('C'.$start, $user->mname." ".$user->mlastname)
->setCellValue('D'.$start, $user->pname." ".$user->plastname)
->setCellValue('E'.$start, $sdata->description);
$start++;
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="oldreservations-'.time().'.xlsx"');
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
