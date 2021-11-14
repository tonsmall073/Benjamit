<?php
require('Product.vendor.php');
$_context = new ConnectDatabases(true);

if($_POST['Controller'] == 'AddProduct')
{
    $modelReq = new AddProductRequestModel();
    $modelReq->Username = $_POST['Username'];
    $modelReq->Password = $_POST['Password'];
    $modelReq->ProductName = $_POST['ProductName'];

    if(isset($_POST['ProductPicture']))
    {
        foreach($_POST['ProductPicture'] as $value)
        {
            $row = $modelReq->arrayPushProductPictureList();
            $modelReq->ProductPicture[$row]->FileToBase64 = $value;
        }
    }
    
    if(isset($_POST['ProductPicture']))
    {
        foreach($_POST['ProductRelatedName'] as $value)
        {
            $row = $modelReq->arrayPushProductRelatedList();
            $modelReq->ProductRelated[$row]->Name = $value;
        }
    }
    if(isset($_POST['IdBarcode']))
    {
        for($index = 0;$index < count($_POST['IdBarcode']);$index++)
        {
            $row = $modelReq->arrayPushProductPriceList();
            $modelReq->ProductPrice[$row]->CostPrice = preg_replace('/[^0-9\.]/','',$_POST['CostPrice'][$index]);
            $modelReq->ProductPrice[$row]->SalePrice = preg_replace('/[^0-9\.]/','',$_POST['SalePrice'][$index]);
            $modelReq->ProductPrice[$row]->IdUnitType = $_POST['UnitType'][$index];
            $modelReq->ProductPrice[$row]->IdBarcode = $_POST['IdBarcode'][$index];
        }
    }

    $modelRes = new AddProductResponseModel();
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
if($_POST['Controller'] == 'GetProductsForDataTable')
{
    $modelRes = new GetProductsForDataTableResponseModel();
    $modelRes->Draw = $_POST['draw'];

    echo json_encode($modelRes);
}
?>
