<?php
require('../../Lib/FPDF/V1.84/fpdf.php');

if(!isset($_GET['FileName']))
{
    echo "Method Get Name 'FileName' undefined";
    exit();
}
if(!isset($_GET['Username']))
{
    echo "Method Get Name 'Username' undefined";
    exit();
}
if(!isset($_GET['ImgBase64']))
{
    echo "Method Get Name 'ImgBase64' undefined";
    exit();
}

$fileName = $_GET['FileName'];

$username = base64_decode(str_replace(' ','+',$_GET['Username']));

$barcodeDatas = explode(',',$_GET['ImgBase64']);

$barcodeTypeDatas = explode('/',$barcodeDatas[0]);

$imgtypeDatas = explode(';',$barcodeTypeDatas[1]);

$imgDatas = str_replace(' ','+',$barcodeDatas[1]);

$imgName = '../../Tmp/ProductBarcodeImages/'.$username.$fileName.'.'.$imgtypeDatas[0];

file_put_contents($imgName,base64_decode($imgDatas));

$getImageDatas = getimagesize($imgName);
//คำนวณขนาดรูป จาก px เป็น pt
$getImageWidth = $getImageDatas[0] * 0.75;
$getImageHeight = $getImageDatas[1] * 0.75;
$pdf = new FPDF('L','pt',array($getImageWidth,$getImageHeight));
$pdf->setAutoPageBreak(false);

$pdf->AddPage();

$pdf->Image($imgName,0,0);
$pdf->Output();
unlink($imgName);
?>