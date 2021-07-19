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
<?php require_once('../../Include/Permission.php'); //看到資料量?>
<?php
$colname_ID='-1';
if(isset($_GET['ID'])){
	$colname_ID=$_GET['ID'];
}


mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM season_rule where Com_ID Like %s and Season_ID=%s ORDER BY season_rule.Season_Code desc ,Season_ID desc",GetSQLValueString($colname03_Unit,'text'),GetSQLValueString($colname_ID,'int'));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


$query_Cate2 = "SELECT * FROM seasoncate ORDER BY SeasonCate_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

$query_Community = sprintf("SELECT * FROM community where Com_ID <>4 and Com_Enable = 1 and Com_IsPrivate <> 1  and Com_ID Like %s ORDER BY Com_ID ASC",GetSQLValueString($colname03_Unit,'text'));
$Community = mysql_query($query_Community, $dbline) or die(mysql_error());
$row_Community = mysql_fetch_assoc($Community);
$totalRows_Community = mysql_num_rows($Community);


$query_SeasonNew = sprintf("SELECT Season_Code, Com_ID FROM season_new order by Com_ID");
$SeasonNew = mysql_query($query_SeasonNew, $dbline) or die(mysql_error());
$row_SeasonNew = mysql_fetch_assoc($SeasonNew);
$totalRows_SeasonNew = mysql_num_rows($SeasonNew);
$Max_Season=';';
if($totalRows_SeasonNew>0){
	do{
		
		$Max_Season.=$row_SeasonNew['Season_Code']."_".$row_SeasonNew['Com_ID'].';';		
	
	}while($row_SeasonNew = mysql_fetch_assoc($SeasonNew));
}
mysql_free_result($SeasonNew);

$query_SeasonRule = sprintf("SELECT * FROM srule");
$SeasonRule = mysql_query($query_SeasonRule, $dbline) or die(mysql_error());
$row_SeasonRule = mysql_fetch_assoc($SeasonRule);
$totalRows_SeasonRule = mysql_num_rows($SeasonRule);
$SRule_List=array();
if($totalRows_SeasonRule>0){
  do{
	  if(!isset($SRule_List[$row_SeasonRule['Season_Code']][$row_SeasonRule['Com_ID']])){
	 	$SRule_List[$row_SeasonRule['Season_Code']][$row_SeasonRule['Com_ID']]= $row_SeasonRule['SRule_Name'];
	  }	  
  }while($row_SeasonRule = mysql_fetch_assoc($SeasonRule));	
}
mysql_free_result($SeasonRule);


$today=date("Y-m-d");

