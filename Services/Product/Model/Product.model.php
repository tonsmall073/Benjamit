<?php
class AddProductRequestModel
{
    public string $Username;
    public string $Password;
    public string $ProductName;
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

class GetProductsForDataTableRequestModel
{
    public int $Draw = 0;
    public int $OrderColumn = 0;
    public string $OrderDir;
    public int $StartLimit = 0;
    public int $LengthLimit = 0;
    public string $ColumnName;
    public string $SearchValue;
}
class GetProductsForDataTableResponseModel
{
    public array $Datas = [];
    public int $RecordsTotal = 0;
    public int $RecordsFiltered = 0;
    public int $Draw = 0;
    public string $MessageDesc;
    public int $Status = 0;

    public function arrayPushDatasList()
    {
        $count = array_push($this->Datas,new DatasListForDataTableResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }
}

class DatasListForDataTableResponse
{
    public string $Id;
    public string $Name;
    public array $Price = [];
    public int $ActiveStatus = 0;
    public string $SaveDate;
    public string $Username;

    public function arrayPushPriceList()
    {
        $count = array_push($this->Price,new PriceListForDataTableResponse());
        $row = (int) $count - (int) 1;
        return $row;
    }
}

class PriceListForDataTableResponse
{
    public float $SalePrice;
    public string $UnitName;
    public string $IdBarcode;
}

class switchActiveStatusProductRequestModel
{
    public string $Username;
    public string $Password;
    public int $IdProductName;
    public int $ActiveStatus;
}

class switchActiveStatusProductResponseModel
{
    public string $MessageDesc;
    public int $Status;
}
?>