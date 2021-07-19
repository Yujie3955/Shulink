<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php
$modulename=explode("_",basename("AD_Data"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);
?>
<?php require_once('module_setting.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET['ID'])) {
  $colname_ID = $_GET['ID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * from course_detail_show where Course_ID=%s ",GetSQLValueString($colname_ID, "int"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);

$query_TeacherData = sprintf("SELECT * from teacher where Teacher_ID=%s ",GetSQLValueString($row_Data['Course_TeacherID'], "int"));
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

    
    <?php if($totalRows_Data>0){?>
    	  <?php require_once('Course_DetailAll.php'); ?>
	<?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 無資料</div>   <?php }?><br><br><br>
   
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
<?php require_once('../../Include/zz_WebSet.php'); ?>
<?php //require_once('zz_module_setting.php'); ?>
<?php
mysql_free_result($Data);
?>