?>
<?php

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_Edit")) {
	$str_url='';
	if(isset($_POST['pageNum_Data']) && $_POST['pageNum_Data']<>""){$str_url.="&pageNum_Data=".$_GET['pageNum_Data'];}
	
	
	mysql_select_db($database_dbline, $dbline);
    $query_CateP = sprintf("SELECT * FROM season WHERE Season_ID = %s", GetSQLValueString($_POST['ID'], "int"));
    $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
    $row_CateP = mysql_fetch_assoc($CateP);
    $totalRows_CateP = mysql_num_rows($CateP);

	$Other="修改".$row_Permission['ModuleSetting_Title'];
	$EditTime=date("Y-m-d H:i:s");
	if(isset($_POST['Season_ReturnDay'])&&$_POST['Season_ReturnDay']<>""){$Season_ReturnDay=$_POST['Season_ReturnDay'];}
	else{$Season_ReturnDay=0;}
	if(isset($_POST['Season_Benefit'])&&$_POST['Season_Benefit']==1){$Season_Benefit=1;}
	else{$Season_Benefit=0;}
	if(isset($_POST['SRule_Type'])&&$_POST['SRule_Type']<>""){$Season_IsNewOld=$_POST['SRule_Type'];}
	else{$Season_IsNewOld=$row_CateP['Season_IsNewOld'];}
	$Season_Start=$_POST['Season_StartDate']." ".$_POST['Season_StartTime'].":00";
	$Season_End=$_POST['Season_EndDate']." ".$_POST['Season_EndTime'].":00";
	$Season_SelectStart=$_POST['Season_SelectStartDate']." ".$_POST['Season_SelectStartTime'].":00";
	$Season_SelectEnd=$_POST['Season_SelectEndDate']." ".$_POST['Season_SelectEndTime'].":00";
	$Season_OnsiteStart=$_POST['Season_OnsiteStartDate']." ".$_POST['Season_OnsiteStartTime'].":00";
	$Season_OnsiteEnd=$_POST['Season_OnsiteEndDate']." ".$_POST['Season_OnsiteEndTime'].":00";
	$Season_GroupStart=$_POST['Season_GroupStartDate']." ".$_POST['Season_GroupStartTime'].":00";
	$Season_GroupEnd=$_POST['Season_GroupEndDate']." ".$_POST['Season_GroupEndTime'].":00";
	$Season_Public=$_POST['Season_PublicDate']." ".$_POST['Season_PublicTime'].":00";
	$Season_PayStart=$_POST['Season_PayStartDate']." ".$_POST['Season_PayStartTime'].":00";
	$Season_PayEnd=$_POST['Season_PayEndDate']." ".$_POST['Season_PayEndTime'].":00";
		
	if(isset($_POST['Season_IsAll']) && $_POST['Season_IsAll']==1){
		$Season_IsAll=1;	
	}
	else{
		$Season_IsAll=0;
	}
	
	$updateSQL = sprintf("UPDATE season SET  Season_Start=%s, Season_End=%s, Season_SelectStart=%s, Season_SelectEnd=%s, Season_OnsiteStart=%s, Season_OnsiteEnd=%s, Season_GroupStart=%s, Season_GroupEnd=%s, Season_Public=%s, Season_PayStart=%s, Season_PayEnd=%s, Season_Week=%s, Season_Credit=%s, Season_ReturnDay=%s, Season_Benefit=%s, Season_IsNewOld=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE Season_ID=%s",
	                   
					   GetSQLValueString($Season_Start, "date"),
					   GetSQLValueString($Season_End, "date"),
					   GetSQLValueString($Season_SelectStart, "date"),
					   GetSQLValueString($Season_SelectEnd, "date"),
					   GetSQLValueString($Season_OnsiteStart, "date"),
					   GetSQLValueString($Season_OnsiteEnd, "date"),
					   GetSQLValueString($Season_GroupStart, "date"),
					   GetSQLValueString($Season_GroupEnd, "date"),
					   GetSQLValueString($Season_Public, "date"),
					   GetSQLValueString($Season_PayStart, "date"),
					   GetSQLValueString($Season_PayEnd, "date"),
					   GetSQLValueString($_POST['Season_Week'], "int"),
					   GetSQLValueString($_POST['Season_Credit'], "int"),
					   GetSQLValueString($Season_ReturnDay, "int"),
					   GetSQLValueString($Season_Benefit,"int"),
					   GetSQLValueString($Season_IsNewOld,"int"),
                       GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));
					   

  
  $PastContent=$row_CateP['Season_ID']."/".$row_CateP['SeasonCate_Name']."/".$row_CateP['Season_Year']."/".$row_CateP['Season_Code']."/".$row_CateP['Season_Start']."/".$row_CateP['Season_End']."/".$row_CateP['Season_SelectStart']."/".$row_CateP['Season_SelectEnd']."/".$row_CateP['Season_OnsiteStart']."/".$row_CateP['Season_OnsiteEnd']."/".$row_CateP['Season_Public']."/".$row_CateP['Season_PayStart']."/".$row_CateP['Season_PayEnd']."/".$row_CateP['Season_PayCode']."/".$row_CateP['Season_BankCode']."/".$row_CateP['Season_Bank']."/".$row_CateP['Season_Transaction']."/".$row_CateP['Season_Fee']."/".$row_CateP['Season_BankName']."/".$row_CateP['Season_BankAccount']."/".$row_CateP['Season_ReturnDay']."/".$row_CateP['Season_MemberOffers']."/".$row_CateP['Rule_ID']."/".$row_CateP['Season_Count']."/".$row_CateP['Season_Week']."/".$row_CateP['Season_Credit']."/".$row_CateP['Season_Benefit']."/".$row_CateP['Season_IsNewOld']."/".$row_CateP['Season_Turn']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$row_CateP['Edit_Time']."/".$row_CateP['Edit_Account']."/".$row_CateP['Edit_Unitname']."/".$row_CateP['Edit_Username'];

  
  $NewContent=$row_CateP['Season_ID']."/".$row_CateP['SeasonCate_Name']."/".$row_CateP['Season_Year']."/".$row_CateP['Season_Code']."/".$Season_Start."/".$Season_End."/".$Season_SelectStart."/".$Season_SelectEnd."/".$Season_OnsiteStart."/".$Season_OnsiteEnd."/".$Season_Public."/".$Season_PayStart."/".$Season_PayEnd."/".$row_CateP['Season_PayCode']."/".$row_CateP['Season_BankCode']."/".$row_CateP['Season_Bank']."/".$row_CateP['Season_Transaction']."/".$row_CateP['Season_Fee']."/".$row_CateP['Season_BankName']."/".$row_CateP['Season_BankAccount']."/".$Season_ReturnDay."/".$row_CateP['Season_MemberOffers']."/".$row_CateP['Rule_ID']."/".$row_CateP['Season_Count']."/".$_POST['Season_Week']."/".$_POST['Season_Credit']."/".$Season_Benefit."/".$Season_IsNewOld."/".$row_CateP['Season_Turn']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];

  
  mysql_select_db($database_dbline, $dbline);
  $Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
  
  
  
  
  if(isset($_POST['SRule_Type']) && $_POST['SRule_Type']<>""){
    $query_CateP2 = sprintf("SELECT * FROM srule WHERE srule.Season_Code = %s and Com_ID=%s", GetSQLValueString($row_CateP['Season_Code'], "int"), GetSQLValueString($row_CateP['Com_ID'], "int"));
    $CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
    $row_CateP2 = mysql_fetch_assoc($CateP2);
    $totalRows_CateP2 = mysql_num_rows($CateP2);
	mysql_free_result($CateP2);
	if($totalRows_CateP2<1){
		$updateSQL2 = sprintf("insert into srule (SRule_Name, SRule_Type, Com_ID, Season_Code, Season_Year, Add_Time, Edit_Time) values (%s, %s, %s, %s, %s,    %s, %s)",
	                   
					   GetSQLValueString($_POST['SRule_Name'], "text"),
					   GetSQLValueString($_POST['SRule_Type'], "int"),
					   GetSQLValueString($row_CateP['Com_ID'], "int"),
					   GetSQLValueString($row_CateP['Season_Code'], "int"),
					   GetSQLValueString($row_CateP['Season_Year'], "int"),
					   GetSQLValueString($EditTime, "date"),
					   GetSQLValueString($EditTime, "date"));
		 mysql_select_db($database_dbline, $dbline);
         $Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());
		
	}
	else{
		$updateSQL2 = sprintf("UPDATE srule SET  SRule_Name=%s, SRule_Type=%s, Edit_Time=%s WHERE srule.Season_Code = %s and Com_ID=%s",
	                   
					   GetSQLValueString($_POST['SRule_Name'], "text"),
					   GetSQLValueString($_POST['SRule_Type'], "int"),
					   GetSQLValueString($EditTime, "date"),
					   GetSQLValueString($row_CateP['Season_Code'], "int"),
					   GetSQLValueString($row_CateP['Com_ID'], "int"));
		mysql_select_db($database_dbline, $dbline);
        $Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());
	}
  }
  
  

  require_once('../../Include/Data_BrowseUpdate.php');
  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateOK&ID=".$_POST['ID'].$str_url;  
  mysql_free_result($CateP);
  header(sprintf("Location: %s", $updateGoTo));
  
}

