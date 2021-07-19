<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>分校代號篩選</title>
</head>

<?php

if (isset($_GET['Unit_ID'])) {
  $colname_Area = $_GET['Unit_ID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Area = sprintf("SELECT * FROM unit where Unit_ID = %s and Unit_IsSchool=1 and Unit_Enable=1 order by Unit_ID ASC", GetSQLValueString($colname_Area, "text"));
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);
 
    echo "<input value='".$row_Area['Unit_Code']."' name='Unit_Code' id='Unit_Code' type='hidden'>";





?>
