<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php //require_once('module_setting.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>學員報名條件</title>
</head>

<?php
if (isset($_GET['Course_ID'])) {
  $colname_Course = $_GET['Course_ID'];
}
if (isset($_GET['Credit_Money'])) {
  $colname_credit_Money = $_GET['Credit_Money'];
}
if (isset($_GET['Credit2_Money'])) {
  $colname_credit2_Money = $_GET['Credit2_Money'];
}
if (isset($_GET['Pro_Money'])) {
  $colname_pro_Money = $_GET['Pro_Money'];
}
if (isset($_GET['Add_Account'])) {
  $colname_Account = $_GET['Add_Account'];
}
if (isset($_GET['Add_Username'])) {
  $colname_Username = $_GET['Add_Username'];
}
if (isset($_GET['Season_ID'])) {
  $colname_Season = $_GET['Season_ID'];
}
if (isset($_GET['Season_Code'])) {
  $colname_SeasonCode = $_GET['Season_Code'];
}
if (isset($_GET['Unit_ID'])) {
  $colname_Unit= $_GET['Unit_ID'];
}
if (isset($_GET['Com_ID'])) {
  $colname_Com= $_GET['Com_ID'];
}
    /*查詢學員ID OP*/
	mysql_select_db($database_dbline, $dbline);
	$query_Member = sprintf("SELECT * FROM member where Member_Identity =%s and Com_ID=%s", GetSQLValueString($colname_Account, "text"), GetSQLValueString($colname_Com, "int"));
	$Member = mysql_query($query_Member, $dbline) or die(mysql_error());
	$row_Member = mysql_fetch_assoc($Member);
	$totalRows_Member = mysql_num_rows($Member);
	$Member_Type=$row_Member['Member_Type'];
	/*查詢學員ID END*/

//搜尋現在報名課程
$query_CourseNowData = sprintf("SELECT * FROM signup_onlinelist where Course_ID= %s", GetSQLValueString($colname_Course, "int"));
$CourseNowData = mysql_query($query_CourseNowData, $dbline) or die(mysql_error());
$row_CourseNowData = mysql_fetch_assoc($CourseNowData);
$totalRows_CourseNowData = mysql_num_rows($CourseNowData);	
if($row_CourseNowData['Season_IsAll']==1){
	mysql_free_result($CourseNowData);
	$query_CourseNowData = sprintf("SELECT * FROM signup_onlinelist_all where Course_ID= %s", GetSQLValueString($colname_Course, "int"));
	$CourseNowData = mysql_query($query_CourseNowData, $dbline) or die(mysql_error());
	$row_CourseNowData = mysql_fetch_assoc($CourseNowData);
	$totalRows_CourseNowData = mysql_num_rows($CourseNowData);
}
$IsRepeat_Time=0;
$Course_NowDay=$row_CourseNowData['Course_Day1'];
$Course_NowStart=strtotime($row_CourseNowData['Course_Start1']);
$Course_NowEnd=strtotime($row_CourseNowData['Course_End1']);
$Season_IsAll=$row_CourseNowData['Season_IsAll'];
$CO_Sale=$row_CourseNowData['CO_Sale'];
mysql_free_result($CourseNowData);

//身分優惠
$P_Sale=1;
$P_Text='';
$P_Sale2='';
$P_Text2='';
$query_PayCate = sprintf("SELECT * FROM pay where Season_Code = %s and Com_ID=%s and P_Enable=1 and P_Cate=2 order by P_Cate desc,Pid asc", GetSQLValueString($colname_SeasonCode, "int"), GetSQLValueString($colname_Com, "int"));
$PayCate = mysql_query($query_PayCate, $dbline) or die(mysql_error());
$row_PayCate = mysql_fetch_assoc($PayCate);
$totalRows_PayCate = mysql_num_rows($PayCate);		
if($totalRows_PayCate>0){
	do{
		
		$P_List=explode("折-",$row_PayCate['P_Text']);
		if(isset($P_List[1]) && $P_List[1]<>""){
			$P_Detail=$P_List[1];
		}
		else{
			$P_Detail=$P_List[0];
		}
		if($row_PayCate['P_Sale']==1){$P_Text=$P_Detail;}
		if($P_Detail==$Member_Type){
			$P_Sale=$row_PayCate['P_Sale'];
			$P_Text=$row_PayCate['P_Text'];
		}
	}while($row_PayCate = mysql_fetch_assoc($PayCate));
}
$colname_Money=0;
$colname_Money=($colname_credit_Money*$CO_Sale)+$colname_credit2_Money+$colname_pro_Money;

