<?php
class AddProductRequestModel
{
    public string $Username;
    public string $Password;
    public string $ProductName;
    public array $ProductPicture;
    public array $ProductRelated;
    public array $ProductPrice;

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
    public String $MessageDesc;
}
?>