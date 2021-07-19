<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php 
$colname_ID='-1';
if (isset($_GET['Com_ID']) && $_GET['Com_ID']<>"") {
  $colname_ID = $_GET['Com_ID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Teacher = "SELECT * FROM teacher where Teacher_Audit=1 and Com_ID = ".$colname_ID;
$Teacher = mysql_query($query_Teacher, $dbline) or die(mysql_error());
$row_Teacher = mysql_fetch_assoc($Teacher);
$totalRows_Teacher = mysql_num_rows($Teacher);
echo '<option value="">請選擇講師</option>';
if($totalRows_Teacher>0){
	do{
		echo '<option value="'.$row_Teacher['Teacher_ID'].'">'.$row_Teacher['Teacher_UserName']."(".$row_Teacher['Teacher_Identity'].")</option>";
	}while($row_Teacher = mysql_fetch_assoc($Teacher));
}
mysql_free_result($Teacher);




?>
