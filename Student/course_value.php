<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/GetSQLValueString.php'); ?>
<?php
if (isset($_GET['Season_Code'])) {
  $colname_Area = $_GET['Season_Code'];
}
if (isset($_GET['Com_ID'])) {
  $colname_Area2 = $_GET['Com_ID'];
}
if (isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>'') {
  
  $colname_Area3 = $_GET['Unit_ID'];
    
}
else{
	$colname_Area3 = '';
}
$today=date("Y-m-d");
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM course where course.Season_Code =%s and Com_ID like %s and Unit_ID like %s  ORDER BY Unit_ID ASC,Course_ID ASC",GetSQLValueString($colname_Area,"int"),GetSQLValueString($colname_Area2,"text"),GetSQLValueString($colname_Area3,"text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
echo '<option value="">請選擇課程...</option>';

if($totalRows_Cate>0){
	 do{?><option value="<?php echo $row_Cate['Course_ID'];?>"><?php $weekname=explode(",","一,二,三,四,五,六,日");
									   echo $row_Cate['Course_Name'];if($row_Cate['Course_Day1']<>""){echo "(".$row_Cate['Course_Day1'].")";}?></option><?php
	 }while($row_Cate = mysql_fetch_assoc($Cate));
 
}

?>


