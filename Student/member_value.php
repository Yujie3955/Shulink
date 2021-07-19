<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php //require_once('module_setting.php'); ?>
<?php

if (isset($_GET['Com_ID'])) {
  $colname_Area = $_GET['Com_ID'];
}
if (isset($_GET['Member_Identity'])) {
  $colname_Name = $_GET['Member_Identity'];
}
if($colname_Area<>""){
mysql_select_db($database_dbline, $dbline);
$query_Area = sprintf("SELECT * FROM member where Com_ID = %s and Member_Identity=%s order by Edit_Time DESC", GetSQLValueString($colname_Area, "int"), GetSQLValueString($colname_Name, "text"));
$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
$row_Area = mysql_fetch_assoc($Area);
$totalRows_Area = mysql_num_rows($Area);

   if($totalRows_Area>0){

   echo '<div align="center">此社區大學已有註冊過！<input name="ResultValue" id="ResultValue" value="重複" type="hidden"></div>';

   }
   else
   {
$query_Cate2 = "SELECT * FROM eduction  order by Edu_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

$query_Cate3 = "SELECT * FROM job  order by Job_ID ASC";
$Cate3 = mysql_query($query_Cate3, $dbline) or die(mysql_error());
$row_Cate3 = mysql_fetch_assoc($Cate3);
$totalRows_Cate3 = mysql_num_rows($Cate3);

$query_County = "SELECT * FROM County Group by County_Cate order by County_ID ASC";
$County = mysql_query($query_County, $dbline) or die(mysql_error());
$row_County = mysql_fetch_assoc($County);
$totalRows_County = mysql_num_rows($County);

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
         //搜索其他社區是否有此身分
	 
	 $query_Data = sprintf("SELECT * FROM member_detail_student where Member_Identity = %s order by Edit_Time DESC,Add_Time DESC,Member_ID DESC", GetSQLValueString($colname_Name, "text"));
	 $Data = mysql_query($query_Data, $dbline) or die(mysql_error());
	 $row_Data = mysql_fetch_assoc($Data);
	 $totalRows_Data = mysql_num_rows($Data);
	 
	 if($totalRows_Data>0){//判斷是否在其他社區註冊過OP
		echo '<div id="RepeatAccount_Msg">此身分於其他社區大學已有資料，將連動資料於此社區大學，若有更動資料，其餘社區大學也將會更動</div>
		  	  <input id="Member_Identity" name="Member_Identity" value="'.$colname_Name.'" type="hidden">';?>
             <?php $add_self=1;
			require_once("form_student_self.php");?>
    	    
             <div align="center">
	     <input name="ResultValue" id="ResultValue" value="通過" type="hidden">
             <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_Data['Member_ID']; ?>">
             <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="學員">
             <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_Data['Member_UserName']; ?>">
             <input type="hidden" name="MM_insert" value="Form_Add" />
             </div>
<?php	   
	   
	   
	}//if($totalRows_Data>0)
mysql_free_result($Cate3);	   
mysql_free_result($Cate2);
   }	   
mysql_free_result($Area);
}

?>
