<?php
class DatasAboutProductService implements IDatasAboutProductService
{
    public function __construct(
        private $_context
    )
    {}
    public function getUnitType(object $modelReq,object $modelRes) : object
    {
        $resUnitType = $this->fetchAllUnitType();

        if($resUnitType == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->Message = 'Method fetchAllUnitType Error!';
            return $modelRes;
        }
        if(empty($resUnitType))
        {
            $modelRes->Status = 204;
            $modelRes->Message = 'ไม่พบข้อมูลประเภทหน่วยครับ!';
            return $modelRes;
        }

        $mapUnitType = $this->mapDatasListUnitTypeRes($modelRes,$resUnitType);

        if($mapUnitType == false)
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method mapDatasListUnitTypeRes Error!';
            return $modelRes;
        }

        $modelRes->Status = 200;
        $modelRes->Message = 'Success';
        return $modelRes;
    }

    public function createDataSimilarProductName(object $modelReq,object $modelRes) : object
    {
        $getProName = $this->fetchAllProductName($modelReq->ProductName);

        if($getProName == 'err')
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method fetchAllProductName Error';
            return $modelRes;
        }

        if(empty($getProName))
        {
            $modelRes->Status = 204;
            $modelRes->MessageDesc = 'No Content';
            return $modelRes;
        }

        $mapProNameRes = $this->mapDatasListSimilarProductNameRes($modelRes,$getProName);

        if($mapProNameRes == false)
        {
            $modelRes->Status = 500;
            $modelRes->MessageDesc = 'Method mapDatasListSimilarProductNameRes Error';
            return $modelRes;
        }

        $modelRes->Status = 200;
        $modelRes->MessageDesc = 'Success';
        return $modelRes;
    }

    private function fetchAllProductName($valSearch = null) : array|string
    {
        try
        {
            $sqlSearch = $valSearch != null ? " AND ProductName.`Name` LIKE '%$valSearch%' " : "";
            $sqlStr = "SELECT ProductName.`Id`,ProductName.`Name` FROM ProductName 
            WHERE ProductName.ActiveStatus = 0 $sqlSearch ";

            $res = $this->_context->query($sqlStr)->fetchAll(2);
            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function fetchAllUnitType() : array|string
    {
        try
        {
            $sqlStr = "SELECT Id,UnitName FROM UnitType WHERE ActiveStatus = 0";
            $res = $this->_context->query($sqlStr)->fetchAll(2);
            return $res;
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }

    private function mapDatasListUnitTypeRes(object $modelRes,array $params) : bool
    {
        try
        {
            foreach($params as $datas)
            {
                $row = $modelRes->arrayPushDatasList();
                $modelRes->Datas[$row]->Id = $datas['Id'];
                $modelRes->Datas[$row]->Name = $datas['UnitName'];
            }
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    private function mapDatasListSimilarProductNameRes(object $modelRes,array $params) : bool
    {
        try
        {
            foreach($params as $datas)
            {
                $row = $modelRes->arrayPushDatasList();
                $modelRes->Datas[$row]->Id = $datas['Id'];
                $modelRes->Datas[$row]->Name = $datas['Name'];
            }
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }
}
?>
