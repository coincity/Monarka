<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/StatusData.php";
include "../core/app/model/UserData.php";
/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$users = PersonData::getAudit("Paciente");

// Set document properties
$objPHPExcel->getProperties()->setCreator("SySpa 3.0")
							 ->setLastModifiedBy("SySpa 3.0")
							 ->setTitle("Audit Pacients - SySpa 3.0")
							 ->setSubject("SySpa Audit Pacients Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Auditoria de Pacientes - SySpa')
->setCellValue('A2', 'Registro')
->setCellValue('B2', 'Movimiento')
->setCellValue('C2', 'Fecha')
->setCellValue('D2', 'Usuario')
->setCellValue('E2', 'Info. Usuario')
->setCellValue('F2', 'ID')
->setCellValue('G2', 'Imagen')
->setCellValue('H2', 'Cédula/RNC')
->setCellValue('I2', 'Nombre Completo')
->setCellValue('J2', 'Sexo')
->setCellValue('K2', 'Fecha Nacimiento')
->setCellValue('L2', 'Edad')
->setCellValue('M2', 'Estado Civil')
->setCellValue('N2', 'Dirección')
->setCellValue('O2', 'Email')
->setCellValue('P2', 'Teléfono')
->setCellValue('Q2', 'Celular')
->setCellValue('R2', 'Peso')
->setCellValue('S2', 'Estatura')
->setCellValue('T2', 'Color Piel')
->setCellValue('U2', 'Estado');


$start = 3;
foreach($users as $pacient){
$sdata  = StatusData::getById($pacient->status);
$user = UserData::getById($pacient->user_id);
$sheet->setCellValue('A'.$start, $pacient->register_id)
->setCellValue('B'.$start, $pacient->movimiento)
->setCellValue('C'.$start, $pacient->history_date)
->setCellValue('D'.$start, $user->name." ".$user->lastname)
->setCellValue('E'.$start, $pacient->client_info)
->setCellValue('F'.$start, $pacient->id)
->setCellValue('G'.$start, $pacient->image)
->setCellValue('H'.$start, $pacient->no)
->setCellValue('I'.$start, $pacient->name." ".$user->lastname)
->setCellValue('J'.$start, $pacient->sexo)
->setCellValue('K'.$start, $pacient->fecha_nacimiento)
->setCellValue('L'.$start, $pacient->age)
->setCellValue('M'.$start, $pacient->estado_civil)
->setCellValue('N'.$start, $pacient->address1)
->setCellValue('O'.$start, $pacient->email1)
->setCellValue('P'.$start, $pacient->phone1)
->setCellValue('Q'.$start, $pacient->phone2)
->setCellValue('R'.$start, $pacient->peso_lb)
->setCellValue('S'.$start, $pacient->estatura)
->setCellValue('T'.$start, $pacient->color)
->setCellValue('U'.$start, $sdata->description);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="auditpacients-'.time().'.xlsx"');
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
