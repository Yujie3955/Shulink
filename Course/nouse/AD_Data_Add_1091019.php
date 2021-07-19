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

<?php
require_once('../../include/Permission.php');

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET['Com_ID']) && $_GET['Com_ID'] != "") {
	if($colname03_Unit=="%" || $_GET['Com_ID']==$row_AdminMember['Com_ID']){
                $colname03_Unit = "".$_GET['Com_ID']."";
	}else{
		$colname03_Unit ="-1";
	}
}	
elseif($row_AdminMember['Unit_Range']<3){
	$colname03_Unit = $row_AdminMember['Com_ID'];	 
}
elseif($row_AdminMember['Unit_Range']>=3){
	$colname03_Unit ="%";
}
else{
	$colname03_Unit ="-1";
}



$query_TableData = sprintf("SELECT * FROM course_text where Com_ID Like %s and CourseText_Enable=1 and concat(';',ModuleSetting_Code,';') like %s  ORDER BY Com_ID ASC, CourseText_Sort asc ",GetSQLValueString($colname03_Unit, "text"),GetSQLValueString("%;Course;%", "text"));
$TableData = mysql_query($query_TableData, $dbline) or die(mysql_error());
$row_TableData = mysql_fetch_assoc($TableData);
$totalRows_TableData = mysql_num_rows($TableData);

$teacher_modes='edits';
?>


