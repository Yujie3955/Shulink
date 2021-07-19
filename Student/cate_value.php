<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php
echo "<option value=''>請選擇區域...</option>";
if (isset($_GET['County_Cate'])) {
  $colname_Area = $_GET['County_Cate'];
}
else {$colname_Area="";}
if (isset($_GET['County_Name'])) {
  $colname_Name = $_GET['County_Name'];
}else{$colname_Name="%";}
if($colname_Area<>""){
	mysql_select_db($database_dbline, $dbline);
	$query_Area = sprintf("SELECT * FROM area where County_Cate Like %s order by County_ID ASC", GetSQLValueString("%".$colname_Area."%", "text"));
	$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
	$row_Area = mysql_fetch_assoc($Area);
	$totalRows_Area = mysql_num_rows($Area);
	
	if($totalRows_Area>0){
		do{ 
			if($colname_Name==$row_Area['County_Name']){$same1="selected";}else{$same1="";}
			echo "<option value='".$row_Area['Postal_Code']."' ".$same1.">";
			echo $row_Area['County_Name'];
			echo "</option>";
		}while($row_Area = mysql_fetch_array($Area));
	
	}
}
mysql_free_result($Area);

?>
