<?php
class DatasAboutProductService
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
}
?>