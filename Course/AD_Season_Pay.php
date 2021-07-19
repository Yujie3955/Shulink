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
<?php require_once('../../Include/Permission.php'); //看到資料量?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit") ) {	
   $Other="修改".$row_Permission['ModuleSetting_Title'].'優惠項目';
   $str_url='';
   if(isset($_POST['pageNum_Data']) && $_POST['pageNum_Data']<>""){
	$str_url.="&pageNum_Data=".$_POST['pageNum_Data'];
   }
   if(isset($_POST['P_Enable']) && $_POST['P_Enable']==1){
	   $P_Enable=$_POST['P_Enable'];   
   }
   else{
	   $P_Enable=0;   
   }
   if(isset($_POST['P_Sale']) && is_numeric($_POST['P_Sale'])){
	   $P_Sale=$_POST['P_Sale'];	  
   }
   else{
	   $P_Sale=1;
   }
   $Edit_Time=date("Y-m-d H:i:s");
   $updateSQL = sprintf("UPDATE pay SET P_Sale=%s, P_Text=%s, P_Enable=%s, P_Sort=%s, P_SaleText=%s,  Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE Pid=%s and Com_ID=%s",
						   
						   GetSQLValueString($P_Sale, "text"),
						   GetSQLValueString($_POST['P_Text'], "text"),
						   GetSQLValueString($P_Enable, "int"),
						   GetSQLValueString($_POST['P_Sort'], "int"),
						   GetSQLValueString($_POST['P_SaleText'], "text"),
						   GetSQLValueString($Edit_Time, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"),
						   GetSQLValueString($_POST['ID'], "int"),
						   GetSQLValueString($_POST['Com_ID'], "int"));
	
	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	require_once('../../Include/Data_BrowseUpdate.php');
	$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateOK&ID=".$_POST['SID'].$str_url;  
        header(sprintf("Location: %s", $updateGoTo));

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add") ) {	
   $Other="新增".$row_Permission['ModuleSetting_Title'].'優惠項目';
   $str_url='';
   if(isset($_POST['pageNum_Data']) && $_POST['pageNum_Data']<>""){
	$str_url.="&pageNum_Data=".$_POST['pageNum_Data'];
   }
   if(isset($_POST['P_Enable']) && $_POST['P_Enable']==1){
	   $P_Enable=$_POST['P_Enable'];   
   }
   else{
	   $P_Enable=0;   
   }
   if(isset($_POST['P_Sale']) && is_numeric($_POST['P_Sale'])){
	   $P_Sale=$_POST['P_Sale'];	  
   }
   else{
	   $P_Sale=1;
   }
   $Add_Time=date("Y-m-d H:i:s");

		//優惠查詢是否新增
		mysql_select_db($database_dbline, $dbline);
		$query_CateP2_3 = sprintf("SELECT Pid FROM pay WHERE pay.Season_Code = %s and Com_ID=%s and P_Text=%s", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"),GetSQLValueString($_POST['P_Text'], "text"));
		$CateP2_3 = mysql_query($query_CateP2_3, $dbline) or die(mysql_error());
		$row_CateP2_3 = mysql_fetch_assoc($CateP2_3);
		$totalRows_CateP2_3 = mysql_num_rows($CateP2_3);
		if($totalRows_CateP2_3<1){
			//搜尋編號到幾項
			$query_CateP2_4 = sprintf("SELECT P_Code FROM pay WHERE pay.Season_Code = %s and Com_ID=%s order by Pid desc", GetSQLValueString($_POST['Season_Code'], "int"), GetSQLValueString($_POST['Com_ID'], "int"));
			$CateP2_4 = mysql_query($query_CateP2_4, $dbline) or die(mysql_error());
			$row_CateP2_4 = mysql_fetch_assoc($CateP2_4);
			$totalRows_CateP2_4 = mysql_num_rows($CateP2_4);
			if($totalRows_CateP2_4>0){
				$P_CodeValue=explode("_",$row_CateP2_4['P_Code']);
				$P_CodeValue=(int)($P_CodeValue[1]+1);
			}
			else{
				$P_CodeValue=1;
			}
			mysql_free_result($CateP2_4);
			$P_Cmain=$_POST['Season_Code'].$Com_Code;//主CODE
			$P_Code=$_POST['Season_Code'].$Com_Code.'1_'.$P_CodeValue;	//子CODE	
			
			$insertSQL3 = sprintf("INSERT INTO pay (Com_ID, Season_Code, P_Enable, P_Cmain,  P_Code, P_Point, P_Cate, P_Pay, P_Sale, P_SaleText, P_Text, P_Sort, P_IsOnline, Add_Time, Edit_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s,    %s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s)",
							   GetSQLValueString($_POST['Com_ID'], "int"),					   
							   GetSQLValueString($_POST['Season_Code'], "int"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString($P_Cmain, "text"),
							   GetSQLValueString($P_Code, "text"),
							   GetSQLValueString(1, "int"),
							   GetSQLValueString($_POST['PR_Cate'], "int"),
							   GetSQLValueString($_POST['PR_Pay'], "int"),
							   GetSQLValueString($_POST['PR_Sale'], "text"),
							   GetSQLValueString($_POST['P_SaleText'], "text"),
							   GetSQLValueString($_POST['PR_Text'], "text"),
							   GetSQLValueString($_POST['PR_Sort'], "int"),
							   GetSQLValueString(0, "int"),
							   
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($_POST['Add_Account'], "text"),
							   GetSQLValueString($_POST['Add_Unitname'], "text"),
							   GetSQLValueString($_POST['Add_Username'], "text"));
					mysql_select_db($database_dbline, $dbline);
					$Result3 = mysql_query($insertSQL3, $dbline) or die(mysql_error());
					$Response_ID=mysql_insert_id($dbline);
					$columns_data="pay";
					$columns_dataid="Pid";
					require_once('../../Include/Data_Insert_Content.php');
					require_once('../../Include/Data_BrowseInsert.php');
					$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddOK&ID=".$_POST['SID'].$str_url;  
		}
		else{
			$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddError&ID=".$_POST['SID'].$str_url;  
		}		
		mysql_free_result($CateP2_3);
		
	
	
        header(sprintf("Location: %s", $updateGoTo));

}
?>

<?php
$colname_ID='-1';
if(isset($_GET['ID']) && is_numeric($_GET['ID'])){
$colname_ID=$_GET['ID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM season inner join community on community.Com_ID=season.Com_ID where season.Com_ID Like %s and Season_ID=%s",GetSQLValueString($colname03_Unit,'text'),GetSQLValueString($colname_ID,'int'));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);

$query_Cate = sprintf("SELECT pay.Pid, pay.Com_ID, pay.Season_Code, pay.P_Enable, pay.P_Cmain, pay.P_Code, pay.P_Point, pay.P_Cate, pay.P_Pay, pay.P_Sale, pay.P_Text, pay.P_Sort, pay.P_IsOnline, pay.Add_Time, pay.Add_Account, pay.Add_Unitname, pay.Add_Username, pay.Edit_Time, pay.Edit_Account, pay.Edit_Unitname, pay.Edit_Username, community.Com_Name, season.Season_Year, season.SeasonCate_Name, season.Rule_ID,season. Season_Code, community.Com_Code,pay.P_SaleText FROM pay inner join community on community.Com_ID=pay.Com_ID inner join season on season.Season_Code=pay.Season_Code and season.Com_ID=pay.Com_ID where pay.Com_ID Like %s and season.Season_ID=%s ORDER BY pay.P_Sort asc",GetSQLValueString($colname03_Unit,'text'),GetSQLValueString($colname_ID,'int'));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$query_RuleData = sprintf("SELECT * FROM rule where Rule_ID=%s ",GetSQLValueString($row_Cate['Rule_ID'],'int'));
$RuleData = mysql_query($query_RuleData, $dbline) or die(mysql_error());
$row_RuleData = mysql_fetch_assoc($RuleData);
$totalRows_RuleData = mysql_num_rows($RuleData);
$Rule_Credit=1000;
if($totalRows_RuleData>0){
	$Rule_Credit=$row_RuleData['Rule_Credit'];
}
mysql_free_result($RuleData);
$today=date("Y-m-d");

?>
<?php

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
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']; ?> - 修改優惠項目</div>
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
<?php if(@$_GET['Msg'] == "UpdateError"){ ?>
	<script language="javascript">
	function UpdateError(){
		$('.UpdateError').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(UpdateError,0);
    </script>
<?php } ?>

      

  
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Error_Msg Error_Add" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料登錄失敗，已有重複</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
          <div class="Error_Msg UpdateError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料更新失敗</div>
      <table width="95%" border="0" cellpadding="5" cellspacing="2">
          <tr>
          <td>
          <input type="button" value="回上頁" class="Button_General" onClick="location.href='AD_Season_Index.php?<?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo "pageNum_Data=".$_GET['pageNum_Data'];}?>'"/>
          </td>
          </tr>
      </table>
      
       <?php if($row_Permission['Per_Add'] == 1){ ?>
	
              <fieldset>
              <legend><?php echo $row_Cate['Com_Name']?>-<?php echo $row_Cate['Season_Year'].'年'.$row_Cate['SeasonCate_Name'];?>-新增</legend>
	      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">	 
              <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
              <tr class="TableBlock_shadow_Head_Back">              
              <td class="center middle" width="8%">類型</td>
              <td class="center middle" width="5%">一學分<br/>費用</td>             
              <td class="center middle" width="8%">折數文字</td>              
              <td class="center middle" width="8%">折數</td>
              <td class="center middle" width="60%">優惠條件</td>
              <td class="center middle" width="5%">啟用</td>
              <td class="center middle" width="10%">排序</td>
			  <td class="center middle">操作</td>
              </tr>
              
              <tr> 
              
              <td class="center middle"><select><option value="學分費">學分費</option></select><input name="P_Cate" type="hidden" value="2"></td>
              <td class="center middle"><?php echo $Rule_Credit;?><input type="hidden" name="P_Pay" value="<?php echo $Rule_Credit;?>"></td>
	      <td class="center middle"><input type="text" name="P_SaleText" id="P_SaleText" value="<?php echo $row_Cate['P_SaleText']?>" style="width:50px;"/></td>
              <td class="center middle"><input type="text" name="P_Sale" id="P_Sale" value="<?php echo $row_Cate['P_Sale']?>" style="width:50px;"/></td>
              <td class="middle"><input type="text" name="P_Text"  onkeydown="if(event.keyCode == 13) return false;"  style="width:95%;  max-height:100px;"/></td>            
              <td class="center middle"><input type="checkbox" name="P_Enable" value="1" <?php if($row_Cate['P_Enable']==1){echo 'checked';}?>/></td>
              <td class="center middle"><input type="number" min="0" name="P_Sort" value="<?php echo $row_Cate['P_Sort']?>"  style="width:50px;"/></td>
			  
              <td class="center middle">
              <input name="Season_Code" type="hidden" id="Season_Code" value="<?php echo $row_Data['Season_Code']; ?>">
	      <input name="Com_ID" type="hidden" id="Com_ID" value="<?php echo $row_Data['Com_ID']; ?>">
	      <input type="hidden" name="SID" id="SID" value="<?php echo $row_Data['Season_ID'];?>" />
              <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
              <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
              <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
              <input name="pageNum_Data" type="hidden" id="pageNum_Data" value="<?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];} ?>">
              <input type="submit" value="新增" class="Button_Submit" /> 	
              <input type="hidden" name="MM_insert" value="Form_Add" />
              </td>
              </form>
              </tr>
              
              </table>
              </fieldset>
              
            
         
       
     
      
      <?php }?>
      <?php if($row_Permission['Per_View'] == 1){ ?>	      
              <fieldset>
              <legend><?php echo $row_Cate['Com_Name']?>-<?php echo $row_Cate['Season_Year'].'年'.$row_Cate['SeasonCate_Name'];?></legend>
              <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
              <tr class="TableBlock_shadow_Head_Back">              
              <td class="center middle" width="8%">類型</td>
              <td class="center middle" width="5%">一學分<br/>費用</td>             
              <td class="center middle" width="8%">折數文字</td>                     
              <td class="center middle" width="8%">折數</td>
              <td class="center middle" width="5%">折扣後<br/>費用</td>
              <td class="center middle" width="60%">優惠條件</td>
              <td class="center middle" width="5%">啟用</td>
              <td class="center middle" width="10%">排序</td>
			  <td class="center middle">操作</td>
              </tr>
              <?php 
			  if($totalRows_Cate>0){
			  do{?>
              <tr> 
              <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit" id="Form_Edit" method="POST">	 
              <td class="center middle"><?php if($row_Cate['P_Cate']==1){echo '報名費';}else{echo '學分費';}?></td>
              <td class="center middle"><?php echo $row_Cate['P_Pay']?></td>
              <td class="center middle"><input type="text" name="P_SaleText" id="P_SaleText" value="<?php echo $row_Cate['P_SaleText']?>" style="width:50px;"/></td>
              <td class="center middle"><input type="text" name="P_Sale" id="P_Sale" value="<?php echo $row_Cate['P_Sale']?>" style="width:50px;"/></td>
              <td class="center middle"><?php echo $row_Cate['P_Pay']*$row_Cate['P_Sale'];?></td>
              <td class="middle"><input type="text" name="P_Text"  onkeydown="if(event.keyCode == 13) return false;"  style="width:95%;  max-height:100px;" value="<?php echo $row_Cate['P_Text']?>"/></td>            
              <td class="center middle"><input type="checkbox" name="P_Enable" value="1" <?php if($row_Cate['P_Enable']==1){echo 'checked';}?>/></td>
              <td class="center middle"><input type="number" min="0" name="P_Sort" value="<?php echo $row_Cate['P_Sort']?>"  style="width:50px;"/></td>
			  
              <td class="center middle">
	      <?php if($row_Permission['Per_Edit'] == 1){ ?>
	      <input type="hidden" name="SID" id="SID" value="<?php echo $row_Data['Season_ID'];?>" />
	      <input type="hidden" name="Com_ID" id="Com_ID" value="<?php echo $row_Data['Com_ID'];?>" />
              <input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['Pid'];?>" />
              <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
              <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
              <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
              <input name="pageNum_Data" type="hidden" id="pageNum_Data" value="<?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];} ?>">
              <input type="submit" value="更新" class="Button_Submit" /> 	
              <input type="hidden" name="MM_update" value="Form_Edit" />
		<?php }?>
              </td>
              </form>
              </tr>
              <?php }while($row_Cate=mysql_fetch_assoc($Cate));
	          }?>
              </table>
              </fieldset>
              
         <?php }else{ ?><br><br><br>
     	 <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
      <?php } ?>    
         
       
        </div>
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
mysql_free_result($Cate);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
