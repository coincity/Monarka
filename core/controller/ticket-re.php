<?php

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";

include "fpdf/fpdf.php";
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }
$title = ConfigurationData::getByPreffix("ticket_title")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;

$sell = SellData::getById($_GET["id"]);
$stock = StockData::getById($sell->stock_to_id);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();


$pdf = new FPDF($orientation='P',$unit='mm', array(52,250));
$pdf->AddPage();
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(5,5,$sell->created_at."                              No. Factura: #".$sell->id);
$pdf->SetFont('Arial','B',7);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(3);
$pdf->setX(11);
$pdf->Cell(5,11,strtoupper($title));
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX(15);
$pdf->Cell(5,17,strtoupper($address));
$pdf->setX(17);
$pdf->Cell(5,23,"Tel.: ".strtoupper($phone));
$pdf->setY(4);
$pdf->setX(2);
$pdf->Cell(5,29,'Cajero: '.$user->name." ".$user->lastname);
$pdf->setX(2);
$pdf->Cell(5,34,"Sucursal: ".$stock->name);
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,39,'----------------------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,45,'Artículo                       Cant.    Precio            Total');


$total =0;
$off = 51;
foreach($operations as $op){
$product = $op->getProduct();
$pdf->setX(2);
$pdf->Cell(5,$off,ucfirst(strtolower(substr($product->name, 0,22))));
$pdf->setX(20);
$pdf->Cell(35,$off,"$op->q");
$pdf->setX(27);
$pdf->Cell(11,$off,  $currency." ".number_format($product->total,2,".",",") ,0,0,"R");
$pdf->setX(41);
$pdf->Cell(11,$off,  $currency." ".number_format($op->q*$product->total,2,".",",") ,0,0,"R");


//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->total;
$off+=6;
}

$pdf->setX(2);
$pdf->Cell(5,$off+12,"TOTAL: " );
$pdf->setX(41);
$pdf->Cell(11,$off+12,$currency." ".number_format($total - ($total*$sell->discount/100),2,".",","),0,0,"R");
$pdf->setX(6);
$pdf->Cell(5,$off+32,'               GRACIAS POR TU COMPRA ');
$pdf->Cell(5,$off+32,'               CUIDAMOS TU PIEL... ');
$pdf->output();
