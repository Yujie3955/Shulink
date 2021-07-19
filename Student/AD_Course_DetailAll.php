<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php //require_once('module_setting.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if (isset($_GET['ID'])) {
  $colname_ID = $_GET['ID'];
}
if (isset($_GET['MID'])) {
  $colname_MID = $_GET['MID'];
}
$query_Cate= sprintf("SELECT SignupRecord_ID from signup_record where Course_ID=%s and Member_ID=%s",GetSQLValueString($colname_ID, "int"),GetSQLValueString($colname_MID, "int"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * from course_onuse where Course_ID=%s ",GetSQLValueString($colname_ID, "int"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);

$query_CourseOfferArea = "SELECT * FROM course_offer ORDER BY CO_Sort ASC";
$CourseOfferArea = mysql_query($query_CourseOfferArea, $dbline) or die(mysql_error());
$row_CourseOfferArea = mysql_fetch_assoc($CourseOfferArea);
$totalRows_CourseOfferArea = mysql_num_rows($CourseOfferArea);

$query_TeacherData = sprintf("SELECT * from teacher where Teacher_ID=%s and Com_ID Like %s ",GetSQLValueString($row_Data['Course_TeacherID'], "int"),GetSQLValueString($row_Data['Com_ID'], "text"));
$TeacherData = mysql_query($query_TeacherData, $dbline) or die(mysql_error());
$row_TeacherData = mysql_fetch_assoc($TeacherData);
$totalRows_TeacherData = mysql_num_rows($TeacherData);
?>





<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<script src="../../ckeditor/ckeditor.js"></script>

</head>
<body>

<div >   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       
        <td class="middle">
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">瀏覽課程</div>
    <?php if($totalRows_Data>0 && $totalRows_Cate>0){?>
     <?php require_once('../Course/Course_Detail.php'); ?>
    
     <?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權瀏覽此資料</div>   <?php }?><br><br><br>
  
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

<?php
mysql_free_result($Data);
?>