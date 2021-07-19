<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/GetSQLValueString.php'); ?>

<?php
if(isset($_GET['Com_ID'])){
  $colname_Com=$_GET['Com_ID'];
}
else{
  $colname_Com='0';
}
mysql_select_db($database_dbline, $dbline);
$query_Student = sprintf("SELECT * FROM member where Com_ID = %s",GetSQLValueString($colname_Com,'text'));
$Student = mysql_query($query_Student, $dbline) or die(mysql_error());
$row_Student = mysql_fetch_assoc($Student);
$totalRows_Student = mysql_num_rows($Student);

$t=0;
$str='';
do{$t++;
if($t<>$totalRows_Student){$x=',';}else{$x='';}
$str.=$row_Student['Member_UserName']."(".$row_Student['Member_Identity'].")".$x;

}while($row_Student = mysql_fetch_assoc($Student));
echo '<input name="Member_List[]" id="Member_List" value="'.$str.'" type="hidden">';

	
	
	

?>
