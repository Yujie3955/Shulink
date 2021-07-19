<?php
//收據
mysql_select_db($database_dbline, $dbline);
$query_CateP2_5 = sprintf("SELECT * FROM prints_text WHERE Com_ID=%s and Season_Code=%s", GetSQLValueString($_POST['Com_ID'], "int"), GetSQLValueString($_POST['Season_Code'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5<1){
	$query_CateP2_6 = sprintf("SELECT * FROM prints_text WHERE Com_ID=%s order by PrintText_ID desc", GetSQLValueString($_POST['Com_ID'], "int"));
	$CateP2_6 = mysql_query($query_CateP2_6, $dbline) or die(mysql_error());
	$row_CateP2_6 = mysql_fetch_assoc($CateP2_6);
	$totalRows_CateP2_6 = mysql_num_rows($CateP2_6);
	$insertSQL3 = sprintf("INSERT INTO prints_text (Com_ID, PrintText_Name, Season_Code, PrintText_Enable, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s,     %s, %s, %s, %s)",					   
			GetSQLValueString($_POST['Com_ID'], "int"),	   
			GetSQLValueString($row_CateP2_6['PrintText_Name'], "text"),	   
			GetSQLValueString($_POST['Season_Code'], "int"),
			GetSQLValueString(1, "int"),
			GetSQLValueString($AddTime, "date"),
			GetSQLValueString($AddTime, "date"),
			GetSQLValueString($_POST['Add_Account'], "text"),
			GetSQLValueString($_POST['Add_Unitname'], "text"),
			GetSQLValueString($_POST['Add_Username'], "text"));
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());	
	mysql_free_result($CateP2_6);
}	
mysql_free_result($CateP2_5);

//身分別
mysql_select_db($database_dbline, $dbline);
$query_CateP2_5 = sprintf("SELECT * FROM member_type_rule WHERE Com_ID=%s and P_Enable=1 order by MemberType_ID asc", GetSQLValueString($_POST['Com_ID'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5>0){
	do{	
		//優惠查詢是否新增
		mysql_select_db($database_dbline, $dbline);
		$query_CateP2_3 = sprintf("SELECT MemberType_ID FROM member_type WHERE member_type.Season_Code = %s and Com_ID=%s and MemberType_Name=%s", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"),GetSQLValueString($row_CateP2_5['MemberType_Name'], "text"));
		$CateP2_3 = mysql_query($query_CateP2_3, $dbline) or die(mysql_error());
		$row_CateP2_3 = mysql_fetch_assoc($CateP2_3);
		$totalRows_CateP2_3 = mysql_num_rows($CateP2_3);
		if($totalRows_CateP2_3<1){
				
			
			$insertSQL3 = sprintf("INSERT INTO member_type (Com_ID, Season_Code, P_Enable, MemberType_Name, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s)",
							   GetSQLValueString($_POST['Com_ID'], "int"),					   
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString($row_CateP2_5['MemberType_Name'], "text"),
							   
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($_POST['Add_Account'], "text"),
							   GetSQLValueString($_POST['Add_Unitname'], "text"),
							   GetSQLValueString($_POST['Add_Username'], "text"));
			mysql_select_db($database_dbline, $dbline);
			$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());
		}		
		mysql_free_result($CateP2_3);
		//優惠查詢是否新增ED
	}
	while($row_CateP2_5 = mysql_fetch_assoc($CateP2_5));//foreach ed
}	
mysql_free_result($CateP2_5);	
?>