<?php
date_default_timezone_set('America/La_Paz');
include "../core/config/config.php";

include "../core/controller/Core.php";
include "../core/controller/Database.php";
include "../core/controller/Executor.php";
include "../core/controller/Model.php";
include "../core/app/model/UserData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/StockData.php";
include "../core/app/model/ConfigurationData.php";
include "../fpdf/fpdf.php";
header("Content-Type: text/html; charset=utf-8");
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }

$imp_name = ConfigurationData::getByPreffix("imp-name")->val;
$imp_val = ConfigurationData::getByPreffix("imp-val")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;
$title = ConfigurationData::getByPreffix("company_name")->val;
$rnc = ConfigurationData::getByPreffix("company_rnc")->val;

$stock = StockData::getPrincipal();
$payment = PaymentData::getById($_GET["id"]);
$sell = SellData::getById($payment->sell_id);
$sell_detail = SellData::getSellsDetails($payment->sell_id,$_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();
$client = $sell->getPerson();
$credit= -1*(PaymentData::sumByClientBySellId($client->id,$sell->id)->total);
$credit_total= -1*(PaymentData::sumByClientId($client->id)->total);
$pdf = new FPDF($orientation='P',$unit='mm', array(74,350));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 4;
$pdf->SetFont('Helvetica','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,5,date("d/m/Y h:ia",strtotime($sell->created_at)));
$pdf->Cell(65,5,"# FACTURA: ".$sell->id,0,0,"R");
$pdf->setY(5);
$pdf->setX(2);
$pdf->SetFont('Helvetica','B',9);
$pdf->setY(4);
$pdf->setX($mid_x - ($pdf->GetStringWidth($title) / 2) + 1);
$pdf->Cell(5,11,strtoupper($title));//largo celda,altura,valor
$pdf->SetFont('Helvetica','B',6);
$pdf->setX($mid_x - ($pdf->GetStringWidth($address) / 2) -1);
$pdf->Cell(5,17,strtoupper(utf8_decode($address)));
$pdf->setX($mid_x - ($pdf->GetStringWidth($phone) / 2) -2);
$pdf->Cell(5,23,"Telefono.: ".strtoupper(utf8_decode($phone)));
$pdf->setY(3);
$pdf->setX($mid_x - ($pdf->GetStringWidth($rnc) / 2));
$pdf->Cell(5,29,"RNC: ".strtoupper($rnc));
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,35,'Cajero: '.utf8_decode($user->name." ".$user->lastname));
$pdf->setX(2);
$pdf->Cell(5,41,"Cliente: ".utf8_decode($client->name." ".$client->lastname));
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,47,"NCF: ");
$pdf->SetFont('Helvetica','B',5);
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,55,'---------------------------------------------------------------------------------------------------------------------');
$total = 0;
$off = 58;
foreach($operations as $op){
    $product = $op->getProduct();
    $total += $op->q*$op->price_out;
    $off+=6;
}
$credit= -1*(PaymentData::sumByClientBySellId($sell->person_id,$sell->id)->total);
$credit_total= -1*(PaymentData::sumByClientId($client->id)->total);
$pdf->setX(2);
$pdf->Cell(5,$off+6,"MONTO ABONO: " );
$pdf->setX(62);
$pdf->Cell(11,$off+6,$currency." ".number_format($sell_detail->pagado,2,".",","),0,0,"R");
$pdf->setX(6);
$pdf->setX(2);
$pdf->Cell(5,$off+12,"PENDIENTE FACTURA: " );
$pdf->setX(62);
$pdf->Cell(11,$off+12,$currency." ".number_format($sell_detail->pendiente,2,".",","),0,0,"R");
if($sell_detail->totalpendiente > $sell_detail->pendiente){
    $pdf->setX(6);
    $pdf->setX(2);
    $pdf->Cell(5,$off+18,"TOTAL PENDIENTE: " );
    $pdf->setX(62);
    $pdf->Cell(11,$off+18,$currency." ".number_format($sell_detail->totalpendiente,2,".",","),0,0,"R");
}
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('GRACIAS POR SU COMPRA') / 2));
$off+=40;
$pdf->Cell(5,$off,'GRACIAS POR SU COMPRA');
//$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth($website) / 2));
//$off+=4;
//$pdf->Cell(5,$off,$website);
$pdf->setX(2);
$off+=5;
$pdf->SetFont('Helvetica','',4);
$pdf->Cell(5,$off,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->output();