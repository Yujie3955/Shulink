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
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php require_once('../../Include/Permission.php'); ?>
<?php


mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM unit_detail WHERE Com_ID Like %s and Unit_ID Like %s and Unit_IsSchool=1 ORDER BY Com_ID ASC,Unit_ID ASC", GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname02_Unit, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


if (isset($_POST['MM_update']) && $_POST['MM_update'] == "Form_Edit") {
  if(isset($_POST['button2']) && $_POST['button2']=="刪除"){
	  $Other="刪除".$row_Permission['ModuleSetting_Title'];
	  $_POST['Title']=$_POST['Del_Title'];
	  require_once('../../Include/Data_BrowseDel.php');	
	  $deleteSQL = sprintf("update unit set Unit_Enable=0  WHERE Unit_ID=%s",
	                       GetSQLValueString($_POST['ID'], "int"));

	  mysql_select_db($database_dbline, $dbline);
	  $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
	  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
  }elseif(isset($_POST['button1']) && $_POST['button1']=="更新"){
  	$Other="修改".$row_Permission['ModuleSetting_Title'];
  	mysql_select_db($database_dbline, $dbline);
	$query_Area2 = "SELECT * FROM area where Postal_Code='".$_POST['Postal_Code']."'";
	$Area2 = mysql_query($query_Area2, $dbline) or die(mysql_error());
	$row_Area2 = mysql_fetch_assoc($Area2);
	$totalRows_Area2= mysql_num_rows($Area2);	
	$UnitRange=0;

	if(isset($_POST['Unit_IsSchool'])&&$_POST['Unit_IsSchool']=="1"){$UnitRange=1;}
	else{$UnitRange=2;}
		
	$EditTime=date("Y-m-d H:i:s");
	$insertSQL = sprintf("Update unit Set Unit_Name=%s, Unit_Postal=%s, Unit_CountyCate=%s, Unit_CountyName=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s,Unit_Range=%s, Unit_Remark=%s where Unit_ID=%s and Unit_IsSchool=1",
                       			   GetSQLValueString($_POST['Title'], "text"),
					   GetSQLValueString($_POST['Postal_Code'], "int"),
					   GetSQLValueString($row_Area2['County_Cate'], "text"),
					   GetSQLValueString($row_Area2['County_Name'], "text"),
					   GetSQLValueString($EditTime, "date"),
		                           GetSQLValueString($_POST['Edit_Account'], "text"),
		                           GetSQLValueString($_POST['Edit_Unitname'], "text"),
		                           GetSQLValueString($_POST['Edit_Username'], "text"),
					   GetSQLValueString($UnitRange, "text"),
					   GetSQLValueString($_POST['Unit_Remark'], "text"),
                       			   GetSQLValueString($_POST['ID'], "int"));
					   
	    $PastContent=$row_Cate['Com_ID']."/".$row_Cate['Unit_IsSchool']."/".$row_Cate['Unit_Name']."/".$row_Cate['Unit_Contacts']."/".$row_Cate['Unit_Email']."/".$row_Cate['Unit_Phone']."/".$row_Cate['Unit_Tel']."/".$row_Cate['Unit_Fax']."/".$row_Cate['Unit_Postal']."/".$row_Cate['Unit_CountyCate']."/".$row_Cate['Unit_CountyName']."/".$row_Cate['Unit_Address']."/".$row_Cate['Unit_Longitude']."/".$row_Cate['Unit_Latitude']."/".$row_Cate['Unit_Url']."/".$row_Cate['Unit_Code']."/".$row_Cate['Add_Time']."/".$row_Cate['Add_Account']."/".$row_Cate['Add_Unitname']."/".$row_Cate['Add_Username']."/".$row_Cate['Edit_Time']."/".$row_Cate['Edit_Account']."/".$row_Cate['Edit_Unitname']."/".$row_Cate['Edit_Username']."/".$row_Cate['Unit_Range']."/".$row_Cate['Unit_Remark']."/".$row_Cate['Unit_ID'];

$NewContent = $row_Cate['Com_ID']."/".$row_Cate['Unit_IsSchool']."/".$_POST['Title']."/".$row_Cate['Unit_Contacts']."/".$row_Cate['Unit_Email']."/".$row_Cate['Unit_Phone']."/".$row_Cate['Unit_Tel']."/".$row_Cate['Unit_Fax']."/".$_POST['Postal_Code']."/".$row_Area2['County_Cate']."/".$row_Area2['County_Name']."/".$row_Cate['Unit_Address']."/".$row_Cate['Unit_Longitude']."/".$row_Cate['Unit_Latitude']."/".$row_Cate['Unit_Url']."/".$row_Cate['Unit_Code']."/".$row_Cate['Add_Time']."/".$row_Cate['Add_Account']."/".$row_Cate['Add_Unitname']."/".$row_Cate['Add_Username']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username']."/".$UnitRange."/".$_POST['Unit_Remark']."/".$_POST['ID'];
	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
	require_once('../../Include/Data_BrowseUpdate.php');
	$updateGoTo = @$_SERVER['PHP_SELF']."?Msg=UpdateOK";  
	mysql_free_result($Area2);
  }
  header(sprintf("Location: %s", $updateGoTo));
}


