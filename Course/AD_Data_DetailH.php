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
$query_Data = sprintf("SELECT * from course_detail_h where Course_ID=%s and Com_ID Like %s ",GetSQLValueString($colname_ID, "int"),GetSQLValueString($colname03_Unit, "text"));
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

$query_CourseOfferArea = "SELECT * FROM course_offer ORDER BY CO_Sort ASC";
$CourseOfferArea = mysql_query($query_CourseOfferArea, $dbline) or die(mysql_error());
$row_CourseOfferArea = mysql_fetch_assoc($CourseOfferArea);
$totalRows_CourseOfferArea = mysql_num_rows($CourseOfferArea);

?>




<?php //require_once('Teacher_DB.php');?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Tools/JQFileUpLoad/JQFileUpLoad.php'); ?>
<script src="../../ckeditor/ckeditor.js"></script>

</head>
<body>

<div>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       
        <td class="middle">
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">瀏覽<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></div>
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
				document.getElementById("Unit_ID").innerHTML = xmlhttp_subitems.responseText;
			}
			xmlhttp_subitems_area.onreadystatechange = function() 
			{
				document.getElementById("Loc_ID").innerHTML = xmlhttp_subitems_area.responseText;
			}
	
			xmlhttp_subitems.open("get", "cate_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Unit_ID="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems.send();
			xmlhttp_subitems_area.open("get", "area_value.php?Com_ID=" + mainItemValue+"&Loc_ID="+mainItemValue3, true);
			xmlhttp_subitems_area.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems_area.send();
	
      }
	   </script>
    
<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
<?php require_once('../../Tools/JQFileUpLoad/UpLoadFile_BulletinJSCSS.php'); ?>
</body>
</html>
<?php //require_once('zz_Teacher_DB.php');?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php
mysql_free_result($Data);
mysql_free_result($Cate2);
mysql_free_result($Cate3);
?>