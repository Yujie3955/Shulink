<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上課地點篩選</title>
</head>

<?php
if (isset($_GET['Com_ID'])) {
  $colname_Com = $_GET['Com_ID'];
}
else{$colname_Com="%";}

if (isset($_GET['Unit_ID'])) {
  $colname_Unit = $_GET['Unit_ID'];
}
else{$colname_Unit="%";}

if (isset($_GET['Loc_ID'])) {
  $colname_Area2 = $_GET['Loc_ID'];
}
else{$colname_Area2="%";}
mysql_select_db($database_dbline, $dbline);
$query_Cate4 = sprintf("SELECT Loc_ID,Loc_Name FROM location where Loc_Enable=1 and Com_ID Like %s and Unit_ID like %s  ORDER BY Add_Time asc",GetSQLValueString($colname_Com, "text"),GetSQLValueString($colname_Unit, "text"));
$Cate4 = mysql_query($query_Cate4, $dbline) or die(mysql_error());
$row_Cate4 = mysql_fetch_assoc($Cate4);
$totalRows_Cate4 = mysql_num_rows($Cate4);
echo "<option value=''>請選擇地點...</option>";
if($totalRows_Cate4>0){
do{ 
if($colname_Area2==$row_Cate4['Loc_ID']){$same1="selected";}
else{$same1="";}
    echo "<option value='".$row_Cate4['Loc_ID']."' ".$same1.">";
    echo $row_Cate4['Loc_Name'];
    echo "</option>";
}while($row_Cate4 = mysql_fetch_array($Cate4));
}
mysql_free_result($Cate4);


?>
