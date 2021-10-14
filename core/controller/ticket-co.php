<?php
date_default_timezone_set('America/La_Paz');
include "core/controller/Core.php";
include "core/controller/Database.php";
include "core/controller/Executor.php";
include "core/controller/Model.php";

include "core/app/model/UserData.php";
include "core/app/model/PersonData.php";
include "core/app/model/CotizationData.php";
include "core/app/model/OperationData.php";
include "core/app/model/ReservationData.php";
include "core/app/model/ProductData.php";
include "core/app/model/StockData.php";
include "core/app/model/ConfigurationData.php";
include "fpdf/fpdf.php";
header("Content-Type: text/html; charset=utf-8");
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }

$title = ConfigurationData::getByPreffix("ticket_title")->val;
$imp_name = ConfigurationData::getByPreffix("imp-name")->val;
$imp_val = ConfigurationData::getByPreffix("imp-val")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;

$stock = StockData::getPrincipal();
$sell = CotizationData::getById($_GET["id"]);

//&& ($payment->payment_type_id != 2)
$operations = OperationData::getAllProductsByRefId($_GET["ref_id"]);
$user = $sell->getUser();
$client = $sell->getPerson();

$pdf = new FPDF($orientation='P',$unit='mm', array(74,250));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 5;
$pdf->SetFont('Arial','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,5,utf8_decode($sell->created_at."                                                  # COTIZACIÓN: ".$sell->id));
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
$pdf->setX(2);
$pdf->Cell(5,39,"Sucursal: ".utf8_decode($stock->name));
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,44,'---------------------------------------------------------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,47,utf8_decode('ARTÍCULO                                      CANT.           PRECIO                     TOTAL'));
$pdf->setX(2);
$pdf->Cell(5,50,'---------------------------------------------------------------------------------------------------------------------');
/*$pdf->Cell(5,17,"TEL. ".strtoupper($phone));
$pdf->setX(15);
$pdf->Cell(5,23,"");
$pdf->setX(2);
*/
//LISTADO PRODUCTO
$total =0;
$off = 59;
foreach($operations as $op){
    $product = $op->getProduct();
    $pdf->setX(2);
//$pdf->Cell(5,$off,  ucfirst(strtolower(substr($product->name, 0,22))) );
    $pdf->Cell(5,$off, utf8_decode(substr($product->name, 0,22)));
    $pdf->setX(30);
    $pdf->Cell(35,$off,"$op->q");
    $pdf->setX(44);
    $pdf->Cell(11,$off,  $currency." ".number_format($product->total,2,".",",") ,0,0,"R");
    $pdf->setX(61);
    $pdf->Cell(11,$off,  $currency." ".number_format($op->q*$product->total,2,".",",") ,0,0,"R");
    $total += $op->q*$product->total;

    $off+=6;
}

//DETALLE FINANCIERO
$details_x = 43;//altura
$values_x = 67;//margen
$pdf->setX($details_x);
$pdf->Cell(5,$off+6,"IMPORTE:  " );
$pdf->setX($values_x);
$pdf->Cell(5,$off+6,$currency." ".number_format($total,2,".",","),0,0,"R");
$pdf->setX($details_x);
$pdf->Cell(5,$off+12,$imp_name." (".$imp_val."%):  " );
$pdf->setX($values_x);
$pdf->Cell(5,$off+12,$currency." ".number_format(($total *($imp_val/100)),2,".",","),0,0,"R");
$pdf->setX($details_x);
$pdf->Cell(5,$off+18,"SUBTOTAL:  ");
$pdf->setX($values_x);
$pdf->Cell(5,$off+18,$currency." ".number_format($total +($total *($imp_val/100)),2,".",","),0,0,"R");
$pdf->setX($details_x);
$pdf->Cell(5,$off+24,"DESCUENTO: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off+24,$currency." ".number_format($sell->discount,2,".",","),0,0,"R");
$pdf->setX($details_x);
$pdf->Cell(5,$off+30,"TOTAL: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off+30,$currency." ".number_format(ceil(($total+($total *($imp_val/100))) - $sell->discount),2,".",","),0,0,"R");
//$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('ESTA COTIZACIÓN TENDRÁ VIGENCIA POR 30 DÍAS A PARTIR DE LA FECHA DE REALIZACIÓN') / 2));
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('ESTA COTIZACIÓN TENDRÁ VIGENCIA DE 30 DÍAS') / 2));
$pdf->Cell(5,$off+70,utf8_decode('ESTA COTIZACIÓN TENDRÁ VIGENCIA DE 30 DÍAS'));
$pdf->setX($mid_x + 4 - ($pdf->GetStringWidth('A PARTIR DE LA FECHA DE REALIZACIÓN') / 2));
$pdf->Cell(5,$off+74,utf8_decode('A PARTIR DE LA FECHA DE REALIZACIÓN'));
$pdf->setX(2);
$pdf->Cell(5,$off+82,'---------------------------------------------------------------------------------------------------------------------');

$pdf->output();