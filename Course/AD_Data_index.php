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
<?php require_once('../../Include/Permission_Cate.php'); ?>
<?php require_once('../../include/Permission.php');?>

<?php
$Other = "修改".$row_Permission['ModuleSetting_Title'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Multi_Edit")) {
	$EditTime=date("Y-m-d H:i:s");
	$str_url='';
	if(isset($_GET['Checks1']) && $_GET['Checks1']<>""){$str_url.="&Checks1=".$_GET['Checks1'];}
	if(isset($_GET['Checks2']) && $_GET['Checks2']<>""){$str_url.="&Checks2=".$_GET['Checks2'];}
	if(isset($_GET['Checks3']) && $_GET['Checks3']<>""){$str_url.="&Checks3=".$_GET['Checks3'];}
	if(isset($_GET['Season_Code']) && $_GET['Season_Code']<>""){$str_url.="&Season_Code=".$_GET['Season_Code'];}
	if(isset($_GET['Unit_ID']) && $_GET['Unit_ID']<>""){$str_url.="&Unit_ID=".$_GET['Unit_ID'];}
	if(isset($_GET['Course_Title']) && $_GET['Course_Title']<>""){$str_url.="&Course_Title=".$_GET['Course_Title'];}


	if(isset($_POST['CourseIDs']) && $_POST['CourseIDs'][0]<>""){//有被勾選
		$str_course_string='(';
		for($c1=0;$c1<count($_POST['CourseIDs']);$c1++){
			if($c1==0){$str_course_string.=" Course_ID = ".$_POST['CourseIDs'][$c1];}
			else{$str_course_string.=" or Course_ID = ".$_POST['CourseIDs'][$c1];}
		}
		$str_course_string.=")";
		if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==1){
			$updateSQL = sprintf("update course set	 CourseProgram_Name=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,
						   
						   GetSQLValueString($_POST['CourseProgram_Name'], "text"),
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));	               
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==2){
			$updateSQL = sprintf("update course set	CourseRepeat_Name=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,
		   				   
						   GetSQLValueString($_POST['CourseRepeat_Name'], "text"),
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));	               
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==3){
			$updateSQL = sprintf("update course set CourseStatus_Name=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,

						   GetSQLValueString($_POST['CourseStatus_Name'], "text"),
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));	               
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==4){
			$updateSQL = sprintf("update course set	Course_StartWeek=%s, Course_StartDay=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,
						   
						   GetSQLValueString($_POST['Course_StartWeek'], "text"),
						   GetSQLValueString($_POST['Course_StartDay'], "date"),
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));	               
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==5){
			$updateSQL = sprintf("update course set	 CourseKind_Name=%s, CourseKindCate_Name=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,
						   
						   GetSQLValueString($_POST['CourseKind_Name'], "text"),
						   GetSQLValueString($_POST['CourseKindCate_Name'], "text"),
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));	               
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==6){
			$updateSQL = sprintf("update course set	CourseProperty_Name=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,
						   
						   GetSQLValueString($_POST['CourseProperty_Name'], "text"),						   
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));	               
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==7){
			for($c1=0;$c1<count($_POST['CourseIDs']);$c1++){
				if(!preg_match("/;".$_POST['Course_Special'].";/i",$_POST['Course_OriSpecial'][$_POST['CourseIDs'][$c1]])){
					if(preg_replace('/\s(?=)/','',$_POST['Course_OriSpecial'][$_POST['CourseIDs'][$c1]])<>""){
						$updateSQL = sprintf("update course set	Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s, Course_Special=%s where Course_ID=%s",
									   
									   GetSQLValueString($EditTime, "date"),

									   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
									   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
									   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
									   GetSQLValueString($_POST['Course_OriSpecial'][$_POST['CourseIDs'][$c1]].";".$_POST['Course_Special'], "text"),
									   GetSQLValueString($_POST['CourseIDs'][$c1], "int"));
						
					}
					else{
						$updateSQL = sprintf("update course set	Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s, Course_Special=%s where Course_ID=%s",
									   
									   GetSQLValueString($EditTime, "date"),

									   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
									   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
									   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
									   GetSQLValueString($_POST['Course_Special'], "text"),
									   GetSQLValueString($_POST['CourseIDs'][$c1], "int"));

					}
					mysql_select_db($database_dbline, $dbline);
					$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
				}
			}	               
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==8){
			$Course_Check1_List=explode(";",$_POST['Course_Check1_List']);
			$Course_Check1=$Course_Check1_List[0];
			$Course_Check1Status=$Course_Check1_List[1];
			$updateSQL = sprintf("update course set Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s, Course_Check1=%s, Course_Check1Status=%s, Course_Check1Remark=%s where ".$str_course_string,
		   				   
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
						   GetSQLValueString($Course_Check1, "int"),
						   GetSQLValueString($Course_Check1Status, "text"),
						   GetSQLValueString($_POST['Course_Check1Remark'], "text"));
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());	               
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==9){
			$Course_Check2_List=explode(";",$_POST['Course_Check2_List']);
			$Course_Check2=$Course_Check2_List[0];
			$Course_Check2Status=$Course_Check2_List[1];
			$updateSQL = sprintf("update course set	Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s, Course_Check2=%s, Course_Check2Status=%s, Course_Check2Remark=%s where ".$str_course_string,
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
						   GetSQLValueString($Course_Check2, "int"),
						   GetSQLValueString($Course_Check2Status, "text"),
						   GetSQLValueString($_POST['Course_Check2Remark'], "text"));
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());	               
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==10){
			$Course_Check3_List=explode(";",$_POST['Course_Check3_List']);
			$Course_Check3=$Course_Check3_List[0];
			$Course_Check3Status=$Course_Check3_List[1];
			$updateSQL = sprintf("update course set	Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s, Course_Check3=%s, Course_Check3Status=%s, Course_Check3Remark=%s where ".$str_course_string,
						   GetSQLValueString($EditTime, "date"),

						   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
						   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
						   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
						   GetSQLValueString($Course_Check3, "int"),
						   GetSQLValueString($Course_Check3Status, "text"),
						   GetSQLValueString($_POST['Course_Check3Remark'], "text"));	  
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==11){
			  for($c1=0;$c1<count($_POST['CourseIDs']);$c1++){
				  //刪除原本的
				  $deleteSQL = sprintf("DELETE FROM course_pay WHERE Course_ID=%s and CP_Text=%s",
			                       GetSQLValueString($_POST['CourseIDs'][$c1], "int"),
			                       GetSQLValueString("學分費", "text"));

				  mysql_select_db($database_dbline, $dbline);
				  $Result1_3 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
				  
				  //學分費OP
				  $Course_Credits=$_POST['Rule_Credit'][$_POST['CourseIDs'][$c1]]*$_POST['Course_Credit'][$_POST['CourseIDs'][$c1]]*$_POST['CO_Sale'];
				  if($Course_Credits>0){
					  $Course_Credits=ceil($Course_Credits);
				  }
				  
				  $query_CPM = sprintf("SELECT * from course_pay where Course_ID=%s and CP_Text=%s",GetSQLValueString($_POST['CourseIDs'][$c1], "int"),GetSQLValueString("學分費", "text"));
				  $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
				  $row_CPM = mysql_fetch_assoc($CPM);
				  $totalRows_CPM = mysql_num_rows($CPM);
				  if($totalRows_CPM<1){
						   $insertSQL=sprintf("INSERT INTO course_pay(CP_Text, CP_Money, Course_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
									   GetSQLValueString("學分費", "text"),
									   GetSQLValueString($Course_Credits, "int"),
									   GetSQLValueString($_POST['CourseIDs'][$c1], "int"),
									   GetSQLValueString($_POST['Season_Code'][$_POST['CourseIDs'][$c1]], "int"),
									   GetSQLValueString($_POST['Com_ID'][$_POST['CourseIDs'][$c1]], "int"),
									   GetSQLValueString(1, "int"),
									   GetSQLValueString(1, "int"), 					   					   
									   GetSQLValueString($EditTime, "date"),
									   GetSQLValueString($EditTime, "date"),
									   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
									   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
									   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));
						   mysql_select_db($database_dbline, $dbline);
						   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
				  }
				  mysql_free_result($CPM);
				  //學分費ED
				  $updateSQL = sprintf("update course set Credit_Money=%s, Course_Money=%s, CO_Text=%s, CO_Sale=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where Course_ID=%s",
			   				   
							   GetSQLValueString($Course_Credits, "int"),
							   GetSQLValueString($Course_Credits+$_POST['Credit2_Money'][$_POST['CourseIDs'][$c1]]+$_POST['Pro_Money'][$_POST['CourseIDs'][$c1]], "int"),

							   GetSQLValueString($_POST['CO_Text'], "text"),
							   GetSQLValueString($_POST['CO_Sale'], "text"),
							   
							   GetSQLValueString($EditTime, "date"),

							   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
							   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
							   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
							   GetSQLValueString($_POST['CourseIDs'][$c1], "int"));	          
				  mysql_select_db($database_dbline, $dbline);
				  $Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
			  }     
		}
		else if(isset($_POST['Multi_Mode']) && $_POST['Multi_Mode']==12){
			  for($c1=0;$c1<count($_POST['CourseIDs']);$c1++){
				  //刪除原本的
				  $deleteSQL = sprintf("DELETE FROM course_pay WHERE Course_ID=%s and CP_Text=%s",
			                       GetSQLValueString($_POST['CourseIDs'][$c1], "int"),
			                       GetSQLValueString("課程保證金", "text"));

				  mysql_select_db($database_dbline, $dbline);
				  $Result1_3 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
				  
				  $query_CPM = sprintf("SELECT * from course_pay where Course_ID=%s and CP_Text=%s",GetSQLValueString($_POST['CourseIDs'][$c1], "int"),GetSQLValueString("課程保證金", "text"));
				  $CPM = mysql_query($query_CPM, $dbline) or die(mysql_error());
				  $row_CPM = mysql_fetch_assoc($CPM);
				  $totalRows_CPM = mysql_num_rows($CPM);
				  if($totalRows_CPM<1){
						   $insertSQL=sprintf("INSERT INTO course_pay(CP_Text, CP_Money, Course_ID, Season_Code, Com_ID, CP_Enable, CP_Cantdel, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES(%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s    ,%s, %s)",
									   GetSQLValueString("課程保證金", "text"),
									   GetSQLValueString($_POST['Pro_Money'], "int"),
									   GetSQLValueString($_POST['CourseIDs'][$c1], "int"),
									   GetSQLValueString($_POST['Season_Code'][$_POST['CourseIDs'][$c1]], "int"),
									   GetSQLValueString($_POST['Com_ID'][$_POST['CourseIDs'][$c1]], "int"),
									   GetSQLValueString(1, "int"),
									   GetSQLValueString(1, "int"), 					   					   
									   GetSQLValueString($EditTime, "date"),
									   GetSQLValueString($EditTime, "date"),
									   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
									   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
									   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));
						   mysql_select_db($database_dbline, $dbline);
						   $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
				  }
				  
				  mysql_free_result($CPM);
				  //學分費ED
				  $updateSQL = sprintf("update course set Pro_Money=%s, Course_Money=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where ".$str_course_string,
			   				   
							   
							   GetSQLValueString($_POST['Pro_Money'], "int"),
							   GetSQLValueString($_POST['Pro_Money']+$_POST['Credit2_Money'][$_POST['CourseIDs'][$c1]]+$_POST['Credit_Money'][$_POST['CourseIDs'][$c1]], "int"),
							   
							   GetSQLValueString($EditTime, "date"),

							   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
							   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
							   GetSQLValueString($row_AdminMember['Account_UserName'], "text"));      
				  mysql_select_db($database_dbline, $dbline);
				  $Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
			  }              
		}
		
		
	}

	$updateGoTo = $_SERVER['PHP_SELF']."?Msg=UpdateOK".$str_url;
	header(sprintf("Location: %s", $updateGoTo));
}



