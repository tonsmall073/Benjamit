<?php
require('Product.vendor.php');
$_context = new ConnectDatabases(true);

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if($_POST['Controller'] == 'AddProduct')
    {
        $modelReq = new AddProductRequestModel();
        $modelReq->Username = $_POST['Username'];
        $modelReq->Password = $_POST['Password'];
        $modelReq->ProductName = $_POST['ProductName'];
        $modelReq->DetailAboutProduct = $_POST['DetailAboutProduct'];

        if(isset($_POST['ProductPicture']))
        {
            foreach($_POST['ProductPicture'] as $value)
            {
                $row = $modelReq->arrayPushProductPictureList();
                $modelReq->ProductPicture[$row]->FileToBase64 = $value;
            }
        }

        if(isset($_POST['ProductRelatedName']))
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
        $modelReq = new GetProductsRequestModel();
        $modelReq->Draw = $_POST['draw'];
        $modelReq->OrderColumn = $_POST['order'][0]['column'];
        $modelReq->OrderDir = $_POST['order'][0]['dir'];
        $modelReq->StartLimit = $_POST['start'];
        $modelReq->LengthLimit = $_POST['length'];
        $modelReq->ColumnName = $_POST['columns'][$modelReq->OrderColumn]['name'];
        $modelReq->SearchValue = $_POST['search']['value'];
        
        $modelRes = new GetProductsResponseModel();

        $service = new ProductService($_context->dbBenjamit());
        $res = $service->createDatasProduct($modelReq,$modelRes);

        echo json_encode($res);
    }
    if($_POST['Controller'] == 'GetProducts')
    {
        $modelReq = new GetProductsRequestModel();
        $modelReq->Draw = 0;
        $modelReq->OrderColumn = 0;
        $modelReq->OrderDir = '';
        $modelReq->StartLimit = $_POST['Start'];
        $modelReq->LengthLimit = $_POST['Length'];
        $modelReq->ColumnName = '';
        $modelReq->SearchValue = $_POST['SearchValue'];
        
        $modelRes = new GetProductsResponseModel();

        $service = new ProductService($_context->dbBenjamit());
        $res = $service->createDatasProduct($modelReq,$modelRes);

        echo json_encode($res);
    }
    if($_POST['Controller'] == 'SwitchActiveStatus')
    {
        $modelReq = new SwitchActiveStatusProductRequestModel();
        $modelReq->Username = $_POST['Username'];
        $modelReq->Password = $_POST['Password'];
        $modelReq->IdProductName = $_POST['IdProductName'];
        $modelReq->ActiveStatus = $_POST['ActiveStatus'];

        $modelRes = new SwitchActiveStatusProductResponseModel();

        $service = new ProductService($_context->dbBenjamit());

        $res = $service->createUpdateActiveStatusProduct($modelReq,$modelRes);
        
        echo json_encode($res);
    }
    if($_POST['Controller'] == 'GetProductDetail')
    {
        $modelReq = new GetProductDetailRequestModel();
        $modelReq->Username = $_POST['Username'];
        $modelReq->Password = $_POST['Password'];
        $modelReq->IdProductName = $_POST['IdProductName'];

        $modelRes = new GetProductDetailResponseModel();

        $service = new ProductService($_context->dbBenjamit());

        $res = $service->createProductDetail($modelReq,$modelRes);

        echo json_encode($res);
    }
}
else
{
    echo 'Unknown method!';
}
?>
