<?php
require('Product.vendor.php');
$_context = new ConnectDatabases(true);

if($_POST['Controller'] == 'AddProduct')
{
    $modelReq = new AddProductRequestModel();
    $modelReq->Username = $_POST['Username'];
    $modelReq->Password = $_POST['Password'];
    $modelReq->ProductName = $_POST['ProductName'];

    foreach($_POST['ProductPicture'] as $value)
    {
        $row = $modelReq->arrayPushProductPictureList();
        $modelReq->ProductPicture[$row]->FileToBase64 = $value;
    }

    foreach($_POST['ProductRelated'] as $value)
    {
        $row = $modelReq->arrayPushProductRelatedList();
        $modelReq->ProductRelated[$row]->Name = $value;
    }

    for($index = 0;$index < count($_POST['IdBarcode']);$index++)
    {
        $row = $modelReq->arrayPushProductPriceList();
        $modelReq->ProductPrice[$row]->CostPrice = preg_replace('/[^0-9\.]/','',$_POST['CostPrice'][$index]);
        $modelReq->ProductPrice[$row]->SalePrice = preg_replace('/[^0-9\.]/','',$_POST['SalePrice'][$index]);
        $modelReq->ProductPrice[$row]->IdUnitType = $_POST['IdUnitType'][$index];
        $modelReq->ProductPrice[$row]->IdBarcode = $_POST['IdBarcode'][$index];
    }

    $service = new ProductService($_context->dbBenjamit());
    $res = $service->createAddProduct($modelReq,$modelRes);
    echo json_encode($res);
}
if($_POST['Controller'] == 'UpdateProduct')
{

}
if($_POST['Controller'] == 'DeleteProduct')
{
    
}
if($_POST['Controller'] == 'GetProductForDataTable')
{

}
?>