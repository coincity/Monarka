<?php
date_default_timezone_set('America/La_Paz');
include "../core/config/config.php";

include "../core/controller/Core.php";
include "../core/controller/Database.php";
include "../core/controller/Executor.php";
include "../core/controller/Model.php";

include "../core/app/model/UserData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/CotizationData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/StockData.php";
include "../core/app/model/ConfigurationData.php";
include "../fpdf/fpdf.php";
header("Content-Type: text/html; charset=utf-8");
session_start();
if(isset($_SESSION["user_id"])){
    Core::$user = UserData::getById($_SESSION["user_id"]);
}

$imp_name = ConfigurationData::getByPreffix("imp-name")->val;
$imp_val = ConfigurationData::getByPreffix("imp-val")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;
$title = ConfigurationData::getByPreffix("company_name")->val;

//$stock = StockData::getPrincipal();
$cotization = CotizationData::getById($_GET["id"]);

//&& ($payment->payment_type_id != 2)
$operations = OperationData::getAllProductsByRefId($cotization->ref_id);
$user = $cotization->getUser();
$client = $cotization->getPerson();

$pdf = new FPDF($orientation='P',$unit='mm', array(74,250));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 5;
$pdf->SetFont('Arial','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,5,utf8_decode(date("d/m/Y h:ia",strtotime($cotization->created_at))."                                                  # COTIZACIÓN: ".$cotization->id));
$pdf->SetFont('Arial','B',9);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(4);
$pdf->setX($mid_x - ($pdf->GetStringWidth($title) / 2));
$pdf->Cell(5,11,strtoupper($title));//largo celda,altura,valor
$pdf->SetFont('Arial','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setX($mid_x - ($pdf->GetStringWidth($address) / 2));
$pdf->Cell(5,17,strtoupper($address));
$pdf->setX($mid_x - ($pdf->GetStringWidth($phone) / 2));
$pdf->Cell(5,23,"Tel.: ".strtoupper($phone));
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,29,'Cajero: '.$user->name." ".$user->lastname);
$pdf->setX(2);
$pdf->Cell(5,34,"Cliente: ".$client->name." ".$client->lastname);
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,44,'---------------------------------------------------------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,47,utf8_decode('ARTÍCULO                             CANT.    PRECIO            ITBIS                TOTAL'));
$pdf->setX(2);
$pdf->Cell(5,50,'---------------------------------------------------------------------------------------------------------------------');

//LISTADO PRODUCTO
$total =0;
$off = 59;
foreach($operations as $op){
    $product = $op->getProduct();
    $pdf->setX(2);
//$pdf->Cell(5,$off,  ucfirst(strtolower(substr($product->name, 0,22))) );
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
    $total += $op->q*$op->price_out+$itbis;

    $off+=6;
}

//DETALLE FINANCIERO
$details_x = 43;//altura
$values_x = 67;//margen
$pdf->setX($details_x);
$pdf->Cell(5,$off+12,"SUBTOTAL:  ");
$pdf->setX($values_x);
$pdf->Cell(5,$off+12,$currency." ".number_format($total,2,".",","),0,0,"R");
$pdf->setX($details_x);
$pdf->Cell(5,$off+18,"DESCUENTO: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off+18,$currency." ".number_format(ceil($total * ($cotization->discount/100)),2,".",","),0,0,"R");
$pdf->setX($details_x);
$pdf->Cell(5,$off+24,"TOTAL: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off+24,$currency." ".number_format(ceil(($total) - ceil($total * ($cotization->discount/100))),2,".",","),0,0,"R");
//$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('ESTA COTIZACIÓN TENDRÁ VIGENCIA POR 30 DÍAS A PARTIR DE LA FECHA DE REALIZACIÓN') / 2));
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('ESTA COTIZACIÓN TENDRÁ VIGENCIA DE 30 DÍAS') / 2));
$pdf->Cell(5,$off+64,utf8_decode('ESTA COTIZACIÓN TENDRÁ VIGENCIA DE 30 DÍAS'));
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('A PARTIR DE LA FECHA DE REALIZACIÓN') / 2));
$pdf->Cell(5,$off+68,utf8_decode('A PARTIR DE LA FECHA DE REALIZACIÓN'));
$pdf->setX(2);
$pdf->Cell(5,$off+76,'---------------------------------------------------------------------------------------------------------------------');

$pdf->output();