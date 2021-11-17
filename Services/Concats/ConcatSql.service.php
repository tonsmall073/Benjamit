<?php 
class ConcatSqlService
{

    public function sqlOrderBy(string $valColumnName,string $valOrderDir) : string
    {
        return !empty($valColumnName) && !empty($valOrderDir) ?
        "ORDER BY $valColumnName $valOrderDir" : "";
    }

    public function sqlLimit(int $valStart,int $valLength) : string
    {
        return "LIMIT ".$valStart.",".$valLength;
    }
    
}
?>