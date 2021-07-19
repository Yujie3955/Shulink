<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>教學進度表-修改</title>
</head>
<style type="text/css">
.thcolor1{background-color:#4090cb; color:#FFFFFF;}
</style>
<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>
<!--日期INPUT ED-->
<?php 

if (isset($_GET['Course_ID'])) {
  $colname_ID = $_GET['Course_ID'];

mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT Course_Schedule FROM Course where Course_ID=%s", GetSQLValueString($colname_ID, "int"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$Schedule=explode(",;,",$row_Cate['Course_Schedule']);
}
else{$Schedule=array();}


?>
<?php
if (isset($_GET['Course_Week'])) {
  $colname_Area = $_GET['Course_Week'];
}
echo   "<table border='1' style='border-style:solid;' cellpadding='5' cellspacing='0'>
<tr><th class='thcolor1'>週數</th><th width='20%' class='thcolor1'><font color='#FF0000'>*</font>日期</th><th width='70%' class='thcolor1'><font color='#FF0000'>*</font>內容(每周必須填寫)</th></tr>



";
$x=1;
$y=2;
for($rows=0;$rows<$colname_Area;$rows++){ 

    echo "<tr class='content_rows' id='rows_".($rows+1)."'>
	      <td nowrap='nowrap'>
		  第".($rows+1)."週
		  <input type='hidden' name='Course_Schedule[]' value='第".($rows+1)."週' size='3'  required>
		  </td>
		  <td>
		  <div class='DateStyle".($rows+1)."'>
                            <div class='input-group date picker_date' >
                            <input type='text' name='Course_Schedule[]' value='".@$Schedule[$x]."'  data-format='yyyy/MM/dd' class='form-control' required/>
                            <span class='input-group-addon'>
                                <span class='glyphicon glyphicon-calendar'></span>
                            </span>
                            </div>
                  </div>
		  
		
		  </td>
		  <td>
		  <input type='text' name='Course_Schedule[]' style='width:100%;' value='".@$Schedule[$y]."' required>
		  </td>
		  
		  </tr>   ";
?>

<?php 		  
$x=$x+3;
$y=$y+3;
   
}
echo "</table>";



?>

