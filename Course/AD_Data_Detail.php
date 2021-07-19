<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
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
$query_Data = sprintf("SELECT * from course_detail where Course_ID=%s and Com_ID Like %s and Unit_ID like %s",GetSQLValueString($colname_ID, "int"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);

$query_TeacherData = sprintf("SELECT * from teacher where Teacher_ID=%s and Com_ID Like %s ",GetSQLValueString($row_Data['Course_TeacherID'], "int"),GetSQLValueString($colname03_Unit, "text"));
$TeacherData = mysql_query($query_TeacherData, $dbline) or die(mysql_error());
$row_TeacherData = mysql_fetch_assoc($TeacherData);
$totalRows_TeacherData = mysql_num_rows($TeacherData);


?>

<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

</head>
<body>

<div>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        
        <td class="middle">
    <?php if($row_Permission['Per_View'] == 1){ ?>
    <?php if($totalRows_Data>0){?>
    <?php require_once('Course_Detail.php'); ?>
   
    
   <!-- <input type="hidden" name="MM_update" value="Form_Edit" />-->
    
    
     <?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權瀏覽此資料</div>   <?php }?><br><br><br>
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
    <?php } ?>
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
<?php //require_once('zz_Teacher_DB.php');?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php
mysql_free_result($Data);
?>