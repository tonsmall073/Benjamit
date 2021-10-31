<?php
session_start();

require('Login.vendor.php');

$_context = new ConnectDatabases();

if($_POST['Controller'] == 'CheckLogin')
{
    $modelReq = new LoginRequestModel();
    $modelReq->Username = $_POST['Username'];
    $modelReq->Password = $_POST['Password'];
    $modelRes = new LoginResponseModel();
    $service = new LoginService($_context->dbBenjamit());
    $res = $service->createDatas($modelReq,$modelRes);
    if($res->Status == 200)
    {
        $_SESSION['Username'] = $res->Content->User;
        $_SESSION['Password'] = $res->Content->Pass;
        $_SESSION['FullName'] = $res->Content->FullName;
        $_SESSION['NickName'] = $res->Content->NickName;
    }
    else
    {
        session_destroy();
    }
    echo json_encode($res);
}
?>