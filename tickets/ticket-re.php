<?php
date_default_timezone_set('America/La_Paz');
include "../core/config/config.php";


include "../core/controller/Core.php";
include "../core/controller/Database.php";
include "../core/controller/Executor.php";
include "../core/controller/Model.php";
include "../core/app/model/UserData.php";
include "../core/app/model/ReData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/StockData.php";
include "../core/app/model/ConfigurationData.php";
include "../fpdf/fpdf.php";
header("Content-Type: text/html; charset=utf-8");
session_start();
if(isset($_SESSION["user_id"])){ Core::$user = UserData::getById($_SESSION["user_id"]); }


$title = ConfigurationData::getByPreffix("company_name")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;
$imp_name = ConfigurationData::getByPreffix("imp-name")->val;
$imp_val = ConfigurationData::getByPreffix("imp-val")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$rnc = ConfigurationData::getByPreffix("company_rnc")->val;
$website = ConfigurationData::getByPreffix("company_website")->val;

$re = ReData::getById($_GET["id"]);
$operations = OperationData::getAllProductsByRefId($re->ref_id); //OperationData::getAllProductsBySellId($_GET["id"]);
$user = $re->getUser();
$client = $re->getPerson();


$pdf = new FPDF($orientation='P',$unit='mm', array(74,350));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 4;
$pdf->SetFont('Helvetica','',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,5,"FECHA: ".date("d-m-Y",strtotime($re->created_at)));
$pdf->Cell(65,5,"HORA: ".date("h:i:sa",strtotime($re->created_at)),0,0,"R");
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,11,"FACTURA #:");
$pdf->Cell(65,11,$re->id,0,0,"R");
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
$pdf->Cell(5,70,"NCF: ");
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
$pdf->SetFont('Helvetica','B',6);
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,94,utf8_decode('DESCRIPCIÓN                                                                              VALOR'));
////LISTADO PRODUCTO
$total =0;
$off = 100;
$pdf->SetFont('Helvetica','',6);
foreach($operations as $op){
    $product = $op->getProduct();
    $pdf->setY(7);
    $pdf->setX(2);
    $pdf->Cell(5,$off,$op->q." x ".number_format($op->price_in,2,".",","));
    $off+=4;
    $pdf->setY(7);
    $pdf->setX(2);
    $pdf->Cell(5,$off,"Cod.: ".utf8_decode($product->barcode));
    $off+=9;
    $pdf->setY(5);
    $pdf->setX(2);
    $pdf->Cell(5,$off, utf8_decode($product->name));
    $pdf->Cell(65,$off, number_format($op->q*$op->price_in,2,".",",") ,0,0,"R");
    $total += $op->q*$op->price_in;
    $off+=6;
}
$imp = 0;// ceil((($total+($total *($re->iva/100)))-$re->discount) * ($re->imp / 100));
////DETALLE FINANCIERO
$details_x = 2;//altura
$values_x = 67;//margen
$pdf->SetFont('Helvetica','',4);
$pdf->setX(2);
$pdf->Cell(5,$off,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->SetFont('Helvetica','',6);
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,"IMPORTE:  " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format($total,2,".",","),0,0,"R");
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,$imp_name." (".$imp_val."%):  " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format(($total *($imp_val/100)),2,".",","),0,0,"R");
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,"SUBTOTAL:  ");
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format($total +($total *($imp_val/100)),2,".",","),0,0,"R");
$pdf->SetFont('Helvetica','',4);
$pdf->setX(2);
$off+=6;
$pdf->Cell(5,$off,'-------------------------------------------------------------------------------------------------------------------------------------------------');
$pdf->SetFont('Helvetica','B',7);
$pdf->setX($details_x);
$off+=6;
$pdf->Cell(5,$off,"TOTAL: " );
$pdf->setX($values_x);
$pdf->Cell(5,$off,$currency." ".number_format($total,2,".",","),0,0,"R");
$pdf->SetFont('Helvetica','',7);
$pdf->setX($details_x);
$off+=6;
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
/*
date_default_timezone_set('America/La_Paz');
include "../core/controller/Core.php";
include "../core/controller/Database.php";
include "../core/controller/Executor.php";
include "../core/controller/Model.php";

include "../core/app/model/UserData.php";
include "../core/app/model/ReData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/StockData.php";
include "../core/app/model/ConfigurationData.php";
include "../fpdf/fpdf.php";
header("Content-Type: text/html; charset=utf-8");
session_start();
if(isset($_SESSION["user_id"])){
    Core::$user = UserData::getById($_SESSION["user_id"]);
}

$title = ConfigurationData::getByPreffix("company_name")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;
$imp_name = ConfigurationData::getByPreffix("imp-name")->val;
$imp_val = ConfigurationData::getByPreffix("imp-val")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;

$sell = ReData::getById($_GET["id"]);
$operations = OperationData::getAllProductsByRefId($sell->ref_id); //OperationData::getAllProductsBySellId($_GET["id"]);
$user = $sell->getUser();

$client = $sell->getPerson();

$pdf = new FPDF($orientation='P',$unit='mm', array(74,250));
$pdf->AddPage();
$mid_x = ($pdf->w / 2) - 5;
$pdf->SetFont('Arial','B',6);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(2);//altura
$pdf->setX(2);//margen
$pdf->Cell(5,5,utf8_decode(date("d/m/Y h:ia",strtotime($sell->created_at))."                                                  # COMPRA: ".$sell->id));
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
$pdf->Cell(5,34,"Proveedor: ".$client->name." ".$client->lastname);
$pdf->SetFont('Arial','B',5);    //Letra Arial, negrita (Bold), tam. 20
$pdf->setY(5);
$pdf->setX(2);
$pdf->Cell(5,44,'---------------------------------------------------------------------------------------------------------------------');
$pdf->setX(2);
$pdf->Cell(5,47,utf8_decode('ARTÍCULO                                      CANT.           PRECIO                     TOTAL'));
$pdf->setX(2);
$pdf->Cell(5,50,'---------------------------------------------------------------------------------------------------------------------');


$total =0;
$off = 60;

foreach($operations as $op){
    $product = $op->getProduct();
    $pdf->setX(2);
    //$pdf->Cell(5,$off,  ucfirst(strtolower(substr($product->name, 0,22))) );
    $pdf->Cell(5,$off, utf8_decode(substr($product->name, 0,22)));
    $pdf->setX(30);
    $pdf->Cell(35,$off,"$op->q");
    $pdf->setX(40);
    $pdf->Cell(11,$off,  $currency." ".number_format($op->price_in,2,".",",") ,0,0,"R");
    $pdf->setX(58);
    $pdf->Cell(11,$off,  $currency." ".number_format($op->q*$op->price_in,2,".",",") ,0,0,"R");
    $total += $op->q*$op->price_in;

    $off+=6;
}

$pdf->setX(2);
$pdf->Cell(5,$off+12,"TOTAL: " );
$pdf->setX(15);
$pdf->Cell(11,$off+12,$currency." ".number_format($total,2,".",","),0,0,"R");
$pdf->setX(2);
$pdf->Cell(5,$off+32,'---------------------------------------------------------------------------------------------------------------------');
$pdf->output();
*/