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
<?php require_once('module_setting.php'); 
$query_ModuleSet2 = "SELECT * FROM module_setting WHERE ModuleSetting_Name ='Area' and ModuleSetting_Code='".$Code."'";
$ModuleSet2 = mysql_query($query_ModuleSet2, $dbline) or die(mysql_error());
$row_ModuleSet2 = mysql_fetch_assoc($ModuleSet2);
$totalRows_ModuleSet2 = mysql_num_rows($ModuleSet2);
$ModuleSet_Unit=$row_ModuleSet2['ModuleSetting_SubName'];
mysql_free_result($ModuleSet2);
?>

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php require_once('../../Include/Permission.php'); ?>
<?php
$colname_ID="-1";
if(isset($_GET['UID']) && $_GET['UID']<>""){
	$colname_ID=$_GET['UID'];
}
$colname_ID2="-1";
if(isset($_GET['LID']) && $_GET['LID']<>""){
	$colname_ID2=$_GET['LID'];
}


mysql_select_db($database_dbline, $dbline);
$query_Cate2 = sprintf("SELECT * FROM location_detail WHERE Com_ID like %s and Unit_ID Like %s and Loc_ID = %s ORDER BY Com_ID ASC", GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname02_Unit, "text"), GetSQLValueString($colname_ID2, "int"));
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM room_detail WHERE Com_ID Like %s and Loc_ID = %s  ORDER BY Com_ID ASC,Loc_ID ASC", GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname02_Unit, "text"), GetSQLValueString($row_Cate2['Unit_ID'], "int"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$query_Cate3 = sprintf("SELECT * FROM credit2 WHERE Com_ID Like %s  and Credit2_Enable=1 ORDER BY Com_ID ASC,Credit2_Sort ASC", GetSQLValueString($row_Cate2['Com_ID'], "text"));
$Cate3 = mysql_query($query_Cate3, $dbline) or die(mysql_error());
$row_Cate3 = mysql_fetch_assoc($Cate3);
$totalRows_Cate3 = mysql_num_rows($Cate3);


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit")) {

	if(isset($_POST['button2']) && $_POST['button2']=="刪除"){
		$Other="刪除".$row_Permission['ModuleSetting_Title'];
		$_POST['Title']=$_POST['Del_Title'];
		require_once('../../Include/Data_BrowseDel.php');	
		$deleteSQL = sprintf("DELETE FROM room WHERE Room_ID=%s",
		                       GetSQLValueString($_POST['ID'], "int"));

		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK&UID=".$colname_ID."&LID=".$colname_ID2;
	
	}elseif(isset($_POST['button1']) && $_POST['button1']=="更新"){
		mysql_select_db($database_dbline, $dbline);
	     	$query_CateP = sprintf("SELECT * FROM room WHERE Room_ID = %s", GetSQLValueString($_POST['ID'], "int"));
		$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
		$row_CateP = mysql_fetch_assoc($CateP);
		$totalRows_CateP = mysql_num_rows($CateP);		
		
		$Other="修改".$row_Permission['ModuleSetting_Title'];
		$EditTime=date("Y-m-d H:i:s");
		$updateSQL = sprintf("UPDATE room SET  Room_Name=%s, Room_Enable=%s, Room_Max=%s, Room_Min=%s, Credit2_ID=%s, Credit2_Name=%s, Credit2_Money=%s, Room_Sort=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE Room_ID=%s",
		                   
	                       GetSQLValueString($_POST['Title'], "text"),
			       GetSQLValueString(isset($_POST['Room_Enable']) ? "true" : "", "defined","1","0"),
	                       GetSQLValueString($_POST['Room_Max'], "text"),
	                       GetSQLValueString($_POST['Room_Min'], "text"),
	                       GetSQLValueString($_POST['Credit2_ID'], "text"),
	                       GetSQLValueString($_POST['Credit2_Name'], "text"),
	                       GetSQLValueString($_POST['Credit2_Money'], "text"),
	                       GetSQLValueString($_POST['Room_Sort'], "text"),
	                       GetSQLValueString($EditTime, "date"),
	                       GetSQLValueString($_POST['Edit_Account'], "text"),
	                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
	                       GetSQLValueString($_POST['Edit_Username'], "text"),
	                       GetSQLValueString($_POST['ID'], "int"));
						   

	  
	  $PastContent=$row_CateP['Room_ID']."/".$row_CateP['Com_ID']."/".$row_CateP['Loc_ID']."/".$row_CateP['Room_Name']."/".$row_CateP['Room_Max']."/".$row_CateP['Room_Min']."/".$row_CateP['Credit2_ID']."/".$row_CateP['Credit2_Name']."/".$row_CateP['Credit2_Money']."/".$row_CateP['Room_Enable']."/".$row_CateP['Room_Sort']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$row_CateP['Edit_Time']."/".$row_CateP['Edit_Account']."/".$row_CateP['Edit_Unitname']."/".$row_CateP['Edit_Username'];
	  
	  $NewContent=$_POST['ID']."/".$row_CateP['Com_ID']."/".$row_CateP['Loc_ID']."/".$_POST['Title']."/".$_POST['Room_Max']."/".$_POST['Room_Min']."/".$_POST['Credit2_ID']."/".$_POST['Credit2_Name']."/".$_POST['Credit2_Money']."/".$_POST['Room_Enable']."/".$_POST['Room_Sort']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());

		require_once('../../Include/Data_BrowseUpdate.php');
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateOK&UID=".$colname_ID."&LID=".$colname_ID2;  
		mysql_free_result($CateP);
	}
	header(sprintf("Location: %s", $updateGoTo));
  
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add")) {
	$Other="新增".$row_Permission['ModuleSetting_Title'];
	$AddTime=date("Y-m-d H:i:s");
	mysql_select_db($database_dbline, $dbline);
	$query_CateP2 = sprintf("SELECT * FROM room where Com_ID=%s and Loc_ID=%s and Room_Name=%s", GetSQLValueString($_POST['Com_ID'], "int"), GetSQLValueString($_POST['Loc_ID'], "int"), GetSQLValueString($_POST['Title'], "text"));
    $CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
	$row_CateP2 = mysql_fetch_assoc($CateP2);
	$totalRows_CateP2 = mysql_num_rows($CateP2);
	if($totalRows_CateP2<1){//不重複時新增
		$insertSQL = sprintf("INSERT INTO room (Com_ID, Loc_ID, Room_Name, Room_Enable, Room_Max, Room_Min, Credit2_ID, Credit2_Name, Credit2_Money, Room_Sort, Add_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s,      %s, %s, %s, %s, %s,      %s, %s, %s, %s)",
						   GetSQLValueString($_POST['Com_ID'], "int"),
						   GetSQLValueString($_POST['Loc_ID'], "int"),
						   GetSQLValueString($_POST['Title'], "text"),
						   GetSQLValueString(isset($_POST['Room_Enable']) ? "true" : "", "defined","1","0"),
						   GetSQLValueString($_POST['Room_Max'], "text"),
						   GetSQLValueString($_POST['Room_Min'], "text"),
						   GetSQLValueString($_POST['Credit2_ID'], "text"),
						   GetSQLValueString($_POST['Credit2_Name'], "text"),
						   GetSQLValueString($_POST['Credit2_Money'], "text"),
						   GetSQLValueString($_POST['Room_Sort'], "text"),
						   GetSQLValueString($AddTime, "date"),
						   GetSQLValueString($_POST['Add_Account'], "text"),
						   GetSQLValueString($_POST['Add_Unitname'], "text"),
						   GetSQLValueString($_POST['Add_Username'], "text"));
						   
		$NewContent=$_POST['Com_ID']."/".$_POST['Loc_ID']."/".$_POST['Title']."/".$_POST['Room_Max']."/".$_POST['Room_Min']."/".$_POST['Credit2_ID']."/".$_POST['Credit2_Name']."/".$_POST['Credit2_Money']."/".$_POST['Room_Enable']."/".$_POST['Room_Sort']."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username'];
	
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		require_once('../../Include/Data_BrowseInsert.php');
	    mysql_free_result($CateP2);
		$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddOK&UID=".$colname_ID."&LID=".$colname_ID2;  
		header(sprintf("Location: %s", $insertGoTo));
	}
	else{
		mysql_free_result($CateP2);
		$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddError&UID=".$colname_ID."&LID=".$colname_ID2;  
		header(sprintf("Location: %s", $insertGoTo));	
		
	}
}