$colname_Money2=0;
if($CO_Sale==1){
	$colname_Money2=($colname_credit_Money*$P_Sale)+$colname_credit2_Money+$colname_pro_Money;
	if($colname_Money2>0){
		$colname_Money2=ceil($colname_Money2);
	}
}
else{
	$colname_Money2=$colname_Money;
}
mysql_free_result($PayCate);

	if($Season_IsAll==1){
		$query_Remain = sprintf("SELECT OnlineTotalNum,OnlineTotalNoNum,Course_Day1,Course_Time FROM signup_countchoose_all where Course_ID =%s", GetSQLValueString($colname_Course , "int"));
	}
	else{
		$query_Remain = sprintf("SELECT OnlineTotalNum,OnlineTotalNoNum,Course_Day1,Course_Time FROM signup_countchoose where Course_ID =%s", GetSQLValueString($colname_Course , "int"));
	}
	$Remain = mysql_query($query_Remain, $dbline) or die(mysql_error());
	$row_Remain = mysql_fetch_assoc($Remain);
	$totalRows_Remain = mysql_num_rows($Remain);
	if($totalRows_Remain<1){
		mysql_free_result($Remain);
		
		$query_Remain2 = sprintf("SELECT Course_OnlineAdd,Course_Online, Course_OnSite, Course_OnSiteAdd, Course_Day1, Course_Time FROM course where Course_ID =%s", GetSQLValueString($colname_Course , "int"));
		$Remain2 = mysql_query($query_Remain2, $dbline) or die(mysql_error());
		$row_Remain2 = mysql_fetch_assoc($Remain2);
		$totalRows_Remain2 = mysql_num_rows($Remain2);
		if($totalRows_Remain2>0){
			$Remain_CourseDay=$row_Remain2['Course_Day1'];
			$Remain_CourseTime=$row_Remain2['Course_Time'];
			if($Season_IsAll==1){			
				$OnlineTotalNoNum=(int)($row_Remain2['Course_Online']+$row_Remain2['Course_OnlineAdd']+$row_Remain2['Course_OnSite']+$row_Remain2['Course_OnSiteAdd']);
			}
			else{
				$OnlineTotalNoNum=(int)($row_Remain2['Course_Online']+$row_Remain2['Course_OnlineAdd']);
			}
			$OnlineTotalNum=$OnlineTotalNoNum;
		}
		else{
			$Remain_CourseDay='星期一';
			$Remain_CourseTime='晚上';
			$OnlineTotalNoNum=0;
			$OnlineTotalNum=0;
		}
		mysql_free_result($Remain2);
	}
	else{
		$Remain_CourseDay=$row_Remain['Course_Day1'];
		$Remain_CourseTime=$row_Remain['Course_Time'];
		$OnlineTotalNoNum=$row_Remain['OnlineTotalNoNum'];
		$OnlineTotalNum=$row_Remain['OnlineTotalNum'];
		mysql_free_result($Remain);
	}
	
	
	
	/*判斷名額OP*/
	if( ($OnlineTotalNum<>""&&$OnlineTotalNum<=0) || $OnlineTotalNoNum<=0){
		echo '<font color="#db2400">此課程名額已滿！</font>';
		echo '<input name="Ins_OK" id="Ins_OK'.$colname_Course.'" value="false" type="hidden">';
	}
	else{
		$query_CourseDay = sprintf("SELECT Course_Day1, Course_Time, Course_Start1, Course_End1 FROM signup_item INNER JOIN signup ON signup.Signup_ID = signup_item.Signup_ID left JOIN signup_record ON signup_record.SignupItem_ID = signup_item.SignupItem_ID INNER JOIN course on course.Course_ID = signup_item.Course_ID where course.Season_Code=%s and signup.Member_Identity=%s and Course_Day1=%s and signup.Signup_Status <> %s and (signup_record.SignupRecord_Returns is null or signup_record.SignupRecord_Returns=0) ", GetSQLValueString($colname_SeasonCode , "int"), GetSQLValueString($row_Member['Member_Identity'], "text"), GetSQLValueString($Remain_CourseDay , "text"), GetSQLValueString("已結單,未繳費" , "text"));
		$CourseDay = mysql_query($query_CourseDay, $dbline) or die(mysql_error());
		$row_CourseDay = mysql_fetch_assoc($CourseDay);
		$totalRows_CourseDay = mysql_num_rows($CourseDay);
		
		
		$query_CourseDay2 = sprintf("SELECT Course_Day1, Course_Time, course.Course_Start1, course.Course_End1 FROM signup_record INNER JOIN course ON course.Course_ID = signup_record.Course_ID INNER JOIN member ON member.Member_ID = signup_record.Member_ID where course.Season_Code=%s and member.Member_Identity=%s and SignupRecord_Returns=0 and course.Course_Day1=%s ", GetSQLValueString($colname_SeasonCode , "int"), GetSQLValueString($row_Member['Member_Identity'], "text"), GetSQLValueString($Remain_CourseDay , "text"));
		$CourseDay2 = mysql_query($query_CourseDay2, $dbline) or die(mysql_error());
		$row_CourseDay2 = mysql_fetch_assoc($CourseDay2);
		$totalRows_CourseDay2 = mysql_num_rows($CourseDay2);
		
			
		
		if($totalRows_CourseDay>0 || $totalRows_CourseDay2>0){
			
			if($totalRows_CourseDay>0){
				$str_repeatcourse='';
				if($row_CourseDay['Course_Day1']<>""){$str_repeatcourse=$row_CourseDay['Course_Day1'];}
			}
			elseif($totalRows_CourseDay2>0){
				$str_repeatcourse='';
				if($row_CourseDay2['Course_Day1']<>""){$str_repeatcourse=$row_CourseDay2['Course_Day1'];}			
			}
			do{
				$Already_Start=strtotime($row_CourseDay2['Course_Start1']);
				$Already_End=strtotime($row_CourseDay2['Course_End1']);
				if(($Already_Start>=$Course_NowStart && $Course_NowEnd>$Already_End)||
				   ($Already_Start<=$Course_NowStart && $Already_End>$Course_NowStart)||
				   ($Already_Start<$Course_NowEnd && $Already_End>=$Course_NowEnd)){
							$IsRepeat_Time=1;
				}
			}while($row_CourseDay2 = mysql_fetch_assoc($CourseDay2));
			do{
				$Already_Start=strtotime($row_CourseDay['Course_Start1']);
				$Already_End=strtotime($row_CourseDay['Course_End1']);
				if(($Already_Start>=$Course_NowStart && $Course_NowEnd>$Already_End)||
				   ($Already_Start<=$Course_NowStart && $Already_End>$Course_NowStart)||
				   ($Already_Start<$Course_NowEnd && $Already_End>=$Course_NowEnd)){
							$IsRepeat_Time=1;
				}
			}while($row_CourseDay = mysql_fetch_assoc($CourseDay));
			if($IsRepeat_Time==1){
			echo '<font color="#db2400">已加選過'.$str_repeatcourse.'的課程！</font>';
			echo '<font color="#db2400">'.$Course_NowDay.'的課程有衝堂！</font>';
			}
		
		}
		mysql_free_result($CourseDay);
		mysql_free_result($CourseDay2);
		
		
			
			$query_Sign = sprintf("SELECT Course_ID FROM signup_choose_onuse where Member_ID=%s and Course_ID=%s ",GetSQLValueString($row_Member['Member_ID'],"int"),GetSQLValueString($colname_Course,"int"));
			$Sign = mysql_query($query_Sign, $dbline) or die(mysql_error());
			$row_Sign = mysql_fetch_assoc($Sign);
			$totalRows_Sign = mysql_num_rows($Sign);
			/*判斷是否加選過OP*/
			if($totalRows_Sign>0){
				echo'<font color="#db2400">此課程已加選過！</font>';
				echo '<input name="Ins_OK" id="Ins_OK'.$colname_Course.'" value="false" type="hidden">';
			}
			else{
				$AddTime=date("Y-m-d H:i:s");				
				$query_Cate = sprintf("SELECT * FROM signup where Season_Code=%s and Com_ID = %s and Member_ID =%s and Signup_OrderNumber is null ORDER BY Season_Code DESC, Com_ID asc, Unit_ID ASC", GetSQLValueString($colname_SeasonCode, "int"), GetSQLValueString($colname_Com, "int"), GetSQLValueString($row_Member['Member_ID'], "int"));
				$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
				$row_Cate = mysql_fetch_assoc($Cate);
				$totalRows_Cate = mysql_num_rows($Cate);
				
				if($totalRows_Cate<1){ /*判斷是否有此校訂單 OP*/
					
					$insertSQL = sprintf("INSERT INTO signup (Season_ID, Season_Code, Member_ID,Member_UserName,Member_Identity,Signup_Status,Com_ID,Add_Time, Edit_Time, Add_Account, Add_Username) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                      			   GetSQLValueString($colname_Season, "int"),
					   GetSQLValueString($colname_SeasonCode, "int"),
					   GetSQLValueString($row_Member['Member_ID'], "int"),
					   GetSQLValueString($colname_Username, "text"),
					   GetSQLValueString($row_Member['Member_Identity'], "text"),
					   GetSQLValueString("未結單", "text"),
					   GetSQLValueString($colname_Com, "int"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($row_Member['Member_ID'], "text"),
					   GetSQLValueString($colname_Username, "text"));
					mysql_select_db($database_dbline, $dbline);
					$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
					
					/*查詢課程自動編*/
					mysql_select_db($database_dbline, $dbline);
					$query_SignID = "SELECT LAST_INSERT_ID();";
					$SignID = mysql_query($query_SignID, $dbline) or die(mysql_error());
					$row_SignID= mysql_fetch_assoc($SignID);
					$totalRows_SignID = mysql_num_rows($SignID);
					/*查詢課程 END*/

					
					$insertSQL2 = sprintf("INSERT INTO signup_item (Signup_ID,Course_ID, SignupItem_Money, SignupItem_OriMoney, Add_Time, Edit_Time, Add_Account, Add_Username, Signup_Status, Com_ID, Member_ID) VALUES (%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s)",
	                   GetSQLValueString($row_SignID['LAST_INSERT_ID()'], "int"),
                       GetSQLValueString($colname_Course, "int"),
					   GetSQLValueString($colname_Money2, "int"),
					   GetSQLValueString($colname_Money, "int"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($row_Member['Member_ID'], "text"),
					   GetSQLValueString($colname_Username, "text"),
					   GetSQLValueString("未結單", "text"),
					   GetSQLValueString($colname_Com, "int"),
					   GetSQLValueString($row_Member['Member_ID'], "int"));
					mysql_select_db($database_dbline, $dbline);
					$Result2 = mysql_query($insertSQL2, $dbline) or die(mysql_error());
					$AutoID=mysql_insert_id($dbline);
					mysql_free_result($SignID);
					//課程詳細費用OP
					$query_PayData = "SELECT * from course_pay where Course_ID='".$colname_Course."' and CP_Enable=1";
					$PayData = mysql_query($query_PayData, $dbline) or die(mysql_error());
					$row_PayData= mysql_fetch_assoc($PayData);
					$totalRows_PayData = mysql_num_rows($PayData);
					
					if($totalRows_PayData>0){
						do{
							$query_PayData2 = "SELECT * from signup_itemmoney where Course_ID='".$colname_Course."' and CP_Text='".$row_PayData['CP_Text']."' and Member_ID='".$row_Member['Member_ID']."' and Signup_ID='".$row_SignID['LAST_INSERT_ID()']."'";
							$PayData2 = mysql_query($query_PayData2, $dbline) or die(mysql_error());
							$row_PayData2= mysql_fetch_assoc($PayData2);
							$totalRows_PayData2 = mysql_num_rows($PayData2);
							$CP_Money=0;
							$CP_OriMoney=0;
							if(preg_match("/學分/i",$row_PayData['CP_Text'])){
								if($CO_Sale==1 && $P_Sale<>1){
									$CP_Money=$row_PayData['CP_Money']*$P_Sale;
									$P_Sale2=$P_Sale;
									$P_Text2=$P_Text;
								}
								else{
									$CP_Money=$row_PayData['CP_Money'];
									$P_Sale2=NULL;
									$P_Text2=NULL;
								}
							}
							else{
								$CP_Money=$row_PayData['CP_Money'];
								$P_Sale2=NULL;
								$P_Text2=NULL;
							}
							$CP_OriMoney=$row_PayData['CP_Money'];
							
							if($totalRows_PayData2<1){
								$insertSQL2 = sprintf("INSERT INTO signup_itemmoney (SignupItem_ID, CP_Text, Signup_ID, Course_ID, CP_Money, CP_OriMoney, P_Sale, P_Text, Member_ID, Com_ID, Add_Time, Edit_Time, Add_Account, Add_Username, Season_Code, Signup_Status, CP_Remark) VALUES (%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,   %s, %s, %s, %s, %s,   %s, %s)",
								   GetSQLValueString($AutoID, "int"),
								   GetSQLValueString($row_PayData['CP_Text'], "text"),
						                   GetSQLValueString($row_SignID['LAST_INSERT_ID()'], "int"),
					                           GetSQLValueString($colname_Course, "int"),
								   GetSQLValueString($CP_Money, "int"),
								   GetSQLValueString($CP_OriMoney, "int"),
								   GetSQLValueString($P_Sale2, "text"),
								   GetSQLValueString($P_Text2, "text"),
								   GetSQLValueString($row_Member['Member_ID'], "int"),
								   GetSQLValueString($colname_Com, "int"),
								   GetSQLValueString($AddTime, "date"),
								   GetSQLValueString($AddTime, "date"),
								   GetSQLValueString($row_Member['Member_ID'], "int"),
								   GetSQLValueString($colname_Username, "text"),
								   GetSQLValueString($colname_SeasonCode, "int"),
								   GetSQLValueString("未結單", "text"),
								   GetSQLValueString($row_PayData['CP_Remark'], "text"));
								mysql_select_db($database_dbline, $dbline);
								$Result2 = mysql_query($insertSQL2, $dbline) or die(mysql_error());
							}else{
								$updateSQL2 = sprintf("update signup_itemmoney set CP_Text=%s, CP_Remark=%s, CP_Money=%s, CP_OriMoney=%s, P_Sale=%s, P_Text=%s, Edit_Time=%s, Edit_Account=%s, Edit_Username=%s where SIM_ID=%s and Signup_OrderNumber is null ",
								   
								   GetSQLValueString($row_PayData['CP_Text'], "text"),
								   GetSQLValueString($row_PayData['CP_Remark'], "text"),						                   
								   GetSQLValueString($CP_Money, "int"), 
								   GetSQLValueString($CP_OriMoney, "int"), 
								   GetSQLValueString($P_Sale2, "text"), 
								   GetSQLValueString($P_Text2, "text"),
								  
								   GetSQLValueString($AddTime, "date"),
								   GetSQLValueString($row_Member['Member_ID'], "text"),
								   GetSQLValueString($colname_Username, "text"),
								   GetSQLValueString($row_PayData2['SIM_ID'], "int"));
								mysql_select_db($database_dbline, $dbline);
								$Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());

							}
							mysql_free_result($PayData2);	
						}while($row_PayData= mysql_fetch_assoc($PayData));
					}
					mysql_free_result($PayData);
					//課程詳細費用ED
					
					
					/*查詢課程餘額 OP*/
					if($Season_IsAll==1){
					$query_onlinelist = sprintf("SELECT OnlineTotalNum FROM signup_countchoose_all where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
					}else{
					$query_onlinelist = sprintf("SELECT OnlineTotalNum FROM signup_countchoose where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
					}
					$onlinelist = mysql_query($query_onlinelist, $dbline) or die(mysql_error());
					$row_onlinelist = mysql_fetch_assoc($onlinelist);
					$totalRows_onlinelist = mysql_num_rows($onlinelist);
					if($totalRows_onlinelist>0){
						$online_TotalNum=$row_onlinelist['OnlineTotalNum'];
					}
					else{
						if($Season_IsAll==1){
							$query_onlinelist2 = sprintf("SELECT (Course_Online+Course_OnSite) as Course_Online,(Course_OnlineAdd+Course_OnSiteAdd) as Course_OnlineAdd FROM course where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
						}
						else{
							$query_onlinelist2 = sprintf("SELECT Course_Online,Course_OnlineAdd FROM course where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
						}
						$onlinelist2 = mysql_query($query_onlinelist2, $dbline) or die(mysql_error());
						$row_onlinelist2 = mysql_fetch_assoc($onlinelist2);
						$totalRows_onlinelist2 = mysql_num_rows($onlinelist2);
						if($totalRows_onlinelist2>0){
							$online_TotalNum=(int)($row_onlinelist2['Course_Online']+$row_onlinelist2['Course_OnlineAdd']);
						}
						else{
							$online_TotalNum=0;
						}
						mysql_free_result($onlinelist2);
						
					}
					mysql_free_result($onlinelist);
					/*查詢課程餘額 END*/
	
					$updateSQL = sprintf("update course set Course_OnlineRemaining=%s where Course_ID=%s",
                       			   GetSQLValueString($online_TotalNum, "int"),
					   GetSQLValueString($colname_Course, "int"));
					   
    					mysql_select_db($database_dbline, $dbline);
					$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
					echo '<input name="Ins_OK" id="Ins_OK'.$colname_Course.'" value="true" type="hidden">';
					
			        }
			    	else{
					$insertSQL2 = sprintf("INSERT INTO signup_item (Signup_ID,Course_ID, SignupItem_Money, SignupItem_OriMoney, Add_Time, Edit_Time, Add_Account, Add_Username, Signup_Status, Com_ID, Member_ID) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s)",
	                   		   GetSQLValueString($row_Cate['Signup_ID'], "int"),
                       			   GetSQLValueString($colname_Course, "int"),
					   GetSQLValueString($colname_Money2, "int"),
					   GetSQLValueString($colname_Money, "int"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($row_Member['Member_ID'], "text"),
					   GetSQLValueString($colname_Username, "text"),
					   GetSQLValueString("未結單", "text"),
					   GetSQLValueString($colname_Com, "int"),
					   GetSQLValueString($row_Member['Member_ID'], "int"));
					
					mysql_select_db($database_dbline, $dbline);
					$Result2 = mysql_query($insertSQL2, $dbline) or die(mysql_error());
					$AutoID=mysql_insert_id($dbline);
					//課程詳細費用OP
					$query_PayData = "SELECT * from course_pay where Course_ID='".$colname_Course."' and CP_Enable=1";
					$PayData = mysql_query($query_PayData, $dbline) or die(mysql_error());
					$row_PayData= mysql_fetch_assoc($PayData);
					$totalRows_PayData = mysql_num_rows($PayData);
					
					if($totalRows_PayData>0){
						do{
							$query_PayData2 = "SELECT SIM_ID from signup_itemmoney where Course_ID='".$colname_Course."' and CP_Text='".$row_PayData['CP_Text']."' and Member_ID='".$row_Member['Member_ID']."' and Signup_ID='".$row_Cate['Signup_ID']."'";
							$PayData2 = mysql_query($query_PayData2, $dbline) or die(mysql_error());
							$row_PayData2= mysql_fetch_assoc($PayData2);
							$totalRows_PayData2 = mysql_num_rows($PayData2);
							$CP_Money=0;
							$CP_OriMoney=0;
							if(preg_match("/學分/i",$row_PayData['CP_Text'])){
								if($CO_Sale==1 && $P_Sale<>1){
									$CP_Money=$row_PayData['CP_Money']*$P_Sale;
									$P_Sale2=$P_Sale;
									$P_Text2=$P_Text;
								}
								else{
									$CP_Money=$row_PayData['CP_Money'];
									$P_Sale2=NULL;
									$P_Text2=NULL;
								}
							}
							else{
								$CP_Money=$row_PayData['CP_Money'];
								$P_Sale2=NULL;
								$P_Text2=NULL;
							}
							$CP_OriMoney=$row_PayData['CP_Money'];
							
							
							if($totalRows_PayData2<1){
								$insertSQL2 = sprintf("INSERT INTO signup_itemmoney (SignupItem_ID, CP_Text, Signup_ID, Course_ID, CP_Money, CP_OriMoney, P_Sale, P_Text, Member_ID, Com_ID, Add_Time, Edit_Time, Add_Account, Add_Username, Season_Code, Signup_Status, CP_Remark) VALUES (%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,   %s, %s, %s, %s, %s,   %s, %s)",
								   GetSQLValueString($AutoID, "int"),
								   GetSQLValueString($row_PayData['CP_Text'], "text"),
						                   GetSQLValueString($row_Cate['Signup_ID'], "int"),
					                           GetSQLValueString($colname_Course, "int"),
								   GetSQLValueString($CP_Money, "int"),
								   GetSQLValueString($CP_OriMoney, "int"),
								   GetSQLValueString($P_Sale2, "text"),
								   GetSQLValueString($P_Text2, "text"),
								   GetSQLValueString($row_Member['Member_ID'], "int"),
								   GetSQLValueString($colname_Com, "int"),
								   GetSQLValueString($AddTime, "date"),
								   GetSQLValueString($AddTime, "date"),
								   GetSQLValueString($row_Member['Member_ID'], "int"),
								   GetSQLValueString($colname_Username, "text"),
								   GetSQLValueString($colname_SeasonCode, "int"),
								   GetSQLValueString("未結單", "text"),
								   GetSQLValueString($row_PayData['CP_Remark'], "text"));
								mysql_select_db($database_dbline, $dbline);
								$Result2 = mysql_query($insertSQL2, $dbline) or die(mysql_error());
							}else{
								$updateSQL2 = sprintf("update signup_itemmoney set CP_Text=%s, CP_Remark=%s, CP_Money=%s, CP_OriMoney=%s, P_Sale=%s, P_Text=%s, Edit_Time=%s, Edit_Account=%s, Edit_Username=%s where SIM_ID=%s and Signup_OrderNumber is null ",
								   
								   GetSQLValueString($row_PayData['CP_Text'], "text"),
								   GetSQLValueString($row_PayData['CP_Remark'], "text"),						                   
								   GetSQLValueString($CP_Money, "int"), 
								   GetSQLValueString($CP_OriMoney, "int"), 
								   GetSQLValueString($P_Sale2, "text"), 
								   GetSQLValueString($P_Text2, "text"),
								  
								   GetSQLValueString($AddTime, "date"),
								   GetSQLValueString($row_Member['Member_ID'], "text"),
								   GetSQLValueString($colname_Username, "text"),
								   GetSQLValueString($row_PayData2['SIM_ID'], "int"));
								mysql_select_db($database_dbline, $dbline);
								$Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());

							}
							mysql_free_result($PayData2);
						}while($row_PayData= mysql_fetch_assoc($PayData));
					}
					mysql_free_result($PayData);
					//課程詳細費用ED
					
					/*查詢課程餘額 OP*/
					if($Season_IsAll==1){
						$query_onlinelist = sprintf("SELECT OnlineTotalNum FROM signup_countchoose_all where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
					}
					else{
						$query_onlinelist = sprintf("SELECT OnlineTotalNum FROM signup_countchoose where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
					}
					
					$onlinelist = mysql_query($query_onlinelist, $dbline) or die(mysql_error());
					$row_onlinelist = mysql_fetch_assoc($onlinelist);
					$totalRows_onlinelist = mysql_num_rows($onlinelist);
					if($totalRows_onlinelist>0){
						$online_TotalNum=$row_onlinelist['OnlineTotalNum'];
					}
					else{
						if($Season_IsAll==1){
							$query_onlinelist2 = sprintf("SELECT (Course_Online+Course_OnSite) as Course_Online,(Course_OnlineAdd+Course_OnSiteAdd) as Course_OnlineAdd FROM course where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
						}
						else{
							$query_onlinelist2 = sprintf("SELECT Course_Online,Course_OnlineAdd FROM course where Course_ID =%s", GetSQLValueString($colname_Course, "int"));
						}
						
						$onlinelist2 = mysql_query($query_onlinelist2, $dbline) or die(mysql_error());
						$row_onlinelist2 = mysql_fetch_assoc($onlinelist2);
						$totalRows_onlinelist2 = mysql_num_rows($onlinelist2);
						if($totalRows_onlinelist2>0){
							$online_TotalNum=(int)($row_onlinelist2['Course_Online']+$row_onlinelist2['Course_OnlineAdd']);
						}
						else{
							$online_TotalNum=0;
						}
						mysql_free_result($onlinelist2);
						
					}
					mysql_free_result($onlinelist);
					/*查詢課程餘額 END*/
					
					$updateSQL = sprintf("update course set Course_OnlineRemaining=%s where Course_ID=%s",
                       GetSQLValueString($online_TotalNum, "int"),
					   GetSQLValueString($colname_Course, "int"));
					
					mysql_select_db($database_dbline, $dbline);
					$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
					echo '<input name="Ins_OK" id="Ins_OK'.$colname_Course.'" value="true" type="hidden">';
					
	             		}/*判斷是否有此校訂單END*/
                 		mysql_free_result($Cate);
             		}/*判斷是否加選過END*/
             		mysql_free_result($Sign);
       }/*判斷名額END*/
    

 	mysql_select_db($database_dbline, $dbline);
	$query_CourseID = sprintf("SELECT * from signup_choose_student where Member_ID=%s and Signup_OrderNumber is null and Com_ID = %s and Season_ID=%s",GetSQLValueString($row_Member['Member_ID'], "text"),GetSQLValueString($colname_Com, "int"),GetSQLValueString($colname_Season, "int"));
	$CourseID = mysql_query($query_CourseID, $dbline) or die(mysql_error());
	$row_CourseID= mysql_fetch_assoc($CourseID);
	$totalRows_CourseID = mysql_num_rows($CourseID);
	
	echo '你已加選'.$totalRows_CourseID.'門課,<a href="AD_Signup_Detail.php?ID='.$colname_Season.'&Com='.$colname_Com.'">點此可以查看明細</a>';
	
	
	mysql_free_result($CourseID);
    mysql_free_result($Member);
?>
