<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if (isset($_GET['ID'])) {
  $colname_ID = $_GET['ID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * from course_detail where Course_ID=%s ",GetSQLValueString($colname_ID, "int"));
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
<script src="../../ckeditor/ckeditor.js"></script>

</head>
<body>

<div >   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       
        <td class="middle">
    <?php if($totalRows_Data>0){?>
     <?php require_once('../Course/Course_DetailAll.php'); ?>
    
     <?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權瀏覽此資料</div>   <?php }?><br><br><br>
  
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
	
			xmlhttp_subitems.open("get", "../Course/cate_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Unit_ID="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems.send();
			xmlhttp_subitems_area.open("get", "area_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Loc_ID="+encodeURI(mainItemValue3), true);
			xmlhttp_subitems_area.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems_area.send();
	
      }
	   </script>
    
<!--Body Layout down Start-->
<?php //require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php require_once('../../Include/zz_WebSet.php'); ?>

<?php
mysql_free_result($Data);
?>