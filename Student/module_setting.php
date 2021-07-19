<?php 
mysql_select_db($database_dbline, $dbline);
$query_ModuleSet = "SELECT * FROM module_setting WHERE ModuleSetting_Name ='".$modulename[1]."' and ModuleSetting_Code='".$Code."'";
$ModuleSet = mysql_query($query_ModuleSet, $dbline) or die(mysql_error());
$row_ModuleSet = mysql_fetch_assoc($ModuleSet);
$totalRows_ModuleSet = mysql_num_rows($ModuleSet);
?>