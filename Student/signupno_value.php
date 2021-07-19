<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('module_setting.php'); ?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php
if (isset($_GET['Season_ID'])) {
  $colname_Season = $_GET['Season_ID'];
}
if (isset($_GET['Add_Account'])) {
  $colname_Account = $_GET['Add_Account'];
}
if (isset($_GET['Signup_ID'])) {
  $colname_Signup = $_GET['Signup_ID'];
}
if (isset($_GET['Course_ID'])) {
  $colname_Course = $_GET['Course_ID'];
}
if (isset($_GET['Com_ID'])) {
  $colname_Com= $_GET['Com_ID'];
}

mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM signup_choose where Member_Identity=%s and Season_ID=%s order by Com_ID ASC,Unit_ID ASC",GetSQLValueString($colname_Account,"text"),GetSQLValueString($colname_Season,"int"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);
if($totalRows_Data>0){
?>
<?php
  $deleteSQL = sprintf("DELETE FROM signup_item WHERE Signup_ID=%s and Course_ID=%s",
                       GetSQLValueString($colname_Signup, "int"),
                       GetSQLValueString($colname_Course, "int"));

  mysql_select_db($database_dbline, $dbline);
  $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
  
    mysql_select_db($database_dbline, $dbline);
	$query_Remain = sprintf("SELECT Course_OnlineRemaining FROM course where Course_ID =%s", 	GetSQLValueString($colname_Course , "int"));
	$Remain = mysql_query($query_Remain, $dbline) or die(mysql_error());
	$row_Remain = mysql_fetch_assoc($Remain);
	$totalRows_Remain = mysql_num_rows($Remain);
	if($totalRows_Remain>0){
  
   $updateSQL = sprintf("update course set Course_OnlineRemaining=%s where Course_ID=%s",
                       GetSQLValueString($row_Remain['Course_OnlineRemaining']+1, "int"),
					   GetSQLValueString($colname_Course, "int"));
					   
    mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());}
	mysql_free_result($Remain);
}


echo '<script type=\'text/javascript\'> location.replace(AD_Signup_Detail.php?ID='.$colname_Season.'&Com='.$colname_Com.');</script>';

?>
</head>
<body>

</body>
</html>