$maxRows_Data = 10;
$pageNum_Data = 0;
if (isset($_GET['search_Count']) && is_numeric($_GET['search_Count']) == true) {
  		$maxRows_Data = $_GET['search_Count'];
	}else{
		$maxRows_Data = 10;
	}
if (isset($_GET['pageNum_Data'])) {
  $pageNum_Data = $_GET['pageNum_Data'];
}
$startRow_Data = $pageNum_Data * $maxRows_Data;



//搜班季
$colname02_Data = "%";
if ((isset($_GET['Season_Code'])) && ($_GET['Season_Code'] != "")) {
  $colname02_Data = $_GET['Season_Code'];
}
//搜課程名稱
$colname03_Data = "%";
if ((isset($_GET['Course_Title'])) && ($_GET['Course_Title'] != "")) {
  $colname03_Data = "%".$_GET['Course_Title']."%";
}
//搜學校
$colname04_Data=$colname02_Unit;
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
  $colname04_Data = $_GET['Unit_ID'];
}

$colname05_Data='%';
if (isset($_GET['Checks1']) && $_GET['Checks1'] <> "") {
  $colname05_Data = $_GET['Checks1']; 
}
$colname06_Data='%';
if (isset($_GET['Checks2']) && $_GET['Checks2'] <> "") {
  $colname06_Data = $_GET['Checks2'];
}
$colname07_Data='%';
if (isset($_GET['Checks3']) && $_GET['Checks3'] <> "") {
  $colname07_Data = $_GET['Checks3'];
}
$is_teacher="";
if(isset($_GET["IsT"]) && $_GET["IsT"]==1){
	$is_teacher=" and Course_IsTeacher=1";
}
$query_Data = sprintf("SELECT * FROM course_list WHERE ifnull(Season_Code,'') Like %s and (ifnull(CourseKind_Name,'') Like %s or ifnull(Course_Name,'') Like %s) and ifnull(Com_ID,'') like %s and ifnull(Unit_ID,'') like %s and Course_Check1 like %s and Course_Check2 like %s and Course_Check3 like %s and Course_Pass=1 ".$is_teacher."  ORDER BY Season_Code DESC, Add_Time DESC, Course_ID DESC",GetSQLValueString($colname02_Data, "text"),GetSQLValueString($colname03_Data, "text"),GetSQLValueString($colname03_Data, "text"), GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname04_Data, "text"), GetSQLValueString($colname05_Data, "text"), GetSQLValueString($colname06_Data, "text"), GetSQLValueString($colname07_Data, "text"));
$query_limit_Data = sprintf("%s LIMIT %d, %d", $query_Data, $startRow_Data, $maxRows_Data);
$Data = mysql_query($query_limit_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);

