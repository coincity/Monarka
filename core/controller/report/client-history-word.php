<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new  PhpOffice\PhpWord\PhpWord();
$clients = PersonData::getClients();
$user = PersonData::getById($_GET["id"]);

$section1 = $word->AddSection();
$section1->addText("PACIENTE ",array("size"=>22,"bold"=>true,"align"=>"right"));


$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell()->addText("Nombre");
$table1->addCell()->addText("Direccion");
$table1->addCell()->addText("Email");
$table1->addCell()->addText("Telefono");
$table1->addRow();
$table1->addCell(5000)->addText($user->name." ".$user->lastname);
$table1->addCell(2500)->addText($user->address1);
$table1->addCell(2000)->addText($user->email1);
$table1->addCell(2000)->addText($user->phone1);

$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell()->addText("Nombre");
$table2->addCell()->addText("Direccion");
$table2->addCell()->addText("Email");
$table2->addCell()->addText("Telefono");
$table2->addRow();
$table2->addCell(5000)->addText($user->name." ".$user->lastname);
$table2->addCell(2500)->addText($user->address1);
$table2->addCell(2000)->addText($user->email1);
$table2->addCell(2000)->addText($user->phone1);

$word->addTableStyle('table1', $styleTable,$styleFirstRow);
$word->addTableStyle('table2', $styleTable,$styleFirstRow);

/// datos bancarios

$filename = "clients-".time().".docx";
#$word->setReadDataOnly(true);
$word->save($filename,"Word2007");
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file



?>