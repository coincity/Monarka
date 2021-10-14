<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/ReservationData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
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
$sells = SellData::getSells();

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

$sheet->setCellValue('A1', 'Comisiones - SySpa')
->setCellValue('A2', 'Factura')
->setCellValue('B2', 'Fecha')
->setCellValue('C2', 'Paciente')
->setCellValue('D2', 'Tipo')
->setCellValue('E2', 'Cantidad')
->setCellValue('F2', 'Descripción')
->setCellValue('G2', '% Comisión')
->setCellValue('H2', 'Total Comisión')
->setCellValue('I2', 'Vendedor/Especialista');

$start = 3;
foreach($sells as $sell){
$operations = OperationData::getAllProductsBySellId($sell->id);

	foreach($operations as $op){
		$product  = $op->getProduct();
		$q = 0;
		IF($op->q == 0) $q = 1;
		else $q = $op->q;
		$c= PersonData::getById($sell->person_id);
		
		if($product->tipo == "Producto"){
			if($sell->user_id!=null){
				$d= $sell->getUser();
			}															 
		}else if($product->tipo == "Servicio") {
			$medic_id = ReservationData::getAllServiceSelledBySellId($sell->id,$product->id)->medic_id;
			$d= PersonData::getById($medic_id);
		}
		
		$sheet->setCellValue('A'.$start, $sell->id)
		->setCellValue('B'.$start, $sell->created_at)
		->setCellValue('C'.$start,$c->name." ".$c->lastname)
		->setCellValue('D'.$start, $product->tipo)
		->setCellValue('E'.$start, $q)
		->setCellValue('F'.$start, $product->name)
		->setCellValue('G'.$start, $product->commision."%")
		->setCellValue('H'.$start, ceil((($product->price_out * $product->commision)/100)) * $q)
		->setCellValue('I'.$start, $d->name." ".$d->lastname);
		$start++;
	}
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="commissions-'.time().'.xlsx"');
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
