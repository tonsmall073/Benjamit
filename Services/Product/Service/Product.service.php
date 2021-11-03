<?php
class ProductService
{
    public function __construct(
        private object $_context
    )
    {}
    public function createAddProduct(object $modelReq,object $modelRes) : object
    {
        $memRes = SecuritySystemService::checkUser($modelReq->Username,$modelReq->Password,'1');

        if($memRes == false)
        {
            $modelRes->Status = 401;
            $modelRes->MessageDesc = 'ชื่อผู้ใช้งานและรหัสผ่านไม่ถูกต้อง หรือ สิทธ์ผู้ใช้งาน ไม่รับอนุณาต';
            return $modelRes;
        }
        if($modelReq->ProductName == '')
        {
            $modelRes->Status = 400;
            $modelRes->MessageDesc = 'กำหนดชื่อสินค้าเป็นค่าว่าง กรุณาป้อนข้อมูลชื่อสินค้า';
            return $modelRes;
        }

        $this->_context->beginTransaction();
        
        $resLastIdProName = $this->insertProductName($modelReq->ProductName);
        if($resLastIdProName == false)
        {
            $this->_context->rollBack();
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method insertProductName Error';
            return $modelRes;
        }

        if(!empty($modelReq->ProductRelated))
        {
            $resProRel = $this->insertProductRelatedName($modelReq->ProductRelated,$resLastIdProName);

            if($resProRel == false)
            {
                $this->_context->rollBack();
                $modelRes->Status = 500;
                $modelRes->MessageDesc = 'Method insertProductRelatedName Error';
                return $modelRes;
            }
        }

        if(!empty($modelReq->ProductPrice))
        {
            $resProPrice = $this->insertProductPrice($modelReq->ProductPrice,$resLastIdProName);

            if($resProPrice == false)
            {
                $this->_context->rollBack();
                $modelRes->Status = 500;
                $modelRes->MessageDesc = 'Method insertProductPrice Error';
                return $modelRes;
            }
        }

        $serviceUpFiles = new UploadFilesService();
        $pictureList = [];
        $filePath = '../../../../Assets/Images/Products/';
        
        foreach($modelReq->ProductPicture as $obj)
        {
            $resImg = $serviceUpFiles
            ->dataTypeBase64MakeToFile($obj->FileToBase64,$filePath,
            $memRes->Id.$resLastIdProName.date('YmdHis').count($pictureList));
            array_push($pictureList,$resImg);
        }

        if(!empty($pictureList))
        {
            $resProPict = $this->insertProductPicture($pictureList,$resLastIdProName);
        
            if($resProPict == false)
            {
                $this->_context->rollBack();
                $serviceUpFiles->deleteFilesMulti($filePath,$pictureList);
                $modelRes->Status = 500;
                $modelRes->MessageDesc = 'Method insertProductName Error';
                return $modelRes;
            }
        }

        $this->_context->commit();
        $modelRes->Status = 200;
        $modelRes->MessageDesc = "ข้อมูลสินค้า $modelReq->ProductName ได้ถูกบันทึกแล้วครับ";
        
        return $modelRes;
    }
    public function createEditProduct(object $modelReq,object $modelRes)
    {

    }
    private function insertProductName(string $valName) : int|bool
    {
        try
        {
            $sqlStr = "INSERT INTO ProductName (`Name`) VALUES (?)";
            $this->_context->prepare($sqlStr)->execute([$valName]);
            return $this->_context->lastInsertId();
        }
        catch(Exception $e)
        {
            return false;
        }

    }
    private function insertProductPicture(array $params,int $lastIdProduct) : bool|int
    {
        try
        {
            $questionMarks = [];
            $multiVals = [];
            foreach($params as $value)
            {
                array_push($questionMarks,'(?,?)');
                array_push($multiVals,$value);
                array_push($multiVals,$lastIdProduct);
            }

            $questionMarkSqlStr = implode(',',$questionMarks);

            $sqlStr = "INSERT INTO ProductPicture (ImageFile,IdProductName) VALUES $questionMarkSqlStr";
            return $this->_context->prepare($sqlStr)->execute($multiVals);
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    private function insertProductRelatedName(array $params,int $lastIdProduct) : bool|int
    {
        try
        {
            $questionMarks = [];
            $multiVals = [];
            foreach($params as $obj)
            {
                array_push($questionMarks,'(?,?)');
                array_push($multiVals,$obj->Name);
                array_push($multiVals,$lastIdProduct);
            }

            $questionMarkSqlStr = implode(',',$questionMarks);

            $sqlStr = "INSERT INTO ProductRelatedName (`Name`,IdProductName) VALUES $questionMarkSqlStr";
            return $this->_context->prepare($sqlStr)->execute($multiVals);
        }
        catch(Exception $e)
        {
            return false;
        }
    }
    private function insertProductPrice(array $params,int $lastIdProduct) : bool|int
    {
        try
        {
            $questionMarks = [];
            $multiVals = [];
            foreach($params as $obj)
            {
                array_push($questionMarks,'(?,?,?,?,?)');
                array_push($multiVals,$obj->CostPrice);
                array_push($multiVals,$obj->SalePrice);
                array_push($multiVals,$obj->lastIdProduct);
                array_push($multiVals,$obj->IdUnitType);
                array_push($multiVals,$obj->IdBarcode);
            }

            $questionMarkSqlStr = implode(',',$questionMarks);

            $sqlStr = "INSERT INTO ProductPrice (CostPrice,SalePrice,IdProductName,IdUnitType,IdBarcode) VALUES $questionMarkSqlStr";
            return $this->_context->prepare($sqlStr)->execute($multiVals);
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}
?>
