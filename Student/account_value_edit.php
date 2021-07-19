<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php
if (isset($_GET['Member_Identity'])) {
  $colname_Area = $_GET['Member_Identity'];
}
if (isset($_GET['Member_OIdentity'])) {
  $colname_Area2 = $_GET['Member_OIdentity'];
}

mysql_select_db($database_dbline, $dbline);
$query_Area = sprintf("SELECT * FROM member where Member_Identity = %s order by Member_ID ASC", GetSQLValueString("".$colname_Area."", "text"));
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);

if($totalRows_Area>0){
	if($colname_Area==$colname_Area2){
		echo '<div id="RepeatAccount">此身分可使用</div><input value="無重複" name="RepeatM" id="RepeatM" type="hidden"><input id="ids" name="ids" value="'.$colname_Area.'" type="hidden">';
	}
	else{
		echo '<div id="RepeatAccount">身分已註冊過，請更換</div><input value="重複" name="RepeatM" id="RepeatM" type="hidden"><input id="ids" name="ids" value="'.$colname_Area.'" type="hidden">';
	}	
}
else{
	echo '<div id="RepeatAccount">此身分可使用</div><input value="無重複" name="RepeatM" id="RepeatM" type="hidden"><input id="ids" name="ids" value="'.$colname_Area.'" type="hidden">';
		}






?>
