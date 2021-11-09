<?php
/*Model Req ยังไม่ใช้งาน อนาคต อาจจะมี วาดไว้ก่อน*/
class UnitTypeRequestModel
{
    public int $IdUnitType = 0;
}
class UnitTypeResponseModel
{
    public array $Datas = [];
    public int $Status = 0;
    public string $MessageDesc;

    public function arrayPushDatasList() : int
    {
        $dataNum = array_push($this->Datas,new DatasListResponse());

        return (int) $dataNum - (int) 1;
    }
}

class DatasListResponse
{
    public int $Id = 0;
    public string $Name;
}
?>