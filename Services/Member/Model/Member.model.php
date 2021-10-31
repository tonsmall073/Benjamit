<?php
class MemberDataTableServerSideScriptRequestModel
{
    public int $LimitStart = 0;
    public int $LimitLength = 0;
}
class MemberDataTableServerSideScriptResponseModel
{
    public array $Datas = array();
    public int $Status = 0;
    public function arrPushDatasList()
    {
        $amountArrList = array_push($this->Datas,new MemberDatasList());
        $positionArr = (int) $amountArrList - (int) 1;
        return $positionArr;
    }
}
Class MemberDatasList
{
    public string $FullName;
    public string $NickName;
    public string $BirthDate;
    public string $Year;
    public string $ActiveStatus;
    public string $UserRights;
}
?>