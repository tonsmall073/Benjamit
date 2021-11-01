<?php
require('Member.vendor.php');
$_context = new ConnectDatabases(true);
$_POST['Controller'] = 'GetDtServerSideMemberAll';
if($_POST['Controller'] == 'GetDtServerSideMemberAll')
{
    
    $modelReq = new MemberDataTableServerSideScriptRequestModel();
    $modelRes = new MemberDataTableServerSideScriptResponseModel();
    $modelReq->LimitStart = 0;
    $modelReq->LimitLength = 10;
    $service = new MemberService($_context->dbBenjamit());
    $res = $service->createDatasMemberForDataTableServerSideScript($modelReq,$modelRes);
    echo json_encode($res);
}
?>