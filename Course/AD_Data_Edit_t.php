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
$today=date("Y-m-d");

$Com='-1';
$Code='-1';
if($totalRows_Data>0){
	$Com=$row_Data['Com_ID'];
	$Code=$row_Data['Season_Code'];
}
$query_Cate = sprintf("SELECT Season_Turn, Season_IsAll from season where Com_ID = %s and season.Season_Code = %s ",GetSQLValueString($Com, "int"),GetSQLValueString($Code, "int"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
$Season_Turn=0;
$Season_IsAll=0;
if($totalRows_Cate>0){
	$Season_Turn=$row_Cate['Season_Turn'];
	$Season_IsAll=$row_Cate['Season_IsAll'];
}
mysql_free_result($Cate);
$query_Cate2 = sprintf("SELECT AllNum,SignupRecord_Identity from signup_countonlinedetail where Course_ID = %s ",GetSQLValueString($colname_ID, "int"));
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);
$AllNum=array();
if($totalRows_Cate2>0){
	do{
		if(!isset($AllNum[$row_Cate2['SignupRecord_Identity']])){
			$AllNum[$row_Cate2['SignupRecord_Identity']]='';
		}
		$AllNum[$row_Cate2['SignupRecord_Identity']]=$row_Cate2['AllNum'];
	}while($row_Cate2 = mysql_fetch_assoc($Cate2));

}
mysql_free_result($Cate2);

?>


<?php
$Other = "修改".$row_Permission['ModuleSetting_Title'];
$EditTime=date("Y-m-d H:i:s");
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit")) { 

	$updateSQL = sprintf("update  course set Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s,Course_OnlineAdd=%s where Course_ID=%s",
                      					   
					   GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
                       GetSQLValueString($_POST['Course_OnlineAdd'], "int"),
					   GetSQLValueString($_POST['ID'], "int"));			               
					   
	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
      
$PastContent=$row_Data['Course_ID']."/".$row_Data['Edit_Time']."/".$row_Data['Edit_Account']."/".$row_Data['Edit_Unitname']."/".$row_Data['Edit_Username']."/".$row_Data['Course_OnlineAdd'];		
				  
$NewContent=$row_Data['Course_ID']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username']."/".$_POST['Course_OnlineAdd'];	
	if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){ $Season_Code='&Season_Code='.$_GET['Season_Code'];}
	else{$Season_Code='';}
    if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){$Unit_ID='&Unit_ID='.$_GET['Unit_ID'];}
	else{$Unit_ID='';}
    if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){$Course_Title='&Course_Title='.$_GET['Course_Title'];}
	else{$Course_Title='';}
	if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){$pageNum_Data='&pageNum_Data='.$_GET['pageNum_Data'];}
	else{$pageNum_Data='';}
    require_once('../../Include/Data_BrowseUpdate.php');
    $insertGoTo = @$_SERVER["PHP_SELF"]."?ID=".$colname_ID."&Msg=UpdateOK".$Season_Code.$Unit_ID.$Course_Title.$pageNum_Data;
    header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST["MM_update2"])) && ($_POST["MM_update2"] == "Form_Edit2")) { 

	$updateSQL = sprintf("update  course set Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s,Course_OnSiteAdd=%s where Course_ID=%s",
                      					   
					   GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
                       GetSQLValueString($_POST['Course_OnSiteAdd'], "int"),
					   GetSQLValueString($_POST['ID'], "int"));			               
					   
	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
      
$PastContent=$row_Data['Course_ID']."/".$row_Data['Edit_Time']."/".$row_Data['Edit_Account']."/".$row_Data['Edit_Unitname']."/".$row_Data['Edit_Username']."/".$row_Data['Course_OnSiteAdd'];		
				  
$NewContent=$row_Data['Course_ID']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username']."/".$_POST['Course_OnSiteAdd'];	
    if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){ $Season_Code='&Season_Code='.$_GET['Season_Code'];}
	else{$Season_Code='';}
    if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){$Unit_ID='&Unit_ID='.$_GET['Unit_ID'];}
	else{$Unit_ID='';}
    if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){$Course_Title='&Course_Title='.$_GET['Course_Title'];}
	else{$Course_Title='';}
	if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){$pageNum_Data='&pageNum_Data='.$_GET['pageNum_Data'];}
	else{$pageNum_Data='';}
	require_once('../../Include/Data_BrowseUpdate.php');
	$insertGoTo = @$_SERVER["PHP_SELF"]."?ID=".$colname_ID."&Msg=UpdateOK".$Season_Code.$Unit_ID.$Course_Title.$pageNum_Data;
	header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST["MM_update3"])) && ($_POST["MM_update3"] == "Form_Edit3")) { 

	$updateSQL = sprintf("update  course set Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s,Course_ReserveAdd=%s where Course_ID=%s",
                      					   
					   GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
					   GetSQLValueString($_POST['Course_ReserveAdd'], "int"),
					   GetSQLValueString($_POST['ID'], "int"));			               
					   
	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
      
$PastContent=$row_Data['Course_ID']."/".$row_Data['Edit_Time']."/".$row_Data['Edit_Account']."/".$row_Data['Edit_Unitname']."/".$row_Data['Edit_Username']."/".$row_Data['Course_ReserveAdd'];	
				  
$NewContent=$row_Data['Course_ID']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username']."/".$_POST['Course_ReserveAdd'];	

	if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){ $Season_Code='&Season_Code='.$_GET['Season_Code'];}
	else{$Season_Code='';}
    if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){$Unit_ID='&Unit_ID='.$_GET['Unit_ID'];}
	else{$Unit_ID='';}
    if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){$Course_Title='&Course_Title='.$_GET['Course_Title'];}
	else{$Course_Title='';}
	if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){$pageNum_Data='&pageNum_Data='.$_GET['pageNum_Data'];}
	else{$pageNum_Data='';}
  require_once('../../Include/Data_BrowseUpdate.php');
  $insertGoTo = @$_SERVER["PHP_SELF"]."?ID=".$colname_ID."&Msg=UpdateOK".$Season_Code.$Unit_ID.$Course_Title.$pageNum_Data;
  header(sprintf("Location: %s", $insertGoTo));
}
?> 