?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<!--驗證CSS OP-->
<?php require_once('../../Include/spry_style.php'); ?>
<style type="text/css">
.Season_Add{display:inline-block;}
.Public_Add{display:inline-block;}
.Pay_Add{display:inline-block;}
.Onsite_Add{display:inline-block;}
.Select_Add{display:inline-block;}
.Group_Add{display:inline-block;}
</style>
<!--驗證CSS ED-->
<script src="../../Tools/jscolor/jscolor.js" type="text/javascript"></script><!--選色器-->
<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>

<!--日期INPUT ED-->
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
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']; ?> - 修改<?php echo $row_ModuleSet['ModuleSetting_SubName']; ?></div>
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
<?php if(@$_GET["Msg"] == "DelError"){ ?>
	<script language="javascript">
	function Error_Del(){
		$('.Error_Del').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(Error_Del,0);
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
<?php if(@$_GET['Msg'] == "UpdateError"){ ?>
	<script language="javascript">
	function UpdateError(){
		$('.UpdateError').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(UpdateError,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "UpdateErrGroup"){ ?>
	<script language="javascript">
	function UpdateErrGroup(){
		$('.UpdateErrGroup').fadeIn(1000).delay(2000); 			
	}
	setTimeout(UpdateErrGroup,0);
    </script>
<?php } ?>

  
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Error_Msg Error_Add" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料登錄失敗，已有重複</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
          <div class="Error_Msg UpdateError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料更新失敗</div>
          <div class="Error_Msg UpdateErrGroup" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料更新失敗，該社區大學有尚未結團體報名單據，請前往<a href="../Sign/AD_Data_Group.php?IsPrint=0">此連結</a>結單</div>
          <div class="Error_Msg Error_Del" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料刪除失敗，已有線上報名紀錄</div>
      
      
      
      <?php if($row_Permission['Per_Edit'] == 1){ ?>
      
      <div align="center">
      
         <?php if ($totalRows_Cate > 0) { // Show if recordset not empty ?>
		 		 <form name="form_Edit" id="form_Edit" method="POST" action="<?php echo $editFormAction; ?>">
				<div align="center">		
			 <table cellpadding="0" cellspacing="2" border="0">
              <tr>
              <td>
              		<table cellpadding="5" cellspacing="0" border="0" width="90%">
                    <tr>
                    <td class="FormTitle02"  nowrap>社區大學:</td>
                    <td class="middle">
					<?php  echo $row_Cate['Com_Name']; ?>					
                    </td>
                    </tr>
                    <tr>
                    <td class="FormTitle02" nowrap>班季:</td>
                    <td class="middle MainColor fontAB" style="color:#E60012;">
                    <?php echo $row_Cate['Season_Year'].'年'.$row_Cate['SeasonCate_Name'];?>
					</td>
					<td class="FormTitle02" nowrap>方式：</td>
					<td class="middle"><?php  if($row_Cate['Rule_ID']==1){echo '線上與現場';}else{echo '僅線上';}?>
                    <input name="Season_IsAll" id="Season_IsAll" value="1" type="hidden">	
                    </td>                    
                    </tr>
                    <tr>
                    <td class="FormTitle02" >預設學分:</td>
                    <td class="middle">
					<?php if(strtotime($row_Cate['Season_SelectStart'])>strtotime($today)&&strtotime($row_Cate['Season_OnsiteStart'])>strtotime($today)){?>	
							<input type="text" name="Season_Credit" id="Season_Credit" required value="<?php echo $row_Cate['Season_Credit'];?>" size="2">	
					<?php }else{?>		
							 <?php echo $row_Cate['Season_Credit']; ?><input type="hidden" name="Season_Credit" id="Season_Credit" required value="<?php echo $row_Cate['Season_Credit'];?>" size="2">
					<?php }?></td>
                    <td class="FormTitle02" >預設週數:</td>
                    <td class="middle">
                    <?php if(strtotime($row_Cate['Season_SelectStart'])>strtotime($today)&&strtotime($row_Cate['Season_OnsiteStart'])>strtotime($today)){?>	
							<input type="text" name="Season_Week" id="Season_Week" required value="<?php echo $row_Cate['Season_Week'];?>" size="2">	
					<?php }else{?>		
							<?php echo $row_Cate['Season_Week']; ?><input type="hidden" name="Season_Week" id="Season_Week" required value="<?php echo $row_Cate['Season_Week'];?>" size="2">
					<?php }?>	
                    </td>
                    </tr>
                    <tr>
                    <td class="FormTitle02" colspan="2"  nowrap>是否使用優惠:
                    
					<?php if(strtotime($row_Cate['Season_SelectStart'])>strtotime($today)&&strtotime($row_Cate['Season_OnsiteStart'])>strtotime($today)){?>				
							 <input type="checkbox" name="Season_Benefit" id="Season_Benefit" <?php if($row_Cate['Season_Benefit']=="1"){echo 'checked';}?> value="1"/> 
					<?php }
					      else{?>		
							 <img src="../../Icon/<?php echo $row_Cate['Season_Benefit'];?>.png">
							 <input type="hidden" name="Season_Benefit" id="Season_Benefit" value="<?php echo $row_Cate['Season_Benefit'];?>"/> 
					<?php }?> 
                    
                    </td>                   
                    <td class="FormTitle02" colspan="2" nowrap>
                    新舊生規則:<?php if(strtotime($row_Cate['Season_SelectStart'])>strtotime($today)&&strtotime($row_Cate['Season_OnsiteStart'])>strtotime($today)){?>				
							  
                             <select name="SRule_Type" id="SRule_Type" onChange="SRule_check()"> 
                             <option value="" >請選擇規則</option>
                             <option value="5" <?php if($row_Cate['Season_IsNewOld']==5){echo 'selected';}?> >繳報名費為舊生</option>
                             <!--<option value="1" <?php /*if($row_Cate['Season_IsNewOld']==1){echo 'selected';}?>>上一班季</option>
                             <option value="2" <?php if($row_Cate['Season_IsNewOld']==2){echo 'selected';}?>>當年度班季</option>
                             <option value="3" <?php if($row_Cate['Season_IsNewOld']==3){echo 'selected';}?>>全新生</option>
                             <option value="4" <?php if($row_Cate['Season_IsNewOld']==4){echo 'selected';}*/?>>全舊生</option>-->
                             </select>
                             <input type="hidden" name="SRule_Name" id="SRule_Name" />
                             <script type="text/javascript">
							 SRule_check();　
							 function SRule_check(){
								 document.getElementById("SRule_Name").value=document.getElementById("SRule_Type").options[document.getElementById("SRule_Type").selectedIndex].text;
							 }
							 </script> 
                             
                             
							 <?php }
								   else{
									    if(isset($SRule_List[$row_Cate['Season_Code']][$row_Cate['Com_ID']])){
								 		    echo $SRule_List[$row_Cate['Season_Code']][$row_Cate['Com_ID']];
								   		}
								   }?>
                    </td>
                    </tr>
                    </table>
                    </td>
                    </tr>
                    <tr>
                    <td class="FormTitle03" >
								<table cellpadding="5" cellspacing="0" border="0">
                                  <tr>
                                  <td class="right" nowrap>公告日期：</td>
                                  <td>
                                  <div class="Public_Add">
                                          <div class="DateStyle">
                                                <div class='input-group date picker_date' >
                                                <input type='text' name="Season_PublicDate" id="Season_PublicDate" data-format="yyyy/MM/dd" class="form-control" value="<?php if($row_Cate['Season_Public']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_Public']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                </div>
                                          </div>
                                          <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_PublicTime" id="Season_PublicTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_Public']<>""){echo date("H:i",strtotime($row_Cate['Season_Public']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                                </div>
                                          </div>                      
                                  </div>
                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="right" nowrap>上課日期：</td>
                                  <td>
                                  <div class="Season_Add"><div class="DateStyle">
                                                <div class='input-group date picker_date' >
                                                <input type='text' name="Season_StartDate" id="Season_StartDate" data-format="yyyy/MM/dd" class="form-control" value="<?php if($row_Cate['Season_Start']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_Start']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                </div>
                                          </div>
                                          <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_StartTime" id="Season_StartTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_Start']<>""){echo date("H:i",strtotime($row_Cate['Season_Start']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                                </div>
                                          </div>
                                           至
                                          <div style="display:inline-block;">
                                               <div class="DateStyle">
                                                    <div class='input-group date picker_date' >
                                                    <input name="Season_EndDate" type="text" id="Season_EndDate"  data-format="yyyy/MM/dd hh:mm:ss" value="<?php if($row_Cate['Season_End']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_End']));}?>" class="form-control" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                               <div class="TimeStyle">
                                                    <div class='input-group date picker_time' >
                                                    <input type='text' name="Season_EndTime" id="Season_EndTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_End']<>""){echo date("H:i",strtotime($row_Cate['Season_End']));}?>" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                          </div>
                                          <div class="Season_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
                                  </div>
                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="right" nowrap>團體報名：</td>
                                  <td>
                                  <div class="Group_Add">
                                          <div class="DateStyle">
                                                <div class='input-group date picker_date' >
                                                <input type='text' name="Season_GroupStartDate" id="Season_GroupStartDate" data-format="yyyy/MM/dd" class="form-control" value="<?php if($row_Cate['Season_GroupStart']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_GroupStart']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                </div>
                                          </div>
                                          <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_GroupStartTime" id="Season_GroupStartTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_GroupStart']<>""){echo date("H:i",strtotime($row_Cate['Season_GroupStart']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                                </div>
                                          </div>
                                           至
                                          <div style="display:inline-block;">
                                               <div class="DateStyle">
                                                    <div class='input-group date picker_date' >
                                                    <input name="Season_GroupEndDate" type="text" id="Season_GroupEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_GroupEnd']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_GroupEnd']));}?>" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                               <div class="TimeStyle">
                                                    <div class='input-group date picker_time' >
                                                    <input type='text' name="Season_GroupEndTime" id="Season_GroupEndTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_GroupEnd']<>""){echo date("H:i",strtotime($row_Cate['Season_GroupEnd']));}?>" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                          </div>
                                          <div class="Group_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
                                  </div>
                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="right" nowrap>線上選課：</td>
                                  <td>
                                  <div class="Select_Add">
                                          <div class="DateStyle">
                                                <div class='input-group date picker_date' >
                                                <input type='text' name="Season_SelectStartDate" id="Season_SelectStartDate" data-format="yyyy/MM/dd" class="form-control" value="<?php if($row_Cate['Season_SelectStart']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_SelectStart']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                </div>
                                          </div>
                                          <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_SelectStartTime" id="Season_SelectStartTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_SelectStart']<>""){echo date("H:i",strtotime($row_Cate['Season_SelectStart']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                                </div>
                                          </div>
                                           至
                                          <div style="display:inline-block;">
                                               <div class="DateStyle">
                                                    <div class='input-group date picker_date' >
                                                    <input name="Season_SelectEndDate" type="text" id="Season_SelectEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_SelectEnd']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_SelectEnd']));}?>" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                               <div class="TimeStyle">
                                                    <div class='input-group date picker_time' >
                                                    <input type='text' name="Season_SelectEndTime" id="Season_SelectEndTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_SelectEnd']<>""){echo date("H:i",strtotime($row_Cate['Season_SelectEnd']));}?>" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                          </div>
                                          <div class="Select_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
                                  </div>
                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="right" nowrap>繳費日期：</td>
                                  <td>
                                  <div class="Pay_Add">
                                          <div class="DateStyle">
                                                <div class='input-group date picker_date' >
                                                <input type='text' name="Season_PayStartDate" id="Season_PayStartDate" data-format="yyyy/MM/dd" class="form-control" value="<?php if($row_Cate['Season_PayStart']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_PayStart']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                </div>
                                          </div>
                                          <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_PayStartTime" id="Season_PayStartTime" data-format="hh:mm:ss" class="form-control" value="<?php if($row_Cate['Season_PayStart']<>""){echo date("H:i",strtotime($row_Cate['Season_PayStart']));}?>" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                                </div>
                                          </div>
                                           至
                                          <div style="display:inline-block;">
                                               <div class="DateStyle">
                                                    <div class='input-group date picker_date' >
                                                    <input name="Season_PayEndDate" type="text" id="Season_PayEndDate"  data-format="yyyy/MM/dd hh:mm:ss" value="<?php if($row_Cate['Season_PayEnd']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_PayEnd']));}?>" class="form-control" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                               <div class="TimeStyle">
                                                    <div class='input-group date picker_time' >
                                                    <input type='text' name="Season_PayEndTime" id="Season_PayEndTime" data-format="hh:mm:ss" value="<?php if($row_Cate['Season_PayEnd']<>""){echo date("H:i",strtotime($row_Cate['Season_PayEnd']));}?>" class="form-control" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                          </div>
                                          <div class="Pay_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
                                  </div>
                                  </td>
                                  </tr>
                                  <tr>
                                  <td class="right" nowrap>現場選課：</td>
                                  <td>
                                  <div class="Onsite_Add">
                                          <div class="DateStyle">
                                                <div class='input-group date picker_date' >
                                                <input type='text' name="Season_OnsiteStartDate" id="Season_OnsiteStartDate" data-format="yyyy/MM/dd" value="<?php if($row_Cate['Season_OnsiteStart']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_OnsiteStart']));}?>" class="form-control" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </span>
                                                </div>
                                          </div>
                                          <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_OnsiteStartTime" id="Season_OnsiteStartTime" data-format="hh:mm:ss" value="<?php if($row_Cate['Season_OnsiteStart']<>""){echo date("H:i",strtotime($row_Cate['Season_OnsiteStart']));}?>" class="form-control" required/>
                                                <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-time"></span>
                                                </span>
                                                </div>
                                          </div>
                                           至
                                          <div style="display:inline-block;">
                                               <div class="DateStyle">
                                                    <div class='input-group date picker_date' >
                                                    <input name="Season_OnsiteEndDate" type="text" id="Season_OnsiteEndDate"  data-format="yyyy/MM/dd hh:mm:ss" value="<?php if($row_Cate['Season_OnsiteEnd']<>""){echo date("Y-m-d",strtotime($row_Cate['Season_OnsiteEnd']));}?>" class="form-control" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                               <div class="TimeStyle">
                                                    <div class='input-group date picker_time' >
                                                    <input type='text' name="Season_OnsiteEndTime" id="Season_OnsiteEndTime" data-format="hh:mm:ss" value="<?php if($row_Cate['Season_OnsiteEnd']<>""){echo date("H:i",strtotime($row_Cate['Season_OnsiteEnd']));}?>" class="form-control" required/>
                                                    <span class="input-group-addon">
                                                        <span class="glyphicon glyphicon-time"></span>
                                                    </span>
                                                    </div>
                                               </div>
                                          </div>
                                          <div class="Onsite_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
                                  </div>
                                  </td>
                                  </tr>
                                  </table>
                                   <?php
											/*echo '上課期間退八成天數：'.$row_Cate['Season_ReturnDay'].'天';?>	
                                            <input type="hidden" name="Season_ReturnDay" id="Season_ReturnDay" value="<?php echo $row_Cate['Season_ReturnDay'];?>"/> 
							      <?php */?>	
								 
					</td>
                    </tr>
                    </table>
                            
							
							 <?php if(strtotime($row_Cate['Season_End'])>=strtotime($today)){?>
								<input type="submit" value="確定更新" class="Button_Submit"/>
							    <input type="button" value="回上頁" class="Button_General" onClick="location.href='AD_Season_Index.php?<?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo "pageNum_Data=".$_GET['pageNum_Data'];}?>'"/>
								<input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['Season_ID']; ?>">
							   
								<input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
								<input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
								<input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
								<br/><br/>
							 <?php } ?>    
			                 				
			                <input type="hidden" name="pageNum_Data" value="<?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>" />			
							<input type="hidden" name="MM_update" value="form_Edit" />						   
                            </div>
                            
							
                            </form>
                            
                            </div>
            <?php require_once('season_addtimes.php');?>           
            <?php } // Show if recordset not empty ?>
       
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

<script type="text/javascript">



$(document).ready(function(event) {
  
		
		$('form[name=form_Edit]').submit(function(event){
			//add stuff here			
			var Season_SelectStart=Date.parse(document.getElementById("Season_SelectStartDate").value+" "+document.getElementById("Season_SelectStartTime").value+":00");
			
			var Season_SelectEnd=Date.parse(document.getElementById("Season_SelectEndDate").value+" "+document.getElementById("Season_SelectEndTime").value+":00");
			
			var Season_OnsiteStart=Date.parse(document.getElementById("Season_OnsiteStartDate").value+" "+document.getElementById("Season_OnsiteStartTime").value+":00");
			
			var Season_OnsiteEnd=Date.parse(document.getElementById("Season_OnsiteEndDate").value+" "+document.getElementById("Season_OnsiteEndTime").value+":00");
			
			var Season_PayStart=Date.parse(document.getElementById("Season_PayStartDate").value+" "+document.getElementById("Season_PayStartTime").value+":00");
			
			var Season_PayEnd=Date.parse(document.getElementById("Season_PayEndDate").value+" "+document.getElementById("Season_PayEndTime").value+":00");
			
			var Season_GroupStart=Date.parse(document.getElementById("Season_GroupStartDate").value+" "+document.getElementById("Season_GroupStartTime").value+":00");
			
			var Season_GroupEnd=Date.parse(document.getElementById("Season_GroupEndDate").value+" "+document.getElementById("Season_GroupEndTime").value+":00");
			
			var Season_Start=Date.parse(document.getElementById("Season_StartDate").value+" "+document.getElementById("Season_StartTime").value+":00");
			
			var Season_End=Date.parse(document.getElementById("Season_EndDate").value+" "+document.getElementById("Season_EndTime").value+":00");
			
			 
			  if(Season_Start<=Season_End){ 
				  if(Season_SelectStart<=Season_SelectEnd){ 
						if(Season_OnsiteStart<=Season_OnsiteEnd){
							if(Season_PayStart<=Season_PayEnd){ 
								if(Season_GroupStart<=Season_GroupEnd){//線上要不能在現場時間開始之間
								
								}
								else{
									alert("團體報名結束日期不可在團體報名開始日期之前！");
									return false;
								}
							
							}
							else{ 
								alert("繳費日期結束日期不可在繳費日期開始日期之前！");
								return false;
							}}
						else{ 
							alert("現場選課結束日期不可在現場選課開始日期之前！");
							return false;
						
						}
				  }
				  else{ 
				 
					alert("線上選課結束日期不可在線上選課開始日期之前！");
					return false;
				  }
			  }
			  else{ 			 
				alert("上課結束日期不可上課開始日期之前！");
				return false;
			  }
		
		
			 
		});
	
});
</script>
<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
