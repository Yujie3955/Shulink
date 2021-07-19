<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>

<?php
echo "<option value=''>請選擇區域...</option>";
if (isset($_GET['Season_Code'])) {
  $colname_Area = $_GET['Season_Code'];
}
if (isset($_GET['Com_ID'])) {
	if($_GET['Com_ID']=='all'){$colname_Com='%';}
    else{$colname_Com= $_GET['Com_ID'];}
}
if($colname_Area<>""){
mysql_select_db($database_dbline, $dbline);
$query_Area = sprintf("SELECT * FROM season_new where season_new.Season_Code = ".$colname_Area." and Com_ID Like %s order by Com_ID ASC",GetSQLValueString($colname_Com, "text"));
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);

   if($totalRows_Area>0){
do{ 
    
    echo "<option value='".$row_Area['Com_ID']."' >";
    echo $row_Area['Com_Name'];
    echo "</option>";
}while($row_Area = mysql_fetch_array($Area));

   }
}

?>