<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Tools/JQFileUpLoad/JQFileUpLoad.php'); ?>
<script src="../../ckeditor/ckeditor.js"></script>

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
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">修改<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></div>
    <?php if($row_Permission['Per_Edit'] == 1&&$row_Permission['Per_Pass'] == 1&&$row_AdminMember['Unit_Range']>=1){ ?>
    <?php if(@$_GET['Msg'] == "UpdateOK"){ ?>
	<script language="javascript">
	function UpdateOK(){
		$('.UpdateOK').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(UpdateOK,0);
    </script>
    
<?php } ?>
   	<div align="center">
		<div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
	</div>
    <?php if($totalRows_Data>0){?>
   
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2" style="max-width:750px;">
      <tr>
      <td class="right FormTitle02" nowrap width="5%"><font color="#FF0000">*</font> 班季:</td>
      <td class="middle" colspan="4" >
      <?php echo $row_Data['Season_Year']."年度".$row_Data['SeasonCate_Name']; ?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 社區大學:</td>
      <td class="middle" nowrap>
      <?php echo $row_Data['Com_Name'];?></td>
      <td class="right FormTitle02" width="5%"><font color="#FF0000">*</font> 分校:</td>
      <td class="middle" nowrap><?php echo $row_Data['Unit_Name'];?></td>
      <td></td>
      </tr>
      <tr>
      <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 標題:</td>
      <td colspan="4">
      <?php echo $row_Data['Course_Name'];?></td>
      </tr>
      <?php if($Season_IsAll<>1){?>
      			  <tr>
                  <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 原線上可報名人數:</td>
                  <td width="10%" class="middle"> 
                  <?php echo $row_Data['Course_Online'];?>人</td>  
                  <?php 
                        if($Season_Turn==1){?>
                            <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 額外增加線上總名額:</td>
                            <td  class="middle" colspan="2"><?php echo $row_Data['Course_OnlineAdd'];?>人</td>
                        </tr>
                        <tr>
                            <td class="middle" colspan="3">﹝線上名額：已有 <?php if(isset($AllNum['線上報名'])){echo $AllNum['線上報名'];}else{echo 0;}?> 人線上報名成功，團體報名 <?php if(isset($AllNum['團體報名'])){echo $AllNum['團體報名'];}else{echo 0;}?> 人成功﹞</td>
                        </tr><?php }
                        else{?>
                          <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 額外增加線上總名額:</td>
                          <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit" id="Form_Edit" method="POST">
                          <td class="middle">
                          <?php if($row_Data['Season_OnsiteEnd']<>""&&$today<=$row_Data['Season_OnsiteEnd']){?>
                          <input name="Course_OnlineAdd" type="number" id="Course_OnlineAdd"  required value="<?php echo $row_Data['Course_OnlineAdd'];?>" style="width:70%;">人
                          <?php }else{echo $row_Data['Course_OnlineAdd'].'人'; }?>
                          
                          </td>
                          
                          <td class="middle">
                          <?php if($row_Data['Season_OnsiteEnd']<>""&&$today<=$row_Data['Season_OnsiteEnd']){?>
                          <input name="Title" type="hidden" id="Title" value="名額">
                          <input name="ID" type="hidden" id="ID" value="<?php echo $_GET['ID']; ?>">
                          <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
                          <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
                          <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
                          <input name="Season_Code" id="Season_Code" type="hidden" value="<?php if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $_GET['Season_Code'];}?>">
                          <input name="Unit_ID" id="Unit_ID" type="hidden" value="<?php if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $_GET['Unit_ID'];}?>">
                          <input name="Course_Title" id="Course_Title" type="hidden" value="<?php if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){echo $_GET['Course_Title'];}?>">
                          <input name="pageNum_Data" id="pageNum_Data" type="hidden" value="<?php if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>">
                          <input type="submit" value="確定更新" class="Button_Submit"/>
                          <input type="hidden" name="MM_update" value="Form_Edit" />
                          <?php }?>
                          </td> 
                          </form>
                  <?php }?>
                  </tr>
      <?php }?>
      <tr>
      <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 原<?php if($Season_IsAll<>1){?>現場<?php }?>可報名人數:</td>
      <td width="20%" class="middle"> 
      <?php echo $row_Data['Course_OnSite'];?>人</td>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit2" id="Form_Edit2" method="POST">
      <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 額外增加<?php if($Season_IsAll<>1){?>現場<?php }?>總名額:</td>
      <td width="20%" class="middle">
      <?php if($row_Data['Season_OnsiteEnd']<>""&&$today<=$row_Data['Season_OnsiteEnd']){?>
      <input name="Course_OnSiteAdd" type="number" id="Course_OnSiteAdd" required value="<?php echo $row_Data['Course_OnSiteAdd'];?>" style="width:70%;">人
      <?php }else{echo $row_Data['Course_OnSiteAdd']; }?></td>
      <td class="middle">
      <?php if($row_Data['Season_OnsiteEnd']<>""&&$today<=$row_Data['Season_OnsiteEnd']){?>
      <input name="Title" type="hidden" id="Title" value="名額">
      <input name="ID" type="hidden" id="ID" value="<?php echo $_GET['ID']; ?>">
      <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
      <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
      <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
      <input name="Season_Code" id="Season_Code" type="hidden" value="<?php if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $_GET['Season_Code'];}?>">
      <input name="Unit_ID" id="Unit_ID" type="hidden" value="<?php if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $_GET['Unit_ID'];}?>">
      <input name="Course_Title" id="Course_Title" type="hidden" value="<?php if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){echo $_GET['Course_Title'];}?>">
      <input name="pageNum_Data" id="pageNum_Data" type="hidden" value="<?php if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>">
      <input type="submit" value="確定更新" class="Button_Submit"/>
      <input type="hidden" name="MM_update2" value="Form_Edit2" />
      <?php }?>
      </td> 
      </form>
      </tr>
      <?php /*
      <tr>
      <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 原身障可報名人數:</td>
      <td width="20%" class="middle"> 
      <?php echo $row_Data['Course_Reserve'];?>人</td>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit3" id="Form_Edit3" method="POST">
      <td class="right FormTitle02" nowrap><font color="#FF0000">*</font> 額外增加身障總名額:</td>
      <td width="20%" class="middle">
      <?php if($row_Data['Season_OnsiteEnd']<>""&&$today<=$row_Data['Season_OnsiteEnd']){?>
      <input name="Course_ReserveAdd" type="number" id="Course_ReserveAdd" required value="<?php echo $row_Data['Course_ReserveAdd'];?>" style="width:70%;">人
       <?php }else{echo $row_Data['Course_ReserveAdd']; }?></td>
       <td class="middle">
         <?php if($row_Data['Season_OnsiteEnd']<>""&&$today<=$row_Data['Season_OnsiteEnd']){?>
      <input name="Title" type="hidden" id="Title" value="名額">
      <input name="ID" type="hidden" id="ID" value="<?php echo $_GET['ID']; ?>">
      <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
      <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
      <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
      <input type="submit" value="確定更新" class="Button_Submit"/>
      <input type="hidden" name="MM_update3" value="Form_Edit3" />
      <input name="Season_Code" id="Season_Code" type="hidden" value="<?php if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $_GET['Season_Code'];}?>">
      <input name="Unit_ID" id="Unit_ID" type="hidden" value="<?php if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $_GET['Unit_ID'];}?>">
      <input name="Course_Title" id="Course_Title" type="hidden" value="<?php if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){echo $_GET['Course_Title'];}?>">
      <input name="pageNum_Data" id="pageNum_Data" type="hidden" value="<?php if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>">
       <?php }?>
      </td> 
      </form>
      </tr>
     */?>
    </table>
    <br/>
    <input type="button" value="回上一頁" onClick="location.href='AD_Data_index.php?Season_Code=<?php if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $_GET['Season_Code'];}?>&Unit_ID=<?php if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $_GET['Unit_ID'];}?>&Course_Title=<?php if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){echo $_GET['Course_Title'];}?>&pageNum_Data=<?php if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>'" class="Button_General">
   </div>
    
   
     <?php }else{?><div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權修改此資料</div>   <?php }?><br><br><br>
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
    <?php } ?>
        </td>
      </tr>
    </table>
	</center>
    <br><br><br>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php
mysql_free_result($Data);
?>