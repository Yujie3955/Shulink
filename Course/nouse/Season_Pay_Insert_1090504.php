<?php

mysql_select_db($database_dbline, $dbline);
$query_CateP2_5 = sprintf("SELECT * FROM pay_rule WHERE Com_ID=%s and PR_Enable=1 order by PR_Sort asc", GetSQLValueString($_POST['Com_ID'], "int"));
$CateP2_5 = mysql_query($query_CateP2_5, $dbline) or die(mysql_error());
$row_CateP2_5 = mysql_fetch_assoc($CateP2_5);
$totalRows_CateP2_5 = mysql_num_rows($CateP2_5);
if($totalRows_CateP2_5>0){
	do{	
		//優惠查詢是否新增
		mysql_select_db($database_dbline, $dbline);
		$query_CateP2_3 = sprintf("SELECT Pid FROM pay WHERE pay.Season_Code = %s and Com_ID=%s and P_Sale=%s", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"),GetSQLValueString($row_CateP2_5['PR_Sale'], "text"));
		$CateP2_3 = mysql_query($query_CateP2_3, $dbline) or die(mysql_error());
		$row_CateP2_3 = mysql_fetch_assoc($CateP2_3);
		$totalRows_CateP2_3 = mysql_num_rows($CateP2_3);
		if($totalRows_CateP2_3<1){
			//搜尋編號到幾項
			$query_CateP2_4 = sprintf("SELECT P_Code FROM pay WHERE pay.Season_Code = %s and Com_ID=%s order by Pid desc", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"));
			$CateP2_4 = mysql_query($query_CateP2_4, $dbline) or die(mysql_error());
			$row_CateP2_4 = mysql_fetch_assoc($CateP2_4);
			$totalRows_CateP2_4 = mysql_num_rows($CateP2_4);
			if($totalRows_CateP2_4>0){
				$P_CodeValue=explode("_",$row_CateP2_4['P_Code']);
				$P_CodeValue=(int)($P_CodeValue[1]+1);
			}
			else{
				$P_CodeValue=1;
			}
			mysql_free_result($CateP2_4);
			$P_Cmain=$_POST['Season_Code'].$Com_Code;//主CODE
			$P_Code=$_POST['Season_Code'].$Com_Code.'1_'.$P_CodeValue;	//子CODE	
			
			$insertSQL3 = sprintf("INSERT INTO pay (Com_ID, Season_Code, P_Enable, P_Cmain,  P_Code, P_Point, P_Cate, P_Pay, P_Sale, P_SaleText, P_Text, P_Sort, P_IsOnline, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s)",
							   GetSQLValueString($_POST['Com_ID'], "int"),					   
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString($P_Cmain, "text"),
							   GetSQLValueString($P_Code, "text"),
							   GetSQLValueString($row_CateP2_5['PR_Point'], "int"),
							   GetSQLValueString($row_CateP2_5['PR_Cate'], "int"),
							   GetSQLValueString($row_CateP2_5['PR_Pay'], "int"),
							   GetSQLValueString($row_CateP2_5['PR_Sale'], "text"),
							   GetSQLValueString($row_CateP2_5['PR_SaleText'], "text"),
							   GetSQLValueString($row_CateP2_5['PR_Text'], "text"),
							   GetSQLValueString($row_CateP2_5['PR_Sort'], "int"),
							   GetSQLValueString($row_CateP2_5['PR_IsOnline'], "int"),
							   
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