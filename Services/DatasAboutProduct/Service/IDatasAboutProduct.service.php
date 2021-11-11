<?php
interface IDatasAboutProductService
{
    public function getUnitType(object $modelReq,object $modelRes) : object;
    public function createDataSimilarProductName(object $modelReq,object $modelRes) : object;
}
?>