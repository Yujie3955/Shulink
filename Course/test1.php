<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<?php
include_once ('global.php');
if (isset($_POST["send"])) {
$leadExcel=$_POST["leadExcel"];;

if($leadExcel == "true"){

//獲取上傳的文件名
$filename = $_FILES['inputExcel']['name'];

//上傳到服務器上的臨時文件名
$tmp_name = $_FILES['inputExcel']['tmp_name'];
$msg = uploadFile($filename,$tmp_name);
}
}
if (isset($_POST["clear"])) {
$sql = "TRUNCATE TABLE net_mailuser";
if(!mysql_query($sql)){
return false;
}
echo '<script type=\'text/javascript\'>alert(\'電子報會員資料已清空！\');window.location=\'test1.php\';</script>';
}
?>

<form name="form2" method="post" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
<input type="hidden" name="leadExcel" value="true">
<table align="center" width="90%" border="0">
<tr>
<td>
<input type="file" name="inputExcel"><input type="submit" value="上傳" name="send">
</td>
</tr>

</table>
</form>


<? $filePath = '../../UpLoad/Score/';
//導入Excel文件
function uploadFile($file,$filetempname) {
//自己設置的上傳文件存放路徑

$str = ""; 
echo $filePath;
require_once("IOFactory.php");

$filename=explode(".",$file);//把上傳的文件名以「.」好為準做一個數組。
$time=date("y-m-d");//去當前上傳的時間
//取文件名t替換
$name=implode(".",$filename); //上傳後的文件名
$uploadfile=$filePath.$name;//上傳後的文件名地址

//move_uploaded_file() 函數將上傳的文件移動到新位置。若成功，則返回 true，否則返回 false。
$result=move_uploaded_file($filetempname,$uploadfile);//假如上傳到當前目錄下
if($result) { //如果上傳文件成功，就執行導入excel操作

$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$highestRow = $sheet->getHighestRow(); // 取得總行數
$highestColumn = $sheet->getHighestColumn(); // 取得總列數


//循環讀取excel文件,讀取一條,插入一條
for($j=2;$j<=$highestRow;$j++){
for($k='A';$k<=$highestColumn;$k++){
$str = $str.$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().';'; 


//讀取單元格

//explode:函數把字符串分割為數組。
$strs = explode(";",$str);
$sql = "INSERT INTO score(Score_Competition,Score_Year,Score_Event,Score_Type,Score_Group,Score_Unit,Score_PlayerName,Score_PlayerSex,Score_CoachName,Score_Score,Score_Place,Score_Hint,Score_Break)VALUES('$strs[0]','$strs[1]','$strs[2]','$strs[3]','$strs[4]','$strs[5]','$strs[6]','$strs[7]','$strs[8]','$strs[9]','$strs[10]','$strs[11]','$strs[12]')";
}

if(!mysql_query($sql)){
return false;
}

$str = "";
}

//unlink($uploadfile); //刪除上傳的excel文件
echo '<script type=\'text/javascript\'>alert(\'匯入完成！\');window.location=\'test1.php\';</script>';
}else{
echo '<script type=\'text/javascript\'>alert(\'匯入失敗！\');window.location=\'../../Module/Score/AD_Import.php\';</script>';

}
}
?>
</body>
</html>