<?php
$Other = "新增".$row_Permission['ModuleSetting_Title'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add")) {
	  
   	   $DayCode=array('Z','A','B','C','D','E','F','G');
	   $DayCodeList=array('','星期一','星期二','星期三','星期四','星期五','星期六','星期日');
	   $Course_NOCode='Z';
	   if(isset($_POST['Course_Day1']) && $_POST['Course_Day1']<>""){
		foreach($DayCodeList as $key=>$value){
			if($_POST['Course_Day1']==$value){
				$Course_NOCode=$DayCode[$key];
			}
		}
	   }
	  	  
	   //新增就有編號
	   $query_CateP = sprintf("SELECT max(Course_NOCount) as Max_Course FROM course where Com_ID=%s and course.Season_Code = %s and Course_NOCode=%s",GetSQLValueString($_POST['Com_ID'], "int"),GetSQLValueString($_POST['Season_Code'], "int"),GetSQLValueString($Course_NOCode, "text"));
    	   $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
	   $row_CateP = mysql_fetch_assoc($CateP);
	   $totalRows_CateP = mysql_num_rows($CateP);
	   if($totalRows_CateP>0){
	   	$id=str_pad($row_CateP['Max_Course']+1,2,"0",STR_PAD_LEFT);
	   }
           else{
		$id=str_pad("1",2,"0",STR_PAD_LEFT); 
	   }
	   mysql_free_result($CateP);
	   $Course_NO=$_POST['Season_Code'].$Course_NOCode.$id;
	   $Course_NOCount=$id;
	   
	   //算人數OP
		$query_CateP2 = sprintf("SELECT * from rule where Rule_ID=%s",GetSQLValueString($_POST['Rule_ID'], "int"));
		$CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
		$row_CateP2 = mysql_fetch_assoc($CateP2);
		$totalRows_CateP2 = mysql_num_rows($CateP2);
		if(mb_strlen($row_CateP2['Rule_Online'], "utf-8")<3){
			$online_percent=(double)"0".".".str_pad($row_CateP2['Rule_Online'],2,"0",STR_PAD_LEFT);
		}
		else{
			$online_percent=(double)$row_CateP2['Rule_Online']/100;
		}
		if(isset($_POST['Course_IsReserve'])&&$_POST['Course_IsReserve']==1){		    
				$Course_Reserve=round((int)($_POST['Course_Max']) * (double)("0".".".str_pad($row_CateP2['Rule_Reserve'],2,"0",STR_PAD_LEFT)),0);
				$Course_Online=round(((int)($_POST['Course_Max']) - $Course_Reserve)*($online_percent),0);
				$Course_OnSite=((int)($_POST['Course_Max']) - $Course_Online-$Course_Reserve);
		}
	        else{
				$Course_Reserve=0;
				$Course_Online=round(((int)($_POST['Course_Max']) - $Course_Reserve)*($online_percent),0);
				$Course_OnSite=((int)($_POST['Course_Max']) - $Course_Online-$Course_Reserve);
		}
		
		//算人數END

	       	$AddTime=date("Y-m-d H:i:s");
		$_POST['Add_Time']=$AddTime;

	  	$query_CateP3 = sprintf("SELECT Com_Code from community where Com_ID=%s",GetSQLValueString($_POST['Com_ID'], "int"));
		$CateP3 = mysql_query($query_CateP3, $dbline) or die(mysql_error());
		$row_CateP3 = mysql_fetch_assoc($CateP3);
		$totalRows_CateP3 = mysql_num_rows($CateP3);
		$SkinMain_Code="main;".$row_CateP3['Com_Code'];
		mysql_free_result($CateP3);
		$_POST['Title']=$_POST['Course_Name'];
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
		
			
		
		if(isset($_POST['Course_Pic']) && $_POST['Course_Pic']<>""){
			$Course_Pic=join(";",$_POST['Course_Pic']);
			$Course_PicText=join(";",$_POST['Course_PicText']);
		}
		else{
			$Course_Pic=NULL;
			$Course_PicText=NULL;
		}
		$insertSQL = sprintf("INSERT INTO course (
	   SkinMain_Code, Com_ID, Com_Name, Unit_ID, Unit_Name, 
	   
	   Course_NO,Course_NOCount, Course_NOCode, Season_Year, 
	
	   SeasonCate_Name, Season_Code, SeasonWeek_Name, SeasonWeek_Number, Course_TeacherResume,
	   
	   Course_Name, Course_Remark, Course_IsShrine, CourseRepeat_Name, CourseStatus_Name,

	   CourseProperty_Name, CourseProgram_Name, CourseArea_Name, CourseKind_Name, CourseKindCate_Name,
	   
	   Loc_Name, Loc_Address, Room_Name, Course_Min, Course_Max, 

	   Course_StartWeek, Course_StartDay, Course_Day1, Course_Start1, Course_End1,

	   Course_TDay1, Course_TDay2, Course_TDay3, Unit_ID2, Unit_Name2,  
	   
	   Loc_Name2, Room_Name2, Course_Credit, Course_Weekhour, Credit_Money,
	   
	   CO_Text, CO_Sale, Credit2_Money, Credit2_Name, Pro_Money, 
	   
	   Course_Leader, Course_Aim, Course_Idea,  Course_Limit, Course_Item, 
	   
	   Course_Method, Course_Evaluation, Course_Condition, Course_Books, Course_Youtube, 
	   
	   Course_ItemWeb, Course_UseItem, Course_Online, Course_OnSite, Course_Reserve,

	   Course_OnlineRemaining, Course_OnSiteRemaining, Course_ReserveRemaining, Course_Time,

	   Course_Day2, Course_Start2, Course_End2, Add_Time, Edit_Time,
	   
	   Add_Account, Add_Unitname,  Add_Username, Course_Cate, Course_Pass, 
           
           Course_PassTime, Course_Pic, Course_PicText, Course_Special) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,    %s, %s, %s, %s)",
	   				   GetSQLValueString($SkinMain_Code, "text"),
                       			   GetSQLValueString($_POST['Com_ID'], "int"),
					   GetSQLValueString($_POST['Com_Name'], "text"),
					   GetSQLValueString($_POST['Unit_ID'], "int"),
					   GetSQLValueString($_POST['Unit_Name'], "text"),

					   GetSQLValueString($Course_NO, "text"),
					   GetSQLValueString($Course_NOCount, "int"),
					   GetSQLValueString($Course_NOCode, "text"),					   
					   GetSQLValueString($_POST['Season_Year'], "int"),

					   GetSQLValueString($_POST['SeasonCate_Name'], "text"),
					   GetSQLValueString($_POST['Season_Code'], "int"),
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
					   GetSQLValueString($_POST['Unit_ID2'], "text"),	
					   GetSQLValueString($_POST['Unit_Name2'], "text"),
					   
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
					   GetSQLValueString($_POST['Add_Time'], "date"),
					   GetSQLValueString($_POST['Add_Time'], "date"),

					   GetSQLValueString($_POST['Add_Account'], "text"),
					   GetSQLValueString($_POST['Add_Unitname'], "text"),
					   GetSQLValueString($_POST['Add_Username'], "text"),
					   GetSQLValueString($_POST['Course_Cate'], "text"),
					   GetSQLValueString(1, "int"),

					   GetSQLValueString($_POST['Add_Time'], "date"),
					   GetSQLValueString($Course_Pic, "text"),
					   GetSQLValueString($Course_PicText, "text"),
					   GetSQLValueString(@join(";",$_POST['Course_Special']), "text"));	               
					   
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		$Response_ID=mysql_insert_id($dbline);
		$columns_data="course";
		$columns_dataid="Course_ID";
		require_once('../../Include/Data_Insert_Content.php');
	//教師ID
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
		  if(!in_array($Teacher_ID,$Total_ID)){
		  	array_push($Total_ID,$Teacher_ID);
			array_push($Total_Name,$Teacher_Name);
		  }
		  
			
		  $Course_TeacherID=$Teacher_ID;
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
	//教師ED
	
	//新增費用進去OP
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

	 

	  require_once('../../Include/Data_BrowseInsert.php');
	  mysql_free_result($CateP2);

		
	  $insertGoTo = "AD_Data_Schedule.php?ID=".$Response_ID;  
          header(sprintf("Location: %s", $insertGoTo));
	//新增費用進去ED
    
 
}
?> 


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<!--驗證CSS OP-->
<?php require_once('../../Include/spry_style.php'); ?>
<style type="text/css">
.Course_Add{display:inline-block;}
</style>
<!--驗證CSS ED-->


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
<div >   
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
<div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg DelError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料刪除失敗，此課程已有學員報名</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
           <div class="Error_Msg AddError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料登錄失敗！</div>
</div>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="19%"><?php require_once('../../Include/Menu_AdminLeft.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> 新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></div>
    <?php if($row_Permission['Per_Add'] == 1){ ?>
   
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
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
	<input name="MM_insert" id="MM_insert" value="Form_Add" type="hidden">
	<input type="submit" name="sub1" id="sub1" value="下一步" class="Button_Submit">
    </div>
    </form>
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
    <?php }?>
    </td>
    </tr>
    </table>
    <br><br><br>
</div>
    
<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->

</body>
</html>

<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
