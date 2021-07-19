<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/GetSQLValueString.php'); ?>
<?php
if (isset($_GET['Teacher_Name']) && $_GET['Teacher_Name']<>'') {
  $colname_Area = $_GET['Teacher_Name'];
}
else{
  $colname_Area ='';
}
if (isset($_GET['Teacher_Identity']) && $_GET['Teacher_Name']<>'') {
  $colname_Area2 = $_GET['Teacher_Identity'];
}
else{
  $colname_Area2 ='';
}
if (isset($_GET['Teacher_ID'])) {
  $colname_Area3 = $_GET['Teacher_ID'];
}
else{
  $colname_Area3 = '';
}
mysql_select_db($database_dbline, $dbline);
if($colname_Area3<>'' && $colname_Area=='' && $colname_Area2==''){
	$query_Area = sprintf("SELECT  Teacher_ID,Teacher_UserName,Teacher_Identity  FROM teacher where Teacher_Audit = 1  and Teacher_ID =%s order by Edit_Time DESC, Teacher_ID DESC", GetSQLValueString($colname_Area3, "text"));
}
else{
	$query_Area = sprintf("SELECT  Teacher_ID,Teacher_UserName,Teacher_Identity  FROM teacher where Teacher_Audit = 1 and Teacher_UserName=%s and Teacher_Identity=%s order by Edit_Time DESC, Teacher_ID DESC", GetSQLValueString($colname_Area, "text"), GetSQLValueString($colname_Area2, "text"));
}
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);

if($totalRows_Area>0){
	if($colname_Area3<>'' && $colname_Area=='' && $colname_Area2==''){
		echo '
              <input id="Teacher_ID5" name="Teacher_ID5" type="hidden" value="'.$row_Area['Teacher_ID'].'">
			  <input id="Teacher_NameValue5" name="Teacher_NameValue5" value="'.$row_Area['Teacher_UserName']."(".$row_Area['Teacher_Identity'].")".'" type="hidden">';
	}
	else{
		 echo '<input id="Teacher_ID5" name="Teacher_ID5" type="hidden" value="'.$row_Area['Teacher_ID'].'">';
	}
}
else{
	echo '<input id="Teacher_ID5" name="Teacher_ID5" type="hidden">';
}



mysql_free_result($Area);

?>
