<?php
include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/SellData.php";
include "core/app/model/PaymentData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "fpdf/fpdf.php";
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }

$title = ConfigurationData::getByPreffix("ticket_title")->val;
$imp_name = ConfigurationData::getByPreffix("imp-name")->val;
$imp_val = ConfigurationData::getByPreffix("imp-val")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;

$stock = StockData::getPrincipal();
$sell = SellData::getById($_GET["id"]);
$payment = PaymentData::getBySellId($_GET["id"]);
//&& ($payment->payment_type_id != 2)
$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();


$pdf = new FPDF($orientation='P',$unit='mm', array(45,180));
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);    //Letra Arial, negrita (Bold), tam. 20
/*$pdf->setY(2);
$pdf->setX(2);
$pdf->Cell(5,5,strtoupper($title));
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX(2);
$pdf->Cell(5,11,strtoupper($address));
$pdf->setX(2);
$pdf->Cell(5,17,"TEL. ".strtoupper($phone));
$pdf->setX(15);
$pdf->Cell(5,23,"");
$pdf->setX(2);
$pdf->Cell(5,29,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,34,'Cant.    Articulo                   Precio          Total');
*/

$pdf->Cell(5,$off+68,"FACTURA: ".$sell->id.' - FECHA: '.$sell->created_at);
$total =0;
$off = 39;
foreach($operations as $op){
/*$product = $op->getProduct();
$pdf->setX(2);
$pdf->Cell(5,$off,"$op->q");
$pdf->setX(8);
$pdf->Cell(35,$off,  ucfirst(strtolower(substr($product->name, 0,22))) );
$pdf->setX(22);
$pdf->Cell(11,$off,  "$ ".number_format($product->total,2,".",",") ,0,0,"R");
$pdf->setX(32);
$pdf->Cell(11,$off,  "$ ".number_format($op->q*$product->total,2,".",",") ,0,0,"R");

//    ".."  ".number_format($op->q*$product->price_out,2,".",","));
$total += $op->q*$product->total;
$off+=6;*/
}

/*
$pdf->setX(2);
$pdf->Cell(5,$off+6,"IMPORTE:  " );
$pdf->setX(38);
$pdf->Cell(5,$off+6,"$ ".number_format($total,2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+12,$imp_name." (".$imp_val."%):  " );
$pdf->setX(38);
$pdf->Cell(5,$off+12,"$ ".number_format(($total *($imp_val/100)),2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+18,"SUBTOTAL:  ");
$pdf->setX(38);
$pdf->Cell(5,$off+18,"$ ".number_format($total +($total *($imp_val/100)),2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+24,"DESCUENTO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+24,"$ ".number_format($sell->discount,2,".",","),0,0,"R");


$pdf->setX(2);
$pdf->Cell(5,$off+30,"TOTAL: " );
$pdf->setX(38);
$pdf->Cell(5,$off+30,"$ ".number_format(ceil(($total+($total *($imp_val/100))) - $sell->discount),2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+36,"RECIBIDO: " );
$pdf->setX(38);
$pdf->Cell(5,$off+36,"$ ".number_format($sell->cash,2,".",","),0,0,"R");

$pdf->setX(2);
$pdf->Cell(5,$off+42,"CAMBIO: " );
$pdf->setX(38);
//$pdf->Cell(5,$off+42,"$ ".number_format($sell->cash-ceil(($total+($total *($imp_val/100))) - $sell->discount),2,".",","),0,0,"R");
//else $pdf->Cell(5,$off+42,"$ ".number_format(0,2,".",","),0,0,"R");

if($sell->cash > 0) {
	$result = $sell->cash-ceil(($total+($total *($imp_val/100))) - $sell->discount);
	if($result > 0) {
		$pdf->Cell(5,$off+42,"$ ".number_format($result,2,".",","),0,0,"R");
	}
	else $pdf->Cell(5,$off+42,"$ ".number_format(0,2,".",","),0,0,"R");
	
}
else $pdf->Cell(5,$off+42,"$ ".number_format(0,2,".",","),0,0,"R");

$pdf->setX(3);
if($sell->cash >= (($total+($total *($imp_val/100))) - $sell->discount)) $pdf->Cell(5,$off+48,"");
else if(($payment->payment_type_id == 1) && ($sell->cash < (($total+($total *($imp_val/100))) - $sell->discount)) && ($sell->cash > 0)) $pdf->Cell(5,$off+50,"                       ABONO DE VENTA");
else $pdf->Cell(5,$off+50,"                       VENTA A CREDITO");
$pdf->setX(38);

*/
/*
$pdf->setX(2);
$pdf->Cell(5,$off+56,'-------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,$off+62,"SUCURSAL: ".strtoupper($stock->name));
$pdf->setX(2);
$pdf->Cell(5,$off+68,"FACTURA: ".$sell->id.' - FECHA: '.$sell->created_at);
$pdf->setX(2);
$pdf->Cell(5,$off+74,'ATENDIDO POR '.strtoupper($user->name." ".$user->lastname));
$pdf->setX(2);
$pdf->Cell(5,$off+88,'              GRACIAS POR TU COMPRA ');*/


$pdf->output();