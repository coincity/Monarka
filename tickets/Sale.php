<?php

session_start();
include "../core/config/config.php";


include "../core/controller/Core.php";
include "../core/controller/Database.php";
include "../core/controller/Executor.php";
include "../core/controller/Utils.php";
include "../core/controller/Model.php";

include "../core/app/model/UserData.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/PaymentData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/ProductData.php";
include "../core/app/model/NcfDetailData.php";


include "../core/app/model/ConfigurationData.php";
include "../core/app/helpers/PrintDesigner.php";
include "../fpdf/fpdf.php";

$saleId = isset($_GET["id"]) ? $_GET["id"] : 0;
$taxes = ConfigurationData::getByPreffix("imp-name")->val;
$address = ConfigurationData::getByPreffix("company_address")->val;
$phone = ConfigurationData::getByPreffix("company_phone")->val;
$currency = ConfigurationData::getByPreffix("currency")->val;
$companyName = ConfigurationData::getByPreffix("company_name")->val;
$rnc = ConfigurationData::getByPreffix("company_rnc")->val;

$sell = SellData::getById($saleId);
$payment = PaymentData::getBySellId($saleId);
$operations = OperationData::getAllProductsBySellId($saleId);
$client = PersonData::getById($sell->person_id);
$user = UserData::getById($sell->user_id);
$ncf = NcfDetailData::getSaleNCF($saleId);

$printDesigner = new PrintDesigner();

if(isset($_GET["largePrint"])) {
    $printDesigner = new PrintDesigner(318);
    $printDesigner->columns = 90;
    $printDesigner->setBaseFontSize(16);
} 

$printDesigner->setBold();
$printDesigner->setMediumFont();
$printDesigner->addLine($companyName, "C");
$printDesigner->setSmallFont();
$printDesigner->addLine($address, "C");
$printDesigner->addLine($phone, "C");
$printDesigner->addLine("RNC : " . $rnc, "C");


$printDesigner->addLine("CLIENTE : " . $client->name." ".$client->lastname);
if($ncf != null) {
    $printDesigner->addLine("NCF : " . $ncf->ncf);
}

$headerColumnsSettings = array("L", "C,2", "L", "R", "C,2", "R");
$printDesigner->addDataRow([ "NO. FACTURA", ":", $sell->id, "CAJERO", ":", $user->name." ".$user->lastname], $headerColumnsSettings);
$printDesigner->addDataRow([ "FECHA", ":", date("d/m/Y",strtotime($sell->created_at)), "HORA", ":",strtoupper(date("h:ia",strtotime($sell->created_at)))], $headerColumnsSettings);



$printDesigner->addLine();
$columnSettings = array("L,15", "R", "R");
$printDesigner->addDivider();
$printDesigner->addDataRow(["PRODUCTO", "ITBIS", "TOTAL"], $columnSettings);
$printDesigner->addDivider();
$printDesigner->setNormal();
$subTotal = 0;
$totalTaxes = 0;
foreach($operations as $op){
    $product = $op->getProduct();    
    $productName = $product->name;
    $productQuantity = $op->q;
    $productUnitPrice = $op->price_out;
    $productTaxes = $product->itbis == 1 ? ($productUnitPrice * $productQuantity) * ($sell->iva / 100) : 0;

    $printDesigner->addDataRow([
        $productQuantity . " " . $productName, 
        Utils::moneyFormat("", $productTaxes), 
        Utils::moneyFormat("", $productUnitPrice * $productQuantity)
    ], $columnSettings);

    $subTotal += $productUnitPrice * $productQuantity;
    $totalTaxes += $productTaxes;
}
$printDesigner->setBold();
$printDesigner->addDivider();
$footerColumnsSettings = array("R", "C,2", "R");
$printDesigner->addDataRow(["SUB-TOTAL", ":", Utils::moneyFormat($currency, $subTotal)], $footerColumnsSettings);

$printDesigner->addDataRow(["ITBIS $sell->iva%", ":", Utils::moneyFormat($currency, $totalTaxes)], $footerColumnsSettings);
$printDesigner->addDataRow(["TOTAL", ":", Utils::moneyFormat($currency, $subTotal + $totalTaxes)], $footerColumnsSettings);
$printDesigner->end();

?>