<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/GetSQLValueString.php'); ?>
<?php
if (isset($_GET['Member_Name']) && $_GET['Member_Name']<>'') {
  $colname_Area = $_GET['Member_Name'];
}
else{
  $colname_Area ='';
}
if (isset($_GET['Member_Identity']) && $_GET['Member_Name']<>'') {
  $colname_Area2 = $_GET['Member_Identity'];
}
else{
  $colname_Area2 ='';
}
if (isset($_GET['Com_ID'])) {
  $colname_Area3 = $_GET['Com_ID'];
}
else{
  $colname_Area3 =-1;
}
mysql_select_db($database_dbline, $dbline);

  $query_Area = sprintf("SELECT  Member_ID,Member_UserName,Member_Identity,Com_ID  FROM Member where Member_UserName=%s and Member_Identity=%s and Com_ID=%s order by Edit_Time DESC, Member_ID DESC", GetSQLValueString($colname_Area, "text"), GetSQLValueString($colname_Area2, "text"), GetSQLValueString($colname_Area3, "int"));

$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);
if($totalRows_Area>0){
		 echo '<input id="Member_ID" name="Member_ID" type="hidden" value="'.$row_Area['Member_ID'].'">';
}
else{
	echo '<input id="Member_ID" name="Member_ID" type="hidden">';
}



mysql_free_result($Area);

?>
