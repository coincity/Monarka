<?php

include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/PersonData.php";
include "core/app/model/SellData.php";
include "core/app/model/PaymentData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";

include "fpdf/fpdf.php";
session_start();
if(isset($_GET["user_id"])){ Core::$user = UserData::getById($_GET["user_id"]); }
$title = ConfigurationData::getByPreffix("ticket_title")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;

$income = PaymentData::getById($_GET["id"]);
$sell = SellData::getById($income->sell_id);
$stock = StockData::getById($sell->stock_to_id);
$operations = OperationData::getAllProductsBySellId($income->sell_id);
$user = $income->getUser();
$client = $sell->getPerson();

$pdf = new FPDF($orientation='P',$unit='mm', array(74,250));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 5;
$pdf->SetFont('Arial','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(5,5,$income->created_at."                                      No. Recibo: 000000".$income->id);
$pdf->SetFont('Arial','B',9);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(3);
$pdf->setX($mid_x - ($pdf->GetStringWidth($title) / 2));
$pdf->Cell(5,11,strtoupper($title));
$pdf->setX($mid_x - ($pdf->GetStringWidth($title) / 2));
$pdf->SetFont('Arial','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX($mid_x - ($pdf->GetStringWidth($address) / 2));
$pdf->Cell(5,17,strtoupper($address));
$pdf->setX($mid_x - ($pdf->GetStringWidth($phone) / 2));
$pdf->Cell(5,23,"Tel.: ".strtoupper($phone));
$pdf->setY(4);
$pdf->setX(2);
$pdf->Cell(5,29,'Cajero: '.$user->name." ".$user->lastname);
$pdf->setY(4);
$pdf->setX(2);
$pdf->Cell(5,34,'Cliente: '.$client->name." ".$client->lastname);
$pdf->setX(2);
$pdf->Cell(5,39,"Sucursal: ".utf8_decode($stock->name));
$pdf->setX(2);
$pdf->Cell(5,45,"No. Factura: " );
$pdf->setX(14);
$pdf->Cell(11,45," ".$sell->id);
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,51,'---------------------------------------------------------------------------------------------------------------------');
$pdf->setX(2);
//$pdf->Cell(5,51,'Articulo                       Cant.    Precio            Total');


$total =0;
$off = 51;
foreach($operations as $op){
$product = $op->getProduct();
/*$pdf->setX(2);
$pdf->Cell(5,$off,ucfirst(strtolower(substr($product->name, 0,22))));
$pdf->setX(20);
$pdf->Cell(35,$off,"$op->q");
$pdf->setX(27);
$pdf->Cell(11,$off,  $currency." ".number_format($product->total,2,".",",") ,0,0,"R");
$pdf->setX(41);
$pdf->Cell(11,$off,  $currency." ".number_format($op->q*$product->total,2,".",",") ,0,0,"R");
*/

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->total;
$off+=6;
}
$credit= -1*(PaymentData::sumByClientBySellId($sell->person_id,$sell->id)->total);
$pdf->setX(2);
$pdf->Cell(5,$off+6,"MONTO ABONO $: " );
$pdf->setX(62);
$pdf->Cell(11,$off+6,$currency." ".number_format($income->val,2,".",","),0,0,"R");
$pdf->setX(6);
$pdf->setX(2);
$pdf->Cell(5,$off+12,"BALANCE PENDIENTE $: " );
$pdf->setX(62);
$pdf->Cell(11,$off+12,$currency." ".number_format($credit,2,".",","),0,0,"R");
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('CUIDAMOS TU PIEL...') / 2));
$pdf->Cell(5,$off+35,'GRACIAS POR SU COMPRA');
$pdf->output();
