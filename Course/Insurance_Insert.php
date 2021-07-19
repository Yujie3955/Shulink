<?php
//學員規則增加
mysql_select_db($database_dbline, $dbline);
$query_CateP2_5 = sprintf("SELECT * FROM insurance_fill WHERE InsuranceFill_Max=14 and InsuranceFill_Min=0 and InsuranceFill_IsTeacher=0 and Season_Code=%s", GetSQLValueString($_POST['Season_Code'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5<1){
	if(preg_match("/夏/i",$_POST['SeasonCate_Name'])){
		$InsuranceFill_Money=75;
	}
	else{
		$InsuranceFill_Money=150;
	}
	$insertSQL3 = sprintf("INSERT INTO insurance_fill (Season_Code, InsuranceFill_Max, InsuranceFill_Min,  InsuranceFill_IsTeacher, InsuranceFill_Money) VALUES (%s, %s, %s, %s, %s)",					   
			GetSQLValueString($_POST['Season_Code'], "int"),
			GetSQLValueString(14, "int"),
			GetSQLValueString(0, "text"),
			GetSQLValueString(0, "text"),
			GetSQLValueString($InsuranceFill_Money, "int"));
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());
}	
mysql_free_result($CateP2_5);
$query_CateP2_5 = sprintf("SELECT * FROM insurance_fill WHERE InsuranceFill_Max=80 and InsuranceFill_Min=15 and InsuranceFill_IsTeacher=0 and Season_Code=%s ", GetSQLValueString($_POST['Season_Code'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5<1){
	if(preg_match("/夏/i",$_POST['SeasonCate_Name'])){
		$InsuranceFill_Money=100;
	}
	else{
		$InsuranceFill_Money=200;
	}
	$insertSQL3 = sprintf("INSERT INTO insurance_fill (Season_Code, InsuranceFill_Max, InsuranceFill_Min,  InsuranceFill_IsTeacher, InsuranceFill_Money) VALUES (%s, %s, %s, %s, %s)",					   
			GetSQLValueString($_POST['Season_Code'], "int"),
			GetSQLValueString(80, "int"),
			GetSQLValueString(15, "text"),
			GetSQLValueString(0, "text"),
			GetSQLValueString($InsuranceFill_Money, "int"));
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());
}	
mysql_free_result($CateP2_5);
$query_CateP2_5 = sprintf("SELECT * FROM insurance_fill WHERE InsuranceFill_Min=81 and InsuranceFill_IsTeacher=0 and Season_Code=%s ", GetSQLValueString($_POST['Season_Code'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5<1){
	if(preg_match("/夏/i",$_POST['SeasonCate_Name'])){
		$InsuranceFill_Money=50;
	}
	else{
		$InsuranceFill_Money=100;
	}
	$insertSQL3 = sprintf("INSERT INTO insurance_fill (Season_Code, InsuranceFill_Max, InsuranceFill_Min,  InsuranceFill_IsTeacher, InsuranceFill_Money) VALUES (%s, %s, %s, %s, %s)",					   
			GetSQLValueString($_POST['Season_Code'], "int"),
			GetSQLValueString(200, "int"),
			GetSQLValueString(81, "text"),
			GetSQLValueString(0, "text"),
			GetSQLValueString($InsuranceFill_Money, "int"));
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());
}	
mysql_free_result($CateP2_5);	
$query_CateP2_5 = sprintf("SELECT * FROM insurance_fill WHERE InsuranceFill_IsTeacher=1 and Season_Code=%s ", GetSQLValueString($_POST['Season_Code'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5<1){
	if(preg_match("/春/i",$_POST['SeasonCate_Name'])){
		$InsuranceFill_Money=400;
	}
	else{
		$InsuranceFill_Money=200;
	}
	$insertSQL3 = sprintf("INSERT INTO insurance_fill (Season_Code, InsuranceFill_Max, InsuranceFill_Min,  InsuranceFill_IsTeacher, InsuranceFill_Money) VALUES (%s, %s, %s, %s, %s)",					   
			GetSQLValueString($_POST['Season_Code'], "int"),
			GetSQLValueString(200, "int"),
			GetSQLValueString(0, "text"),
			GetSQLValueString(1, "text"),
			GetSQLValueString($InsuranceFill_Money, "int"));
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());
}	
mysql_free_result($CateP2_5);		
?>