$query_Cate2 = sprintf("SELECT * FROM community WHERE Com_ID like %s and Com_ID <>4 and Com_Enable=1 and Com_IsPrivate <> 1 ORDER BY Com_ID ASC",  GetSQLValueString($colname03_Unit, "text"));
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);



$query_County = "SELECT * FROM County Group by County_Cate order by County_ID ASC";
$County = mysql_query($query_County, $dbline) or die(mysql_error());
$row_County = mysql_fetch_assoc($County);
$totalRows_County = mysql_num_rows($County);

$query_Area3 = sprintf("SELECT * FROM area order by County_ID ASC");
$Area3 = mysql_query($query_Area3, $dbline) or die(mysql_error());
$row_Area3 = mysql_fetch_assoc($Area3);
$totalRows_Area3 = mysql_num_rows($Area3);
?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<script type="text/javascript">
function checkLength(which) {
    var maxChars = 100; //
    if (which.value.length > maxChars)
    {
        alert("您出入的字數超多限制!");
        // 超過限制的字數了就將 文字框中的內容按規定的字數 擷取
        which.value = which.value.substring(0,maxChars);
        return false;
    }
    else
    {
        var curr = maxChars - which.value.length; //250 減去 當前輸入的
        //document.getElementByIdx_x("sy").innerHTML = curr.toString();
        return true;
    }
}
<script>

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
<div>   
	<center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']; ?> - <?php echo $row_ModuleSet['ModuleSetting_SubName']; ?>管理</div>
