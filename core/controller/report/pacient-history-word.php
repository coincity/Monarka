<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/HistoryData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();

$pacient = PersonData::getById($_GET["id"]);
$histories = HistoryData::getAllByReservationId($_GET["rid"]);

$section1 = $word->AddSection();
$section1->addText("Indicaciones",array("size"=>22,"bold"=>true,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$total=0;

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell(3000)->addText("Nombre");
$table1->addCell(9000)->addText($pacient->name." ".$pacient->lastname);
$table1->addRow();
$table1->addCell()->addText("Direccion");
$table1->addCell()->addText($pacient->address1);
$table1->addRow();
$table1->addCell()->addText("Email");
$table1->addCell()->addText($pacient->email1);
$table1->addRow();
$table1->addCell()->addText("Telefono");
$table1->addCell()->addText($pacient->phone1);
$section1->addText("");

$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell(3000)->addText("Fecha");
$table2->addCell(3000)->addText("Procedimiento");
$table2->addCell(10000)->addText("Medicamentos");
$table2->addCell(6000)->addText("Observaciones");
$table2->addCell(6000)->addText("Tratamiento");

foreach($histories as $h){
	$date=date_create($h->created_at);
	$table2->addRow();
	$table2->addCell()->addText(date_format($date,"d/m/Y"));
	$table2->addCell()->addText($h->proc);
	$table2->addCell()->addText($h->medicament);
	$table2->addCell()->addText($h->observations);
	$table2->addCell()->addText($h->treatment);
}

/////////////////////////////////////////
$word->addTableStyle('table1', $styleTable);
$word->addTableStyle('table2', $styleTable,$styleFirstRow);


/// datos bancarios

$filename = "history-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>