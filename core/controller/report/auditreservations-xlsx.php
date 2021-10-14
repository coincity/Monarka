<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/ReservationData.php";
include "../core/app/model/StatusData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/CabinsData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$users = ReservationData::getAudit();
$statuses = new StatusData();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SySpa 3.0")
							 ->setLastModifiedBy("SySpa 3.0")
							 ->setTitle("Audit Reservations - SySpa 3.0")
							 ->setSubject("SySpa Audit Reservations Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Auditoria de Citas - SySpa')
->setCellValue('A2', 'Registro')
->setCellValue('B2', 'Movimiento')
->setCellValue('C2', 'Fecha')
->setCellValue('D2', 'Usuario')
->setCellValue('E2', 'Info. Usuario')
->setCellValue('F2', 'ID')
->setCellValue('G2', 'Fecha')
->setCellValue('H2', 'Servicio')
->setCellValue('I2', 'Cabina')
->setCellValue('J2', 'Medico')
->setCellValue('K2', 'Paciente')
->setCellValue('L2', 'Estado');

$start = 3;
foreach($users as $reservation){
$sdata  = StatusData::getById($reservation->status);
$user = UserData::getById($reservation->user_id);
$cabin = CabinsData::getById($reservation->cabin_id);
$sheet->setCellValue('A'.$start, $reservation->register_id)
->setCellValue('B'.$start, $reservation->movimiento)
->setCellValue('C'.$start, $reservation->history_date)
->setCellValue('D'.$start, $user->name." ".$user->lastname)
->setCellValue('E'.$start, $reservation->client_info)
->setCellValue('F'.$start, $reservation->id)
->setCellValue('G'.$start, $reservation->date_at." ".$reservation->time_at)
->setCellValue('H'.$start, $reservation->service_name)
->setCellValue('I'.$start, $cabin->description)
->setCellValue('J'.$start, $reservation->mname." ".$reservation->mlastname)
->setCellValue('K'.$start, $reservation->pname." ".$reservation->plastname)
->setCellValue('L'.$start, $sdata->description);
$start++;
}


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="auditreservations-'.time().'.xlsx"');
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
