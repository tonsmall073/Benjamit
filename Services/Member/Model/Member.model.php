<?php
class MemberDataTableServerSideScriptRequestModel
{
    public int $LimitStart = 0;
    public int $LimitLength = 0;
}
class MemberDataTableServerSideScriptResponseModel
{
    public array $Datas = [];
    public int $Status = 0;
    public function arrPushDatasList() : int
    {
        $amountArrList = array_push($this->Datas,new MemberDatasListResponse());
        $positionArr = (int) $amountArrList - (int) 1;
        return $positionArr;
    }
}
Class MemberDatasListResponse
{
    public string $FullName;
    public string $NickName;
    public string $BirthDate;
    public string $Year;
    public string $ActiveStatus;
    public string $UserRights;
}
?>