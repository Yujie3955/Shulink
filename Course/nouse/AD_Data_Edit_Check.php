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
mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * from courseteacher_detail where CourseTeacher_Pass<>1 and CourseTeacher_ID=%s and Com_ID Like %s and Unit_ID like %s",GetSQLValueString($colname_ID, "int"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);


mysql_select_db($database_dbline, $dbline);
$query_Cate2 = sprintf("SELECT * FROM community where Com_Enable=1 and Com_ID Like %s and Com_ID<>4 and Com_IsPrivate <> 1 ORDER BY Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

mysql_select_db($database_dbline, $dbline);
$query_Cate3 = "SELECT CourseKind_ID,CourseKind_Name FROM course_kind ORDER BY CourseKind_Sort ASC,Add_Time asc";
$Cate3 = mysql_query($query_Cate3, $dbline) or die(mysql_error());
$row_Cate3 = mysql_fetch_assoc($Cate3);
$totalRows_Cate3 = mysql_num_rows($Cate3);





if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit")) {
	
	if(isset($_POST['Course_IsNotOnline']) && $_POST['Course_IsNotOnline']==1){
		  $Course_Private=0;
    }
    else{
		  $Course_Private=1;
	}
	if(isset($_POST['Course_Audit']) && $_POST['Course_Audit']==1){
			$Course_Audit=1;
	}
	else{
			$Course_Audit=2;
	}
	if(isset($_POST['Course_Check']) && $_POST['Course_Check']==1){
			$Course_Check=1;
	}
	else{
			$Course_Check=2;
			$Course_Audit=2;
	}
	$EditTime=date("Y-m-d H:i:s");
	$query_CateP2 = sprintf("SELECT * from rule where Rule_ID=%s ",GetSQLValueString($row_Data['Rule_ID'], "int"));
    $CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
	$row_CateP2 = mysql_fetch_assoc($CateP2);
	$totalRows_CateP2 = mysql_num_rows($CateP2);
	
	
	
	if(isset($_POST['Course_Check']) && $_POST['Course_Check']=="1"){
       $Other = "新增".$row_Permission['ModuleSetting_Title'];
	   mysql_select_db($database_dbline, $dbline);
		$query_SeasonData = sprintf("SELECT * from season where Season_Code=%s and Com_ID=%s",GetSQLValueString($_POST['Season_Code'], "int"),GetSQLValueString($_POST['Com_ID'], "int"));
		$SeasonData = mysql_query($query_SeasonData, $dbline) or die(mysql_error());
		$row_SeasonData = mysql_fetch_assoc($SeasonData);
		$totalRows_SeasonData = mysql_num_rows($SeasonData);
		$Course_OnlineStart=$row_SeasonData['Season_SelectStart'];
		$Course_OnlineEnd=$row_SeasonData['Season_SelectEnd'];
		$Course_OnsiteStart=$row_SeasonData['Season_OnsiteStart'];
		$Course_OnsiteEnd=$row_SeasonData['Season_OnsiteEnd'];
		$Course_GroupStart=$row_SeasonData['Season_GroupStart'];
		$Course_GroupEnd=$row_SeasonData['Season_GroupStart'];
		mysql_free_result($SeasonData);	  
	   if(isset($_POST['Course_Audit']) && $_POST['Course_Audit']==1){
		   if($_POST['CourseTeacher_Day']>0 && $_POST['CourseTeacher_Day']<8){
			$Course_Day=$_POST['CourseTeacher_Day'];
		   }
		   else{
			$Course_Day=0;
		   }
		   $query_CateP = sprintf("SELECT max(Course_NOCount) as Max_Course FROM course where Com_ID=%s and course.Season_Code = %s",GetSQLValueString($_POST['Com_ID'], "int"),GetSQLValueString($_POST['Season_Code'], "int"));
		   $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
		   $row_CateP = mysql_fetch_assoc($CateP);
		   $totalRows_CateP = mysql_num_rows($CateP);
		   if($totalRows_CateP>0){
			  $id=str_pad($row_CateP['Max_Course']+1,2,"0",STR_PAD_LEFT);
		   }
		   else{$id=str_pad("0",2,"0",STR_PAD_LEFT); }
		   $Course_NO=$_POST['Season_Code'].$DayCode[$Course_Day].$id;
		   $Course_NOCount=$id;
	   }
	   else{
		   $Course_NO=NULL;
		   $Course_NOCount=NULL;
		   
	   }
	   //算人數OP
	   
	   if(mb_strlen($row_CateP2['Rule_Online'], "utf-8")<3){
		    	$online_percent=(float)"0".".".str_pad($row_CateP2['Rule_Online'],2,"0",STR_PAD_LEFT);
	   }
	   else{
				$online_percent=(float)$row_CateP2['Rule_Online']/100;
	   }
	   if(isset($_POST['Course_IsReserve']) && $_POST['Course_IsReserve']==1){
	   		$Course_Reserve=round((int)($_POST['Course_Max']) * (float)("0".".".str_pad($row_CateP2['Rule_Reserve'],2,"0",STR_PAD_LEFT)),0);
	  		$Course_Online=round(((int)($_POST['Course_Max']) - $Course_Reserve)*($online_percent),0);
       		$Course_OnSite=((int)($_POST['Course_Max']) - $Course_Online-$Course_Reserve);
	   }
	   else{
		  	$Course_Reserve=0;
	  		$Course_Online=round(((int)($_POST['Course_Max']) - $Course_Reserve)*($online_percent),0);
       		$Course_OnSite=((int)($_POST['Course_Max']) - $Course_Online-$Course_Reserve);
	   
	   }
	   //算人數END
	   
	   
	   
	   $SkinMain_Code="main;".$row_Data['Com_Code'];
	   
	   
	   $insertSQL = sprintf("INSERT INTO course (
	   
	   SkinMain_Code, Com_ID, Com_Name, Unit_ID, Unit_Name, 
	   
	   Season_Year, SeasonCate_Name, Season_Code, Course_IsCredit, CourseKind_ID, 
	   
	   CourseKind_Name, Course_NO, Course_Time, Course_Name, Season_Credit,
	    
	   Course_Assistant, CO_Text, CO_Sale, Course_Repeat, Loc_ID, 
	   
	   Loc_Name, Course_IsCWeek, Season_Week, Course_Day, Course_StartDate, 
	   
	   Course_Start, Course_EndDate, Course_End, Course_Hour, Course_Summary,
	   
	   Course_Min, Course_Max, Course_Online, Course_OnSite, Course_Reserve, 
	   
	   Course_Private, Course_OnlineRemaining, Course_OnSiteRemaining,  Course_ReserveRemaining, Course_Require, 
	   
	   Course_Object, Course_Audit, Course_Schedule, Course_NOCount, Course_Check, 
	   
	   Course_OnlineStart, Course_OnlineEnd, Course_OnsiteStart, Course_OnsiteEnd,
	   
	   Course_GroupStart, Course_GroupEnd, Add_Time, Edit_Time, Add_Account, 
	   
	   Add_Unitname,  Add_Username) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s)",
	   				   GetSQLValueString($SkinMain_Code, "text"),
                       GetSQLValueString($row_Data['Com_ID'], "int"),
					   GetSQLValueString($row_Data['Com_Name'], "text"),
					   GetSQLValueString($row_Data['Unit_ID'], "int"),
					   GetSQLValueString($row_Data['Unit_Name'], "text"),
					   
					   GetSQLValueString($row_Data['Season_Year'], "int"),
					   GetSQLValueString($row_Data['SeasonCate_Name'], "text"),
					   GetSQLValueString($row_Data['Season_Code'], "int"),
					   GetSQLValueString($row_Data['CourseTeacher_IsCredit'], "int"),
					   GetSQLValueString($_POST['CourseKind_ID'], "int"),					   
					  
					   GetSQLValueString($_POST['CourseKind_Name'], "text"),					  
					   GetSQLValueString($Course_NO, "text"),
					   GetSQLValueString(NULL, "text"),
					   GetSQLValueString($_POST['Title'], "text"),
					   GetSQLValueString($row_Data['Season_Credit'], "int"),
					   
					   GetSQLValueString($row_Data['CourseTeacher_Assistant'], "text"),
					   GetSQLValueString($_POST['CO_Text'], "text"),
					   GetSQLValueString($_POST['CO_Sale'], "text"),	
					   GetSQLValueString($row_Data['CourseTeacher_Repeat'], "text"),
					   GetSQLValueString($_POST['Loc_ID'], "int"),
					   
					   GetSQLValueString($_POST['Loc_Name'], "text"),
                       GetSQLValueString($row_Data['CourseTeacher_IsCWeek'], "int"),	
					   GetSQLValueString($row_Data['Season_Week'], "int"),
					   GetSQLValueString($row_Data['CourseTeacher_Day'], "int"),
					   GetSQLValueString($row_Data['CourseTeacher_StartDate'], "date"),
					   
					   GetSQLValueString($row_Data['CourseTeacher_Start'], "date"),
					   GetSQLValueString($row_Data['CourseTeacher_EndDate'], "date"),
					   GetSQLValueString($row_Data['CourseTeacher_End'], "date"),
					   GetSQLValueString($row_Data['CourseTeacher_Hour'], "int"),
					   GetSQLValueString($row_Data['CourseTeacher_Summary'], "text"),
					   
					   GetSQLValueString($_POST['Course_Min'], "int"),
					   GetSQLValueString($_POST['Course_Max'], "int"),
					   GetSQLValueString($Course_Online, "int"),
                       GetSQLValueString($Course_OnSite, "int"),
					   GetSQLValueString($Course_Reserve, "int"),
					   
                       GetSQLValueString($row_Data['CourseTeacher_Private'],"int"),
					   GetSQLValueString($Course_Online, "int"),
                       GetSQLValueString($Course_OnSite, "int"),
					   GetSQLValueString($Course_Reserve, "int"),
                       GetSQLValueString($row_Data['CourseTeacher_Require'], "text"),
					   
                       GetSQLValueString($row_Data['CourseTeacher_Object'], "text"),
					   GetSQLValueString($Course_Audit,"int"),
					   GetSQLValueString($row_Data['CourseTeacher_Schedule'], "text"),
					   GetSQLValueString($Course_NOCount,"int"),
					   GetSQLValueString($Course_Check,"int"),
					   
					   GetSQLValueString($Course_OnlineStart, "date"),
					   GetSQLValueString($Course_OnlineEnd, "date"),
					   GetSQLValueString($Course_OnsiteStart, "date"),
					   GetSQLValueString($Course_OnsiteEnd, "date"),					   
					   GetSQLValueString($Course_GroupStart, "date"),
					   
					   GetSQLValueString($Course_GroupEnd, "date"), 					   
                       
					   GetSQLValueString($EditTime, "date"),
					   GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"));
					   
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		$Response_ID=mysql_insert_id($dbline);
	   $Total_ID=array();
       $Total_Name=array();
	   $Total_IDText='';
       $Total_NameText='';
	  
      if(isset($_POST['Teacher_ID2']) && $_POST['Teacher_ID2']<>""){
		  
		  $Teacher_ID=$_POST['Teacher_ID2'];
		  if ( strpos($_POST['Teacher_Name2'], '(') !== false && strpos($_POST['Teacher_Name2'], ')') !== false) {
			  $TeacherStr=explode("(",$_POST['Teacher_Name2']);
			  $Teacher_Name=$TeacherStr[0];
		  }
		  else{
			  $Teacher_Name='';
		  }
		  array_push($Total_ID,$Teacher_ID);
		  array_push($Total_Name,$Teacher_Name);
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
			  
			  array_push($Total_ID,$Teacher_ID2);
		      array_push($Total_Name,$Teacher_Name2);
			  
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
	  //新增費用進去OP
	  $Course_Money=0;
	  for($m=0;$m<count($_POST['CP_Money']);$m++){
		   $Course_Money=$Course_Money+$_POST['CP_Money'][$m];					   
	  }
	  $Course_Credits=$row_CateP2['Rule_Credit']*$row_Data['Season_Credit']*$_POST['CO_Sale'];
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
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),					   
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"));
			   mysql_select_db($database_dbline, $dbline);
			   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
	  }
	  mysql_free_result($CPM);
	  
	  for($m=0;$m<count($_POST['CP_Money']);$m++){
		   $query_CPM = sprintf("SELECT * from course_pay where Course_ID=%s and CP_Text=%s",GetSQLValueString($Response_ID, "int"),GetSQLValueString($_POST['CP_Text'][$m], "text"));
		   $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
		   $row_CPM = mysql_fetch_assoc($CPM);
		   $totalRows_CPM = mysql_num_rows($CPM);
		   if($totalRows_CPM<1){
			   $insertSQL=sprintf("INSERT INTO course_pay(CP_Text, CP_Money, Course_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
						   GetSQLValueString($_POST['CP_Text'][$m], "text"),
						   GetSQLValueString($_POST['CP_Money'][$m], "text"),
						   GetSQLValueString($Response_ID, "int"),
						   GetSQLValueString($_POST['Season_Code'], "int"),
						   GetSQLValueString($_POST['Com_ID'], "int"),
						   GetSQLValueString(1, "int"),
						   GetSQLValueString(1, "int"), 					   					   
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),					   
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"));
			   mysql_select_db($database_dbline, $dbline);
			   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		   }
		   mysql_free_result($CPM);
					   
	  }
	  
	  //新增費用進去ED
	  if($Total_ID<>""){
		  $Total_IDText=join(",",$Total_ID);  
	  }
	  if($Total_Name<>""){
		  $Total_NameText=join(",",$Total_Name);
	  }
	  $updateSQL3 = sprintf("update course set Teacher_ID=%s, Teacher_UserName=%s, Course_Money=%s where Course_ID=%s",
                       GetSQLValueString($Total_IDText, "text"),
					   GetSQLValueString($Total_NameText, "text"),
					   GetSQLValueString($Course_Money, "int"),
					   GetSQLValueString($Response_ID, "int"));
	  mysql_select_db($database_dbline, $dbline);
	  $Result3 = mysql_query($updateSQL3, $dbline) or die(mysql_error());	
	  	
	  $NewContent=$row_Data['Com_ID']."/".$row_Data['Unit_ID']."/".$row_Data['Season_Code']."/".$_POST['CourseKind_ID']."/".$_POST['CourseKind_Name']."/".$Course_NO."/".$_POST['Title']."/".$row_Data['Season_Credit']."/".$_POST['Loc_ID']."/".$_POST['Loc_Name']."/".$row_Data['Season_Week']."/".$row_Data['CourseTeacher_Day']."/".$row_Data['CourseTeacher_Start']."/".$row_Data['CourseTeacher_End']."/".$row_Data['CourseTeacher_Hour']."/".$row_Data['CourseTeacher_Time']."/".$row_Data['CourseTeacher_Aims']."/".$row_Data['CourseTeacher_Summary']."/".$row_Data['CourseTeacher_Evaluation']."/".$_POST['Course_Min']."/".$_POST['Course_Max']."/".$row_Data['CourseTeacher_Private']."/".$row_Data['CourseTeacher_Require']."/".$row_Data['CourseTeacher_Book']."/".$row_Data['CourseTeacher_Item']."/".$row_Data['CourseTeacher_Benefit']."/".$Course_Audit."/".$Course_Check."/".$row_Data['CourseTeacher_Pay']."/".$row_Data['CourseTeacher_Schedule']."/".$EditTime."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username']."/講師:".$Total_IDText.'('.$Total_NameText.')'."/規則:".$Course_Reserve."/".$Course_Online."/".$Course_OnSite."是否保留名額:".@$_POST['Course_IsReserve'].'/是否有公民素養週:'.@$_POST['Course_IsCWeek'];		
		require_once('../../Include/Data_Browseinsert.php');
		if(isset($_POST['Course_Audit']) && $_POST['Course_Audit']==1){
	    	mysql_free_result($CateP);
		}
		
	 
	}
	


    $Other = "修改".$row_Permission['ModuleSetting_Title'];
	$updateSQL = sprintf("update course_teacher set CourseKind_ID=%s, CourseKind_Name=%s,  CourseTeacher_Name=%s, Loc_ID=%s, Loc_Name=%s, CourseTeacher_Min=%s, CourseTeacher_Max=%s, CourseTeacher_Pass=%s, CourseTeacher_Reviews=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s where CourseTeacher_ID=%s",
                     
					   GetSQLValueString($_POST['CourseKind_ID'], "int"),
					   GetSQLValueString($_POST['CourseKind_Name'], "text"),
					   
					   
					   GetSQLValueString($_POST['Title'], "text"),					  
					   GetSQLValueString($_POST['Loc_ID'], "int"),
					   GetSQLValueString($_POST['Loc_Name'], "text"),
					 
					   
					   
					   GetSQLValueString($_POST['Course_Min'], "int"),
					   GetSQLValueString($_POST['Course_Max'], "int"),
                      
					   GetSQLValueString($Course_Check,"int"),
					 
					   GetSQLValueString($_POST['CourseTeacher_Reviews'],"text"),
					   
					   
					   GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
                       GetSQLValueString($_POST['ID'], "text"));
					          
							               
					   
	mysql_select_db($database_dbline, $dbline);
	$Result1_2 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	$Response_ID=$_POST['ID'];
    //刪除之前的
    $deleteSQL = sprintf("DELETE FROM teacher_nrecord WHERE CourseTeacher_ID=%s",
                       GetSQLValueString($_POST['ID'], "int"));

    mysql_select_db($database_dbline, $dbline);
    $Result1_3 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
	//刪除END
	
	$Total_ID=array();
    $Total_Name=array();
	$Total_IDText='';
    $Total_NameText='';
	  
    if(isset($_POST['Teacher_ID2']) && $_POST['Teacher_ID2']<>""){
		  
		  $Teacher_ID=$_POST['Teacher_ID2'];
		  if ( strpos($_POST['Teacher_Name2'], '(') !== false && strpos($_POST['Teacher_Name2'], ')') !== false) {
			  $TeacherStr=explode("(",$_POST['Teacher_Name2']);
			  $Teacher_Name=$TeacherStr[0];
		  }
		  else{
			  $Teacher_Name='';
		  }
		  array_push($Total_ID,$Teacher_ID);
		  array_push($Total_Name,$Teacher_Name);
		  $query_TM = sprintf("SELECT * from teacher_nrecord where Teacher_ID=%s and CourseTeacher_ID=%s",GetSQLValueString($Teacher_ID, "int"),GetSQLValueString($Response_ID, "int"));
		  $TM = mysql_query($query_TM, $dbline) or die(mysql_error());
		  $row_TM = mysql_fetch_assoc($TM);
		  $totalRows_TM = mysql_num_rows($TM);
		  
		  if($totalRows_TM<1){
			  $insertSQL2 = sprintf("INSERT INTO teacher_nrecord (Teacher_ID, CourseTeacher_ID, Teacher_UserName) VALUES (%s, %s, %s)",
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
			  
			  array_push($Total_ID,$Teacher_ID2);
		      array_push($Total_Name,$Teacher_Name2);
			  
			  $query_TM = sprintf("SELECT * from teacher_nrecord where Teacher_ID=%s and CourseTeacher_ID=%s",GetSQLValueString($Teacher_ID2, "int"),GetSQLValueString($Response_ID, "int"));
			  $TM = mysql_query($query_TM, $dbline) or die(mysql_error());
			  $row_TM = mysql_fetch_assoc($TM);
			  $totalRows_TM = mysql_num_rows($TM);
			  if($totalRows_TM<1){
				  $insertSQL2_2 = sprintf("INSERT INTO teacher_nrecord (Teacher_ID, CourseTeacher_ID, Teacher_UserName) VALUES (%s, %s, %s)",
							   GetSQLValueString($Teacher_ID2, "int"),
							   GetSQLValueString($Response_ID, "int"),
							   GetSQLValueString($Teacher_Name2, "text"));
			    
				  mysql_select_db($database_dbline, $dbline);
				  $Result2_2 = mysql_query($insertSQL2_2, $dbline) or die(mysql_error());
			  }
			  mysql_free_result($TM);
	      }
    }
	//協同ED
	//新增費用進去OP
	
	  $deleteSQL = sprintf("DELETE FROM courseteacher_pay WHERE CourseTeacher_ID=%s",
                       GetSQLValueString($Response_ID, "int"));

      mysql_select_db($database_dbline, $dbline);
      $Result1_3 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
	  
	  $Course_Money=0;
	  for($m=0;$m<count($_POST['CP_Money']);$m++){
		   $Course_Money=$Course_Money+$_POST['CP_Money'][$m];					   
	  }
	  $Course_Credits=$row_CateP2['Rule_Credit']*$row_Data['Season_Credit']*$_POST['CO_Sale'];
	  if($Course_Credits>0){
		  $Course_Credits=ceil($Course_Credits);
	  }
	  $Course_Money=$Course_Money+$Course_Credits;
	  $query_CPM = sprintf("SELECT * from courseteacher_pay where CourseTeacher_ID=%s and CP_Text=%s",GetSQLValueString($Response_ID, "int"),GetSQLValueString("學分費", "text"));
	  $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
	  $row_CPM = mysql_fetch_assoc($CPM);
	  $totalRows_CPM = mysql_num_rows($CPM);
	  if($totalRows_CPM<1){
			   $insertSQL=sprintf("INSERT INTO courseteacher_pay(CP_Text, CP_Money, CourseTeacher_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
						   GetSQLValueString("學分費", "text"),
						   GetSQLValueString($Course_Credits, "int"),
						   GetSQLValueString($Response_ID, "int"),
						   GetSQLValueString($_POST['Season_Code'], "int"),
						   GetSQLValueString($_POST['Com_ID'], "int"),
						   GetSQLValueString(1, "int"),
						   GetSQLValueString(1, "int"), 					   					   
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),					   
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"));
			   mysql_select_db($database_dbline, $dbline);
			   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
	  }
	  mysql_free_result($CPM);
	  
	  for($m=0;$m<count($_POST['CP_Money']);$m++){
		   $query_CPM = sprintf("SELECT * from courseteacher_pay where CourseTeacher_ID=%s and CP_Text=%s",GetSQLValueString($Response_ID, "int"),GetSQLValueString($_POST['CP_Text'][$m], "text"));
		   $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
		   $row_CPM = mysql_fetch_assoc($CPM);
		   $totalRows_CPM = mysql_num_rows($CPM);
		   if($totalRows_CPM<1){
			   $insertSQL=sprintf("INSERT INTO courseteacher_pay(CP_Text, CP_Money, CourseTeacher_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
						   GetSQLValueString($_POST['CP_Text'][$m], "text"),
						   GetSQLValueString($_POST['CP_Money'][$m], "text"),
						   GetSQLValueString($Response_ID, "int"),
						   GetSQLValueString($_POST['Season_Code'], "int"),
						   GetSQLValueString($_POST['Com_ID'], "int"),
						   GetSQLValueString(1, "int"),
						   GetSQLValueString(1, "int"), 					   					   
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),					   
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"));
			   mysql_select_db($database_dbline, $dbline);
			   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		   }
		   mysql_free_result($CPM);
					   
	  }	  
	  //新增費用進去ED
	
	if($Total_ID<>""){
		  $Total_IDText=join(",",$Total_ID);  
	}
	if($Total_Name<>""){
		  $Total_NameText=join(",",$Total_Name);
	}
	$updateSQL3 = sprintf("update course_teacher set Teacher_ID=%s, Teacher_UserName=%s, CourseTeacher_Money=%s where CourseTeacher_ID=%s",
                       GetSQLValueString($Total_IDText, "text"),
					   GetSQLValueString($Total_NameText, "text"),	
					   GetSQLValueString($Course_Money, "int"),				  
					   GetSQLValueString($Response_ID, "int"));
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL3, $dbline) or die(mysql_error());	
	
	 	   
					  
   $PastContent=$row_Data['CourseTeacher_ID']."/".$row_Data['Com_ID']."/".$row_Data['Unit_ID']."/".$row_Data['Season_Code']."/".$row_Data['CourseKind_ID']."/".$row_Data['CourseKind_Name']."/".$row_Data['CourseTeacher_Area']."/".$row_Data['CourseTeacher_Name']."/".$row_Data['Season_Credit']."/".$row_Data['CourseTeacher_Free']."/".$row_Data['Loc_ID']."/".$row_Data['Loc_Name']."/".$row_Data['Season_Week']."/".$row_Data['CourseTeacher_Day']."/".$row_Data['CourseTeacher_Start']."/".$row_Data['CourseTeacher_End']."/".$row_Data['CourseTeacher_Hour']."/".$row_Data['CourseTeacher_Time']."/".$row_Data['CourseTeacher_Aims']."/".$row_Data['CourseTeacher_Summary']."/".$row_Data['CourseTeacher_Evaluation']."/".$row_Data['CourseTeacher_Min']."/".$row_Data['CourseTeacher_Max']."/".$row_Data['CourseTeacher_Private']."/".$row_Data['CourseTeacher_Require']."/".$row_Data['CourseTeacher_Book']."/".$row_Data['CourseTeacher_Item']."/".$row_Data['CourseTeacher_Benefit']."/".$row_Data['CourseTeacher_Pass']."/".$row_Data['CourseTeacher_Pay']."/".$row_Data['CourseTeacher_Schedule']."/".$row_Data['CourseTeacher_Reviews']."/".$row_Data['Add_Time']."/".$row_Data['Add_Account']."/".$row_Data['Add_Unitname']."/".$row_Data['Add_Username']."/".$row_Data['Edit_Time']."/".$row_Data['Edit_Account']."/".$row_Data['Edit_Unitname']."/".$row_Data['Edit_Username']."/講師:".$row_Data['Teacher_UserName']."(".$row_Data['Teacher_ID'].")是否保留名額:".$row_Data['CourseTeacher_IsReserve'];

   $NewContent=$_POST['ID']."/".$row_Data['Com_ID']."/".$row_Data['Unit_ID']."/".$row_Data['Season_Code']."/".$_POST['CourseKind_ID']."/".$_POST['CourseKind_Name']."/".$row_Data['CourseTeacher_Area']."/".$row_Data['CourseTeacher_Name']."/".$row_Data['Season_Credit']."/".$row_Data['CourseTeacher_Free']."/".$row_Data['Loc_ID']."/".$row_Data['Loc_Name']."/".$row_Data['Season_Week']."/".$row_Data['CourseTeacher_Day']."/".$row_Data['CourseTeacher_Start']."/".$row_Data['CourseTeacher_End']."/".$row_Data['CourseTeacher_Hour']."/".$row_Data['CourseTeacher_Time']."/".$row_Data['CourseTeacher_Aims']."/".$row_Data['CourseTeacher_Summary']."/".$row_Data['CourseTeacher_Evaluation']."/".$_POST['Course_Min']."/".$_POST['Course_Max']."/".$row_Data['CourseTeacher_Private']."/".$row_Data['CourseTeacher_Require']."/".$row_Data['CourseTeacher_Book']."/".$row_Data['CourseTeacher_Item']."/".$row_Data['CourseTeacher_Benefit']."/".$Course_Check."/".$row_Data['CourseTeacher_Pay']."/".$row_Data['CourseTeacher_Schedule']."/".$_POST['CourseTeacher_Reviews']."/".$row_Data['Add_Time']."/".$row_Data['Add_Account']."/".$row_Data['Add_Unitname']."/".$row_Data['Add_Username']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username']."/講師:".@$Total_NameText."/".@$Total_IDText."/"."/是否保留名額:".@$row_Data['Course_IsReserve'];
  mysql_free_result($CateP2);
  require_once('../../Include/Data_BrowseUpdate.php');
   if($_POST['Course_Check']==2){$insertGoTo = "AD_Data_Check.php?Msg=AddOK&Check=2";  }
   else{$insertGoTo = "AD_Data_Check.php?Msg=AddOK"; } 
  header(sprintf("Location: %s", $insertGoTo));
}

