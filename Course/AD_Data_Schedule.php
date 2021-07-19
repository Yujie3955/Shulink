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
$colname_ID='-1';
if(isset($_GET['ID']) && is_numeric($_GET['ID'])){
	$colname_ID=$_GET['ID'];
}

$query_Data = sprintf("SELECT * from course_detail where Course_ID=%s and Com_ID Like %s and Unit_ID like %s and Course_Pass=1",GetSQLValueString($colname_ID, "int"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);

$SWeek_List=array("","第一週","第二週","第三週","第四週","第五週","第六週","第七週","第八週","第九週","第十週","第十一週","第十二週","第十三週","第十四週","第十五週","第十六週","第十七週","第十八週","第十九週","第二十週","第二十一週","第二十二週","第二十三週","第二十四週","第二十五週","第二十六週","第二十七週","第二十八週","第二十九週","第三十週","第三十一週","第三十二週","第三十三週","第三十四週","第三十五週","第三十六週");
$page_modes="course_schedule";
$page2_modes="course";
$page_course_code="Course_ID";
require_once("../SetData/Course_ScheduleSQL.php");

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
<?php require_once('../../Include/Menu_AdminLeft.php'); ?>
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
<center>
    <table width="70%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_SubName'];?>大綱</div>
	<div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td><input type="button" value="回課程管理" onclick="location.href='AD_Data_Index.php';" class="Button_General"></td>
      </tr>
    </table>
    </div>
    <?php require_once("../SetData/Course_Schedule.php");?>
    </td>
    </tr>
    </table>
    <br><br><br>
</div>
    </center>
<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->

</body>
</html>

<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
