<?php 
class UploadFilesService
{
    public function dataTypeBase64MakeToFile($dataBase64,$pathUploadFile,$fileName) : string|bool
    {
        $typeAndData = explode(',',$dataBase64);
        if(count($typeAndData) != 2) return false;
        
        $typeAndExtension = explode('/',$typeAndData[0]);
        if(count($typeAndExtension) != 2) return false;

        $fileNameExtensionAndEnBase = explode(';',$typeAndExtension[1]);
        if(count($fileNameExtensionAndEnBase) != 2) return false;

        $pathUpload = $pathUploadFile.$fileName.".".$fileNameExtensionAndEnBase[0];
        $resUpload = @file_put_contents($pathUpload,base64_decode($typeAndData[1]),0);
        
        $res = "$fileName.$fileNameExtensionAndEnBase[0]";
        if($resUpload)
        {
            return $res;
        }
        else
        {
            return false;
        }
    }

    public function deleteFilesMulti($filePath,$paramFiles) : array
    {
        $resList = [];
        $delFileName = null;
        foreach($paramFiles as $datafileName)
        {
            $delFileName = $filePath.$datafileName;
            array_push($resList,@unlink($delFileName));
        }
        return $resList;
    }
}
?>
