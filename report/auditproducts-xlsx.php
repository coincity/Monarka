<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
include "../core/autoload.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/CategoryData.php";
include "../core/app/model/UserData.php";

/** Include PHPExcel */
//require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
require_once '../core/controller/PHPExcel/Classes/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
$products = ProductData::getAudit();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SySpa 3.0")
							 ->setLastModifiedBy("SySpa 3.0")
							 ->setTitle("Audit Products - SySpa 3.0")
							 ->setSubject("SySpa Audit Products Report")
							 ->setDescription("")
							 ->setKeywords("")
							 ->setCategory("");


// Add some data
$sheet = $objPHPExcel->setActiveSheetIndex(0);

$sheet->setCellValue('A1', 'Auditoría de Productos - SySpa')
->setCellValue('A2', 'Registro')
->setCellValue('B2', 'Movimiento')
->setCellValue('C2', 'Fecha')
->setCellValue('D2', 'Usuario')
->setCellValue('E2', 'Info. Usuario')
->setCellValue('F2', 'ID')
->setCellValue('G2', 'Imagen')
->setCellValue('H2', 'Codigo')
->setCellValue('I2', 'Nombre')
->setCellValue('J2', 'Descripcion')
->setCellValue('K2', 'Minimo en Inventario')
->setCellValue('L2', 'Precio Entrada')
->setCellValue('M2', 'Precio Salida')
->setCellValue('N2', 'Comision')
->setCellValue('O2', 'Precio Venta')
->setCellValue('P2', 'Presentacion')
->setCellValue('Q2', 'Categoria')
->setCellValue('R2', 'Fecha Caducidad')
->setCellValue('S2', 'Estado');

$start = 3;
foreach($products as $product){
$user = UserData::getById($product->user_id);
$sheet->setCellValue('A'.$start, $product->register_id)
->setCellValue('B'.$start, $product->movimiento)
->setCellValue('C'.$start, $product->history_date)
->setCellValue('D'.$start, $user->name." ".$user->lastname)
->setCellValue('E'.$start, $product->client_info)
->setCellValue('F'.$start, $product->id)
->setCellValue('G'.$start, $product->image)
->setCellValue('H'.$start, $product->barcode)
->setCellValue('I'.$start, $product->name)
->setCellValue('J'.$start, $product->description)
->setCellValue('K'.$start, $product->inventary_min)
->setCellValue('L'.$start, $product->price_in)
->setCellValue('M'.$start, $product->price_out)
->setCellValue('N'.$start, $product->commision)
->setCellValue('O'.$start, $product->total)
->setCellValue('P'.$start, $product->presentation)
->setCellValue('Q'.$start, $product->getCategory()->name)
->setCellValue('R'.$start, $product->expire_at)
->setCellValue('S'.$start, $product->status_dsc);
$start++;
}

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="auditproducts-'.time().'.xlsx"');
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