if (isset($_GET['totalRows_Data'])) {
  $totalRows_Data = $_GET['totalRows_Data'];
} else {
  $all_Data = mysql_query($query_Data);
  $totalRows_Data = mysql_num_rows($all_Data);
}
$totalPages_Data = ceil($totalRows_Data/$maxRows_Data)-1;
$queryString_Data = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_Data") == false && stristr($param, "totalRows_Data") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_Data = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_Data = sprintf("&totalRows_Data=%d%s", $totalRows_Data, $queryString_Data);
	
	
$query_Cate = sprintf("SELECT distinct course_list.Season_Code FROM course_list where Com_ID like %s ORDER BY course_list.Season_Code ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

//看到範圍
if($row_AdminMember['Unit_Range']>=3){
$query_Cate2 = "SELECT * FROM unit_detail where Unit_IsSchool=1 ORDER BY Com_ID ASC,Unit_ID ASC";
}
else{
$query_Cate2 = "SELECT * FROM unit_detail where Com_ID like '".$colname03_Unit."' and Unit_ID like '".$colname02_Unit."' and Unit_IsSchool=1 ";	
	}
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

$query_ComCate = sprintf("SELECT * FROM community where Com_IsSchool=1 and Com_IsPrivate=0 and Com_Enable=1 and Com_ID like %s ORDER BY Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$ComCate = mysql_query($query_ComCate, $dbline) or die(mysql_error());
$row_ComCate = mysql_fetch_assoc($ComCate);
$totalRows_ComCate = mysql_num_rows($ComCate);


if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
	
	$Other = "刪除".$row_Permission['ModuleSetting_Title']; 	
	//查詢線上選課
	mysql_select_db($database_dbline, $dbline);
	$query_DelData0= sprintf("SELECT * from signup_item WHERE Course_ID=%s",GetSQLValueString($_POST['ID'], "int"));
	$DelData0 = mysql_query($query_DelData0, $dbline) or die(mysql_error());
	$row_DelData0= mysql_fetch_assoc($DelData0);
	$totalRows_DelData0= mysql_num_rows($DelData0);
	
	//查詢正式名單
	$query_DelData1= sprintf("SELECT * from signup_record WHERE Course_ID=%s",GetSQLValueString($_POST['ID'], "int"));
	$DelData1 = mysql_query($query_DelData1, $dbline) or die(mysql_error());
	$row_DelData1= mysql_fetch_assoc($DelData1);
	$totalRows_DelData1= mysql_num_rows($DelData1);

	if($totalRows_DelData0<1 && $totalRows_DelData1<1)
	{
		
		$deleteSQL = sprintf("update course set Course_Enable=0 WHERE Course_ID=%s",
						   GetSQLValueString($_POST['ID'], "int"));
	
		
		mysql_select_db($database_dbline, $dbline);
		$Result2 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
		
		require_once('../../Include/Data_BrowseDel.php'); 
		mysql_free_result($DelData0); 
		mysql_free_result($DelData1); 
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
		header(sprintf("Location: %s", $updateGoTo));
		 
		}
	else{
		mysql_free_result($DelData0); 
		mysql_free_result($DelData1); 
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelError";
		header(sprintf("Location: %s", $updateGoTo));
	}

}


?>


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>
<!--日期INPUT ED-->
</head>
<body>
<!-- Body Top Start -->
<?php require_once('../../Include/Admin_Body_Top.php'); ?>
<?php  require_once('../../Include/Menu_AdminLeft.php'); ?>
<!-- Body Top End -->
<!--Body menu top Start-->
<?php //require_once('../../Include/Admin_menu_upon.php'); ?>
<!--Body menu top End-->
<!--Body Layout up Start-->
<?php //require_once('../../Include/Admin_Body_Layout_up.php'); ?>
<!--Body Layout up End-->
<div>   
	<center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']?>管理：<?php if(isset($_GET['Checks']) && $_GET['Checks']==0){echo '未審區';}elseif(isset($_GET['Checks']) && $_GET['Checks']==1){echo '審核通過';}elseif(isset($_GET['Checks']) && $_GET['Checks']==2){echo '保留區';}?></div>
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

     <?php if($row_Permission['Per_Add'] == 1){ ?>
     新增課程：<select name="Add_Com_Course" id="Add_Com_Course">
	<option value=''>請選擇</option>
	<?php if($totalRows_ComCate>0){
		do{?>
	<option value="<?php echo $row_ComCate['Com_ID'];?>"><?php echo $row_ComCate['Com_Name'];?></option>
        <?php 	}while($row_ComCate=mysql_fetch_assoc($ComCate));
		mysql_data_seek($ComCate,0);
		$row_ComCate=mysql_fetch_assoc($ComCate);
		}?>
     </select>
     <input type="button" value="新增課程" onclick="addcourse_com();" class="Button_Add">
     <script type="text/javascript">
	function addcourse_com(){
	    if($("#Add_Com_Course").val()!=""){
	    	location.href='AD_Data_Add.php?Com_ID='+$("#Add_Com_Course option:selected").val();
	    }else{alert("請選擇新增哪個社區大學的課程！")}
	}
     </script>
     <?php }?>
    
    <?php if($row_Permission['Per_View'] == 1){ ?>
    <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
	
		
      
      <?php if($row_PermissionCate['Per_View']==1){?>   
      <input type="button" value="歷史課程" class="Button_Add" onClick="location.href='AD_Data_History.php<?php if(isset($_GET["IsT"]) && $_GET["IsT"]==1){echo "?IsT=".$_GET["IsT"];}?>'"/>
      <input type="button" value="課審審查表單" class="Button_Add" onClick="window.open('AD_Data_ExcelGov.php')"/>
      <?php }?></td>
      <td class="right"><img src="../../Icon/find.png" class="middle">   
      初審:<select name="Checks1">
      <option value="">全部</option>
      <option value="1" <?php if(isset($_GET['Checks1']) && $_GET['Checks1']=='1'){echo 'selected';}?>>通過</option>
      <option value="2" <?php if(isset($_GET['Checks1']) && $_GET['Checks1']=='2'){echo 'selected';}?>>未通過</option>      
      <option value="0" <?php if(isset($_GET['Checks1']) && $_GET['Checks1']=='0'){echo 'selected';}?>>待審</option>
      </select>
      複審:<select name="Checks2">
      <option value="">全部</option>
      <option value="1" <?php if(isset($_GET['Checks2']) && $_GET['Checks2']=='1'){echo 'selected';}?>>通過</option>
      <option value="2" <?php if(isset($_GET['Checks2']) && $_GET['Checks2']=='2'){echo 'selected';}?>>未通過</option>      
      <option value="0" <?php if(isset($_GET['Checks2']) && $_GET['Checks2']=='0'){echo 'selected';}?>>待審</option>
      </select>
      決審:<select name="Checks3">
      <option value="">全部</option>
      <option value="1" <?php if(isset($_GET['Checks3']) && $_GET['Checks3']=='1'){echo 'selected';}?>>通過</option>
      <option value="2" <?php if(isset($_GET['Checks3']) && $_GET['Checks3']=='2'){echo 'selected';}?>>未通過</option>      
      <option value="0" <?php if(isset($_GET['Checks3']) && $_GET['Checks3']=='0'){echo 'selected';}?>>待審</option>
      </select>   
      班季:<select name="Season_Code" id="Season_Code" >
        <option value="">:::全部:::</option>
        <?php if($totalRows_Cate>0){
				  do { ?>
			<option value="<?php echo $row_Cate['Season_Code']; ?>" <?php if (isset($_GET['Season_Code'])&&$_GET['Season_Code'] == $row_Cate['Season_Code']) { echo "selected='selected'"; } ?>><?php if(substr($row_Cate['Season_Code'],-1,1)=="1"){echo substr_replace($row_Cate['Season_Code'],'春季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="2"){echo substr_replace($row_Cate['Season_Code'],'夏季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="3"){echo substr_replace($row_Cate['Season_Code'],'秋季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="4"){echo substr_replace($row_Cate['Season_Code'],'冬季班',-1);}  ?></option>
			<?php } while ($row_Cate = mysql_fetch_assoc($Cate));
		      } ?>
      </select>
      <select name="Unit_ID" id="Unit_ID" >
        <option value="">:::全部:::</option>
        <?php do { ?>
        <option value="<?php echo $row_Cate2['Unit_ID']; ?>" <?php if (isset($_GET['Unit_ID'])&&$_GET['Unit_ID'] == $row_Cate2['Unit_ID']) { echo "selected"; } ?>><?php echo $row_Cate2['Unit_Name'];?></option>
        <?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
      </select>
      
      	 <div class="display-inline">標題、類別:</div><input type="text" name="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Title" id="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Title" value="<?php echo @$_GET['Course_Title']; ?>" placeholder="請輸入標題/類別關鍵字"> <input type="submit" value="查詢" class="Button_General">
         <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General">
			<?php if (isset($_GET['IsT'])) {  ?><input name="IsT" type="hidden" value="<?php echo $_GET['IsT']; ?>"/><?php }  ?></td>
      </tr>
    </table>
    </div>
    </form>
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg DelError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料刪除失敗，此課程已有學員報名</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
           <div class="Error_Msg AddError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料登錄失敗！</div>
          
      
 <form name="Multi_Edit" id="M<ulti_Edit" method="post">   
<input name="Course_CheckOn" id="Course_CheckOn" value="全勾選" type="button">
<input name="Course_CheckOff" id="Course_CheckOff" value="全取消" type="button"><br/>
批次修改:<select name="Com_Mode" id="Com_Mode" onchange="Mode_Change()">
	<option value=''>請選擇社大</option>
	<?php if($totalRows_ComCate>0){
		do{?>
	<option value="<?php echo $row_ComCate['Com_ID'];?>" <?php if($totalRows_ComCate==1){echo 'selected';}?>><?php echo $row_ComCate['Com_Name'];?></option>
        <?php 	}while($row_ComCate=mysql_fetch_assoc($ComCate));
		
		}mysql_free_result($ComCate);?>
        </select>
	 <select name="Multi_Mode" id="Multi_Mode" onchange="Mode_Change()">
	 <option value="">請選擇批次修改項目...</option>
	 <option value="1">學程</option>
	 <option value="2">新舊課程類別</option>
	 <option value="3">前台週課表課程狀態</option>
	 <option value="4">開課日期</option>
	 <option value="5">課程分類</option>
	 <option value="6">課程屬性</option>
	 <option value="7">特別標註</option>
	 <?php if(isset($row_Permission['Per_Pass']) && $row_Permission['Per_Pass']==1){?>
	 <option value="8">初審</option>
	 <option value="9">複審</option>
	 <option value="10">決審</option>
	 <?php }?>
	 <option value="11">課程折扣</option>
	 <option value="12">保證金</option>
	</select>
        <div id="Mode_Area" style="display:inline-block;"></div>
	<script type="text/javascript">
	function Mode_Change(){
	var mode1=$("#Multi_Mode option:selected").val();
	var com1=$("#Com_Mode option:selected").val();
	
	if(com1!=""){
	if (window.XMLHttpRequest) {
		xmlhttp_season_mode = new XMLHttpRequest();
	} 
	else {  
		xmlhttp_season_mode = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var chs=$(".Com_IDArea").length;
	var id_value=0;
	for(var i=0;i<chs;i++){
		id_value=$("input[name='CourseIDs[]']").get(i).value;
		if(id_value>0){
			if($("#Com_ID"+id_value).val()!=com1){
				$("#CourseIDs"+id_value).attr("disabled",true);
			}
			if($("#Com_ID"+id_value).val()==com1){
				$("#CourseIDs"+id_value).attr("disabled",false);
			}
		}
	}
	xmlhttp_season_mode.onreadystatechange = function(){
	//alert(xmlhttp_season_mode.responseText);
		if (xmlhttp_season_mode.readyState==4 && xmlhttp_season_mode.status==200){		
			$("#Mode_Area").html(xmlhttp_season_mode.responseText);	
			
			if($(".DateStyle").length>0){
						
					$('.DateStyle .picker_date').datetimepicker({
						format: 'YYYY/MM/DD',
						locale: 'zh-tw',
						showClear:true,
						showClose:false,
						useCurrent:false
												
					}).on('dp.change', function (e) { 
					     Course_StartWeek();	
					});
					
					$("#Course_StartDay").bind('input', function() {
						Course_StartWeek();
					});
					
			
			}
			if(mode1=="5"){
				callbyAJAX_Course_Kind();
			}
			if(mode1=="11"){
				callbyAJAX_Course_COText();
			}
		
		}
	}
	xmlhttp_season_mode.open("get", "mode_area.php?MID=" + encodeURI(mode1)+"&Com_ID="+encodeURI(com1), true);
	xmlhttp_season_mode.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_season_mode.send();
	}else{
		alert("請選擇社區大學");
	}
	}
	function Course_StartWeek(){
		if($("#Course_StartDay").length>0){
			    	if($("#Course_StartDay").val()!=''){
					var string=$("#Course_StartDay").val();
					var OneDay = new Array();
					    OneDay = string.split("/");
				    var cdate = OneDay[1]+"/"+OneDay[2]+"/"+OneDay[0];    
				    var day = new Date(Date.parse(cdate));   
				    var today = new Array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
				    $("#Course_StartWeek").val(today[day.getDay()]);
				}
				else{
					$("#Course_StartWeek").val('');
				}
		}
				
	}
	function callbyAJAX_Course_Kind(){
		
		var mainItemValue = $("#Com_Mode option:selected").val();
		if (window.XMLHttpRequest) {
			xmlhttp_course_kind = new XMLHttpRequest();
		} 
		else {  xmlhttp_course_kind = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp_course_kind.onreadystatechange = function(){
				
			if (xmlhttp_course_kind.readyState==4 && xmlhttp_course_kind.status==200){		
				$("#CourseKind_NameArea").html(xmlhttp_course_kind.responseText);
				if(window.Call_CourseKinde_Code){Call_CourseKinde_Code();}		
			}
		}
	
		xmlhttp_course_kind.open("get", "../SetData/js/course_kind.php?Com_ID=" + encodeURI(mainItemValue), true);
		xmlhttp_course_kind.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
		xmlhttp_course_kind.send();
	}
	function Call_CourseKinde_Code(){
		var string=$("#CourseKind_NameArea option:selected").val();
		var CourseKind_Code = new Array();
		CourseKind_Code = string.split(" ： ");
		$("#CourseKind_Name").val(CourseKind_Code[1]);
		$("#CourseKind_Code").val(CourseKind_Code[0]);
		if(window.callbyAJAX_CourseKind_Cate){callbyAJAX_CourseKind_Cate();}
	}
	function callbyAJAX_CourseKind_Cate(){
		
		var mainItemValue = $("#Com_Mode option:selected").val();
		var mainItemValue2=$("#CourseKind_Code").val();
		if(mainItemValue!="" && mainItemValue2!=""){
			if (window.XMLHttpRequest) {
				xmlhttp_coursekind_cate = new XMLHttpRequest();
			} 
			else {  xmlhttp_coursekind_cate = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp_coursekind_cate.onreadystatechange = function(){

				if (xmlhttp_coursekind_cate.readyState==4 && xmlhttp_coursekind_cate.status==200){		
					$("#CourseKindCate_Name").html(xmlhttp_coursekind_cate.responseText);			
				}
			}
			
			xmlhttp_coursekind_cate.open("get", "../SetData/js/coursekind_cate.php?Com_ID=" + encodeURI(mainItemValue)+"&CourseKind_Code="+encodeURI(mainItemValue2), true);
			xmlhttp_coursekind_cate.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_coursekind_cate.send();
		}
		else{
			$("#CourseKindCate_Name").html("<option value=''>請選擇...</option>");	
		}
	}
	
	function callbyAJAX_Course_COText(){
	
		var mainItemValue = $("#Com_Mode option:selected").val();//Com_ID
		if (window.XMLHttpRequest) {
			xmlhttp_course_cotext = new XMLHttpRequest();
		} 
		else {  xmlhttp_course_cotext = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp_course_cotext.onreadystatechange = function(){

			if (xmlhttp_course_cotext.readyState==4 && xmlhttp_course_cotext.status==200){		
				$("#Course_COTextArea").html(xmlhttp_course_cotext.responseText);
				if(window.call_Credit2_Money){
					call_Course_COText();	
				}		
			}
		}

		xmlhttp_course_cotext.open("get", "../SetData/js/course_cotext.php?Com_ID=" + encodeURI(mainItemValue), true);	  
		xmlhttp_course_cotext.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
		xmlhttp_course_cotext.send();
	}
	function call_Course_COText(){
		if($("#Course_COTextArea option:selected").val()!=""){
			var string=$("#Course_COTextArea option:selected").val();
			var CO_List = new Array();
			CO_List = string.split("n;n");
			$("#CO_Text").val(CO_List[0]);
			$("#CO_Sale").val(CO_List[1]);
		}
		else{
			$("#CO_Text").val('無');
			$("#CO_Sale").val(1);
		}
	}
	</script>
        <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
          <tr class="TableBlock_shadow_Head_Back">
          	<td class="middle center" width="8%">班季</td>
            <td class="middle center" width="10%">分校</td>
<td class="middle center" width="2%"></td>
            <td class="middle center" width="15%">名稱</td>
            <td class="middle center" width="15%">類別</td>
            <td class="middle center" width="8%">類型</td>
            <td class="middle center" width="8%">上課時間</td>
           
            <td class="middle center" width="10%">老師</td>

	    <td class="middle center" width="8%">招數</td>
	    <td class="middle center" width="8%">開數</td>
	    <td class="middle center" width="8%">報數</td>
	    <td class="middle center" width="8%">繳數</td>

            <td class="middle center" width="8%">初審</td>
	    <td class="middle center" width="8%">複審</td>
	    <td class="middle center" width="8%">決審</td>
			
            <td class="middle center" width="22%">操作</td>
          </tr>
           <?php if ($totalRows_Data > 0) { // Show if recordset not empty 
		   //分頁功能OP
		 
				$now_page = 0; //取得當前頁數
				if (isset($_GET['pageNum_Data']) && $_GET['pageNum_Data'] != "") { $now_page = $_GET['pageNum_Data']; }
				$min_page = max(0,$now_page-5);
				$max_page = min($totalPages_Data, $now_page+5);
				if (($now_page < 7) && ($totalPages_Data > 10)) {
					$min_page = 0;
					$max_page = 11;
				}	   
		   //分頁功能END
		   ?>
			<?php do { ?>
              <tr>
              	<td class="middle center">
				<?php echo $row_Data['Season_Year'].$row_Data['SeasonCate_Name'];?></td>
                <td class="middle center">
				 <?php echo $row_Data['Com_Name'].$row_Data['Unit_Name'];?>
                </td>
		<td class="middle center">
				 <input type="checkbox" name="CourseIDs[]" id="CourseIDs<?php echo $row_Data['Course_ID'];?>" class="CheckCourse" value="<?php echo $row_Data['Course_ID'];?>">
				 <input type="hidden" name="Rule_Credit[<?php echo $row_Data['Course_ID'];?>]" id="Rule_Credit<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Rule_Credit'];?>">
				 <input type="hidden" name="Course_Credit[<?php echo $row_Data['Course_ID'];?>]" id="Course_Credit<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Course_Credit'];?>">
				 <input type="hidden" name="Course_OriSpecial[<?php echo $row_Data['Course_ID'];?>]" id="Course_OriSpecial<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Course_Special'];?>">
				 <input type="hidden" name="Season_Code[<?php echo $row_Data['Course_ID'];?>]" id="Season_Code" value="<?php echo $row_Data['Season_Code'];?>">
				 <input type="hidden" name="Com_ID[<?php echo $row_Data['Course_ID'];?>]" id="Com_ID<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Com_ID'];?>" class="Com_IDArea">
				 <input type="hidden" name="Credit2_Money[<?php echo $row_Data['Course_ID'];?>]" id="Credit2_Money<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Credit2_Money'];?>">
				 <input type="hidden" name="Pro_Money[<?php echo $row_Data['Course_ID'];?>]" id="Pro_Money<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Pro_Money'];?>">
				 <input type="hidden" name="Credit_Money[<?php echo $row_Data['Course_ID'];?>]" id="Credit_Money<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Credit_Money'];?>">
                </td>
                <td class="middle Black">
                <a href="javascript:newin(900,700,'AD_Data_Detail.php?ID=<?php echo $row_Data['Course_ID'];?>')"><?php echo mb_substr($row_Data['Course_Name'],0,12,"utf8");if(mb_strlen($row_Data['Course_Name'],'utf-8')>12){echo '...';}?></a>
                </td>
                <td class="middle center MainColor">
                <?php echo $row_Data['CourseKind_Name'];?>
                </td>
                <td class="middle center MainColor">
                <?php if($row_Data['Course_Free']==1){echo '推廣';}elseif($row_Data['Course_Free']==2){echo '非正規教育認證課程';}else{echo '一般';}?>
                </td>
                <td class="middle center"><?php echo $row_Data['Course_Day1'].$row_Data['Course_Time'].date("H:i",strtotime($row_Data['Course_Start1']))."~".date("H:i",strtotime($row_Data['Course_End1'])); ?></td>
       			
                <td class="middle center"><?php echo str_replace(",",",<br/>",$row_Data['Teacher_UserName'])."&nbsp;"; ?></td>

<?php

	//選課人次
/*$query_SignCate = sprintf("select a.Course_ID, sum(Course_Num) as Course_Num from (SELECT
count(Course_ID) as Course_Num,
signup_itemmoney.Course_ID
FROM
signup_itemmoney
inner join signup on signup.Signup_ID=signup_itemmoney.Signup_ID and signup_itemmoney.Signup_Status <> '已繳費'
where signup_itemmoney.CP_Text='學分費' and signup_itemmoney.Course_ID=%s
group by signup_itemmoney.Course_ID,signup_itemmoney.Member_ID
union all select 
count(Course_ID) as Course_Num,
signup_record_count.Course_ID
FROM
signup_record_count 
where signup_record_count.Course_ID=%s
group by signup_record_count.Course_ID
) as a
group by a.Course_ID",GetSQLValueString($row_Data['Course_ID'], "int"),GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate = mysql_query($query_SignCate, $dbline) or die(mysql_error());
$row_SignCate = mysql_fetch_assoc($SignCate);
$totalRows_SignCate = mysql_num_rows($SignCate);
$SignupNum=0;//選課人次
if($totalRows_SignCate>0){
	$SignupNum=$row_SignCate['Course_Num'];//選課人次
}*/
$query_SignCate = sprintf("SELECT
count(Course_ID) as Course_Num,
signup_itemmoney.Course_ID
FROM
signup_itemmoney
inner join signup on signup.Signup_ID=signup_itemmoney.Signup_ID and signup_itemmoney.Signup_Status <> '已繳費'
where signup_itemmoney.CP_Text='學分費' and signup_itemmoney.Course_ID=%s
group by signup_itemmoney.Course_ID,signup_itemmoney.Member_ID
",GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate = mysql_query($query_SignCate, $dbline) or die(mysql_error());
$row_SignCate = mysql_fetch_assoc($SignCate);
$totalRows_SignCate = mysql_num_rows($SignCate);
$SignupNum=0;//選課人次
if($totalRows_SignCate>0){
	$SignupNum=$SignupNum+$row_SignCate['Course_Num'];//選課人次
}
mysql_free_result($SignCate);
$query_SignCate = sprintf("select 
count(b.Course_ID) as Course_Num,
b.Course_ID
FROM
(
SELECT
SUBSTRING_INDEX(
		SUBSTRING_INDEX(
			signup_record_alist.Course_ID,
			';',
			numbers.Number
		),
		';',
		- 1
	)  Course_ID,
signup_record_alist.SignupRecord_ID,
signup_record_alist.Member_ID,
signup_record_alist.Season_Code
FROM
	numbers
INNER JOIN signup_record_alist ON CHAR_LENGTH(signup_record_alist.Course_ID) - CHAR_LENGTH(REPLACE(signup_record_alist.Course_ID,';',''))>= (numbers.Number - 1)
where Course_ID=%s
ORDER BY
	SignupRecord_ID ,
	Number 
) as b
where b.Course_ID=%s
group by b.Course_ID
",GetSQLValueString($row_Data['Course_ID'], "int"),GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate = mysql_query($query_SignCate, $dbline) or die(mysql_error());
$row_SignCate = mysql_fetch_assoc($SignCate);
$totalRows_SignCate = mysql_num_rows($SignCate);
if($totalRows_SignCate>0){
	$SignupNum=$SignupNum+$row_SignCate['Course_Num'];//選課人次
}
mysql_free_result($SignCate);
$query_SignCate2 = sprintf("select ifnull(prints_record_people.People,0) as PayNum from prints_record_people 
where prints_record_people.Course_ID=%s
group by prints_record_people.Course_ID",GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate2 = mysql_query($query_SignCate2, $dbline) or die(mysql_error());
$row_SignCate2 = mysql_fetch_assoc($SignCate2);
$totalRows_SignCate2 = mysql_num_rows($SignCate2);

$PayNum=0;//繳費
if($totalRows_SignCate2>0){
	$PayNum=$row_SignCate2['PayNum'];
}
mysql_free_result($SignCate2);
?>
	   	<td class="middle center"><?php echo $row_Data['Course_Max'];?></td>
	    	<td class="middle center"><?php echo $row_Data['Course_Min'];?></td>
	    	<td class="middle center"><?php echo $SignupNum;?></td>
	    	<td class="middle center"><?php echo $PayNum;?></td>

                <td class="middle center">
		<?php if($row_Data['Course_Check1']==1){echo '通過';}elseif($row_Data['Course_Check1']==2){echo '不通過';}elseif($row_Data['Course_Check1']==3){echo '修正通過';}else{echo '待審';}?></td>
		<td class="middle center">
		<?php if($row_Data['Course_Check2']==1){echo '通過';}elseif($row_Data['Course_Check2']==2){echo '不通過';}elseif($row_Data['Course_Check2']==3){echo '修正通過';}else{echo '待審';}?></td>
		<td class="middle center">
		<?php if($row_Data['Course_Check3']==1){echo '通過';}elseif($row_Data['Course_Check3']==2){echo '不通過';}elseif($row_Data['Course_Check3']==3){echo '修正通過';}else{echo '待審';}?></td>
                 
                <td class="middle">
                
              
                  
                    <?php if($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Edit'] == 1&&$row_Permission['Per_Pass'] == 1){ ?>
                    <input type="button" value="修改" class="Button_Edit" onClick="location.href='AD_Data_Edit.php?ID=<?php echo $row_Data['Course_ID']; ?><?php if(isset($_GET["IsT"]) && $_GET["IsT"]==1){echo "&IsT=".$_GET["IsT"];}?>'"/>
                    <?php } ?>
                    <?php if($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Edit'] == 1&&$row_Permission['Per_Pass'] == 1){ ?>
                    <input type="button" value="名額管理" class="Button_Edit" onClick="location.href='AD_Data_Edit_t.php?ID=<?php echo $row_Data['Course_ID']; ?>&Season_Code=<?php if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $_GET['Season_Code'];}?>&Unit_ID=<?php if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $_GET['Unit_ID'];}?>&Course_Title=<?php if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){echo $_GET['Course_Title'];}?>&pageNum_Data=<?php if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>'"/>
                    <?php } ?>
                    <?php if($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Del'] == 1&&$row_Permission['Per_Pass'] == 1){ ?>
                    <input type="button" name="button2" id="button2" value="刪除"  class="Button_Del" onClick="del_course('<?php echo $row_Data['Course_ID']?>','<?php echo $row_Data['Course_Name']?>');">
                    <?php } ?>
                
              
                </td>
              </tr>
              <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
            <?php } // Show if recordset not empty ?>
        </table>
</form>
<script type="text/javascript">
$("#Course_CheckOn").on('click',function(){
	$(".CheckCourse").prop("checked",true);
});
$("#Course_CheckOff").on('click',function(){
	$(".CheckCourse").prop("checked",false);
});
</script>

          <br>
<?php if($row_AdminMember['Unit_Range']>="1" && $row_Permission['Per_Del'] == 1 && $row_Permission['Per_Pass'] == 1){ ?>
<form name="form_Del" id="form_Del" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>" class="center">
<input type="hidden" name="Del" value="form_del">
<input type="hidden" name="ID" id="ID">
<input type="hidden" name="Title" id="Title">
</form>
<?php }?>
<script type="text/javascript">
function del_course(id, name){
	var string_del=confirm('您即將刪除以下資料\n'+name+'\n刪除後資料無法復原,確定要刪除嗎?');
	$("#ID").val(id);
	$("#Title").val(name);
	if(string_del==true){									
		$( "#form_Del" ).submit();
	}
	else{
		$("#ID").val('');
		$("#Title").val('');
	}
}
</script>
		  <!--分頁OP-->
          <div align="center">
        	<form id="search_Count" name="search_Count" method="get" action="" class="center">
        		每頁筆數：<select id="search_Count" name="search_Count" onChange="this.form.submit()">
	                    	<option value="10">10</option>
	                        <option value="20" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 20) { echo "selected='selected'"; } ?>>20</option>
	                        <option value="50" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 50) { echo "selected='selected'"; } ?>>50</option>
	                        <option value="100" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 100) { echo "selected='selected'"; } ?>>100</option>
                    	</select>
                    	<?php 
						if (isset($_GET['Unit_ID'])) {  ?><input name="Unit_ID" type="hidden" value="<?php echo $_GET['Unit_ID']; ?>"/><?php }  ?>
                    	<?php if (isset($_GET['Course_Title'])) {  ?><input name="Course_Title" type="hidden" value="<?php echo $_GET['Course_Title']; ?>"/><?php }  ?>
                        <?php if (isset($_GET['Season_Code'])) {  ?><input name="Season_Code" type="hidden" value="<?php echo $_GET['Season_Code']; ?>"/><?php }  ?>
			<?php if (isset($_GET['Checks1'])) {  ?><input name="Checks1" type="hidden" value="<?php echo $_GET['Checks1']; ?>"/><?php }  ?>
			<?php if (isset($_GET['Checks2'])) {  ?><input name="Checks2" type="hidden" value="<?php echo $_GET['Checks2']; ?>"/><?php }  ?>
			<?php if (isset($_GET['Checks3'])) {  ?><input name="Checks3" type="hidden" value="<?php echo $_GET['Checks3']; ?>"/><?php }  ?>
			<?php if (isset($_GET['IsT'])) {  ?><input name="IsT" type="hidden" value="<?php echo $_GET['IsT']; ?>"/><?php }  ?>
        	</form>		
		   <table border="0">
                    <tr>
                        <td>
                        <?php if ($pageNum_Data > 0) { // Show if not first page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, 0, $queryString_Data); ?>'" class="gotopage Button_General" type="button"  value="第一頁" name="b1">
                        <?php } // Show if not first page ?>
                        <?php if ($pageNum_Data > 0) { // Show if not first page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, max(0, $pageNum_Data - 1), $queryString_Data); ?>'" class="gotopage Button_General" type="button"  value="上一頁" name="b2">
                        <?php } // Show if not first page ?>
                        <?php for($ii=@$min_page;$ii<=@$max_page;$ii++){ ?>
                            <?php if ($ii == @$now_page) { ?>
                                <span class="nowpage"><input  class="gotopage Navi_Use" value="<?php echo ($ii+1); ?>" type="button"></span>
                            <?php } else { ?>
                                <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, $ii, $queryString_Data); ?>'" class="gotopage Navi_NoUse" value="<?php echo ($ii+1); ?>" type="button">
                            <?php } ?>
                        <?php } ?>
                        <?php if ($pageNum_Data < $totalPages_Data) { // Show if not last page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, min($totalPages_Data, $pageNum_Data + 1), $queryString_Data); ?>'" class="gotopage Button_General" value="下一頁" type="button">
                        <?php } // Show if not last page ?>
                        <?php if ($pageNum_Data < $totalPages_Data) { // Show if not last page ?>
                           <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, $totalPages_Data, $queryString_Data); ?>'" class="gotopage Button_General" value="最後一頁" type="button">
                        <?php } // Show if not last page ?>
                        </td>
                    </tr>
                </table>
                <br /><br />
                No. <?php echo ($startRow_Data + 1) ?> ~ <?php echo min($startRow_Data + $maxRows_Data, $totalRows_Data) ?> 共 <?php echo $totalRows_Data ?> 筆資料
                </div>
                <!--分頁END-->  
      </div>
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
      <?php } ?>
        </td>
      </tr>
    </table>
    <br><br><br>
	</center>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php
mysql_free_result($Data);
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_PermissionCate.php'); ?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>