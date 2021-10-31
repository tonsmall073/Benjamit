<?php
class LoginService
{
    public function __construct(
        private $_context
    )
    {}
    public function createDatas($modelReq,$modelRes) : object
    {
        $res = $this->checkDatas($modelReq->Username,$modelReq->Password);
        
        if($res == 'err')
        {
            $modelRes->MessageDesc = 'Method checkDatas Error';
            $modelRes->Status = 500;
            return $modelRes;
        }
        if(empty($res))
        {
            $modelRes->MessageDesc = 'Login Fail!';
            $modelRes->Status = 204;
            return $modelRes;
        }
        //$modelReq->Content->Id = $res['Id'];
        $modelRes->Content->User = $res['UserId'];
        $modelRes->Content->Pass = $res['Password'];
        $modelRes->Content->FullName = $res['FirstName'].' '.$res['LastName'];
        $modelRes->Content->NickName = $res['NickName'];
        $modelRes->MessageDesc = 'Success';
        $modelRes->Status = 200;
        return $modelRes;
    }
    private function checkDatas($valUser,$valPass) : array|string
    {
        try
        {
            $sqlStr = "SELECT * FROM Member WHERE UserId = '$valUser' AND `Password` = '$valPass' LIMIT 0,1";
            return $this->_context->query($sqlStr)->fetch(2);
        }
        catch(Exception $e)
        {
            return 'err';
        }
    }
}
?>