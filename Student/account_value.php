<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/DB_Admin.php'); ?>
<?php 
$modulename=explode("_",basename(__FILE__, ".php"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);
/*權限*/
mysql_select_db($database_dbline, $dbline);
$query_Permission = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString("Data", "text"));
$Permission = mysql_query($query_Permission, $dbline) or die(mysql_error());
$row_Permission = mysql_fetch_assoc($Permission);
$totalRows_Permission= mysql_num_rows($Permission);

?>
<?php

if (isset($_GET['Member_Identity'])) {
  $colname_Area = $_GET['Member_Identity'];
}
if (isset($_GET['Com_ID'])) {
  $colname_Area2 = $_GET['Com_ID'];
}

mysql_select_db($database_dbline, $dbline);
$query_Area = sprintf("SELECT Member_ID FROM member where Member_Identity = %s and Com_ID=%s order by Member_ID ASC", GetSQLValueString($colname_Area, "text"),GetSQLValueString($colname_Area2, "int"));
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);


$query_County = "SELECT * FROM County Group by County_Cate order by County_ID ASC";
$County = mysql_query($query_County, $dbline) or die(mysql_error());
$row_County = mysql_fetch_assoc($County);
$totalRows_County = mysql_num_rows($County);


$query_Cate2 = "SELECT * FROM eduction  order by Edu_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);


$query_Cate3 = "SELECT * FROM job  order by Job_ID ASC";
$Cate3 = mysql_query($query_Cate3, $dbline) or die(mysql_error());
$row_Cate3 = mysql_fetch_assoc($Cate3);
$totalRows_Cate3 = mysql_num_rows($Cate3);
//學區
	$query_RecCate = "SELECT StudyArea_ID,StudyArea_Name FROM study_area WHERE StudyArea_Enable=1";
	$RecCate = mysql_query($query_RecCate, $dbline) or die(mysql_error());
	$learnlocationnumber=mysql_num_rows($RecCate);
	while($row = mysql_fetch_array($RecCate)){
		$learnlocation_name[]=$row['StudyArea_Name'];
		$learnlocation_id[]=$row['StudyArea_ID'];
	}
	mysql_free_result($RecCate);
if(isset($_GET['forms_type'])){
	$forms_type=$_GET['forms_type'];
}

$query_TypeData = sprintf("SELECT * FROM member_type inner join season_new on member_type.Season_Code=season_new.Season_Code and member_type.Com_ID=season_new.Com_ID where member_type.Com_ID =%s  order by MemberType_ID ASC",GetSQLValueString($colname_Area2, "int"));
$TypeData = mysql_query($query_TypeData, $dbline) or die(mysql_error());
$row_TypeData = mysql_fetch_assoc($TypeData);
$totalRows_TypeData = mysql_num_rows($TypeData);


?>
<?php
if($totalRows_Area>0){//判斷本區是否註冊OP
	echo '<div id="RepeatAccount_Msg">身分已是本社區大學學員，請更換</div><input value="重複" name="RepeatM" id="RepeatM" type="hidden"><input id="ids" name="ids" value="'.$colname_Area.'" type="hidden">';?>
    
<?php 
}
else{
	 //搜索其他社區是否有此身分
	 mysql_select_db($database_dbline, $dbline);
	 $query_Data = sprintf("SELECT * FROM member_detail_student where Member_Identity = %s order by Edit_Time DESC,Add_Time DESC,Member_ID DESC", GetSQLValueString($colname_Area, "text"));
	 $Data = mysql_query($query_Data, $dbline) or die(mysql_error());
	 $row_Data = mysql_fetch_assoc($Data);
	 $totalRows_Data = mysql_num_rows($Data);
	 
	 if($totalRows_Data>0){//判斷是否在其他社區註冊過OP
		echo '<div id="RepeatAccount_Msg">此身分於其他社區大學已有資料，將連動資料於此社區大學，若有更動資料，其餘社區大學也將會更動</div>
		 	  <input value="無重複1" name="RepeatM" id="RepeatM" type="hidden">
		  	  <input id="ids" name="ids" value="'.$colname_Area.'" type="hidden">';?>
             <?php require_once("form_student_edit.php");?>
    	    
             <div align="center">
             <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
             <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
             <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
             <input type="hidden" name="MM_insert" value="Form_Add" />
             </div>
             
             
<?php 
 	 }//判斷是否在其他社區註冊過END
  	 else{ //從未註冊過OP
	
	      echo '<div id="RepeatAccount_Msg">此身分可使用</div>
		 		<input value="無重複" name="RepeatM" id="RepeatM" type="hidden">
		 		<input id="ids" name="ids" value="'.$colname_Area.'" type="hidden">';?>

       <?php require_once("form_student_edit.php");?>
       <div align="center">
       <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
       <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
       <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
       <input type="hidden" name="MM_insert" value="Form_Add" />
       </div>
       
    <?php }//從未註冊過END?> 
    <input type="submit" value="確定新增" class="Button_Submit" id="CheckOK" style="display:none;"/>    <input type="button" value="取消" class="Button_General" onClick="location.href='AD_Data_Index.php'" /> 
<?php }//判斷本區是否註冊END?>  



<?php mysql_free_result($Area);?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>