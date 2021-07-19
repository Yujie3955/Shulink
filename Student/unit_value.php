<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>


<?php
if (isset($_GET['Com_ID'])) {
  $colname_Area = $_GET['Com_ID'];
}
else{$colname_Area="%";}
if (isset($_GET['Unit_ID'])) {
  $colname_Area2 = $_GET['Unit_ID'];
}
else{$colname_Area2="%";}
if (isset($_GET['Unit_Range'])) {
  $colname_Area3 = $_GET['Unit_Range'];
}
else{$colname_Area3="%";}
mysql_select_db($database_dbline, $dbline);
$query_Area = sprintf("SELECT * FROM unit where Com_ID = %s and Unit_IsSchool=1 and Unit_ID like %s and Unit_Enable=1 order by Unit_ID ASC", GetSQLValueString($colname_Area, "text"), GetSQLValueString($colname_Area3, "text"));
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);
echo "<option value=''>請選擇區域...</option>";
if($totalRows_Area>0){
do{
if($colname_Area2==$row_Area['Unit_ID']){$same1="selected";}
else{$same1="";}	 
    echo "<option value='".$row_Area['Unit_ID']."' ".$same1.">";
    echo $row_Area['Unit_Name'];
    echo "</option>";
}while($row_Area = mysql_fetch_array($Area));
}



?>
