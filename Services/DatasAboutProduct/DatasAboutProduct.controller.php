<?php
require('DatasAboutProduct.vendor.php');
$_context = new ConnectDatabases(true);

if($_POST['Controller'] == 'GetUnitType')
{
    $modelReq = new UnitTypeRequestModel();
    $modelReq->IdUnitType = 0;

    $modelRes = new UnitTypeResponseModel();
    
    $service = new DatasAboutProductService($_context->dbBenjamit());
    $res = $service->getUnitType($modelReq,$modelRes);

    echo json_encode($res);
}

if($_POST['Controller'] == 'GetSimilarProductName')
{
    $modelReq = new SimilarProductNameRequestModel();
    $modelReq->ProductName = $_POST['ProductName'];

    $modelRes = new SimilarProductNameResponseModel();

    $service = new DatasAboutProductService($_context->dbBenjamit());
    $res = $service->createDataSimilarProductName($modelReq,$modelRes);

    echo json_encode($res);
}
?>