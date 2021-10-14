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
$sell = SellData::getById($_GET["id"]);
$sell_detail = SellData::getSellsDetails($_GET["id"],$_GET["transaction_id"]);
$payment = PaymentData::getBySellId($_GET["id"]);
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();
$client = $sell->getPerson();
$credit= -1*(PaymentData::sumByClientBySellId($client->id,$sell->id)->total);
$credit_total= -1*(PaymentData::sumByClientId($client->id)->total);
$pdf = new FPDF($orientation='P',$unit='mm', array(74,350));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 4;
$pdf->SetFont('Helvetica','',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,5,"FECHA: ".date("d-m-Y",strtotime($payment->created_at)));
$pdf->Cell(65,5,"HORA: ".date("h:i:sa",strtotime($payment->created_at)),0,0,"R");
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,11,"RECIBO #:");
$pdf->Cell(65,11,$sell_detail->id,0,0,"R");
$pdf->SetFont('Helvetica','',4);
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,17,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->SetFont('Helvetica','',6);
$pdf->setX($mid_x - ($pdf->GetStringWidth("**COPIA DE DOCUMENTO FISCAL**") / 2) +3);
$pdf->Cell(5,23,utf8_decode('**COPIA DE DOCUMENTO FISCAL**'));
$pdf->SetFont('Helvetica','',4);
$pdf->setX(2);
$pdf->Cell(5,28,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->SetFont('Helvetica','',8);
$pdf->setY(4);
$pdf->setX($mid_x - ($pdf->GetStringWidth($title) / 2) + 1);
$pdf->Cell(5,37,strtoupper($title));//largo celda,altura,valor
$pdf->SetFont('Helvetica','',6);
$pdf->setX($mid_x - ($pdf->GetStringWidth($address) / 2) -1);
$pdf->Cell(5,42,strtoupper(utf8_decode($address)));
$pdf->setX($mid_x - ($pdf->GetStringWidth($phone) / 2) -2);
$pdf->Cell(5,47,"Telefono.: ".strtoupper(utf8_decode($phone)));
$pdf->setY(3);
$pdf->setX($mid_x - ($pdf->GetStringWidth($rnc) / 2));
$pdf->Cell(5,53,"RNC: ".strtoupper($rnc));
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,59,'Cajero: '.utf8_decode($user->name." ".$user->lastname));
$pdf->setX(2);
$pdf->Cell(5,64,"Cliente: ".utf8_decode($client->name." ".$client->lastname));
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,70,"No. Factura: ".$sell->id);
$pdf->SetFont('Helvetica','',4);
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,76,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->SetFont('Helvetica','',6);
$pdf->setX($mid_x - ($pdf->GetStringWidth("FACTURA PARA CONSUMIDOR FINAL") / 2) +3);
$pdf->Cell(5,82,utf8_decode('FACTURA PARA CONSUMIDOR FINAL'));
$pdf->SetFont('Helvetica','',4);
$pdf->setX(2);
$pdf->Cell(5,88,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->SetFont('Helvetica','',6);
$total = 0;
$off = 90;
foreach($operations as $op){
    $product = $op->getProduct();
    $total += $op->q*$product->total;
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
$off+=30;
$pdf->Cell(5,$off,'GRACIAS POR SU COMPRA');
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth($website) / 2));
$off+=4;
$pdf->Cell(5,$off,$website);
$pdf->setX(2);
$off+=5;
$pdf->SetFont('Helvetica','',4);
$pdf->Cell(5,$off,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->output();