$query_CourseOfferArea = "SELECT * FROM course_offer ORDER BY CO_Sort ASC";
$CourseOfferArea = mysql_query($query_CourseOfferArea, $dbline) or die(mysql_error());
$row_CourseOfferArea = mysql_fetch_assoc($CourseOfferArea);
$totalRows_CourseOfferArea = mysql_num_rows($CourseOfferArea);

$query_UnitCode = "SELECT Unit_Code FROM unit where Unit_ID='".$row_Data['Unit_ID']."'";
$UnitCode = mysql_query($query_UnitCode, $dbline) or die(mysql_error());
$row_UnitCode = mysql_fetch_assoc($UnitCode);
$totalRows_UnitCode = mysql_num_rows($UnitCode);
if($totalRows_UnitCode>0){
	$Unit_Code=$row_UnitCode['Unit_Code'];
}
mysql_free_result($UnitCode);
?> 


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Tools/JQFileUpLoad/JQFileUpLoad.php'); ?>
<script src="../../ckeditor/ckeditor.js"></script>
<!--驗證CSS OP-->
<?php require_once('../../Include/spry_style.php'); ?>
<style type="text/css">
.Course_Add{display:inline-block;}
</style>
<!--驗證CSS ED-->
<script src="../../Tools/jscolor/jscolor.js" type="text/javascript"></script><!--選色器-->
<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>
<!--日期INPUT ED-->
<!--Autocomplete JQUERY OP-->
<link href="../../Tools/selectjs/select2.css" rel="stylesheet" />
<script src="../../Tools/selectjs/select2.min.js"></script>
<!--Autocomplete JQUERY ED-->
<style type="text/css">
label{float:none;}
</style>
</head>
<body>
<!-- Body Top Start -->
<?php require_once('../../Include/Admin_Body_Top.php'); ?>
<!-- Body Top End -->
<!--Body menu top Start-->
<?php //require_once('../../Include/Admin_menu_upon.php'); ?>
<!--Body menu top End-->
<!--Body Layout up Start-->
<?php //require_once('../../Include/Admin_Body_Layout_up.php'); ?>
<!--Body Layout up End-->
<div>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="19%"><?php require_once('../../Include/Menu_AdminLeft.php'); ?></td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">修改<?php echo $row_ModuleSet['ModuleSetting_SubName']?></div>
    <?php if($row_Permission['Per_Edit'] == 1){ ?>
    <?php if($totalRows_Data>0){?>
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit" id="Form_Edit" method="POST">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2" style="max-width:800px;">
     
      <tr>
       <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 班季/社區大學:</td>
      <td class="middle">
     <?php echo $row_Data['Season_Year']."年度".$row_Data['SeasonCate_Name'].' / '.$row_Data['Com_Name']; ?>
	<input name="Season_Area" id="Season_Area"  type="hidden" value="<?php echo $row_Data['Season_Week'].'/'.$row_Data['Season_Credit'].'/'.$row_Data['Season_Code'].'/'.$row_Data['Com_ID'];?>">      
     <input value="<?php echo $row_Data['Season_Code']; ?>" name="Season_Code" id="Season_Code" type='hidden'>
      &nbsp;<font class="FormTitle02">不公開課程：</font> <?php if($row_Data['CourseTeacher_Private']==1){echo "是";}else{echo '否';}?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 分校:</td>
      <td class="middle">
     
     
			
    
    <input type="hidden" value="<?php echo $row_Data['Com_ID'];?>" name="Com_ID" id="Com_ID">
			
     
       <?php echo $row_Data['Unit_Name'];?>
      <input value="<?php echo $row_Data['Unit_ID'];?>" name="Unit_Value" id="Unit_Value" type="hidden">
      <input  name="Unit_ID" id="Unit_ID" type="hidden" value="<?php echo $row_Data['Unit_ID'];?>">
      <font id="Unit_CodeArea"></font>
	
      
       
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 課程名稱:</td>
      <td width="90%">
        <?php echo $row_Data['CourseTeacher_Name'];?>
        <input name="Title" type="hidden" id="Title" style="text-align: left; width: 50%; height: 20px;" value="<?php echo $row_Data['CourseTeacher_Name'];?>" required>
      </td>
      </tr> 
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 類別:</td>
      <td class="middle">
     
        <select name="CourseKind_ID" id="CourseKind_ID"  required onChange="CourseCateName()">
        
        <option value="">請選擇...</option>       
        <?php do { ?>
        <option value="<?php echo $row_Cate3['CourseKind_ID']; ?>" <?php if($row_Data['CourseKind_ID']==$row_Cate3['CourseKind_ID']){echo 'selected';}?>><?php echo $row_Cate3['CourseKind_Name']; ?></option>
        <?php 
			} while ($row_Cate3 = mysql_fetch_assoc($Cate3)); ?>
       </select> 
       
       <input type="hidden" name="CourseKind_Name" id="CourseKind_Name">
       <script type="text/javascript">
	   CourseCateName();
	   function CourseCateName(){
		   document.getElementById("CourseKind_Name").value=document.getElementById("CourseKind_ID").options[document.getElementById("CourseKind_ID").selectedIndex].text;
		   
		   }
	   </script>
       
      
       <font class="FormTitle02" style="display:inline-block;">
       <font color="#FF0000">*</font> 學分:&nbsp;</font><?php echo $row_Data['Season_Credit'];?>
       
       
       <font style="display:inline-block;">
       <font class="FormTitle02">
       <font color="#FF0000">* </font>授課時數:</font>
       <font id="Course_HourShow"></font>
       <?php echo $row_Data['CourseTeacher_Hour'];?>
       </font>
       <font class="FormTitle02" style="display:inline-block;" >
       &nbsp;&nbsp;是否有公民素養週:&nbsp;&nbsp;<?php if($row_Data['CourseTeacher_IsCWeek']=="1"){echo '是';}else{echo '否';}?>  &nbsp;</font>
      
			

     
      </td></tr> 
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 班別性質:</td>
      <td class="middle">
      <?php if($row_Data['CourseTeacher_IsCredit']==1){echo '學分班';}
	        elseif($row_Data['CourseTeacher_IsCredit']==0){echo '非學分班';}?>
      </td>
      </tr> 
       
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 地點:</td>
      <td class="middle">
     
       <select name="Loc_ID" id="Loc_ID" required onChange="Location_Name()">
       </select> 
       <input type="hidden" name="Loc_Value" id="Loc_Value" value="<?php echo $row_Data['Loc_ID'];?>">
       <input type="hidden" name="Loc_Name" id="Loc_Name" value="<?php echo $row_Data['Loc_Name'];?>">
       <script type="text/javascript">
	
		Location_Name();
	   function Location_Name(){
			
		   document.getElementById("Loc_Name").value=document.getElementById("Loc_ID").options[document.getElementById("Loc_ID").selectedIndex].text;
		
	   }
	   
	   </script>
     &nbsp;<font color="#FF0000">*</font> <font class="FormTitle02">週數/堂數:</font>&nbsp;<?php echo $row_Data['Season_Week'];?><input type="hidden" name="Season_Week" id="Season_Week" size="2" onKeyUp="tableopen()" required value="<?php echo $row_Data['Season_Week'];?>">&nbsp;
      </td></tr> 
      <tr>
      <td class="right" width="10%"  nowrap><label for="Teacher_Name" class="FormTitle02"><font color="#FF0000">*</font> 講師一:</label></td>
      <td class="middle">
      <div class="ui-widget">      
      <select id="Teacher_ID" name="Teacher_ID" onChange="Teacher1()" required style="width:250px;">
      </select> 
      <input type="hidden" id="Teacher_Name" name="Teacher_Name" >    
      <input type="hidden" id="Teacher_ID2" name="Teacher_ID2"  style="width:250px;"> 
      <input type="hidden" id="Teacher_Name2" name="Teacher_Name2" >   
      </div>
       </td>
      </tr>
      <tr>
      <td class="right" width="10%"  nowrap><label for="together_teacher" class="FormTitle02"><font color="#FF0000">*</font> 協同講師:</label></td>
      <td class="middle">
      <div class="ui-widget">      
      <select id="together_teacher" name="together_teacher" style="width:250px;">
      </select>    
      
      <input type="button" id="together_teacher_add" value="新增協同講師" >  <br/>
      <div id="Teachers_Area"></div>      
      </div>
      
      </td>
      </tr>
      <?php $teacher_modes='edits';
	    require('Teacher_DB.php');?>
      <?php require_once('TeacherAJAX.php'); ?>
     
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 授課日期:</td>
      <td class="middle">
      <?php if($row_Data['CourseTeacher_StartDate']<>""){echo date("Y/m/d",strtotime($row_Data['CourseTeacher_StartDate']));}?> 
                       至
                      <?php if($row_Data['CourseTeacher_EndDate']<>""){echo date("Y/m/d",strtotime($row_Data['CourseTeacher_EndDate']));}?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle03" width="10%"  nowrap><font color="#FF0000">*</font> 授課時間:</td>
      <td width="90%">
        <font style="float:left;">
       
       <?php 
	   $weekname=explode(",","一,二,三,四,五,六,日");
	   if($row_Data['CourseTeacher_Day']<>""){echo "星期".$weekname[$row_Data['CourseTeacher_Day']-1];}?></font>
       &nbsp;<font  style="float:left;">
       &nbsp;<font id="StartHour"><?php echo str_pad(date("H",strtotime($row_Data['CourseTeacher_Start'])),2,'0',STR_PAD_LEFT);?></font>&nbsp;時&nbsp;<font id="StartMini"><?php echo str_pad(date("i",strtotime($row_Data['CourseTeacher_Start'])),2,'0',STR_PAD_LEFT);?></font>&nbsp;分&nbsp;~&nbsp;<font id="EndHour"><?php echo str_pad(date("H",strtotime($row_Data['CourseTeacher_End'])),2,'0',STR_PAD_LEFT);?></font>&nbsp;時&nbsp;<font id="EndMini"><?php echo str_pad(date("i",strtotime($row_Data['CourseTeacher_End'])),2,'0',STR_PAD_LEFT);?></font>&nbsp;分&nbsp;</font>
       <!--<font style="display:inline-block;">
       <font class="FormTitle02">
       <font color="#FF0000">* </font>授課區段:</font>
              <?php if(preg_match("/上/i",$row_Data['CourseTeacher_Time'])){echo '上午';}
			  		elseif(preg_match("/下/i",$row_Data['CourseTeacher_Time'])){echo '下午';}
					elseif(preg_match("/晚/i",$row_Data['CourseTeacher_Time'])){echo '晚上';}?>
       </font></font>-->
       <div style="clear:both">&nbsp;</div>
       <script type="text/javascript">
	   diffdate();
	   function diffdate(){
		   var ONE_HOUR = 1000 * 60 * 60;  // 1小時的毫秒數
		   var ONE_MIN = 1000 * 60; // 1分鐘的毫秒數
		   var ONE_SEC = 1000;   // 1秒的毫秒數
			
		   var Date_A = new Date(2012,6,8,document.getElementById("StartHour").innerHTML,document.getElementById("StartMini").innerHTML,0);  
		   var Date_B = new Date(2012,6,8,document.getElementById("EndHour").innerHTML,document.getElementById("EndMini").innerHTML,0);  
			
		   var diff = Date_B - Date_A;
			
		   var leftHours = Math.floor(diff/ONE_HOUR);
		   if(leftHours > 0) diff = diff - (leftHours * ONE_HOUR);
			
		   var leftMins = Math.floor(diff/ONE_MIN);
		   if(leftMins >0) diff = diff - (leftMins * ONE_MIN);
			
		   var leftSecs = Math.floor(diff/ONE_SEC);
		   var h=Math.floor(((leftHours*60)+leftMins)/50);
		
		if($("#Course_IsCWeek").prop("checked")==true){
			//document.getElementById("Course_Hour").value=(h * document.getElementById("Season_Week").value)-h;
			//document.getElementById("Course_HourShow").innerHTML=(h * document.getElementById("Season_Week").value)-h;
			document.getElementById("Course_Hour").value=(h * document.getElementById("Season_Week").value);
			document.getElementById("Course_HourShow").innerHTML=(h * document.getElementById("Season_Week").value);
		}
		else{
			//console.log("兩個時間差距為%d小時,%d分",leftHours,leftMins)
			document.getElementById("Course_Hour").value=(h * document.getElementById("Season_Week").value);
			document.getElementById("Course_HourShow").innerHTML=(h * document.getElementById("Season_Week").value);
		}
	   }
	   </script>
       
        </td></tr>
        <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 招生人數(最高):</td>
      <td width="90%" class="FormTitle02"> 
        
        <input name="Course_Max" type="text" id="Course_Max" size="2" required value="<?php echo $row_Data['CourseTeacher_Max'];?>">人<span class="Msg_Admissions">請輸入數字格式</span>
        &nbsp;
        <font style="display:inline-block;" class="FormTitle02">
         <font color="#FF0000">*</font> 開班人數(最低):&nbsp;
         <input name="Course_Min" type="text" id="Course_Min"  size="2" required value="<?php echo $row_Data['CourseTeacher_Min'];?>">人<span class="Msg_OpenClass">請輸入正整數數字格式</span><span class="Msg_OpenClassFormat">開班人數不可高於招生人數</span><span class="Msg_OpenClassFormat2">請輸入0~20數字</span>
        </font>
        <?php /*
         <font style="display:inline-block;" >
         &nbsp;&nbsp;<font class="FormTitle02">是否有身障保留名額:</font>
       <input name="Course_IsReserve" type="checkbox" id="Course_IsReserve"  <?php if($row_Data['CourseTeacher_IsReserve']==1){echo 'checked';}?> value="1">
       </font>
	   */?>
      </td>
      </tr> 
      <tr>
      <td class="right FormTitle02"> 助教人員:</td>
      <td>
      <?php echo $row_Data['CourseTeacher_Assistant'];?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"><font color="#FF0000">*</font> 選課條件:</td>
      <td>
      <?php echo $row_Data['CourseTeacher_Require'];?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"><font color="#FF0000">* </font>課程對象:</td>
      <td>
     <?php echo $row_Data['CourseTeacher_Object'];?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"><font color="#FF0000">* </font>課程簡介:</td>
      <td>
      <?php echo $row_Data['CourseTeacher_Summary'];?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02" width="10%"  nowrap><font color="#FF0000">*</font> 課程折扣:</td>
      <td width="90%">
      <select name="CO_Area" id="CO_Area" onChange="COArea();" required>      
      <?php if($totalRows_CourseOfferArea>0){
		  do{?>
	  <option value="<?php echo $row_CourseOfferArea['CO_Text'];?>;<?php echo $row_CourseOfferArea['CO_Sale'];?>" ><?php echo $row_CourseOfferArea['CO_Text'];?></option>
	  <?php 
		  }while($row_CourseOfferArea=mysql_fetch_assoc($CourseOfferArea));
	  		}else{?>
	  <option value="無;1">無</option>
	  <?php }
	  mysql_free_result($CourseOfferArea);?>
      </select>
      <input type="hidden" name="CO_Text" id="CO_Text">
      <input type="hidden" name="CO_Sale" id="CO_Sale">
      <script type="text/javascript">
	  COArea();
          function COArea(){
		  var mains=$("#CO_Area").val();
		  var mainItemValue = mains.split(";");
		  $("#CO_Text").val(mainItemValue[0]);
		  $("#CO_Sale").val(mainItemValue[1]);
	  }
	  </script>
      <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font class="FormTitle02">忽略身分折扣：<input type="checkbox" name="Course_IsMemberType" id="Course_IsMemberType" checked value="1"></font>-->
      </td>
      </tr><tr>
      <td class="right FormTitle03"><font color="#FF0000">* </font>課程相關費用:</td>
      <td style="line-height:26px;"> 
      <?php 
	    function utf8_str_to_unicode($utf8_str) {//utf8字元轉換成Unicode字元
			$unicode = 0;
			$unicode = (ord($utf8_str[0]) & 0x1F) << 12;
			$unicode |= (ord($utf8_str[1]) & 0x3F) << 6;
			$unicode |= (ord($utf8_str[2]) & 0x3F);
			return dechex($unicode);
		}
		$query_DataPay = sprintf("SELECT * FROM courseteacher_pay where CourseTeacher_ID = %s and CP_Enable=1 ",GetSQLValueString($colname_ID, "text"));
		$DataPay = mysql_query($query_DataPay, $dbline) or die(mysql_error());
		$row_DataPay = mysql_fetch_assoc($DataPay);
		$totalRows_DataPay = mysql_num_rows($DataPay);
		
		$PayList=array();
	
		if($totalRows_DataPay>0){
			do{
				if(!isset($PayList[$row_DataPay['CP_Text']])){$PayList[$row_DataPay['CP_Text']]='';}
				$PayList[$row_DataPay['CP_Text']]=$row_DataPay['CP_Money'];
			}while($row_DataPay = mysql_fetch_assoc($DataPay));
		}
		
		mysql_free_result($DataPay);
		
		?>     
      場地費：<input name="CP_Text[]" type="hidden" value="場地費"><input name="CP_Money[]"  min="0" type="number"  required style="width:150px;" value="<?php if(isset($PayList['場地費']) && $PayList['場地費']<>""){echo $PayList['場地費'];}?>" >
      <br/>
      保證金費用：<input name="CP_Text[]" type="hidden" value="保證金費用"><input name="CP_Money[]"  min="0" type="number"  style="width:150px;" value="<?php if(isset($PayList['保證金費用']) && $PayList['保證金費用']<>""){echo $PayList['保證金費用'];}?>" required>
      <br/>
      學雜費：<input name="CP_Text[]" type="hidden" value="學雜費"><input name="CP_Money[]"  min="0" type="number" required style="width:150px;" value="<?php if(isset($PayList['學雜費']) && $PayList['學雜費']<>""){echo $PayList['學雜費'];}?>">
      </td>
      </tr>
      <tr>
      <td class="right FormTitle03"><font color="#FF0000">* </font>新舊投課類別:</td>
      <td style="line-height:26px;">
      <?php if($row_Data['CourseTeacher_Repeat']==1){echo '舊講師，【續開】';}
		    elseif($row_Data['CourseTeacher_Repeat']==2){echo '舊講師，【加開】初、中或高階';}
		    elseif($row_Data['CourseTeacher_Repeat']==3){echo '舊講師，新投課(非原教授課目)';}
		    elseif($row_Data['CourseTeacher_Repeat']==4){echo '新講師，新投課';}?>		
	 
      </td>
      </tr>
      
      <tr>
      <td class="right FormTitle02"><font color="#FF0000">* </font>教學進度表:  <?php $Count_Table=explode(",;,",$row_Data['CourseTeacher_Schedule']);
	 ?>
        <input type="hidden" name="CourseID" id="CourseID" value="<?php echo $row_Data['CourseTeacher_ID'];?>">
      </td>
      <td id="ContentTable">
    
         <script type="text/javascript">
		 tableopen();
		 
		 function tableopen(){
			 // mainItemValue 代表 option value, 其值對應到 printing p_id
			var mainItemValue = document.getElementById("Season_Week").value;
			var mainItemValue2 = document.getElementById("CourseID").value;
	
			if (window.XMLHttpRequest) 
			{
		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp_subitems_table = new XMLHttpRequest();
			} 
			else 
			{  
		// code for IE6, IE5
				xmlhttp_subitems_table = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp_subitems_table.onreadystatechange = function() 
			{
				document.getElementById("ContentTable").innerHTML = xmlhttp_subitems_table.responseText;
			
			}
	
			xmlhttp_subitems_table.open("get", "table_teachervalue_view.php?CourseTeacher_Week=" + encodeURI(mainItemValue)+"&CourseTeacher_ID="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems_table.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems_table.send();
			 
			 }
         </script>
      
      
      
      </td>
      </tr>
      
      <tr>
      <td class="right FormTitle02">評語:</td>
      <td>
     <textarea name="CourseTeacher_Reviews" id="CourseTeacher_Reviews" cols="60" rows="10" wrap="Virtual"><?php echo $row_Data['CourseTeacher_Reviews'];?></textarea></td>
      </tr>
     <tr>      
          <td class="right FormTitle02" nowrap>初審狀態:</td>
          <td nowrap class="middle FormTitle02"> 
     
          
          <input name="Course_Check" type="radio" class="middle" id="Course_Check3"  value="1"  onclick="audit_check()" <?php if($row_Data['CourseTeacher_Pass']==1){echo 'checked';}?>>通過
          &nbsp;&nbsp;&nbsp;
          <input name="Course_Check" type="radio" class="middle" id="Course_Check3"  value="2"  onclick="audit_check()"<?php if($row_Data['CourseTeacher_Pass']==2){echo 'checked';}?> >保留
          &nbsp;&nbsp;&nbsp;
    
     
</td>
      </tr>
      <tr class="AuditArea" style="display:none;">      
          <td class="right FormTitle02" nowrap>複審狀態:</td>
          <td nowrap class="middle FormTitle02"> 
          
          <input name="Course_Audit" type="radio" class="middle" id="Course_Audit1"  value="1">通過
&nbsp;&nbsp;&nbsp;
		  <input name="Course_Audit" type="radio" class="middle" id="Course_Audit2"  value="2">保留
&nbsp;&nbsp;&nbsp;
    
     
</td>
      </tr>
      
          
    </table>
   
     <input type="hidden" name="ID" id="ID" value="<?php echo $_GET['ID'];?>">
    <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
    <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
    <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
    <input type="submit" value="確定新增" class="Button_Submit"/>  <input type="reset" value="重填" class="Button_General"/>  <input type="button" value="取消" class="Button_General" onClick="location.href='<?php if($row_Data['CourseTeacher_Pass']==1){echo 'AD_Data_Index.php';}elseif($row_Data['CourseTeacher_Pass']==2){echo 'AD_Data_Store.php';}else{echo 'AD_Data_Check.php';}?>'"/>
    </div>
    <input type="hidden" name="MM_update" value="Form_Edit" />
    
    </form>
     <?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權修改此資料</div>   <?php }?><br><br><br>
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能修改權限</div>    
    <?php } ?>
        </td>
      </tr>
    </table>
    <br><br><br>
</div>      
<script type="text/javascript">
	audit_check();
	function audit_check(){
		if($("input[name='Course_Check']:checked").val()==1){
			$('.AuditArea').css('display','table-row');
		}
		else{
			$('.AuditArea').css('display','none');
			$('input[name="Course_Audit"]').prop('checked', false);
		}	
	}
</script>
<script type="text/javascript">
	   callbyAJAX();
	   function callbyAJAX(){
			// mainItemValue 代表 option value, 其值對應到 printing p_id
			var mainItemValue = document.getElementById("Com_ID").value;
	        var mainItemValue2 = document.getElementById("Unit_Value").value;
			var mainItemValue3 = document.getElementById("Loc_Value").value;
			
			
	
			if (window.XMLHttpRequest) 
			{
		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp_subitems = new XMLHttpRequest();
				xmlhttp_subitems_area = new XMLHttpRequest();
			} 
			else 
			{  
		// code for IE6, IE5
				xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp_subitems_area = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp_subitems.onreadystatechange = function() 
			{
				document.getElementById("Unit_CodeArea").innerHTML = xmlhttp_subitems.responseText;
			}
			xmlhttp_subitems_area.onreadystatechange = function() 
			{
				document.getElementById("Loc_ID").innerHTML = xmlhttp_subitems_area.responseText;
			}
	
			xmlhttp_subitems.open("get", "cate_value_code.php?Unit_ID="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems.send();
			xmlhttp_subitems_area.open("get", "area_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Loc_ID="+encodeURI(mainItemValue3)+"&Unit_ID="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems_area.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems_area.send();
	
      }
	   </script>
 <script type="text/javascript">
$(document).ready(function() {
	 $('#Course_Max').on("change", function () {
		var r= /^[0-9]*[1-9][0-9]*$/;
		var Course_Max=document.getElementById("Course_Max").value;
		var Course_Min=document.getElementById("Course_Min").value;
		if((r.test(Course_Max)==true || Course_Max==0) && parseInt(Course_Max)>=parseInt(Course_Min)||Course_Min==''){
			document.getElementById('Course_Max').style.backgroundColor='#D7FFD7';	
			if(document.getElementById('Course_Min').style.backgroundColor=='rgb(255, 225, 225)'){
				document.getElementById('Course_Min').style.backgroundColor='#D7FFD7';
				}		
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_Admissions").hide();
		}
		else if(parseInt(Course_Max)<parseInt(Course_Min)){
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';
			document.getElementById('Course_Max').style.backgroundColor='#D7FFD7';
			$("span.Msg_OpenClassFormat").show();
			$("span.Msg_Admissions").hide();
		}
		else{
			document.getElementById('Course_Max').style.backgroundColor='#ffe1e1';
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_Admissions").show(); 
			
		}
	 });
	 $('#Course_Min').on("change", function () {
		var r= /^[0-9]*[1-9][0-9]*$/;
		var Course_Max=document.getElementById("Course_Max").value;
		var Course_Min=document.getElementById("Course_Min").value;
		if(((r.test(Course_Min)==true && parseInt(Course_Min)<21)  || Course_Min==0) && parseInt(Course_Max)>=parseInt(Course_Min)||Course_Max==''){
			document.getElementById('Course_Min').style.backgroundColor='#D7FFD7';
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_OpenClassFormat2").hide();
			$("span.Msg_OpenClass").hide();
		}
		else if(parseInt(Course_Max)<parseInt(Course_Min)){			
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';
			$("span.Msg_OpenClassFormat").show();
			$("span.Msg_OpenClassFormat2").hide();
			$("span.Msg_OpenClass").hide();
		}
		else if(parseInt(Course_Min)>20){			
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_OpenClassFormat2").show();
			$("span.Msg_OpenClass").hide();
		}
		else{
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_OpenClassFormat2").hide();
			$("span.Msg_OpenClass").show(); 
		}
	 });
	
	 
});
$(document).ready(function(event) {

    $('form[name=Form_Edit]').submit(function(event){
		var r= /^[0-9]*[1-9][0-9]*$/;
        var Course_Min=document.getElementById("Course_Min").value;
		var Course_Max=document.getElementById("Course_Max").value;
		
		/*var list= $('input:radio[name="Course_Time"]:checked').val();
		if(list==null){
			$(".Msg_CourseTime").show();
			document.getElementById('Course_Time1').focus();
			return false;
		}
		else{
			$(".Msg_CourseTime").hide();
		}*/
		if(r.test(Course_Min)==false && Course_Min!=0){	
		    document.getElementById('Course_Min').focus();
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';	
			$("span.Msg_OpenClass").show();		
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_OpenClassFormat2").hide();			
	    }
		else if(parseInt(Course_Max)<parseInt(Course_Min)){
		    document.getElementById('Course_Min').focus();
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';			
			$("span.Msg_OpenClass").hide();
			$("span.Msg_OpenClassFormat").show();
			$("span.Msg_OpenClassFormat2").hide();
		}
		else if(parseInt(Course_Min)>20){
		    document.getElementById('Course_Min').focus();
			document.getElementById('Course_Min').style.backgroundColor='#ffe1e1';			
			$("span.Msg_OpenClass").hide();
			$("span.Msg_OpenClassFormat").hide();
			$("span.Msg_OpenClassFormat2").show();
		}
		if(r.test(Course_Max)==false && Course_Max!=0){	
		    document.getElementById('Course_Max').focus();
			document.getElementById('Course_Max').style.backgroundColor='#ffe1e1';			
			$("span.Msg_Admissions").show();
	    }
		
		
		if(((r.test(Course_Min)==true && parseInt(Course_Min)<21) || Course_Min==0) && (r.test(Course_Max)==true || Course_Max==0) && parseInt(Course_Max)>=parseInt(Course_Min)){	
			
		}		
		else{return false;}
		 
		 
		 
    });
});	

</script>   
<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
<?php require_once('../../Tools/JQFileUpLoad/UpLoadFile_BulletinJSCSS.php'); ?>
</body>
</html>


<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php
mysql_free_result($Data);
mysql_free_result($Cate2);
mysql_free_result($Cate3);
?>