<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教學進度表-瀏覽</title>
</head>
<style type="text/css">
.thcolor1{background-color:#4090cb; color:#FFFFFF;}
</style>
<?php 
if (isset($_GET['Course_ID'])) {
  $colname_ID = $_GET['Course_ID'];

mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT Course_Schedule FROM Course where Course_ID=%s", GetSQLValueString($colname_ID, "int"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
$Schedule=explode(",;,",$row_Cate['Course_Schedule']);
}else{
	$Schedule=array();
}
?>
<?php
if (isset($_GET['Course_Week'])) {
  $colname_Area = $_GET['Course_Week'];
}
echo   "<table border='1' style='border-style:solid;' cellpadding='5' cellspacing='0'>
<tr><th class='thcolor1'>週數</th><th width='70%' class='thcolor1'><font color='#FF0000'>*</font>內容(每周必須填寫)</th><th width='20%' class='thcolor1'>備註</th></tr>



";
$x=1;
$y=2;
for($rows=0;$rows<$colname_Area;$rows++){ 

    echo "<tr>
	      <td nowrap='nowrap'>
		  第".($rows+1)."週
		 
		  </td>
		  <td>
		  ".@$Schedule[$x]."
		  </td>
		  <td>
		  ".@$Schedule[$y]."
		
		  </td>
		  </tr>   ";
		  
$x=$x+3;
$y=$y+3;
   
}
echo "</table>";



?>
