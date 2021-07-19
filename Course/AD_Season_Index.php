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
$maxRows_Data = 10;
$pageNum_Data = 0;
if (isset($_GET['pageNum_Data'])) {
  $pageNum_Data = $_GET['pageNum_Data'];
}
$startRow_Data = $pageNum_Data * $maxRows_Data;

mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM season_rule where Com_ID Like %s ORDER BY season_rule.Season_Code desc ,Season_ID desc",GetSQLValueString($colname03_Unit,'text'));
$query_limit_Data = sprintf("%s LIMIT %d, %d", $query_Data, $startRow_Data, $maxRows_Data);
$Data = mysql_query($query_limit_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
if (isset($_GET['totalRows_Data'])) {
  $totalRows_Data = $_GET['totalRows_Data'];
} else {
  $all_Data = mysql_query($query_Data);
  $totalRows_Data = mysql_num_rows($all_Data);
}
$totalPages_Data = ceil($totalRows_Data/$maxRows_Data)-1;


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

/*轉名額OP*/
if ((isset($_POST["MM_update2"])) && ($_POST["MM_update2"] == "Form_Ed") ) {	
	if(isset($_POST['Season_IsAll']) && $_POST['Season_IsAll']<>1){
		$Other="修改".$row_Permission['ModuleSetting_Title'];
		mysql_select_db($database_dbline, $dbline);
		$query_SignGroupData = sprintf("SELECT * FROM sign_group WHERE sign_group.Season_Code = %s and sign_group.Com_ID = %s and sign_group.Pass_Enable <> 1", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"));
		$SignGroupData = mysql_query($query_SignGroupData, $dbline) or die(mysql_error());
		$row_SignGroupData = mysql_fetch_assoc($SignGroupData);
		$totalRows_SignGroupData = mysql_num_rows($SignGroupData);
		mysql_free_result($SignGroupData);
		if($totalRows_SignGroupData<1){		
		//搜索此季課程
		mysql_select_db($database_dbline, $dbline);
		$query_CateP3 = sprintf("SELECT * FROM course WHERE course.Season_Code = %s and Com_ID = %s", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"));
		$CateP3 = mysql_query($query_CateP3, $dbline) or die(mysql_error());
		$row_CateP3 = mysql_fetch_assoc($CateP3);
		$totalRows_CateP3 = mysql_num_rows($CateP3);
		
		$EditTime=date("Y-m-d H:i:s");
		if($totalRows_CateP3>0){
			do{	
				/*查詢線上已繳費、團報已報名OP*/
				$query_OnList = sprintf("SELECT SignupRecord_ID FROM signup_record WHERE Course_ID = %s and (SignupRecord_Identity like %s or (SignupRecord_Identity like %s and SignGroup_IsOnline=1)) and SignupRecord_Returns=0", GetSQLValueString($row_CateP3['Course_ID'], "int"), GetSQLValueString('%線上%', "text"), GetSQLValueString('%團體%', "text"));
				$OnList = mysql_query($query_OnList, $dbline) or die(mysql_error());
				$row_OnList = mysql_fetch_assoc($OnList);
				$totalRows_OnList= mysql_num_rows($OnList);
				mysql_free_result($OnList);
				/*查詢線上已繳費、團報已報名END*/
		
				$Course_OnSiteAdd=(($row_CateP3['Course_OnSiteAdd'])+($row_CateP3['Course_Online']+$row_CateP3['Course_OnlineAdd'])-$totalRows_OnList);
				if($row_CateP3['Course_ID']==7057){
				$CA=$row_CateP3['Course_OnSite'];
				$CC=$row_CateP3['Course_OnSite']+$Course_OnSiteAdd;
				}
					
				$updateSQL = sprintf("UPDATE Course SET  Course_OnSiteAdd=%s, Course_OnSiteRemaining=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE Course_ID=%s",
						   
						   GetSQLValueString($Course_OnSiteAdd, "int"),
						   GetSQLValueString($row_CateP3['Course_OnSite']+$Course_OnSiteAdd, "int"),
						  
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"),
						   GetSQLValueString($row_CateP3['Course_ID'], "int"));
							 
				mysql_select_db($database_dbline, $dbline);
				$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	
			}while($row_CateP3 = mysql_fetch_assoc($CateP3));
			//已報名但未繳費OP		
			$query_CateP4 = sprintf("SELECT SignupItem_ID,signup.Signup_ID,Signup_Status FROM signup inner join signup_item on signup.Signup_ID=signup_item.Signup_ID WHERE Season_ID = %s and Signup_Status<>'已繳費'", GetSQLValueString($_POST['ID'], "int"));
			$CateP4 = mysql_query($query_CateP4, $dbline) or die(mysql_error());
			$row_CateP4 = mysql_fetch_assoc($CateP4);
			$totalRows_CateP4 = mysql_num_rows($CateP4);
			if($totalRows_CateP4>0){
				do{//歸還優惠
					$updateSQL_Offers = sprintf("UPDATE offers SET  Course_ID=%s, SignupItem_ID=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE SignupItem_ID=%s ",
							   
							   GetSQLValueString(NULL, "text"),
							   GetSQLValueString(NULL, "text"),
							   GetSQLValueString($EditTime, "date"),
							   GetSQLValueString($_POST['Edit_Account'], "text"),
							   GetSQLValueString($_POST['Edit_Unitname'], "text"),
							   GetSQLValueString($_POST['Edit_Username'], "text"),
							   GetSQLValueString($row_CateP4['SignupItem_ID'], "int"));
								 
					mysql_select_db($database_dbline, $dbline);
					$Result1_2 = mysql_query($updateSQL_Offers, $dbline) or die(mysql_error());
				}while($row_CateP4 = mysql_fetch_assoc($CateP4));
			}
			mysql_free_result($CateP4);
			//更新單據
			$updateSQL2 = sprintf("UPDATE signup SET  Signup_Status=CONCAT(Signup_Status,%s), Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE Season_ID=%s and Signup_Status<>'已繳費'",
						   
						   GetSQLValueString(",未繳費", "text"),
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"),
						   GetSQLValueString($_POST['ID'], "int"));
							 
			mysql_select_db($database_dbline, $dbline);
			$Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());		
			//已報名但未繳費ED
			//更新班季已轉名額完成OP
			$updateSQL3 = sprintf("UPDATE season SET  Season_Turn=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE Season_ID=%s and Com_ID =%s",
						   
						   GetSQLValueString(1, "int"),
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"),
						   GetSQLValueString($_POST['ID'], "int"),
						   GetSQLValueString($_POST['Com_ID'], "int"));
							 
			mysql_select_db($database_dbline, $dbline);
			$Result3 = mysql_query($updateSQL3, $dbline) or die(mysql_error());
			//更新班季已轉名額完成ED
		}
		$_POST['Title']="";
		require_once('../../Include/Data_BrowseUpdate.php');
		mysql_free_result($CateP3);
		?>
		<script type="text/javascript">
		alert("名額轉換完成！將重新整理該頁面");
		window.location='Ad_Season_Index.php?Msg=UpdateOK';
		</script>
		<?php
		}
		else{
			$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateErrGroup";  
			header(sprintf("Location: %s", $updateGoTo));
		}
	
	}else{
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateErr";  
		header(sprintf("Location: %s", $updateGoTo));
	}

}
/*轉名額ED*/


/*刪除班季OP*/
if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
  $Other="刪除".$row_Permission['ModuleSetting_Title'];
 /*查詢刪除資料*/
 mysql_select_db($database_dbline, $dbline);
 $query_DelDataP = sprintf("SELECT Signup_ID FROM signup WHERE Season_Code=%s and Com_ID=%s ",GetSQLValueString($_POST['Season_Code'], "int"),GetSQLValueString($_POST['Com_ID'], "int"));
 $DelDataP = mysql_query($query_DelDataP, $dbline) or die(mysql_error());
 $row_DelDataP= mysql_fetch_assoc($DelDataP);
 $totalRows_DelDataP= mysql_num_rows($DelDataP);
 
 /*查詢刪除資料*/
 mysql_select_db($database_dbline, $dbline);
 $query_DelDataP2 = sprintf("SELECT SignupRecord_ID FROM signup_record inner join course on course.Course_ID=signup_record.Course_ID WHERE course.Season_Code=%s and course.Com_ID=%s ",GetSQLValueString($_POST['Season_Code'], "int"),GetSQLValueString($_POST['Com_ID'], "int"));
 $DelDataP2 = mysql_query($query_DelDataP2, $dbline) or die(mysql_error());
 $row_DelDataP2= mysql_fetch_assoc($DelDataP2);
 $totalRows_DelDataP2= mysql_num_rows($DelDataP2);
 
 mysql_free_result($DelDataP);
 mysql_free_result($DelDataP2);
 if($totalRows_DelDataP<1 && $totalRows_DelDataP2<1){
	 $query_DelData = sprintf("SELECT * FROM season WHERE Season_ID=%s",GetSQLValueString($_POST['ID'], "int"));
	 $DelData = mysql_query($query_DelData, $dbline) or die(mysql_error());
	 $row_DelData= mysql_fetch_assoc($DelData);
	 $totalRows_DelData= mysql_num_rows($DelData);
	 
	   
	  $PastContent=$row_DelData['Season_ID']."/".$row_DelData['SeasonCate_Name']."/".$row_DelData['Season_Year']."/".$row_DelData['Season_Code']."/".$row_DelData['Season_Start']."/".$row_DelData['Season_End']."/".$row_DelData['Season_SelectStart']."/".$row_DelData['Season_SelectEnd']."/".$row_DelData['Season_OnsiteStart']."/".$row_DelData['Season_OnsiteEnd']."/".$row_DelData['Season_Public']."/".$row_DelData['Season_PayStart']."/".$row_DelData['Season_PayEnd']."/".$row_DelData['Season_Week']."/".$row_DelData['Season_Credit']."/".$row_DelData['Season_Benefit']."/".$row_DelData['Season_PayCode']."/".$row_DelData['Season_BankCode']."/".$row_DelData['Rule_ID']."/".$row_DelData['Season_Count']."/".$row_DelData['Add_Time']."/".$row_DelData['Add_Account']."/".$row_DelData['Add_Unitname']."/".$row_DelData['Add_Username']."/".$row_DelData['Edit_Time']."/".$row_DelData['Edit_Account']."/".$row_DelData['Edit_Unitname']."/".$row_DelData['Edit_Username'];
	  require_once('../../Include/Data_BrowseDel.php');	
	  /*刪除*/
	  $deleteSQL = sprintf("DELETE FROM season WHERE Season_ID=%s",
						   GetSQLValueString($_POST['ID'], "int"));
	
	  mysql_select_db($database_dbline, $dbline);
	  $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
	  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
	  mysql_free_result($DelData);
  }
  else{
	$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelError";  
  }
  
  header(sprintf("Location: %s", $updateGoTo));
}
/*刪除班季ED*/


?>
<?php
#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_Data,$totalRows_Data;
	$pagesArray = ""; $firstArray = ""; $lastArray = "";
	if($max_links<2)$max_links=2;
	if($pageNum_Recordset1<=$totalPages_Recordset1 && $pageNum_Recordset1>=0)
	{
		if ($pageNum_Recordset1 > ceil($max_links/2))
		{
			$fgp = $pageNum_Recordset1 - ceil($max_links/2) > 0 ? $pageNum_Recordset1 - ceil($max_links/2) : 1;
			$egp = $pageNum_Recordset1 + ceil($max_links/2);
			if ($egp >= $totalPages_Recordset1)
			{
				$egp = $totalPages_Recordset1+1;
				$fgp = $totalPages_Recordset1 - ($max_links-1) > 0 ? $totalPages_Recordset1  - ($max_links-1) : 1;
			}
		}
		else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1+1;
		}
		if($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$_get_vars = '';			
			if(!empty($_GET) || !empty($HTTP_GET_VARS)){
				$_GET = empty($_GET) ? $HTTP_GET_VARS : $_GET;
				foreach ($_GET as $_get_name => $_get_value) {
					if ($_get_name != "pageNum_Data") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_Data=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_Data) + 1;
					$max_l = ($a*$maxRows_Data >= $totalRows_Data) ? $totalRows_Data : ($a*$maxRows_Data);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "";
					$pagesArray .= "<input type=\"button\" value=\"$textLink\" class=\"Navi_NoUse\" onClick=\"location.href='$_SERVER[PHP_SELF]?pageNum_Data=$theNext$_get_vars&#content'\">" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "<input type=\"button\" value=\"$textLink\" class=\"Navi_Use\">"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_Data=$successivo$_get_vars&#content\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
}
?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
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
      
      
      
      <?php if($row_Permission['Per_View'] == 1){ ?>
      <div align="center"> 
      <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
      <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
      <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
	  <br>
      <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
          <tr class="TableBlock_shadow_Head_Back">
          	<td class="middle center" width="5%">年度</td>
            <td class="middle center" width="10%">季別</td>
            <td class="middle center" width="10%">社區大學</td>
            <td class="middle center" width="20%">發佈日期</td>
            <td class="middle center" width="8%">預設學分</td>
			<td class="middle center" width="8%">預設週數</td>
            <td class="middle center" width="9%">發布人</td>
            <td class="middle center" width="20%">操作</td>
          </tr>
           <?php if ($totalRows_Data > 0) { // Show if recordset not empty ?>
			<?php do { ?>
              <tr>
              	<td class="middle center"><?php echo $row_Data['Season_Year']; ?></td>
                <td class="middle center">
				<?php echo $row_Data['SeasonCate_Name']?>
                </td>
                <td class="middle Black">
                <?php echo $row_Data['Com_Name'];?></td>
                <td class="middle center MainColor">[<?php if($row_Data['Season_Public']<>""){echo date("Y-m-d",strtotime($row_Data['Season_Public']));}?>]</td>
                <td class="middle center"><?php echo $row_Data['Season_Credit']; ?></td> 
                <td class="middle center"><?php echo $row_Data['Season_Week']; ?></td>
                <td class="middle center"><?php if($row_Data['Edit_Username']<>""){echo $row_Data['Edit_Username'];}else{echo $row_Data['Add_Username'];} ?></td>
                <td class="middle">
                  <?php if($row_Permission['Per_Edit'] == 1){ ?>
							 <?php if(strtotime($row_Data['Season_End'])>=strtotime($today)){?>
								<input type="button" value="修改" class="Button_Edit" onClick="location.href='AD_Season_Edit.php?ID=<?php echo $row_Data['Season_ID'];?><?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo '&pageNum_Data='.$_GET['pageNum_Data'];}?>';" style="margin:3px 0px;"/>
							
								<input type="hidden" name="ID" id="ID" value="<?php echo $row_Data['Season_ID']; ?>">
							   
								<input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
								<input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
								<input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
                               
								
						
                             <input type="button" value="優惠項目" class="Button_Edit" onClick="location.href='AD_Season_Pay.php?ID=<?php echo $row_Data['Season_ID']?>&Com_ID=<?php echo $row_Data['Com_ID'];?><?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo '&pageNum_Data='.$_GET['pageNum_Data'];}?>';" style="margin:3px 0px;"/> 
								 <?php } ?>   
							<?php } ?>
			
						
							
							<form name="form_Del" id="form_Del" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>" style="display:inline-block">
							<?php if($row_Permission['Per_Del'] == 1){ ?>
								 <?php if(strtotime($row_Data['Season_End'])>=strtotime($today)){?>
								<input type="submit" name="button2" id="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n[<?php echo $row_Data['Season_Year'].'年度'.$row_Data['SeasonCate_Name']; ?>]\n刪除後資料無法復原,確定要刪除嗎?'))" style="margin:3px 0px;">
								<input type="hidden" name="Del" value="form_del">
								<input type="hidden" name="Season_Code"  value="<?php echo $row_Data['Season_Code']; ?>">
								<input type="hidden" name="Com_ID"  value="<?php echo $row_Data['Com_ID']; ?>">
								<input type="hidden" name="ID" id="ID" value="<?php echo $row_Data['Season_ID']; ?>">
								<input type="hidden" name="Title" id="Title" value="<?php echo $row_Data['SeasonCate_Name']; ?>">    
								 
								 <?php } ?>    
							<?php } ?> 
						   </form> 
						   <?php if($row_Data['Season_Turn']==0){?>
						   <form name="Form_Ed" id="Form_Ed" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>" style="display:inline-block">
							<?php if($row_Permission['Per_Edit'] == 1){ ?>
								 <?php if(strtotime($row_Data['Season_SelectEnd'])<strtotime($today)&&strtotime($row_Data['Season_End'])>=strtotime($today) && $row_Data['Season_IsAll']<>1){?>
								<input type="submit" name="turn_button2" id="turn_button<?php echo $row_Data['Season_ID'];?>" value="轉換名額"  class="Button_Add" onClick="return(cpp<?php echo $row_Data['Season_ID'];?>());" style="margin:3px 0px;">
                                
                               
                                
								<input type="hidden" name="MM_update2" value="Form_Ed">
								<input type="hidden" name="Season_Code" id="Season_Code" value="<?php echo $row_Data['Season_Code']; ?>">
								<input type="hidden" name="Com_ID" id="Com_ID" value="<?php echo $row_Data['Com_ID']; ?>">
								<input type="hidden" name="ID" id="ID" value="<?php echo $row_Data['Season_ID']; ?>">
								<input type="hidden" name="Title" id="Title" value="<?php echo $row_Data['SeasonCate_Name']; ?>">
								<input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
								<input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
								<input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
								 <?php } ?>    
							<?php } ?>   
						   </form> 
						   <?php }else{/*echo '<br/>已轉名額';*/}?>
                           <?php if(preg_match('/;'.$row_Data['Season_Code']."_".$row_Data['Com_ID'].';/i',$Max_Season)){ ?>
                           <input type="button" value="產生季報表" onClick="return(cpp2_<?php echo $row_Data['Season_ID'];?>())" class="Button_Edit" style="margin:3px 0px;">
                           <?php }?>
                            <script type="text/javascript">
								function cpp<?php echo $row_Data['Season_ID'];?>(){
									var string=confirm("您即將轉換以下資料\n[<?php echo $row_Data['Season_Year'].'年度'.$row_Data['SeasonCate_Name']; ?>]<?php echo $row_Data['Com_Name'];?>\n轉換後剩餘線上名額將成為現場可報名名額，且資料無法復原，確定要轉換嗎?");
									if(string==true){
										$('#turn_button<?php echo $row_Data['Season_ID'];?>').hide();										
										return true;
										
										
									 }
									 else{return false;}
									 
								
								}
								function cpp2_<?php echo $row_Data['Season_ID'];?>(){
									var string=confirm("將使用目前學員、講師資料更新以下資料\n[<?php echo $row_Data['Season_Year'].$row_Data['SeasonCate_Name']."：".$row_Data['Com_Name'];?>]統計報表\n確定要執行嗎?");
									if(string==true){
										
									    location.href='../Statistic/Teacher_Sex.php?Com_ID=<?php echo $row_Data['Com_ID']?>&Season_Code=<?php echo $row_Data['Season_Code'];?>';		
										
										
									 }
									 else{}
									 
								
								}
								</script>
              
                </td>
              </tr>
              <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
            <?php } // Show if recordset not empty ?>
        </table>
          <br>
		  <?php 
          # variable declaration
          $prev_Data = "<input type='button' value='上一頁' class='Button_General'>";
          $next_Data = "<input type='button' value='下一頁' class='Button_General'>";
          $separator = " ";
          $max_links = 15;
          $pages_navigation_Data = buildNavigation($pageNum_Data,$totalPages_Data,$prev_Data,$next_Data,$separator,$max_links,true); 
          
          print $pages_navigation_Data[0]; 
          ?>
          <?php print $pages_navigation_Data[1]; ?> <?php print $pages_navigation_Data[2]; ?>         
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
<?php
mysql_free_result($Data);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