?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

<script src="../../Tools/jscolor/jscolor.js" type="text/javascript"></script><!--選色器-->
</head>
<body>
<!-- Body Top Start -->
<?php require_once('../../Include/Admin_Body_Top.php'); ?>
<!-- Body Top End -->
<!--Body menu top Start-->
<?php //require_once('../../Include/Admin_menu_upon.php'); ?>
<!--Body menu top End-->
<!--Body Layout up Start-->
<?php //require_once('../../Include/Admin_Body_Layout_up.php'); ?>
<!--Body Layout up End-->
<div>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="19%"><?php require_once('../../Include/Menu_AdminLeft.php'); ?>
      </td>
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
     <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
      <input type="button" value="回<?php echo $ModuleSet_Unit;?>管理" onclick="location.href='AD_Area_Index.php?UID=<?php echo $row_Cate2['Unit_ID'];?>'" class="Button_General"></td>
      </tr>
     </table>
     </div>
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
                <span class="FormTitle03">社區大學：</span><?php echo $row_Cate2['Com_Name'];?>
		<input type="hidden" name="Com_ID" id="Com_ID" value="<?php echo $row_Cate2['Com_ID'];?>">
                <span class="FormTitle03"><?php echo $ModuleSet_Unit?>：</span><?php echo $row_Cate2['Loc_Name'];?>
		<input type="hidden" name="Loc_ID" id="Loc_ID" value="<?php echo $row_Cate2['Loc_ID'];?>">
              &nbsp;啟用：<input name="Room_Enable" type="checkbox" id="Room_Enable" class="middle" checked="checked"><br/>
                <span class="FormTitle03"><?php echo $row_ModuleSet['ModuleSetting_SubName'];?>名稱：</span><input name="Title" type="text" id="Title" style="width:50%;">
                <br/>
		<span class="FormTitle03">排序：<input name="Room_Sort" type="number" id="Room_Sort" style="width:75px;" >&nbsp;人數上限：<input name="Room_Max" type="number" id="Room_Max" style="width:75px;">&nbsp;人數下限：<input name="Room_Min" type="number" id="Room_Min" style="width:75px;"></span><br/>
		<span class="FormTitle03">雜費：</span>
		<select name="Credit2_Area" id="Credit2_Area0" required onchange="Credit2_Check('0')"><option value="">請選擇雜費</option><?php if($totalRows_Cate3>0){do{?><option value="<?php echo $row_Cate3['Credit2_ID'].';'.$row_Cate3['Credit2_Money'];?>"><?php echo $row_Cate3['Credit2_Name']?></option><?php }while($row_Cate3=mysql_fetch_assoc($Cate3));}mysql_free_result($Cate3);?></select>
		<input name="Credit2_ID" type="hidden" id="Credit2_ID0" >
		<input name="Credit2_Name" type="hidden" id="Credit2_Name0" >
		<input name="Credit2_Money" type="hidden" id="Credit2_Money0" >
             
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
	      <td><?php echo $row_Cate2['Com_Name'].'：'.$row_Cate2['Loc_Name'];?></td>	      
	      <td class="right"><img src="../../Icon/find.png" class="middle">
	         <input type="text" name="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Name" id="Search_<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Name" placeholder="請輸入標題關鍵字" > <input type="button" value="查詢" class="Button_General" onclick="Room_Content();">
	         <input type="button" value="全部顯示"  class="Button_General"  onclick="Room_Content_Clear();"></td>
	      </tr>
	</table>
	</div>
        <div id="Room_Area" style="width:100%;"></div>
	<div align="center"><br/>
        <input type="button" value="回<?php echo $ModuleSet_Unit;?>管理" onclick="location.href='AD_Area_Index.php?UID=<?php echo $row_Cate2['Unit_ID'];?>'" class="Button_General">
	</div>
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
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<script type="text/javascript">
//修改OP

function Room_Content_Clear(){
	
	document.getElementById("Search_<?php echo $row_ModuleSet['ModuleSetting_Code'];?>_Name").value='';
	Room_Content();
}
Room_Content();
function Room_Content(){
	var search_locid='<?php echo $row_Cate2["Loc_ID"];?>';
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
		document.getElementById("Room_Area").innerHTML = xmlhttp_subitems_search.responseText;
		
	}
	xmlhttp_subitems_search.open("get", "ajax/room_content.php?LID=" + encodeURI(search_locid) +"&T="+ encodeURI(mainItemValue), true);
	xmlhttp_subitems_search.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp_subitems_search.send();
}	
function Credit2_Check(id){

	var string_credit2=$("#Credit2_Area"+id+" option:selected").val();		
	var mainItemValue = new Array();
	mainItemValue = string_credit2.split(";");

	$("#Credit2_ID"+id).val(mainItemValue[0]);
	$("#Credit2_Money"+id).val(mainItemValue[1]);
	$("#Credit2_Name"+id).val($("#Credit2_Area"+id+" option:selected").text());
}		
//修改ED
</script>
<?php
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
