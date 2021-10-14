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
$isabono = false;
if($payment!=null){
	if($payment->payment_type_id == 2) $isabono = true;
}
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
$pdf->setX(2);
$pdf->Cell(5,58,utf8_decode('ARTICULO                             CANT.    PRECIO            ITBIS                TOTAL'));
$pdf->setX(2);
$pdf->Cell(5,61,'---------------------------------------------------------------------------------------------------------------------');

////LISTADO PRODUCTO
$total =0;
$titbis = 0;
$off = 67;
$pdf->SetFont('Helvetica','B',5);
foreach($operations as $op){
	$product = $op->getProduct();
    $pdf->setX(2);
    $pdf->Cell(5,$off, utf8_decode(substr($product->name, 0,22)));
    $pdf->setX(26);
    $pdf->Cell(35,$off,"$op->q");
    $pdf->setX(33);
    $pdf->Cell(11,$off,  $currency.number_format($op->price_out,2,".",",") ,0,0,"L");
    $pdf->setX(45);
    $itbis = 0;
    if($product->itbis == 1) $itbis = ($op->q*$op->price_out *($imp_val/100));
    $pdf->Cell(11,$off,  $currency.number_format($itbis,2,".",",") ,0,0,"L");
    $pdf->setX(58);
    $pdf->Cell(11,$off,  $currency.number_format(($op->q*$op->price_out)+$itbis,2,".",",") ,0,0,"L");
    $total += $op->q*$op->price_out;
    $titbis += $itbis;

    $off+=6;
}
$imp = ceil((($total+($total *($sell->iva/100)))-$sell->discount) * (0 / 100));
////DETALLE FINANCIERO
$details_x = 40;//altura
$values_x = 67;//margen
$pdf->setX($details_x);
$off+=15;
$pdf->Cell(5,$off,"IMPORTE:  " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format($total,2,".",","),0,0,"R");
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,$imp_name." (".$sell->iva."%):  " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format($titbis,2,".",","),0,0,"R");
if($sell->discount > 0) {
	$pdf->setX($details_x);
    $off+=6;
    $pdf->Cell(5,$off,"SUBTOTAL:  ");
    $pdf->setX($values_x);
    $pdf->Cell(5,$off,$currency." ".number_format($total +($total *($sell->iva/100)),2,".",","),0,0,"R");
    $pdf->setX($details_x);
    $off+=6;
    $pdf->Cell(5,$off,"DESCUENTO: " );
    $pdf->setX($values_x);
    $pdf->Cell(5,$off,$currency." ".number_format($sell->discount,2,".",","),0,0,"R");
}
if($imp > 0) {
    //$pdf->setX($details_x);
    //$off+=6;
    //$pdf->Cell(5,$off,"IMP. TARJETA: " );
    //$pdf->setX($values_x);
    //$pdf->Cell(5,$off,$currency." ".number_format($imp,2,".",","),0,0,"R");
}
$pdf->setX(2);
$off+=6;
$pdf->setX($details_x);
$pdf->Cell(5,$off,"TOTAL: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format(ceil($total+$titbis - $sell->discount + $imp),2,".",","),0,0,"R");
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,"EFECTIVO: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format($sell->cash,2,".",","),0,0,"R");
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,"CAMBIO: " );
$pdf->setX($values_x);
if($sell->cash > 0) {
    $result = $sell->cash-ceil(($total+($total *($sell->iva/100))) - $sell->discount + $imp);
    if($result > 0) {
        $pdf->Cell(5,$off,$currency." ".number_format($result,2,".",","),0,0,"R");
    }
    else $pdf->Cell(5,$off,$currency." ".number_format(0,2,".",","),0,0,"R");
}
else $pdf->Cell(5,$off,$currency." ".number_format(0,2,".",","),0,0,"R");
$pdf->setX(3);
if($sell->cash >= (($total+($total *($sell->iva/100))) - $sell->discount + $imp)) {
    $pdf->Cell(5,$off,"");
    $off+=12;
}
//else if(($isabono == true) && ($sell->cash < (($total+($total *($imp_val/100))) - $sell->discount + $imp)) && ($sell->cash > 0)) {
else if($sell->p_id == 3) {
    $pdf->setX(2);
    $off+=16;
    $pdf->Cell(5,$off,"ABONO DE VENTA");
    $pdf->setX(2);
    $off+=6;
    $pdf->Cell(5,$off,utf8_decode("PENDIENTE FACTURA: "));
    $pdf->setX(60);
    $pdf->Cell(11,$off,$currency." ".number_format($credit,2,".",","),0,0,"R");
}
else if($sell->p_id == 2) {
    $pdf->setX(2);
    $off+=22;
    $pdf->Cell(70,$off,utf8_decode("VENTA A CRÉDITO"),0,0,"R");
}

$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('GRACIAS POR SU COMPRA') / 2));
$off+=30;
$pdf->Cell(5,$off,'GRACIAS POR SU COMPRA');
$pdf->setX(2);
$off+=5;
$pdf->SetFont('Helvetica','',4);
$pdf->Cell(5,$off,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->output();