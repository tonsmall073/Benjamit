<?php
    class MemberService
    {
        public function __construct(
            private $_context
        )
        {}
        public function createDatasMemberForDataTableServerSideScript($modelReq,$modelRes)
        {
            $datas = $this->getMember('',$modelReq->LimitStart,$modelReq->LimitLength);
            if($datas == 'err')
            {
                $modelRes->MessageDesc = 'Method getMember Error';
                $modelRes->Status = 500;
                return $modelRes;
            }
            $map = $this->mapModelDatasListResponse($datas,$modelRes);
            if($map == 'err')
            {
                $modelRes->MessageDesc = 'Method mapModelDatasListResponse Failed';
                $modelRes->Status = 500;
                return $modelRes;
            }
            $modelRes->MessageDesc = 'Success';
            $modelRes->Status = 200;
            return $modelRes;
        }
        private function getMember($valSearch = null,$valLimitStart = 0,$valLimitLength = 0)
        {
            try
            {
                $limit = !empty($valLimitStart) ? " LIMIT $valLimitStart,$valLimitLength " : "";
                if(!empty($valSearch))
                {
                    $search = " AND (Member.FirstName LIKE %$valSearch%
                    OR Member.LastName
                    OR Member.NickName)";
                }
                else
                {
                    $search = null;
                }
                $str = "SELECT * FROM Member WHERE Member.Id != '' $search $limit";
                return $this->_context->query($str)->fetchAll(2);
            }
            catch(Exception $e)
            {
                return 'err';
            }
        }
        private function mapModelDatasListResponse($params,$modelRes)
        {
            try
            {
                $convert = new ConvertPersonAgeService();
                foreach($params as $datas)
                {
                    $row = $modelRes->arrPushDatasList();
                    $modelRes->Datas[$row]->FullName = $datas['FirstName']." ".$datas['LastName'];
                    $modelRes->Datas[$row]->NickName = $datas['NickName'];
                    $modelRes->Datas[$row]->BirthDate = $datas['BirthDate'];
                    $modelRes->Datas[$row]->Year = $convert->ceBirthDateToCurrenYear($datas['BirthDate']);
                    $modelRes->Datas[$row]->ActiveStatus = $datas['ActiveStatus'];
                    $modelRes->Datas[$row]->UserRights = $datas['UserRights'];
                }
                return 'success';
            }
            catch(Exception $e)
            {
                return 'err';
            }
        }
    }
?>