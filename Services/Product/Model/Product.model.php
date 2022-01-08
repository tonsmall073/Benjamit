<?php
class AddProductRequestModel
{
    public string $Username;
    public string $Password;
    public string $ProductName;
    public string $DetailAboutProduct;
    public array $ProductPicture = [];
    public array $ProductRelated = [];
    public array $ProductPrice = [];

    public function arrayPushProductPictureList() : int
    {
        $amountArrList = array_push(
            $this->ProductPicture,
            new ProductPictureListRequest()
        );
        $positionArr = (int) $amountArrList - (int) 1;
        return $positionArr;
    }

    public function arrayPushProductRelatedList() : int
    {
        $amountArrList = array_push(
            $this->ProductRelated,
            new ProductRelatedListRequest()
        );
        $positionArr = (int) $amountArrList - (int) 1;
        return $positionArr;
    }

    public function arrayPushProductPriceList() : int
    {
        $amountArrList = array_push($this->ProductPrice,
        new ProductPriceListRequest()
    );
        $positionArr = (int) $amountArrList - (int) 1;
        return $positionArr;
    }
}

class ProductPictureListRequest
{
    public string $FileToBase64;
    // public string $FileName;
    // public string $FileTmp;
    // public int $FileSize = 0;
    // public string $FileType;
    // public int $FileError = 0;
}

class ProductRelatedListRequest
{
    public string $Name;
}

class ProductPriceListRequest
{
    public float $CostPrice = 0;
    public float $SalePrice = 0;
    public int $IdUnitType = 0;
    public string $IdBarcode;
}

class AddProductResponseModel
{
    public int $Status = 0;
    public string $MessageDesc;
}

class GetProductsRequestModel
{
    public int $Draw = 0;
    public int $OrderColumn = 0;
    public string $OrderDir;
    public int $StartLimit = 0;
    public int $LengthLimit = 0;
    public string $ColumnName;
    public string $SearchValue;
}
class GetProductsResponseModel
{
    public array $Datas = [];
    public int $RecordsTotal = 0;
    public int $RecordsFiltered = 0;
    public int $Draw = 0;
    public string $MessageDesc;
    public int $Status = 0;

    public function arrayPushDatasList()
    {
        $count = array_push($this->Datas,new DatasListResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }
}

class DatasListResponse
{
    public string $Id;
    public string $Name;
    public array $Prices = [];
    public array $Images = [];
    public int $ActiveStatus = 0;
    public string $SaveDate;
    public string $Username;
    

    public function arrayPushPricesList()
    {
        $count = array_push($this->Prices,new PricesListResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }

    public function arrayPushImagesList()
    {
        $count = array_push($this->Images,new ImagesListResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }
}

class PricesListResponse
{
    public float $SalePrice = 0;
    public int $IdUnitName = 0;
    public string $UnitName;
    public string $IdBarcode;
    public float $CostPrice = 0;
}

class ImagesListResponse
{
    public string $FileName;
}

class RelatedProductsListResponse
{
    public string $Name;
}

class SwitchActiveStatusProductRequestModel
{
    public string $Username;
    public string $Password;
    public int $IdProductName;
    public int $ActiveStatus;
}

class SwitchActiveStatusProductResponseModel
{
    public string $MessageDesc;
    public int $Status = 0;
}

class GetProductDetailRequestModel
{
    public string $Username;
    public string $Password;
    public int $IdProductName;
}
class GetProductDetailResponseModel
{
    public object $Content;
    public string $MessageDesc;
    public int $Status = 0;

    public function __construct()
    {
        $this->Content = new ContentListResponse();
    }
}

class ContentListResponse
{
    public string $Id;
    public string $ProductName;
    public string $DetailAboutProduct;
    public array $Prices = [];
    public array $Images = [];
    public int $ActiveStatus = 0;
    public string $SaveDate;
    public string $Username;
    public string $UserSaveFullName;
    public string $NickName;
    public array $RelatedProducts = [];

    public function arrayPushPricesList()
    {
        $count = array_push($this->Prices,new PricesListResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }

    public function arrayPushImagesList()
    {
        $count = array_push($this->Images,new ImagesListResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }

    public function arrayPushRelatedProductsList()
    {
        $count = array_push($this->RelatedProducts,new RelatedProductsListResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }
}
?>