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
        
        $resLastIdProName = $this->insertProductName($modelReq->ProductName,$modelReq->DetailAboutProduct,$memRes->Id);
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
        $modelRes->MessageDesc = "Success";
        
        return $modelRes;
    }

    public function createEditProduct(object $modelReq,object $modelRes) : object
    {
        return (object) ['datas' => 0];
    }

    private function insertProductName(string $valName,string $valDetail,int $idMember) : int|bool
    {
        try
        {
            $sqlStr = "INSERT INTO ProductName (`Name`,`DetailAboutProduct`,`IdMemberSave`,`SaveDate`) VALUES (?,?,?,NOW())";
            $this->_context->prepare($sqlStr)->execute([$valName,$valDetail,$idMember]);
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

    public function createDatasProduct(object $modelReq,object $modelRes) : object
    {

        $serviceConcat = new ConcatSqlService();

        $sqlOrderBy = $serviceConcat->sqlOrderBy($modelReq->ColumnName,$modelReq->OrderDir);
        $sqlLimit = $serviceConcat->sqlLimit($modelReq->StartLimit,$modelReq->LengthLimit);

        $resBarcodeSearch = $this->getIdProductNameSearchIdBarcode($modelReq->SearchValue);

        if($resBarcodeSearch == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getIdProductNameSearchIdBarcode Error';
            return $modelRes;
        }

        $resPro = $this->getAllProduct($modelReq->SearchValue,$sqlOrderBy,$sqlLimit,$resBarcodeSearch);

        if($resPro == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getAllProduct Error';
            return $modelRes;
        }
        
        $resProPrice = [];
        $resProImg = [];
        if(!empty($resPro))
        {
            $resProPrice = $this->getAllProductPriceDetailForMapPricesResponse($resPro);
            if($resProPrice == 'err')
            {
                $modelRes->Status = 500;
                $modelRes->MessageDesc = 'Method getAllProductPriceDetailForMapPriceResponse Error';
                return $modelRes;
            }

            $resProImg = $this->getAllProductPictureForMapImages($resPro);
            if($resProImg == 'err')
            {
                $modelRes->Status = 500;
                $modelRes->MessageDesc = 'Method getAllProductPictureForMapImages Error';
                return $modelRes;
            }
        }

        $resMap = $this->mapDatasProductResponse($modelRes,$resPro,$resProPrice,$resProImg);

        if($resMap == false)
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method mapDatasProduct Error';
            return $modelRes;
        }

        $resRowFull = $this->getFullRowProduct($modelReq->SearchValue,$resBarcodeSearch);

        if($resRowFull == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getFullRowProduct Error';
            return $modelRes;
        }

        $modelRes->RecordsTotal = $resRowFull;
        $modelRes->RecordsFiltered = $resRowFull;
        $modelRes->Draw = $modelReq->Draw;
        $modelRes->Status = 200;
        $modelRes->MessageDesc = 'Success';
        return $modelRes;
    }

    private function getAllProduct(
        string $valSearch = null,
        string $valOrderBy = null,
        string $valLimit = null,
        string $valIdProductName = null
        ) : array|string
    {
        try
        {
            $sqlOrderByStr = !empty($valOrderBy) ?
            $valOrderBy : "";

            $sqlLimitStr = !empty($valLimit) ?
            $valLimit : "";

            $sqlWhereIdPro = !empty($valIdProductName) ?
            " AND ProductName.Id = '$valIdProductName' " : "";

            if($sqlWhereIdPro != '') $valSearch = '';

            $sqlStr = "SELECT ProductName.Id,
            ProductName.`Name`,
            ProductName.ActiveStatus,
            ProductName.SaveDate,
            Member.UserId
            FROM ProductName
            LEFT JOIN Member ON (ProductName.IdMemberSave = Member.Id)
            WHERE (
                ProductName.Id LIKE '%$valSearch%' OR 
                ProductName.`Name` LIKE '%$valSearch%' OR 
                ProductName.SaveDate LIKE '%$valSearch%' OR  
                Member.UserId LIKE '%$valSearch%'
            )
            $sqlWhereIdPro 
            $sqlOrderByStr 
            $sqlLimitStr 
            ";

            $res = $this->_context->query($sqlStr)->fetchAll(2);
            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getFullRowProduct(string $valSearch = null,string $valIdProductName = null) : int|string
    {
        try
        {
            $sqlWhereIdPro = !empty($valIdProductName) ?
            " AND ProductName.Id = '$valIdProductName' " : "";

            if($sqlWhereIdPro != '') $valSearch = '';

            $sqlStr = "SELECT COUNT(ProductName.Id) AS RowFull 
            FROM ProductName
            LEFT JOIN Member ON (ProductName.IdMemberSave = Member.Id)
            WHERE (
                ProductName.Id LIKE '%$valSearch%' OR 
                ProductName.`Name` LIKE '%$valSearch%' OR 
                ProductName.SaveDate LIKE '%$valSearch%' OR  
                Member.UserId LIKE '%$valSearch%'
            ) $sqlWhereIdPro ";

            $res = $this->_context->query($sqlStr)->fetch(2);
            return $res['RowFull'];
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getAllProductPriceDetailForMapPricesResponse($params) : array|string
    {
        try
        {
            $multiVals = [];
            foreach($params as $datas)
            {
                array_push($multiVals,$datas['Id']);
            }
            $sqlIn = implode(',',$multiVals);
            $sqlStr = "SELECT UnitType.UnitName,
            ProductPrice.CostPrice,
            ProductPrice.SalePrice,
            ProductPrice.IdBarcode,
            ProductPrice.IdProductName
            FROM ProductPrice
            LEFT JOIN UnitType ON (ProductPrice.IdUnitType = UnitType.Id)
            WHERE ProductPrice.IdProductName IN ($sqlIn)
            ";
            $res = $this->_context->query($sqlStr)->fetchAll(2);
            $mapRes = [];
            foreach($res as $datas)
            {
                if(array_key_exists($datas['IdProductName'], $mapRes) == false)
                $mapRes[$datas['IdProductName']] = [];

                array_push($mapRes[$datas['IdProductName']],
                    array(
                        'UnitName' => $datas['UnitName'],
                        'CostPrice' => $datas['CostPrice'],
                        'SalePrice' => $datas['SalePrice'],
                        'IdBarcode' =>$datas['IdBarcode']
                    )
                );
            }
            return $mapRes;
            
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getAllProductPictureForMapImages(array $params) : array|string
    {
        try
        {
            $multiVals = [];
            
            foreach($params as $datas)
            {
                array_push($multiVals,$datas['Id']);
            }
            $sqlIn = implode(',',$multiVals);

            $sqlStr = "SELECT IdProductName,ImageFile FROM ProductPicture WHERE IdProductName IN ($sqlIn)";
            $res = $this->_context->query($sqlStr)->fetchAll(2);

            $mapRes = [];
            foreach($res as $datas)
            {
                if(array_key_exists($datas['IdProductName'], $mapRes) == false)
                $mapRes[$datas['IdProductName']] = [];

                array_push($mapRes[$datas['IdProductName']],
                    array(
                        'ImageFile' => $datas['ImageFile']
                    )
                );
            }
            return $mapRes;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getIdProductNameSearchIdBarcode(string $valIdBarcode) : int|string
    {
        try
        {
            $sqlStr = "SELECT IdProductName FROM ProductPrice WHERE IdBarcode != '' AND IdBarcode = '$valIdBarcode' ";
            $res = $this->_context->query($sqlStr)->fetch(2);
            // print_r($sqlStr);
            // exit();
            return !empty($res['IdProductName']) ? $res['IdProductName'] : '';
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function mapDatasProductResponse(object $modelRes,array $params,array $paramsPrice,array $paramsImg) : bool
    {
        try
        {
            foreach($params as $datas)
            {
                $row = $modelRes->arrayPushDatasList();
                $modelRes->Datas[$row]->Id = $datas['Id'];
                $modelRes->Datas[$row]->Name = $datas['Name'];
                $modelRes->Datas[$row]->Username = $datas['UserId'];
                $modelRes->Datas[$row]->ActiveStatus = $datas['ActiveStatus'];
                $modelRes->Datas[$row]->SaveDate = $datas['SaveDate'];

                if(!empty($paramsPrice[$datas['Id']]))
                {
                    foreach($paramsPrice[$datas['Id']] as $datasSub)
                    {
                        $rowSub = $modelRes->Datas[$row]->arrayPushPricesList();
                        $modelRes->Datas[$row]->Prices[$rowSub]->UnitName = $datasSub['UnitName'];
                        $modelRes->Datas[$row]->Prices[$rowSub]->SalePrice = $datasSub['SalePrice'];
                        $modelRes->Datas[$row]->Prices[$rowSub]->IdBarcode = $datasSub['IdBarcode'];
                    }
                }

                if(!empty($paramsImg[$datas['Id']]))
                {
                    foreach($paramsImg[$datas['Id']] as $datasSub)
                    {
                        $rowSub = $modelRes->Datas[$row]->arrayPushImagesList();
                        $modelRes->Datas[$row]->Images[$rowSub]->FileName = $datasSub['ImageFile'];
                    }
                }
            }
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function createUpdateActiveStatusProduct(object $modelReq,object $modelRes) : object
    {
        $memRes = SecuritySystemService::checkUser($this->_context,$modelReq->Username,$modelReq->Password,'1');

        if($memRes == false)
        {
            $modelRes->Status = 401;
            $modelRes->MessageDesc = 'ชื่อผู้ใช้งานและรหัสผ่านไม่ถูกต้อง หรือ สิทธ์ผู้ใช้งาน ไม่รับอนุณาต';
            return $modelRes;
        }

        $res = $this->updateProductName($modelReq->IdProductName,$modelReq->ActiveStatus,$memRes->Id);

        if($res != true)
        {
            $modelRes->MessageDesc = "อัพเดทสถานะใช้งานล้มเหลว!";
            $modelRes->Status = 400;
            return $modelRes;
        }

        $modelRes->MessageDesc = 'Success';
        $modelRes->Status = 200;
        return $modelRes;
    }

    private function updateProductName(int $valId,int $valStatus,$valIdMember,string $valText = null) : bool
    {
        try
        {
            $arrayList = [];
            $updateFilter = null;
            

            if(!empty($valText)) 
            {
                $arrayList['TextName'] = $valText;
                $updateFilter = ",`Name` = :TextName ";
            }

            $arrayList['IdMemberSave'] = $valIdMember;

            $sqlStr = "UPDATE ProductName SET ActiveStatus = $valStatus 
            $updateFilter,
            IdMemberSave = :IdMemberSave,SaveDate = NOW() 
            WHERE Id = $valId";
            return $this->_context->prepare($sqlStr)->execute($arrayList);
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function createProductDetail($modelReq,$modelRes) : object
    {
        $memRes = SecuritySystemService::checkUser($this->_context,$modelReq->Username,$modelReq->Password,'1');

        if($memRes == false)
        {
            $modelRes->Status = 401;
            $modelRes->MessageDesc = 'ชื่อผู้ใช้งานและรหัสผ่านไม่ถูกต้อง หรือ สิทธ์ผู้ใช้งาน ไม่รับอนุณาต';
            return $modelRes;
        }

        $resProName = $this->getProductName($modelReq->IdProductName);
        if($resProName == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getProductName Error';
            return $modelRes;
        }

        $resMember = $this->getMember($resProName['IdMemberSave']);
        if($resMember == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getMember Error';
            return $modelRes;
        }

        $resAllProductPicture = $this->getAllProductPicture($modelReq->IdProductName);
        if($resAllProductPicture == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getAllProductPicture Error';
            return $modelRes;
        }

        $resAllRelatedProduct = $this->getAllRelatedProduct($modelReq->IdProductName);
        if($resAllRelatedProduct == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getAllRelatedProduct Error';
            return $modelRes;
        }

        $resAllProductPrice = $this->getAllProductPrice($modelReq->IdProductName);
        if($resAllProductPrice == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method getAllProductPrice Error';
            return $modelRes;
        }

        $modelRes->Content->Id = $resProName['Id'];
        $modelRes->Content->ProductName = $resProName['Name'];
        $modelRes->Content->DetailAboutProduct = $resProName['DetailAboutProduct'];
        
        $modelRes->Content->Prices = [];
        foreach($resAllProductPrice as $datas)
        {
            $row = $modelRes->Content->arrayPushPricesList();
            $modelRes->Content->Prices[$row]->CostPrice = $datas['CostPrice'];
            $modelRes->Content->Prices[$row]->SalePrice = $datas['SalePrice'];
            $modelRes->Content->Prices[$row]->UnitName = $datas['UnitName'];
            $modelRes->Content->Prices[$row]->IdBarcode = $datas['IdBarcode'];
        }

        $modelRes->Content->Images = [];
        foreach($resAllProductPicture as $datas)
        {
            $row = $modelRes->Content->arrayPushImagesList();
            $modelRes->Content->Images[$row]->FileName = $datas['ImageFile'];
        }

        $modelRes->Content->RelatedProducts = [];
        foreach($resAllRelatedProduct as $datas)
        {
            $row = $modelRes->Content->arrayPushRelatedProductsList();
            $modelRes->Content->RelatedProducts[$row]->Name = $datas['Name'];
        }

        $modelRes->Content->ActiveStatus = $resProName['ActiveStatus'];
        $modelRes->Content->SaveDate = $resProName['SaveDate'];
        $modelRes->Content->Username = $resMember['UserId'];
        $modelRes->Content->UserSaveFullName = $resMember['NameTitle'].$resMember['FirstName']." ".$resMember['LastName'];
        $modelRes->Content->NickName = $resMember['NickName'];
        $modelRes->Status = 200;
        $modelRes->MessageDesc = 'Success';

        return $modelRes;
    }
    
    private function getProductName(int $valId) : array|string
    {
        try
        {
            $sqlStr = "SELECT 
            `Id`,
            `Name`,
            DetailAboutProduct,
            ActiveStatus,
            IdMemberSave,
            SaveDate
            FROM ProductName
            WHERE id = '$valId' ";

            $res = $this->_context->query($sqlStr)->fetch(2);

            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getMember(int $valId)
    {
        try
        {
            $sqlStr = "SELECT 
            `Member`.`Id`,
            `Member`.`IdTitle`,
            `Member`.`FirstName`,
            `Member`.`LastName`,
            `Member`.`NickName`,
            `Member`.`BirthDate`,
            `Member`.`UserId`,
            `Member`.`Password`,
            `Member`.`IdUserRights`,
            `Member`.`ActiveStatus`,
            `NameTitle`.`Name` AS NameTitle
            FROM `Member` 
            LEFT JOIN NameTitle ON (Member.IdTitle = NameTitle.Id)
            WHERE `Member`.`Id` = '$valId' ";

            $res = $this->_context->query($sqlStr)->fetch(2);

            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getAllProductPicture(int $valIdProductName) : array|string
    {
        try
        {
            $sqlStr = "SELECT 
            `Id`,
            `ImageFile`,
            `IdProductName`,
            `ActiveStatus`
            FROM `ProductPicture` 
            WHERE `IdProductName` = '$valIdProductName' ";

            $res = $this->_context->query($sqlStr)->fetchAll(2);

            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getAllRelatedProduct(int $valIdProductName) : array|string
    {
        try
        {
            $sqlStr = "SELECT `Id`,
            `Name`,
            `IdProductName`,
            `ActiveStatus` 
            FROM `ProductRelatedName` 
            WHERE `IdProductName` = ' $valIdProductName' ";

            $res = $this->_context->query($sqlStr)->fetchAll(2);

            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function getAllProductPrice(int $valIdProductName) : array|string
    {
        try
        {
            $sqlStr = "SELECT `ProductPrice`.`Id`,
            `ProductPrice`.`CostPrice`,
            `ProductPrice`.`SalePrice`,
            `ProductPrice`.`IdProductName`,
            `ProductPrice`.`IdUnitType`,
            `ProductPrice`.`IdBarcode`,
            `ProductPrice`.`ActiveStatus`,
            `UnitType`.`UnitName` 
            FROM `ProductPrice` 
            LEFT JOIN UnitType ON (`ProductPrice`.`IdUnitType` = `UnitType`.`Id`) 
            WHERE IdProductName = '$valIdProductName' ";

            $res = $this->_context->query($sqlStr)->fetchAll(2);

            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }
}
?>
