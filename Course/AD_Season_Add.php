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

mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM season_rule where Com_ID Like %s ORDER BY season_rule.Season_Code desc ,Season_ID desc",GetSQLValueString($colname03_Unit,'text'));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


$query_Cate2 = "SELECT * FROM seasoncate ORDER BY SeasonCate_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

$query_Community = sprintf("SELECT * FROM community where Com_IsPrivate<>1 and Com_Enable = 1 and Com_IsSchool=1  and Com_ID Like %s ORDER BY Com_ID ASC",GetSQLValueString($colname03_Unit,'text'));
$Community = mysql_query($query_Community, $dbline) or die(mysql_error());
$row_Community = mysql_fetch_assoc($Community);
$totalRows_Community = mysql_num_rows($Community);




$today=date("Y-m-d");

?>
<?php

/*新增班季OP*/
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add")) {
	$Other="新增".$row_Permission['ModuleSetting_Title'];
	$AddTime=date("Y-m-d H:i:s");
	if(isset($_POST['Season_Benefit'])&&$_POST['Season_Benefit']==1){$Season_Benefit=1;}
	else{$Season_Benefit=0;}
	if(isset($_POST['SRule_Type'])&&$_POST['SRule_Type']<>""){$Season_IsNewOld=$_POST['SRule_Type'];}
	else{$Season_IsNewOld=5;}
	mysql_select_db($database_dbline, $dbline);
    $query_CateP2 = sprintf("SELECT * FROM season WHERE season.Season_Code = %s and Com_ID = %s", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"));
    $CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
    $row_CateP2 = mysql_fetch_assoc($CateP2);
    $totalRows_CateP2 = mysql_num_rows($CateP2);
	
	$query_SeasonOld = sprintf("SELECT * FROM season_oldmax WHERE Com_ID = %s", GetSQLValueString($_POST['Com_ID'], "int"));
    $SeasonOld = mysql_query($query_SeasonOld, $dbline) or die(mysql_error());
    $row_SeasonOld = mysql_fetch_assoc($SeasonOld);
    $totalRows_SeasonOld = mysql_num_rows($SeasonOld);
	$Season_Old=$row_SeasonOld['Season_Code'];
	mysql_free_result($SeasonOld);
	
	
	
	if($totalRows_CateP2<1){
		if(preg_match("/夏/i",$_POST['SeasonCate_Name']) && $_POST['Rule_ID']==1){
		   $Rule_ID=3;
		   //$Season_MemberOffers=250;
		}
		elseif(preg_match("/夏/i",$_POST['SeasonCate_Name']) && $_POST['Rule_ID']==2){
		   $Rule_ID=4;
		   //$Season_MemberOffers=250;
		}
		else{
		   $Rule_ID=$_POST['Rule_ID'];
		}
		
		$query_RuleCost = sprintf("SELECT Rule_ReserveCost FROM rule WHERE Rule_ID = %s", GetSQLValueString($Rule_ID, "int"));
		$RuleCost = mysql_query($query_RuleCost, $dbline) or die(mysql_error());
		$row_RuleCost = mysql_fetch_assoc($RuleCost);
		$totalRows_RuleCost = mysql_num_rows($RuleCost);
		if($totalRows_RuleCost>0){
			$Season_MemberOffers=$row_RuleCost['Rule_ReserveCost'];
		}
		else{
			$Season_MemberOffers=500;
		}
		mysql_free_result($RuleCost);
		
		$query_ComCodeData = sprintf("SELECT Com_Code FROM community WHERE Com_ID = %s and Com_IsPrivate <> 1", GetSQLValueString($_POST['Com_ID'], "int"));
		$ComCodeData = mysql_query($query_ComCodeData, $dbline) or die(mysql_error());
		$row_ComCodeData = mysql_fetch_assoc($ComCodeData);
		$totalRows_ComCodeData = mysql_num_rows($ComCodeData);
		if($totalRows_ComCodeData>0){
			$Com_Code=$row_ComCodeData['Com_Code'];
		}
		else{
			$Com_Code=NULL;
		}
		mysql_free_result($ComCodeData);
		
		
		
		$Season_PayCode='';
		$Season_BankCode='';
		$Season_Bank='';
		$Season_Transaction='';
		$Season_Fee=0;
		$Season_BankName='';
		$Season_BankAccount='';
		
		if(isset($_POST['Season_ReturnDay'])&&$_POST['Season_ReturnDay']<>""){			
			$Season_ReturnDay=$_POST['Season_ReturnDay'];			
		}
		else{
			$Season_ReturnDay=NULL;
		}
		$Season_Start=$_POST['Season_StartDate']." 00:00:00";
		$Season_End=$_POST['Season_EndDate']." 23:59:59";
		$Season_SelectStart=$_POST['Season_SelectStartDate']." 00:00:00";
		$Season_SelectEnd=$_POST['Season_SelectEndDate']." 23:59:59";
		$Season_OnsiteStart=$_POST['Season_OnsiteStartDate']." 00:00:00";
		$Season_OnsiteEnd=$_POST['Season_OnsiteEndDate']." 23:59:59";
		$Season_GroupStart=$_POST['Season_GroupStartDate']." 00:00:00";
		$Season_GroupEnd=$_POST['Season_GroupEndDate']." 23:59:59";
		$Season_Public=$_POST['Season_PublicDate']." ".$_POST['Season_PublicTime'];
		$Season_PayStart=$_POST['Season_PayStartDate']." 00:00:00";
		$Season_PayEnd=$_POST['Season_PayEndDate']." 23:59:59";
		$Season_CastCourseStart=$_POST['Season_CastCourseStartDate']." 00:00:00";
		$Season_CastCourseEnd=$_POST['Season_CastCourseEndDate']." 23:59:59";
		$Season_ReviewStart=$_POST['Season_ReviewStartDate']." 00:00:00";
		$Season_ReviewEnd=$_POST['Season_ReviewEndDate']." 23:59:59";
		
		if(isset($_POST['Season_IsAll']) && $_POST['Season_IsAll']==1){
			$Season_IsAll=1;	
		}
		else{
			$Season_IsAll=0;
		}
		
		
		$insertSQL = sprintf("INSERT INTO season (Com_ID, SeasonCate_Name, Season_Year, Season_Code,  Season_Start, Season_End, Season_SelectStart, Season_SelectEnd, Season_OnsiteStart, Season_OnsiteEnd, Season_GroupStart, Season_GroupEnd, Season_Public, Season_PayStart, Season_PayEnd, Season_Week, Season_Credit, Season_Benefit, Season_MemberOffers, Season_ReturnDay, Season_IsNewOld, Rule_ID, Season_PayCode, Season_BankCode, Season_Bank, Season_Transaction, Season_Fee, Season_BankName, Season_BankAccount, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username, Season_IsAll, Season_IsNew, Season_IsCourseShow, Season_IsPreCourse, Season_IsFillStudent, Season_IsFillTeacher, Season_IsOnline, Season_CastCourseStart, Season_CastCourseEnd, Season_ReviewStart, Season_ReviewEnd) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s)",
						   GetSQLValueString($_POST['Com_ID'], "int"),
						   GetSQLValueString($_POST['SeasonCate_Name'], "text"),
						   GetSQLValueString($_POST['Season_Year'], "int"),
						   GetSQLValueString($_POST['Season_Code'], "int"),
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
						   GetSQLValueString($Season_Benefit,"int"),
						   GetSQLValueString($Season_MemberOffers,"int"),
						   GetSQLValueString($Season_ReturnDay,"int"),
						   GetSQLValueString($Season_IsNewOld,"int"),
						   GetSQLValueString($Rule_ID, "int"),
						   GetSQLValueString($Season_PayCode, "text"),
						   GetSQLValueString($Season_BankCode, "text"),
						   GetSQLValueString($Season_Bank, "text"),
						   GetSQLValueString($Season_Transaction, "text"),
						   GetSQLValueString($Season_Fee, "int"),
						   GetSQLValueString($Season_BankName, "text"),
						   GetSQLValueString($Season_BankAccount, "text"),
						   
						   
						   GetSQLValueString($AddTime, "date"),
						   GetSQLValueString($AddTime, "date"),
						   GetSQLValueString($_POST['Add_Account'], "text"),
						   GetSQLValueString($_POST['Add_Unitname'], "text"),
						   GetSQLValueString($_POST['Add_Username'], "text"),
						   GetSQLValueString($Season_IsAll, "int"),
						   GetSQLValueString($_POST['Season_IsNew'], "int"),
						   GetSQLValueString($_POST['Season_IsCourseShow'], "int"),
						   GetSQLValueString($_POST['Season_IsPreCourse'], "int"),
						   GetSQLValueString($_POST['Season_IsFillStudent'], "int"),
						   GetSQLValueString($_POST['Season_IsFillTeacher'], "int"),
						   GetSQLValueString($_POST['Season_IsOnline'], "int"),
						   GetSQLValueString($Season_CastCourseStart, "date"),
						   GetSQLValueString($Season_CastCourseEnd, "date"),
						   GetSQLValueString($Season_ReviewStart, "date"),
						   GetSQLValueString($Season_ReviewEnd, "date"));
						   
		$NewContent=$_POST['Com_ID']."/".$_POST['SeasonCate_Name']."/".$_POST['Season_Year']."/".$_POST['Season_Code']."/".$Season_Start."/".$Season_End."/".$Season_SelectStart."/".$Season_SelectEnd."/".$Season_OnsiteStart."/".$Season_OnsiteEnd."/".$Season_Public."/".$Season_PayStart."/".$Season_PayEnd."/".$_POST['Season_Week']."/".$_POST['Season_Credit']."/".$Season_Benefit."/".$Season_MemberOffers."/".$Season_IsNewOld."/".$Rule_ID."/".$Season_PayCode."/".$Season_BankCode."/".$Season_Bank."/".$Season_Transaction."/".$Season_Fee."/".$Season_BankName."/".$Season_BankAccount."/".$AddTime."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username']."/".$Season_IsAll."/".$_POST['Season_IsNew']."/".$_POST['Season_IsCourseShow']."/".$_POST['Season_IsPreCourse']."/".$_POST['Season_IsFillStudent']."/".$_POST['Season_IsFillTeacher']."/".$_POST['Season_IsOnline']."/".$Season_CastCourseStart."/".$Season_CastCourseEnd."/".$Season_ReviewStart."/".$Season_ReviewEnd;
	
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		
		
		if(isset($_POST['SRule_Type']) && $_POST['SRule_Type']<>""){
			$query_CateP2_2 = sprintf("SELECT * FROM srule WHERE srule.Season_Code = %s and Com_ID=%s", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"));
			$CateP2_2 = mysql_query($query_CateP2_2, $dbline) or die(mysql_error());
			$row_CateP2_2 = mysql_fetch_assoc($CateP2_2);
			$totalRows_CateP2_2 = mysql_num_rows($CateP2_2);
			mysql_free_result($CateP2_2);
			if($totalRows_CateP2_2<1){
				$updateSQL2 = sprintf("insert into srule (SRule_Name, SRule_Type, Com_ID, Season_Code, Season_Year, Add_Time, Edit_Time) values (%s, %s, %s, %s, %s,    %s, %s)",
							   
							   GetSQLValueString($_POST['SRule_Name'], "text"),
							   GetSQLValueString($_POST['SRule_Type'], "int"),
							   GetSQLValueString($_POST['Com_ID'], "int"),
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString($_POST['Season_Year'], "int"),
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($AddTime, "date"));
				 mysql_select_db($database_dbline, $dbline);
				 $Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());
				
			}
			else{
				$updateSQL2 = sprintf("UPDATE srule SET  SRule_Name=%s, SRule_Type=%s, Edit_Time=%s WHERE srule.Season_Code = %s and Com_ID=%s",
							   
							   GetSQLValueString($_POST['SRule_Name'], "text"),
							   GetSQLValueString($_POST['SRule_Type'], "int"),
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString($_POST['Com_ID'], "int"));
				mysql_select_db($database_dbline, $dbline);
				$Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());
			}
	        }
		
		require_once('Season_Pay_Insert.php');
		
		require_once('Insurance_Insert.php');

		require_once('Print_Text_Insert.php');

		require_once('../../Include/Data_BrowseInsert.php');
	        mysql_free_result($CateP2);		
		/*?>
		<script type="text/javascript">
		alert("新增中...");
		window.location='<?php echo "../Statistic/Teacher_Sex.php?Msg=AddOK&Com_ID=".$_POST['Com_ID']."&Season_Code=".$Season_Old;?>';
		</script>
		
        <?php*/
		$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddOK";  
		header(sprintf("Location: %s", $insertGoTo));
	}
	else{
		mysql_free_result($CateP2);
		$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddError";  
		header(sprintf("Location: %s", $insertGoTo));
		
	}
}
/*新增班季ED*/


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
      
      <?php if($row_Permission['Per_Add'] == 1){ ?>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
        <div align="center">
          <fieldset style="max-width:800px;">
              <legend> 新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></legend>
              <div align="center" >
              
              <table cellpadding="0" cellspacing="2" border="0">
              <tr>
              <td>
              		<table cellpadding="5" cellspacing="0" border="0">
                    <tr>
                    <td class="FormTitle02" colspan="4" nowrap>社區大學:
					<select name="Com_ID" id="Com_ID" required> 
             		<option value="">請選擇社區大學</option>
              		<?php do { ?>
              		<option value="<?php echo $row_Community['Com_ID'];?>"><?php echo $row_Community['Com_Name'];?></option>	
			  		<?php } while ($row_Community = mysql_fetch_assoc($Community)); ?>
              		</select>
                    &nbsp;&nbsp;方式:&nbsp;&nbsp;<select name="Rule_ID" id="Rule_ID"><option value='1'>線上與現場</option><!--<option value='2'>僅線上</option>--></select>
                    <!--&nbsp;&nbsp;報名名額區分線上/現場：<input type="checkbox" name="Season_IsAll" value="1" checked>-->
                    <input name="Season_IsAll" id="Season_IsAll" value="1" type="hidden">
                   
              		</td>
                    </tr>
             		<tr>
                    <td class="FormTitle02" nowrap>季別:</td>
                    <td class="middle">
					<select name="SeasonCate_Name" id="SeasonCate_Name" onChange="SeasonCateID()" required> 
             		<option value="">請選擇季別</option>
              		<?php do { ?>
              		<option value="<?php echo $row_Cate2['SeasonCate_Name'];?>"><?php echo $row_Cate2['SeasonCate_Name'];?></option>	
			  		<?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
              		</select>	
              		</td>	
                    <td class="FormTitle02" nowrap>年度:</td>
                    <td class="middle">
              		<select name="Season_Year" id="Season_Year" required onChange="SeasonCateID()" >
              		<option value="">請選擇年度</option>
			  		<?php for($i=0;$i<3;$i++){?>
              		<option value="<?php echo (date("Y",strtotime("+".$i." Year"))-1911);?>"><?php echo (date("Y",strtotime("+".$i." Year"))-1911);?></option>
			  		<?php }?>
              		</select>
                    </td>
                    </tr>
		    <tr>
                    <td class="FormTitle02" nowrap>是否為新學期:</td>
                    <td class="middle">
			<select name="Season_IsNew" id="Season_IsNew" required> 
              		<option value="0">否</option>
              		<option value="1">是</option>
              		</select>	
              		</td>	
                    <td class="FormTitle02" nowrap>前台課表顯示:</td>
                    <td class="middle">
              		<select name="Season_IsCourseShow" id="Season_IsCourseShow" required>
              		<option value="0">否</option>
              		<option value="1">是</option>
              		</select>
                    </td>
                    </tr>
		    <tr>
                    <td class="FormTitle02" nowrap>前台課表預設顯示:</td>
                    <td class="middle">
			<select name="Season_IsPreCourse" id="Season_IsPreCourse" required> 
              		<option value="0">否</option>
              		<option value="1">是</option>
              		</select>	
              		</td>	
                    <td class="FormTitle02" nowrap></td>
                    <td class="middle">
                    </td>
                    </tr>
                   <tr>
                    <td class="FormTitle02" nowrap>學員問卷填寫:</td>
                    <td class="middle">
			<select name="Season_IsFillStudent" id="Season_IsFillStudent" required> 
              		<option value="0">否</option>
              		<option value="1">是</option>
              		</select>	
              		</td>	
                    <td class="FormTitle02" nowrap>講師問卷填寫:</td>
                    <td class="middle">
              		<select name="Season_IsFillTeacher" id="Season_IsFillTeacher" required>
              		<option value="0">否</option>
              		<option value="1">是</option>
              		</select>
                    </td>
                    </tr>
                   <tr>
                    <td class="FormTitle02" nowrap>是否開放線上選課:</td>
                    <td class="middle">
			<select name="Season_IsOnline" id="Season_IsOnline" required> 
              		<option value="0">否</option>
              		<option value="1">是</option>
              		</select>	
              		</td>	
                    <td class="FormTitle02" ></td>
                    <td class="middle">
                    </td>
                    </tr>
                    <tr>
                    <td class="FormTitle02" colspan="4" nowrap></td>
                    </tr>
                    </table>
                    <input type="hidden" name="Season_Code" id="Season_Code">&nbsp;
			  		
              
              </td>
              </tr>
              <tr>
              <td class="FormTitle03">
              <table cellpadding="5" cellspacing="0" border="0">
              <tr>
              <td class="right" nowrap>公告日期：</td>
              <td>
              <div class="Public_Add">
              		  <div class="DateStyle">
                            <div class='input-group date picker_date' >
                            <input type='text' name="Season_PublicDate" id="Season_PublicDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      	  </div>
			  <div class="TimeStyle">
                                                <div class='input-group date picker_time' >
                                                <input type='text' name="Season_PublicTime" id="Season_PublicTime" data-format="hh:mm:ss" class="form-control"  required/>
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
                            <input type='text' name="Season_StartDate" id="Season_StartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_EndDate" type="text" id="Season_EndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
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
                            <input type='text' name="Season_GroupStartDate" id="Season_GroupStartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_GroupEndDate" type="text" id="Season_GroupEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
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
                            <input type='text' name="Season_SelectStartDate" id="Season_SelectStartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_SelectEndDate" type="text" id="Season_SelectEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
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
                            <input type='text' name="Season_PayStartDate" id="Season_PayStartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_PayEndDate" type="text" id="Season_PayEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
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
                            <input type='text' name="Season_OnsiteStartDate" id="Season_OnsiteStartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_OnsiteEndDate" type="text" id="Season_OnsiteEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                           </div>
                           
                      </div>
                      <div class="Onsite_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
              </div>
			  </td>
              </tr>
	      <tr>
              <td class="right" nowrap>投課日期：</td>
              <td>
              <div class="CastCourse_Add">
                      <div class="DateStyle">
                            <div class='input-group date picker_date' >
                            <input type='text' name="Season_CastCourseStartDate" id="Season_CastCourseStartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_CastCourseEndDate" type="text" id="Season_CastCourseEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                           </div>
                           
                      </div>
                      <div class="CastCourse_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
              </div>
			  </td>
              </tr>
 	      <tr>
              <td class="right" nowrap>課審日期：</td>
              <td>
              <div class="Review_Add">
                      <div class="DateStyle">
                            <div class='input-group date picker_date' >
                            <input type='text' name="Season_ReviewStartDate" id="Season_ReviewStartDate" data-format="yyyy/MM/dd" class="form-control" required/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            </div>
                      </div>
                      
                       至
                      <div style="display:inline-block;">
                           <div class="DateStyle">
                                <div class='input-group date picker_date' >
                                <input name="Season_ReviewEndDate" type="text" id="Season_ReviewEndDate"  data-format="yyyy/MM/dd hh:mm:ss" class="form-control" required/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                </div>
                           </div>
                           
                      </div>
                      <div class="Review_Span"><span class="Msg_Date">起始時間不可大於結束時間</span></div>
              </div>
			  </td>
              </tr>
              </table>
              <!--上課期間退八成天數：<span id="Season_ReturnDay_Add_Span"></span><input name="Season_ReturnDay" id="Season_ReturnDay_Add" type="hidden" size="3" min="0" required />天-->
              </td>
              </tr>
              </table>
		<input name="Season_Credit" type="hidden" size="2" />
		<input name="Season_Week" type="hidden" size="2" />
		<input name="Season_Benefit" type="hidden" value="1"/>
		<input name="SRule_Type" id="SRule_Type" type="hidden" value="5"> 
		<input type="hidden" name="SRule_Name" id="SRule_Name" value="繳報名費為舊生"/>
              
             <br/>
              <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
              <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
              <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
              <input type="submit" value="確定新增" class="Button_Submit" />  <input type="reset" value="重填" class="Button_General"/>
              </div>
          </fieldset>
        <input type="hidden" name="MM_insert" value="Form_Add" />
        </div>
      </form> 
      <script type="text/javascript">
					  function SeasonCateID(){
						  if(Form_Add.SeasonCate_Name.value=="春季班"){
							  document.getElementById("Season_Code").value=Form_Add.Season_Year.value+"1"; document.getElementById("Season_ReturnDay_Add").value=14;
							  document.getElementById("Season_ReturnDay_Add_Span").innerHTML='14';}
						  if(Form_Add.SeasonCate_Name.value=="夏季班"){
							  document.getElementById("Season_Code").value=Form_Add.Season_Year.value+"2"; document.getElementById("Season_ReturnDay_Add").value=7;
							  document.getElementById("Season_ReturnDay_Add_Span").innerHTML='7';}
						  if(Form_Add.SeasonCate_Name.value=="秋季班"){
							  document.getElementById("Season_Code").value=Form_Add.Season_Year.value+"3"; document.getElementById("Season_ReturnDay_Add").value=14;
							  document.getElementById("Season_ReturnDay_Add_Span").innerHTML='14';}
						  if(Form_Add.SeasonCate_Name.value=="冬季班"){
							  document.getElementById("Season_Code").value=Form_Add.Season_Year.value+"4"; document.getElementById("Season_ReturnDay_Add").value=7;
							  document.getElementById("Season_ReturnDay_Add_Span").innerHTML='7';}
				  
					  }
					  
	  </script>  
      <?php require_once('season_addtimes.php');?>
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
      <?php } ?>
      
      </div>

        </td>
      </tr>
    </table>
    <br><br><br>
	</center>
</div>      

<script type="text/javascript">

$(document).ready(function(event) {
    $('form[name=Form_Add]').submit(function(event){
        //add stuff here
		 
		    	var Season_SelectStart=Date.parse(document.getElementById("Season_SelectStartDate").value+" 00:00:00");
			
			var Season_SelectEnd=Date.parse(document.getElementById("Season_SelectEndDate").value+" 23:59:59");
			
			var Season_OnsiteStart=Date.parse(document.getElementById("Season_OnsiteStartDate").value+" 00:00:00");
			
			var Season_OnsiteEnd=Date.parse(document.getElementById("Season_OnsiteEndDate").value+" 23:59:59");
			
			var Season_PayStart=Date.parse(document.getElementById("Season_PayStartDate").value+" 00:00:00");
			
			var Season_PayEnd=Date.parse(document.getElementById("Season_PayEndDate").value+" 23:59:59");
			
			var Season_GroupStart=Date.parse(document.getElementById("Season_GroupStartDate").value+" 00:00:00");
			
			var Season_GroupEnd=Date.parse(document.getElementById("Season_GroupEndDate").value+" 23:59:59");
			
			var Season_Start=Date.parse(document.getElementById("Season_StartDate").value+" 00:00:00");
			
			var Season_End=Date.parse(document.getElementById("Season_EndDate").value+" 23:59:59");
			
			var Season_CastCourseStart=Date.parse(document.getElementById("Season_CastCourseStartDate").value+" 00:00:00");
			
			var Season_CastCourseEnd=Date.parse(document.getElementById("Season_CastCourseEndDate").value+" 23:59:59");
			
			var Season_ReviewStart=Date.parse(document.getElementById("Season_ReviewStartDate").value+" 00:00:00");
			
			var Season_ReviewEnd=Date.parse(document.getElementById("Season_ReviewEndDate").value+" 23:59:59");
			
			 
			  if(Season_Start<=Season_End){ 
				  if(Season_SelectStart<=Season_SelectEnd){ 
						if(Season_OnsiteStart<=Season_OnsiteEnd){
							if(Season_PayStart<=Season_PayEnd){ 
								if(Season_GroupStart<=Season_GroupEnd){//線上要不能在現場時間開始之間
									if(Season_CastCourseStart<=Season_CastCourseEnd){
										if(Season_ReviewStart<=Season_ReviewEnd){
										
										}
										else{
											alert("課審結束日期不可在課審開始日期之前！");
											return false;
										}
									}
									else{
										alert("投課結束日期不可在投課開始日期之前！");
										return false;
									}
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
