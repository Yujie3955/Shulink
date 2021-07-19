<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin.php'); ?>
<?php 
$modulename=explode("_",basename(__FILE__, ".php"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);
/*權限*/
mysql_select_db($database_dbline, $dbline);
$query_Permission = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString($modulename[1], "text"));
$Permission = mysql_query($query_Permission, $dbline) or die(mysql_error());
$row_Permission = mysql_fetch_assoc($Permission);
$totalRows_Permission= mysql_num_rows($Permission);
?>
<?php require_once('module_setting.php'); ?>
<?php require_once('../../include/Permission.php');?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET['ID'])) {
  $colname_ID = $_GET['ID'];
}
$query_Data = sprintf("SELECT * from course_detail where Course_ID=%s and Com_ID Like %s and Unit_ID like %s and Course_Pass=1",GetSQLValueString($colname_ID, "int"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);



$query_TableData = sprintf("SELECT * FROM course_text where Com_ID Like %s and CourseText_Enable=1 and concat(';',ModuleSetting_Code,';') like %s  ORDER BY Com_ID ASC, CourseText_Sort asc ",GetSQLValueString($row_Data['Com_ID'], "text"),GetSQLValueString("%;Course;%", "text"));	

$TableData = mysql_query($query_TableData, $dbline) or die(mysql_error());
$row_TableData = mysql_fetch_assoc($TableData);
$totalRows_TableData = mysql_num_rows($TableData);

$teacher_modes='edits';

$SWeek_List=array("","第一週","第二週","第三週","第四週","第五週","第六週","第七週","第八週","第九週","第十週","第十一週","第十二週","第十三週","第十四週","第十五週","第十六週","第十七週","第十八週","第十九週","第二十週","第二十一週","第二十二週","第二十三週","第二十四週","第二十五週","第二十六週","第二十七週","第二十八週","第二十九週","第三十週","第三十一週","第三十二週","第三十三週","第三十四週","第三十五週","第三十六週");
?>


<?php

$Other = "修改".$row_Permission['ModuleSetting_Title'];
$page_modes="course_schedule";
$page2_modes="course";
$page_course_code="Course_ID";
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit_Course")) {
	$EditTime=date("Y-m-d H:i:s");

	//算名額OP
	$query_CateSign = sprintf("SELECT SignupItem_ID from signup_item where Course_ID=%s ",GetSQLValueString($_POST['ID'], "int"));
	$CateSign = mysql_query($query_CateSign, $dbline) or die(mysql_error());
	$row_CateSign = mysql_fetch_assoc($CateSign);
	$totalRows_CateSign = mysql_num_rows($CateSign);
	$signup_count=0;
	if($totalRows_CateSign>0){
		$signup_count=$totalRows_CateSign;
	}
	mysql_free_result($CateSign);
	
	$query_CateSign = sprintf("SELECT SignupRecord_ID from signup_record where Course_ID=%s ",GetSQLValueString($_POST['ID'], "int"));
	$CateSign = mysql_query($query_CateSign, $dbline) or die(mysql_error());
	$row_CateSign = mysql_fetch_assoc($CateSign);
	$totalRows_CateSign = mysql_num_rows($CateSign);
	$signuprecord_count=0;
	if($totalRows_CateSign>0){
		$signuprecord_count=$totalRows_CateSign;
	}
	mysql_free_result($CateSign);
	
	$query_CateP2 = sprintf("SELECT * from rule where Rule_ID=%s ",GetSQLValueString($row_Data['Rule_ID'], "int"));
	$CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
    	$row_CateP2 = mysql_fetch_assoc($CateP2);
	$totalRows_CateP2 = mysql_num_rows($CateP2);
	
    	if($signup_count<1 && $signuprecord_count<1 ){
	   if(mb_strlen($row_CateP2['Rule_Online'], "utf-8")<3){
		   $online_percent=(double)"0".".".str_pad($row_CateP2['Rule_Online'],2,"0",STR_PAD_LEFT);
	   }
	   else{
		   $online_percent=(double)$row_CateP2['Rule_Online']/100;
	   }
	   if(isset($_POST['Course_IsReserve'])&&$_POST['Course_IsReserve']==1){
		   $Course_Reserve=round((int)($_POST['Course_Max']) * (float)("0".".".str_pad($row_CateP2['Rule_Reserve'],2,"0",STR_PAD_LEFT)),0);
		   $Course_Online=round(((int)($_POST['Course_Max']) - $Course_Reserve)*($online_percent),0);
		   $Course_OnSite=((int)($_POST['Course_Max']) - $Course_Online-$Course_Reserve);
	   }
	   else{
		   $Course_Reserve=0;
		   $Course_Online=round(((int)($_POST['Course_Max']) - $Course_Reserve)*($online_percent),0);
		   $Course_OnSite=((int)($_POST['Course_Max']) - $Course_Online-$Course_Reserve);
	   }
	}
	else{
		$Course_Online=$_POST['Course_Online'];
		$Course_OnSite=$_POST['Course_OnSite'];
		$Course_Reserve=$_POST['Course_Reserve'];		
    	}
	if(isset($_POST['Course_IsReserve']) &&$_POST['Course_IsReserve']==1){
			$Course_IsReserve=1;
	}else{
			$Course_IsReserve=0;	
	}
	
	if(isset($_POST['Course_Pic']) && $_POST['Course_Pic']<>""){
		$Course_Pic=join(";",$_POST['Course_Pic']);
		$Course_PicText=join(";",$_POST['Course_PicText']);
	}
	else{
		$Course_Pic=NULL;
		$Course_PicText=NULL;
	}
	if((isset($_POST['Course_StartHour2']) && $_POST['Course_StartHour2']<>"") || (isset($_POST['Course_StartMinute2']) && $_POST['Course_StartMinute2']<>"")){
			if(isset($_POST['Course_StartHour2']) && $_POST['Course_StartHour2']<>""){
				$Course_Start2Hour=$_POST['Course_StartHour2'];
			}
			else{
				$Course_Start2Hour="00";
			}
			if(isset($_POST['Course_StartMinute2']) && $_POST['Course_StartMinute2']<>""){
				$Course_StartMinute2=$_POST['Course_StartMinute2'];
			}
			else{
				$Course_StartMinute2="00";
			}
			$Course_Start2=$Course_Start2Hour.":".$Course_StartMinute2.":00";	
	}
	else{
			$Course_Start2=NULL;
	}
	if((isset($_POST['Course_EndHour2']) && $_POST['Course_EndHour2']<>"") || (isset($_POST['Course_EndMinute2']) && $_POST['Course_EndMinute2']<>"")){
			if(isset($_POST['Course_EndHour2']) && $_POST['Course_EndHour2']<>""){
				$Course_EndHour2=$_POST['Course_EndHour2'];
			}
			else{
				$Course_EndHour2="00";
			}
			if(isset($_POST['Course_EndMinute2']) && $_POST['Course_EndMinute2']<>""){
				$Course_EndMinute2=$_POST['Course_EndMinute2'];
			}
			else{
				$Course_EndMinute2="00";
			}
			$Course_End2=$Course_EndHour2.":".$Course_EndMinute2.":00";	
	}
	else{
			$Course_End2=NULL;
	}


	//算名額END

	$updateSQL = sprintf("update course set
	   
	   SeasonWeek_Name=%s, SeasonWeek_Number=%s, Course_TeacherResume=%s,
	   
	   Course_Name=%s, Course_Remark=%s, Course_IsShrine=%s, CourseRepeat_Name=%s, CourseStatus_Name=%s,

	   CourseProperty_Name=%s, CourseProgram_Name=%s, CourseArea_Name=%s, CourseKind_Name=%s, CourseKindCate_Name=%s,
	   
	   Loc_Name=%s, Loc_Address=%s, Room_Name=%s, Course_Min=%s, Course_Max=%s, 

	   Course_StartWeek=%s, Course_StartDay=%s, Course_Day1=%s, Course_Start1=%s, Course_End1=%s,

	   Course_TDay1=%s, Course_TDay2=%s, Course_TDay3=%s,  
	   
	   Loc_Name2=%s, Room_Name2=%s, Course_Credit=%s, Course_Weekhour=%s, Credit_Money=%s,
	   
	   CO_Text=%s, CO_Sale=%s, Credit2_Money=%s, Credit2_Name=%s, Pro_Money=%s, 
	   
	   Course_Leader=%s, Course_Aim=%s, Course_Idea=%s,  Course_Limit=%s, Course_Item=%s, 
	   
	   Course_Method=%s, Course_Evaluation=%s, Course_Condition=%s, Course_Books=%s, Course_Youtube=%s, 
	   
	   Course_ItemWeb=%s, Course_UseItem=%s, Course_Online=%s, Course_OnSite=%s, Course_Reserve=%s,

	   Course_OnlineRemaining=%s, Course_OnSiteRemaining=%s, Course_ReserveRemaining=%s, Course_Time=%s,

	   Course_Day2=%s, Course_Start2=%s, Course_End2=%s, Edit_Time=%s,
	   
	   Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s, Course_Cate=%s, Course_Pic=%s, Course_PicText=%s, Course_Special=%s where Course_ID=%s",
	   				   
                       			   
					   

					   
					   GetSQLValueString($_POST['SeasonWeek_Name'], "text"),
					   GetSQLValueString($_POST['SeasonWeek_Number'], "int"),
					   GetSQLValueString($_POST['Course_TeacherResume'], "text"),
					   
					   GetSQLValueString($_POST['Course_Name'], "text"),
					   GetSQLValueString($_POST['Course_Remark'], "text"),
					   GetSQLValueString($_POST['Course_IsShrine'], "int"),
					   GetSQLValueString($_POST['CourseRepeat_Name'], "text"),
					   GetSQLValueString($_POST['CourseStatus_Name'], "text"),
					   
					   GetSQLValueString($_POST['CourseProperty_Name'], "text"),
					   GetSQLValueString($_POST['CourseProgram_Name'], "text"),
					   GetSQLValueString($_POST['CourseArea_Name'], "text"),
					   GetSQLValueString($_POST['CourseKind_Name'], "text"),					   
					   GetSQLValueString($_POST['CourseKindCate_Name'], "text"),
					   
					   GetSQLValueString($_POST['Loc_Name'], "text"),
					   GetSQLValueString($_POST['Loc_Address'],"text"),
					   GetSQLValueString($_POST['Room_Name'],"text"),
					   GetSQLValueString($_POST['Course_Min'], "int"),
					   GetSQLValueString($_POST['Course_Max'], "int"),

					   GetSQLValueString($_POST['Course_StartWeek'], "text"),
					   GetSQLValueString($_POST['Course_StartDay'], "date"),
					   GetSQLValueString($_POST['Course_Day1'], "text"),					   
					   GetSQLValueString($_POST['Course_StartHour1'].":".$_POST['Course_StartMinute1'].":00", "date"),
					   GetSQLValueString($_POST['Course_EndHour1'].":".$_POST['Course_EndMinute1'].":00", "date"),

					   GetSQLValueString($_POST['Course_TDay1'], "text"),	
					   GetSQLValueString($_POST['Course_TDay2'], "text"),	
					   GetSQLValueString($_POST['Course_TDay3'], "text"),	
					   
					   GetSQLValueString($_POST['Loc_Name2'], "text"),
					   GetSQLValueString($_POST['Room_Name2'],"text"),
					   GetSQLValueString($_POST['Course_Credit'], "int"),
					   GetSQLValueString($_POST['Course_Weekhour'], "int"),
					   GetSQLValueString($_POST['Credit_Money'], "int"),

					   GetSQLValueString($_POST['CO_Text'], "text"),
					   GetSQLValueString($_POST['CO_Sale'], "text"),
					   GetSQLValueString($_POST['Credit2_Money'], "int"),
					   GetSQLValueString($_POST['Credit2_Name'], "text"),
					   GetSQLValueString($_POST['Pro_Money'], "int"),

					   GetSQLValueString(NULL, "text"),
					   GetSQLValueString($_POST['Course_Aim'], "text"),
					   GetSQLValueString($_POST['Course_Idea'], "text"),
					   GetSQLValueString($_POST['Course_Limit'], "text"),
					   GetSQLValueString($_POST['Course_Item'], "text"),

					   GetSQLValueString($_POST['Course_Method'], "text"),
					   GetSQLValueString($_POST['Course_Evaluation'], "text"),
					   GetSQLValueString($_POST['Course_Condition'], "text"),
					   GetSQLValueString($_POST['Course_Books'], "text"),
					   GetSQLValueString($_POST['Course_Youtube'], "text"),

					   GetSQLValueString($_POST['Course_ItemWeb'], "text"),
					   GetSQLValueString(@join(";",$_POST['Course_UseItem']), "text"),
					   GetSQLValueString($Course_Online, "int"),
					   GetSQLValueString($Course_OnSite, "int"),
					   GetSQLValueString($Course_Reserve, "int"),

					   GetSQLValueString($Course_Online, "int"),
					   GetSQLValueString($Course_OnSite, "int"),
					   GetSQLValueString($Course_Reserve, "int"),
					   GetSQLValueString($_POST['Course_Time'], "text"),

					   
					   GetSQLValueString($_POST['Course_Day2'], "text"),					   
					   GetSQLValueString($Course_Start2, "date"),
					   GetSQLValueString($Course_End2, "date"),
					   GetSQLValueString($EditTime, "date"),

					   GetSQLValueString($_POST['Edit_Account'], "text"),
					   GetSQLValueString($_POST['Edit_Unitname'], "text"),
					   GetSQLValueString($_POST['Edit_Username'], "text"),
					   GetSQLValueString($_POST['Course_Cate'], "text"),
					   GetSQLValueString($Course_Pic, "text"),
					   GetSQLValueString($Course_PicText, "text"),
					   GetSQLValueString(@join(";",$_POST['Course_Special']), "text"),
					   GetSQLValueString($_POST['ID'], "int"));	               
					   
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		$Response_ID=$_POST['ID'];
		//老師OP
	      //刪除之前的
	      $deleteSQL = sprintf("DELETE FROM teacher_record WHERE Course_ID=%s",
	                       GetSQLValueString($Response_ID, "int"));

	      mysql_select_db($database_dbline, $dbline);
	      $Result1_3 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
		  //刪除END
		  
		
	      $Total_ID=array();
	      $Total_Name=array();
	      $Total_IDText='';
	      $Total_NameText='';
	      $Course_TeacherID='';
		  
	      if(isset($_POST['Teacher_ID']) && $_POST['Teacher_ID']<>""){
			  
			  $Teacher_ID=$_POST['Teacher_ID'];
			  if ( strpos($_POST['Teacher_Name'], '(') !== false && strpos($_POST['Teacher_Name'], ')') !== false) {
				  $TeacherStr=explode("(",$_POST['Teacher_Name']);
				  $Teacher_Name=$TeacherStr[0];
			  }
			  else{
				  $Teacher_Name='';
			  }
			  $Course_TeacherID=$Teacher_ID;

			  if(!in_array($Teacher_ID,$Total_ID)){
			  	array_push($Total_ID,$Teacher_ID);
				array_push($Total_Name,$Teacher_Name);
			  }
			  $query_TM = sprintf("SELECT * from teacher_record where Teacher_ID=%s and Course_ID=%s",GetSQLValueString($Teacher_ID, "int"),GetSQLValueString($Response_ID, "int"));
			  $TM = mysql_query($query_TM, $dbline) or die(mysql_error());
			  $row_TM = mysql_fetch_assoc($TM);
			  $totalRows_TM = mysql_num_rows($TM);
			  
			  if($totalRows_TM<1){
				  $insertSQL2 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName) VALUES (%s, %s, %s)",
								   GetSQLValueString($Teacher_ID, "int"),
								   GetSQLValueString($Response_ID, "int"),
								   GetSQLValueString($Teacher_Name, "text"));
				  mysql_select_db($database_dbline, $dbline);
				  $Result2 = mysql_query($insertSQL2, $dbline) or die(mysql_error());	
				 
			  }
			  mysql_free_result($TM);
	      }	
		  //協同導師	   
		  if(isset($_POST['TeachersID']) && $_POST['TeachersID']<>""){
			  $Teacher_ID2List=$_POST['TeachersID'];
			  for($teacher_count=0;$teacher_count<count($Teacher_ID2List);$teacher_count++){
				  $Teacher_Names2List=$_POST['Teachers'][$teacher_count];
				  $Teacher_ID2=$Teacher_ID2List[$teacher_count];
				  if ( strpos($Teacher_Names2List, '(') !== false && strpos($Teacher_Names2List, ')') !== false) {
					  $TeacherStr2=explode("(",$Teacher_Names2List);
					  $Teacher_Name2=$TeacherStr2[0];
					  $Teacher_Identity2=substr($TeacherStr2[1],0,-1);
			  	  }
				  else{
					  $Teacher_Name2='';
				  }
	
				  if(!in_array($Teacher_ID2,$Total_ID)){
				  	array_push($Total_ID,$Teacher_ID2);
					array_push($Total_Name,$Teacher_Name2);
				  }
				  
				  $query_TM = sprintf("SELECT * from teacher_record where Teacher_ID=%s and Course_ID=%s",GetSQLValueString($Teacher_ID2, "int"),GetSQLValueString($Response_ID, "int"));
				  $TM = mysql_query($query_TM, $dbline) or die(mysql_error());
				  $row_TM = mysql_fetch_assoc($TM);
				  $totalRows_TM = mysql_num_rows($TM);
				  if($totalRows_TM<1){
					  $insertSQL2_2 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName) VALUES (%s, %s, %s)",
								   GetSQLValueString($Teacher_ID2, "int"),
								   GetSQLValueString($Response_ID, "int"),
								   GetSQLValueString($Teacher_Name2, "text"));
				   
					  mysql_select_db($database_dbline, $dbline);
					  $Result2_2 = mysql_query($insertSQL2_2, $dbline) or die(mysql_error());
				  }
				  mysql_free_result($TM);
		      }
	      }
	  //老師ED
    	  //新增費用進去OP
	  //刪除原本的
	  $deleteSQL = sprintf("DELETE FROM course_pay WHERE Course_ID=%s",
                       GetSQLValueString($Response_ID, "int"));

	  mysql_select_db($database_dbline, $dbline);
	  $Result1_3 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
	  $Course_Money=0;	  
	  //學分費OP
	  $Course_Credits=$row_CateP2['Rule_Credit']*$_POST['Course_Credit']*$_POST['CO_Sale'];
	  if($Course_Credits>0){
		  $Course_Credits=ceil($Course_Credits);
	  }
	  $Course_Money=$Course_Money+$Course_Credits;
	  $query_CPM = sprintf("SELECT * from course_pay where Course_ID=%s and CP_Text=%s",GetSQLValueString($Response_ID, "int"),GetSQLValueString("學分費", "text"));
	  $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
	  $row_CPM = mysql_fetch_assoc($CPM);
	  $totalRows_CPM = mysql_num_rows($CPM);
	  if($totalRows_CPM<1){
			   $insertSQL=sprintf("INSERT INTO course_pay(CP_Text, CP_Money, Course_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
						   GetSQLValueString("學分費", "text"),
						   GetSQLValueString($Course_Credits, "int"),
						   GetSQLValueString($Response_ID, "int"),
						   GetSQLValueString($_POST['Season_Code'], "int"),
						   GetSQLValueString($_POST['Com_ID'], "int"),
						   GetSQLValueString(1, "int"),
						   GetSQLValueString(1, "int"), 					   					   
						   GetSQLValueString($_POST['Add_Time'], "date"),
						   GetSQLValueString($_POST['Add_Time'], "date"),
						   GetSQLValueString($_POST['Add_Account'], "text"),					   
						   GetSQLValueString($_POST['Add_Unitname'], "text"),
						   GetSQLValueString($_POST['Add_Username'], "text"));
			   mysql_select_db($database_dbline, $dbline);
			   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
	  }
	  mysql_free_result($CPM);
	  //學分費ED
	  //雜費OP
	  $Course_Credits2=$_POST['Credit2_Money'];
	  if($Course_Credits2>0){
		  $Course_Credits2=ceil($Course_Credits2);
	  }	  	  
	  $Course_Money=$Course_Money+$Course_Credits2;
	  $query_CPM = sprintf("SELECT * from course_pay where Course_ID=%s and CP_Text=%s",GetSQLValueString($Response_ID, "int"),GetSQLValueString("雜費", "text"));
	  $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
	  $row_CPM = mysql_fetch_assoc($CPM);
	  $totalRows_CPM = mysql_num_rows($CPM);
	  if($totalRows_CPM<1){
		$insertSQL=sprintf("INSERT INTO course_pay(CP_Text, CP_Money, CP_Remark, Course_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s, %s)",
							   GetSQLValueString("雜費", "text"),
							   GetSQLValueString($Course_Credits2, "int"),
							   GetSQLValueString($_POST['Credit2_Name'], "text"),
							   GetSQLValueString($Response_ID, "int"),
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString($_POST['Com_ID'], "int"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString(1, "int"), 					   					   
							   GetSQLValueString($_POST['Add_Time'], "date"),
							   GetSQLValueString($_POST['Add_Time'], "date"),
							   GetSQLValueString($_POST['Add_Account'], "text"),					   
							   GetSQLValueString($_POST['Add_Unitname'], "text"),
							   GetSQLValueString($_POST['Add_Username'], "text"));
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
	  }
	  mysql_free_result($CPM);
	  //雜費ED
	  //課程保證金OP
	  $Course_Pro=$_POST['Pro_Money'];
	  if($Course_Pro>0){
		  $Course_Pro=ceil($Course_Pro);
	  }	  	  
	  $Course_Money=$Course_Money+$Course_Pro;
	  $query_CPM = sprintf("SELECT * from course_pay where Course_ID=%s and CP_Text=%s",GetSQLValueString($Response_ID, "int"),GetSQLValueString("課程保證金", "text"));
	  $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
	  $row_CPM = mysql_fetch_assoc($CPM);
	  $totalRows_CPM = mysql_num_rows($CPM);
	  if($totalRows_CPM<1){
		$insertSQL=sprintf("INSERT INTO course_pay(CP_Text, CP_Money, Course_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
							   GetSQLValueString("課程保證金", "text"),
							   GetSQLValueString($Course_Pro, "int"),
							   GetSQLValueString($Response_ID, "int"),
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString($_POST['Com_ID'], "int"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString(1, "int"), 					   					   
							   GetSQLValueString($_POST['Add_Time'], "date"),
							   GetSQLValueString($_POST['Add_Time'], "date"),
							   GetSQLValueString($_POST['Add_Account'], "text"),					   
							   GetSQLValueString($_POST['Add_Unitname'], "text"),
							   GetSQLValueString($_POST['Add_Username'], "text"));
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
	  }
	  mysql_free_result($CPM);
	  //課程保證金ED
	  
	  //新增費用進去ED
	  if($Total_ID<>""){
		  $Total_IDText=join(",",$Total_ID);  
	  }
	  if($Total_Name<>""){
		  $Total_NameText=join(",",$Total_Name);
	  }
	  $updateSQL3 = sprintf("update course set Course_TeacherID=%s, Teacher_ID=%s, Teacher_UserName=%s, Course_Money=%s where Course_ID=%s",
                       			   GetSQLValueString($Course_TeacherID, "text"),
                       			   GetSQLValueString($Total_IDText, "text"),
					   GetSQLValueString($Total_NameText, "text"),
					   GetSQLValueString($Course_Money, "int"),
					   GetSQLValueString($Response_ID, "int"));
	  mysql_select_db($database_dbline, $dbline);
	  $Result3 = mysql_query($updateSQL3, $dbline) or die(mysql_error());	
	  require_once('../../Include/Data_BrowseUpdate.php');
	  mysql_free_result($CateP2);
	  $insertGoTo = $_SERVER['PHP_SELF']."?Msg=UpdateOK&ID=".$Response_ID;	  
          header(sprintf("Location: %s", $insertGoTo));
}



if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit_Status1")) {
	if(isset($_POST['sub_check1']) && $_POST['sub_check1']=='通過'){
		if(isset($_POST['Course_Check1Time']) && $_POST['Course_Check1Time']<>""){
			$Course_Check1Status='修正通過';
		}
		else{
			$Course_Check1Status='通過';
		}
		$Course_Check1=1;
		
	}
	else if(isset($_POST['sub_check1']) && $_POST['sub_check1']=='不通過'){
		$Course_Check1Status='不通過';	
		$Course_Check1=2;
		$Audit='初審';
		$Reason=$_PSOT['Course_Check1Remark'];
		require_once("../../Tools/PHPMailer-ML_v1.8.1_core/_acp-ml/modules/phpmailer/class.phpmailer.php");
		require_once("FailMail.php");
	}
	if(isset($_POST['sub_check1']) && ($_POST['sub_check1']=='通過' || $_POST['sub_check1']=='不通過')){
		$updateSQL = sprintf("update course set Course_Check1=%s, Course_Check1Status=%s, Course_Check1Remark=%s, Course_Check1Time=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username =%s where Course_ID=%s",
			   				   GetSQLValueString($Course_Check1, "text"),	
							   GetSQLValueString($Course_Check1Status, "text"),	
							   GetSQLValueString($_POST['Course_Check1Remark'], "text"),	
							   GetSQLValueString(date("Y-m-d H:i:s"), "date"),
							   GetSQLValueString(date("Y-m-d H:i:s"), "date"),
							   GetSQLValueString($_POST['Edit_Account'], "text"),
							   GetSQLValueString($_POST['Edit_Unitname'], "text"),
							   GetSQLValueString($_POST['Edit_Username'], "text"),
							   GetSQLValueString($_POST['ID'], "int"));
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	}
	$insertGoTo = $_SERVER['PHP_SELF']."?Msg=UpdateOK&ID=".$_POST['ID'];
	header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit_Status2")) {
	if(isset($_POST['sub_check2']) && $_POST['sub_check2']=='通過'){
		if(isset($_POST['Course_Check2Time']) && $_POST['Course_Check2Time']<>""){
			$Course_Check2Status='修正通過';
		}
		else{
			$Course_Check2Status='通過';
		}
		$Course_Check2=1;
		
	}
	else if(isset($_POST['sub_check2']) && $_POST['sub_check2']=='不通過'){
		$Course_Check2Status='不通過';	
		$Course_Check2=2;
		$Audit='複審';
		$Reason=$_PSOT['Course_Check2Remark'];
		require_once("../../Tools/PHPMailer-ML_v1.8.1_core/_acp-ml/modules/phpmailer/class.phpmailer.php");
		require_once("FailMail.php");
	}
	if(isset($_POST['sub_check2']) && ($_POST['sub_check2']=='通過' || $_POST['sub_check2']=='不通過')){
		$updateSQL = sprintf("update course set Course_Check2=%s, Course_Check2Status=%s, Course_Check2Remark=%s, Course_Check2Time=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username =%s where Course_ID=%s",
			   				   GetSQLValueString($Course_Check2, "text"),	
							   GetSQLValueString($Course_Check2Status, "text"),	
							   GetSQLValueString($_POST['Course_Check2Remark'], "text"),	
							   GetSQLValueString(date("Y-m-d H:i:s"), "date"),
							   GetSQLValueString(date("Y-m-d H:i:s"), "date"),
							   GetSQLValueString($_POST['Edit_Account'], "text"),
							   GetSQLValueString($_POST['Edit_Unitname'], "text"),
							   GetSQLValueString($_POST['Edit_Username'], "text"),
							   GetSQLValueString($_POST['ID'], "int"));
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	}
	
	$insertGoTo = $_SERVER['PHP_SELF']."?Msg=UpdateOK&ID=".$_POST['ID'];
	header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit_Status3")) {
	if(isset($_POST['sub_check3']) && $_POST['sub_check3']=='通過'){
		if(isset($_POST['Course_Check3Time']) && $_POST['Course_Check3Time']<>""){
			$Course_Check3Status='修正通過';
		}
		else{
			$Course_Check3Status='通過';
		}
		$Course_Check3=1;
		
	}
	else if(isset($_POST['sub_check3']) && $_POST['sub_check3']=='不通過'){
		$Course_Check3Status='不通過';	
		$Course_Check3=2;
		$Audit='決審';
		$Reason=$_PSOT['Course_Check3Remark'];
		require_once("../../Tools/PHPMailer-ML_v1.8.1_core/_acp-ml/modules/phpmailer/class.phpmailer.php");
		require_once("FailMail.php");
	}
	if(isset($_POST['sub_check3']) && ($_POST['sub_check3']=='通過' || $_POST['sub_check3']=='不通過')){
		$updateSQL = sprintf("update course set Course_Check3=%s, Course_Check3Status=%s, Course_Check3Remark=%s, Course_Check3Time=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username =%s where Course_ID=%s",
			   				   GetSQLValueString($Course_Check3, "text"),	
							   GetSQLValueString($Course_Check3Status, "text"),	
							   GetSQLValueString($_POST['Course_Check3Remark'], "text"),	
							   GetSQLValueString(date("Y-m-d H:i:s"), "date"),
							   GetSQLValueString(date("Y-m-d H:i:s"), "date"),
							   GetSQLValueString($_POST['Edit_Account'], "text"),
							   GetSQLValueString($_POST['Edit_Unitname'], "text"),
							   GetSQLValueString($_POST['Edit_Username'], "text"),
							   GetSQLValueString($_POST['ID'], "int"));
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	}
	 $str_url='';
	if(isset($_POST['IsT']) && $_POST['IsT']==1){
	   $str_url="&IsT=1";
	}
	$insertGoTo = $_SERVER['PHP_SELF']."?Msg=UpdateOK&ID=".$_POST['ID'].$str_url;
	header(sprintf("Location: %s", $insertGoTo));
}
require_once("../SetData/Course_ScheduleSQL.php");
?> 


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Tools/JQFileUpLoad/JQFileUpLoad.php'); ?>
<script src="../../ckeditor/ckeditor.js"></script>
<!--驗證CSS OP-->
<?php require_once('../../Include/spry_style.php'); ?>
<style type="text/css">
.Course_Add{display:inline-block;}
label{float:none;}
</style>
<!--驗證CSS ED-->
<!--分頁TAB OP-->
<link href="../../css/TabStyle.css" rel="stylesheet" type="text/css">
<!--分頁TAB END-->
</head>
<body>
<!-- Body Top Start -->
<?php require_once('../../Include/Admin_Body_Top.php'); ?>
<?php require_once('../../Include/Menu_AdminLeft.php'); ?>
<!-- Body Top End -->
<!--Body menu top Start-->
<?php //require_once('../../Include/Admin_menu_upon.php'); ?>
<!--Body menu top End-->
<!--Body Layout up Start-->
<?php //require_once('../../Include/Admin_Body_Layout_up.php'); ?>
<!--Body Layout up End-->
<?php if(@$_GET['Msg'] == "AddOK"){ ?>
	<script language="javascript">
	function AddOK(){
		$('.Success_Add').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddOK,0);
    </script>
<?php } ?>
<?php if(@$_GET["Msg"] == "DelOK"){ ?>
	<script language="javascript">
	function Success_Del(){
		$('.Success_Del').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(Success_Del,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "UpdateOK"){ ?>
	<script language="javascript">
	function UpdateOK(){
		$('.UpdateOK').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(UpdateOK,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "DelError"){ ?>
	<script language="javascript">
	function DelError(){
		$('.DelError').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(DelError,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "AddError"){ ?>
	<script language="javascript">
	function AddError(){
		$('.AddError').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddError,0);
    </script>
<?php } ?>
<div >   
	<center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">修改<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></div>
	<div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg DelError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料刪除失敗，此課程已有學員報名</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
           <div class="Error_Msg AddError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料登錄失敗！</div>
	</div>
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
      <input type="button" value="回課程管理" onclick="location.href='AD_Data_Index.php'" class="Button_General"></td>
      </tr>
    </table>
    </div>
    <?php if($row_Permission['Per_Edit'] == 1){ ?>
    <div class="news" style="position:relative;z-index:5;float:left;">
                   <div class="tab" align="left" >
                   <ul>
                   <li class="<?php if(isset($_GET['Pg']) && $_GET['Pg']=='Schedule'){echo '';}else{echo 'current';}?>" id="AreaItem_Area1"><div><a href="javascript:showNewsArea('Area1','0','#005881')">基本資料</a></div></li>
		   <li class="<?php if(isset($_GET['Pg']) && $_GET['Pg']=='Schedule'){echo 'current';}else{echo '';}?>" id="AreaItem_Area2"><div><a href="javascript:showNewsArea('Area2','0','#005881')">課程大綱</a></div></li>
                   </ul>
                   </div>
             
              <div class="body SelfTableClear BlockBig BlueBlock" id="Area_Area1" style="display:<?php if(isset($_GET['Pg']) && $_GET['Pg']=='Schedule'){echo 'none';}else{echo 'block';}?>;float:none; width:100%;" >
                  <div class="WhiteBlock">
                  <br/>
    <?php if($totalRows_Data>0){?>
    <?php require_once("../SetData/Course_Check.php");?>
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit_Course" id="Form_Edit_Course" method="POST">
    <div align="center">
    
    <?php if($totalRows_TableData>0){
		echo '<ol>';
				do{
				if($row_TableData['CourseText_ShowTitle']==1){$shows=1;}else{$shows=0;}
					?>
                <?php if($shows==1){?>
				
				<li style=" padding:0px;border-bottom:1px; border-style:solid; border-color:#CCC;">
				<table cellpadding="5" cellspacing="0" border="0">
				<tr>
				<td nowrap>
                		<?php echo $row_TableData['CourseText_Title']."：";?>
				</td>
				<td>        
				<?php require_once('../SetData/'.$row_TableData['CourseText_Fields'].".php");?>
				</td>
                		</tr>
				</table>
				</li>
                <?php }else{
				require_once('../SetData/'.$row_TableData['CourseText_Fields'].".php");
			}?>
                	
                   		
	<?php 		}while($row_TableData=mysql_fetch_assoc($TableData));
		echo '</ol>';
		  }mysql_free_result($TableData);?>
	

    
    <input name="ID" type="hidden" id="ID" value="<?php echo $_GET['ID']; ?>">
    <input name="Course_Online" type="hidden" id="Course_Online" value="<?php echo $row_Data['Course_Online']; ?>">
    <input name="Course_OnSite" type="hidden" id="Course_OnSite" value="<?php echo $row_Data['Course_OnSite']; ?>">
    <input name="Course_Reserve" type="hidden" id="Course_Reserve" value="<?php echo $row_Data['Course_Reserve']; ?>">
    <input type="submit" value="確定更新" class="Button_Submit"/>  <input type="reset" value="重填" class="Button_General"/>  
<input name="IsT" type="hidden" id="IsT" value="<?php if(isset($_GET["IsT"]) && $_GET["IsT"]==1){echo $_GET["IsT"];}?>'"/>
    </div>
    <input name="MM_update" id="MM_update" value="Form_Edit_Course" type="hidden">
    
    </form><br/>
	<div align="center"><input type="button" value="回課程管理" onclick="location.href='AD_Data_Index.php';" class="Button_General"><br/><br/></div>
    </div></div>
    <div class="body SelfTableClear BlockBig BlueBlock" id="Area_Area2" style="display:<?php if(isset($_GET['Pg']) && $_GET['Pg']=='Schedule'){echo 'block';}else{echo 'none';}?>;float:none; width:100%;" >
                  <div class="WhiteBlock">
	<?php require_once("../SetData/Course_Schedule.php");?>
	<br/>
	<div align="center"><input type="button" value="回課程管理" onclick="location.href='AD_Data_Index.php';" class="Button_General"><br/><br/></div>
    </div></div>




     <?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權修改此資料</div>   <?php }?><br><br><br>
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能修改權限</div>    
    <?php } ?>
        </td>
      </tr>
    </table>
    <br><br><br>
	<center>
</div>      

<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->

<script type="text/javascript">
var currentNewsArea;
<?php if(isset($_GET['Pg']) && $_GET['Pg']=='Schedule'){?>
currentNewsArea= 'Area2';
<?php }else{?>
currentNewsArea= 'Area1';
<?php }?>
function showNewsArea(area,CateID,Color){
	if(area!=currentNewsArea){
	       $("#Area_"+area).toggle();
	       $("#AreaItem_"+area).addClass("current");
	       document.getElementById("AreaItem_"+area).style.background = Color;
	                            
	                           
	       $("#Area_"+currentNewsArea).toggle();
	       $("#AreaItem_"+currentNewsArea).removeClass("current");
	       document.getElementById("AreaItem_"+currentNewsArea).style.background = "#dfdfdf";
	       currentNewsArea = area;
	}
}
</script>
</body>
</html>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php
mysql_free_result($Data);
?>