<?php if(@$_GET['Msg'] == "AddOK"){ ?>
	<script language="javascript">
	function AddOK(){
		$('.Success_Add').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddOK,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "AddError"){ ?>
	<script language="javascript">
	function AddError(){
		$('.Error_Add').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddError,0);
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

  
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Error_Msg Error_Add" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料登錄失敗，已有重複地點</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
      
      <?php if($row_Permission['Per_Add'] == 1){ ?>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
        <div align="center">
          <fieldset style="max-width:1000px;">
              <legend> 新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></legend>
             
              <div align="left">
              
			  <span class="FormTitle02"> 社區大學：<select name="Com_ID" id="Com_ID" >
              <option value="">請選擇社區大學...</option>  
              <?php do { ?><option value="<?php echo $row_Cate2['Com_ID'];?>"><?php echo $row_Cate2['Com_Name'];?></option>	<?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); 
				if($totalRows_Cate2> 0) {
				    mysql_data_seek($Cate2, 0);
				    $row_Cate2 = mysql_fetch_assoc($Cate2);
				}?>
              </select>
              <br/>
	      <?php echo $row_ModuleSet['ModuleSetting_SubName']?>名稱：
	      <input name="Title" type="text" id="Title" style="text-align: left; width:100%; max-width: 200px;" required>
              <br/>
	      描述：<input name="Unit_Remark" type="text" id="Unit_Remark" style="text-align: left; width: 84%;  max-width:400px;" onkeyup="checkLength(this);" onchange="checkLength(this);" >
	      <br/>
              區域：<span id="Postal" style="display:none;"></span><input type="hidden" id="Postal_Code" name="Postal_Code" style="size:5; width:50px;" ><select name="County_ID" id="County_ID" onChange="callByAJAX();"><option value="">請選擇縣市...</option>
      <?php if($totalRows_County>0){
		       do{
				   ?><option value="<?php echo $row_County['County_Cate'];?>"><?php echo $row_County['County_Cate'];?></option>
				   
				   
				   
				   
				   <?php }while($row_County = mysql_fetch_assoc($County));
	  			if($totalRows_County> 0) {
				    mysql_data_seek($County, 0);
				    $row_County = mysql_fetch_assoc($County);
				}
	  
	  
	  }?>
      
      
      </select>&nbsp;<select name="County_Name" id="County_Name" onChange="callPostal();">
      <option value="" >:::請選擇區域:::</option>
    </select>
    
   <br/>
                 <div align="center">
                  <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
                  <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
                  <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
                  <input type="submit" value="確定新增" class="Button_Submit"/>  <input type="reset" value="重填" class="Button_General"/>
                  </div>
                 
              </div>
              
          </fieldset>
        <input type="hidden" name="MM_insert" value="Form_Add" />
        </div>
      </form>   
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
      <?php } ?>
      
      <?php if($row_Permission['Per_View'] == 1){ ?>
      
      <div align="center">
	
      <fieldset style="max-width:1000px;">
        <legend> 修改/刪除<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></legend>
        
	<div align="center">
	<table width="100%" border="0" cellpadding="5" cellspacing="2">
	      <tr>	      
	      <td class="right"><img src="../../Icon/find.png" class="middle">
	        社區大學：<select name="Search_ComID" id="Search_ComID" >
              <option value="">請選擇社區大學...</option>  
              <?php do { ?><option value="<?php echo $row_Cate2['Com_ID'];?>"><?php echo $row_Cate2['Com_Name'];?></option>	
	      <?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
              </select> <input type="text" name="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Name" id="Search_<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Name" placeholder="請輸入標題關鍵字" > <input type="button" value="查詢" class="Button_General" onclick="Unit_Content();">
	         <input type="button" value="全部顯示"  class="Button_General"  onclick="Unit_Content_Clear();"></td>
	      </tr>
	</table>
	</div>
        <div id="Unit_Area" style="width:100%;"></div>
        </fieldset>
        </div>
        <?php }else{ ?><br><br><br>
        <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
        <?php } ?>
            
          <br>
      </div>

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

<script type="text/javascript">
//新增OP
function callByAJAX(){
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var mainItemValue = document.getElementById("County_ID").value;  
	var mainItemValue2 = document.getElementById("County_Name").value;  
	
	if (window.XMLHttpRequest) 
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_subitems = new XMLHttpRequest();
	} 
	else 
	{  
		// code for IE6, IE5
		xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp_subitems.onreadystatechange = function() 
	{
		document.getElementById("County_Name").innerHTML = xmlhttp_subitems.responseText;
			
	}
	
	xmlhttp_subitems.open("get", "../College/cate_value.php?County_Cate=" + encodeURI(mainItemValue), true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
}	
function callPostal(){
	document.getElementById("Postal").innerHTML=document.getElementById("County_Name").value;
	document.getElementById("Postal_Code").value=document.getElementById("County_Name").value;
}
function resetall(){
	document.getElementById("Postal").innerHTML="";
	document.getElementById("Postal_Code").value="";

}
//新增ED
//修改OP

function Unit_Content_Clear(){
	var osel=document.getElementById("Search_ComID"); //得到select的ID
 	var opts=osel.getElementsByTagName("option");//得到陣列option
	opts[0].selected=true;
	document.getElementById("Search_<?php echo $row_ModuleSet['ModuleSetting_Code'];?>_Name").value='';
	Unit_Content();
}
Unit_Content();
function Unit_Content(){
	var search_comid=document.getElementById("Search_ComID").options[document.getElementById("Search_ComID").selectedIndex].value;
	var mainItemValue = document.getElementById("Search_<?php echo $row_ModuleSet['ModuleSetting_Code'];?>_Name").value;  
	
	if (window.XMLHttpRequest) 
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_subitems_search = new XMLHttpRequest();
	} 
	else 
	{  
		// code for IE6, IE5
		xmlhttp_subitems_search = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp_subitems_search.onreadystatechange = function() 
	{
		document.getElementById("Unit_Area").innerHTML = xmlhttp_subitems_search.responseText;
		
	}
	xmlhttp_subitems_search.open("get", "ajax/unit_content.php?Com_ID=" + encodeURI(search_comid) +"&T="+ encodeURI(mainItemValue), true);
	xmlhttp_subitems_search.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp_subitems_search.send();
}	
<?php 
if($totalRows_Cate>0){
	do{?>
function callByAJAX<?php echo $row_Cate['Unit_ID'];?>(){
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var mainItemValue = document.getElementById("County_ID<?php echo $row_Cate['Unit_ID'];?>").value;  
	var mainItemValue2 = document.getElementById("County_Name<?php echo $row_Cate['Unit_ID'];?>").value;  
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?> = new XMLHttpRequest();
	} 
	else{  
		// code for IE6, IE5
		xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?> = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?>.onreadystatechange = function() {
		document.getElementById("County_Name<?php echo $row_Cate['Unit_ID'];?>").innerHTML = xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?>.responseText;
	}
	xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?>.open("get", "../College/cate_value.php?County_Cate=" + encodeURI(mainItemValue), true);
	xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?>.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems<?php echo $row_Cate['Unit_ID'];?>.send();
}

function callPostal<?php echo $row_Cate['Unit_ID'];?>(){
	document.getElementById("Postal<?php echo $row_Cate['Unit_ID'];?>").innerHTML=document.getElementById("County_Name<?php echo $row_Cate['Unit_ID'];?>").value;
	document.getElementById("Postal_Code<?php echo $row_Cate['Unit_ID'];?>").value=document.getElementById("County_Name<?php echo $row_Cate['Unit_ID'];?>").value;
}
<?php
	}while($row_Cate=mysql_fetch_assoc($Cate));
}	
?>			
//修改ED			
</script>
<?php
mysql_free_result($Cate);
mysql_free_result($Cate2);
mysql_free_result($Area3);
mysql_free_result($County);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
