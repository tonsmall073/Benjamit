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
?>