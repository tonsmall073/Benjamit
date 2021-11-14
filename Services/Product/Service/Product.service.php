<?php
class ProductService
{
    public function __construct(
        private object $_context
    )
    {}

    public function createAddProduct(object $modelReq,object $modelRes) : object
    {
        $memRes = SecuritySystemService::checkUser($this->_context,$modelReq->Username,$modelReq->Password,'1');

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
        
        $resLastIdProName = $this->insertProductName($modelReq->ProductName,$memRes->Id);
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

        if(empty($modelReq->ProductPrice))
        {
            $this->_context->rollBack();
            $modelRes->Status = 400;
            $modelRes->MessageDesc = 'ไม่มีการกำหนดราคาสินค้ากรุณากำหนดราคาสินค้าด้วยครับ';
            return $modelRes;
        }

        $chkIdBarcode = $this->checkHaveIdBarCodeMulti($modelReq->ProductPrice);

        if($chkIdBarcode == 'err')
        {
            $this->_context->rollBack();
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method checkHaveIdBarCodeMulti Error';
            return $modelRes;
        }

        if(!empty($chkIdBarcode))
        {
            $strIdBar = '';
            $this->_context->rollBack();
            $modelRes->Status = 409;
            foreach($chkIdBarcode as $datas) $strIdBar .= " $datas[IdBarcode]";
            $modelRes->MessageDesc = 'เลข IdBarcode ซํ้ากัน'.$strIdBar;
            return $modelRes;
        }
            
        $resLastIdProPrice = $this->getLastIdProductPrice();

        if($resLastIdProPrice == 'err')
        {
            $this->_context->rollBack();
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getLastIdProductPrice Error';
            return $modelRes; 
        }

        $resProPrice = $this->insertProductPrice($modelReq->ProductPrice,$memRes->Id,$resLastIdProName,$resLastIdProPrice);

        if($resProPrice == false)
        {
            $this->_context->rollBack();
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method insertProductPrice Error';
            return $modelRes;
        }
        
        $serviceUpFiles = new UploadFilesService();
        $pictureList = [];
        $filePath = '../../Assets/Images/Products/';
        
        foreach($modelReq->ProductPicture as $obj)
        {
            $resImg = $serviceUpFiles
            ->dataTypeBase64MakeToFile($obj->FileToBase64,$filePath,
            $memRes->Id.$resLastIdProName.$resLastIdProPrice.date('Ymd').count($pictureList));
            array_push($pictureList,$resImg);
        }

        if(in_array(false,$pictureList) == true)
        {
            $this->_context->rollBack();
            $serviceUpFiles->deleteFilesMulti($filePath,$pictureList);
            $modelRes->Status = 400;
            $modelRes->MessageDesc = 'คำขอในการอัพโหลดไฟล์ไม่สมบูรณ์ หรือ ไฟล์บางตัวเป็นไฟล์เสีย';
            return $modelRes;
        }

        //ไม่ต้องใส่รูปสินค้ามาก็ได้
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

    public function createEditProduct(object $modelReq,object $modelRes) : object
    {
        return (object) ['datas' => 0];
    }

    private function insertProductName(string $valName,int $idMember) : int|bool
    {
        try
        {
            $sqlStr = "INSERT INTO ProductName (`Name`,`IdMemberSave`,`SaveDate`) VALUES (?,?,NOW())";
            $this->_context->prepare($sqlStr)->execute([$valName,$idMember]);
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

    private  function getLastIdProductPrice() : int|string
    {
        try
        {
            $sqlStr = "SELECT ProductPrice.Id FROM ProductPrice 
            ORDER BY ProductPrice.Id DESC LIMIT 0,1";
            $res = $this->_context->query($sqlStr)->fetch(2);

            return !empty($res['Id']) ? $res['Id'] : 0;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }
    private function insertProductPrice(array $params,int $idMember,int $lastIdProduct,int $lastIdProPrice) : bool|int
    {
        try
        {
            $questionMarks = [];
            $multiVals = [];
            $genIdBarcode = null;
            foreach($params as $obj)
            {
                array_push($questionMarks,'(?,?,?,?,?)');
                array_push($multiVals,$obj->CostPrice);
                array_push($multiVals,$obj->SalePrice);
                array_push($multiVals,$lastIdProduct);
                array_push($multiVals,$obj->IdUnitType);

                $genIdBarcode = $obj->IdBarcode != '' && $obj->IdBarcode != 'AutoIdBarcode' ? 
                $obj->IdBarcode : $idMember.$lastIdProduct.$lastIdProPrice.date('Ymd').count($questionMarks);

                array_push($multiVals,$genIdBarcode);
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
    
    private function checkHaveIdBarCodeMulti($params) : array|string
    {
        try
        {
            $multiVals = [];
            foreach($params as $obj)
            {
                array_push($multiVals,"'$obj->IdBarcode'");
            }

            $sqlIn = implode(',',$multiVals);

            $sqlStr = "SELECT IdBarcode FROM ProductPrice WHERE IdBarcode IN ($sqlIn)";
            $res = $this->_context->query($sqlStr)->fetchAll(2);

            return $res;
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
            return 'err';
        }
    }
}
?>
