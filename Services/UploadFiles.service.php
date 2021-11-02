<?php 
class UploadFilesService
{
    public function dataTypeBase64MakeToFile($dataBase64,$pathUploadFile,$fileName) : string|bool
    {
        $typeAndData = explode(',',$dataBase64);
        if(count($typeAndData) == 2) return false;
        
        $typeAndExtension = explode('/',$typeAndData[0]);
        if(count($typeAndExtension) == 2) return false;

        $fileNameExtensionAndEnBase = explode(';',$typeAndExtension[1]);
        if(count($fileNameExtensionAndEnBase) == 2) return false;

        $pathUpload = $pathUploadFile.$fileName.".".$fileNameExtensionAndEnBase[0];
        $res = @file_put_contents($pathUpload,base64_decode($typeAndData[1]),0);

        $fileSuccess = "$fileName.$fileNameExtensionAndEnBase[0]";
        if($res)
        {
            return $fileSuccess;
        }
        else
        {
            return false;
        }
    }
}
?>