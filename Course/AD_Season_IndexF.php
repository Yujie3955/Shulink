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
$query_Cate = sprintf("SELECT * FROM seasonf_list where Com_ID Like %s ORDER BY Com_ID asc ",GetSQLValueString($colname03_Unit,'text'));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

mysql_select_db($database_dbline, $dbline);
$query_Cate2 = "SELECT * FROM seasoncate ORDER BY SeasonCate_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

?>
<?php

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_Edit")) {
	mysql_select_db($database_dbline, $dbline);
    $query_CateP = sprintf("SELECT * FROM season_future WHERE FCom_ID = %s ", GetSQLValueString($_POST['FCom_ID'], "int"));
    $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
    $row_CateP = mysql_fetch_assoc($CateP);
    $totalRows_CateP = mysql_num_rows($CateP);
    
	$Other="修改".$row_Permission['ModuleSetting_Title'];
	
	$EditTime=date("Y-m-d H:i:s");
	if(isset($_POST['FSeason_Enable']) && $_POST['FSeason_Enable']==1){
		$FSeason_Enable=1;
	}
	else{
		$FSeason_Enable=0;
	}
	if($totalRows_CateP<1){
		$insertSQL = sprintf("insert into season_future (FSeason_Week, FSeason_Credit, FSeason_Code, FSeason_Year, FSeasonCate_Name, FCom_ID, FCom_Name, FCom_Code, FSeason_Enable, Edit_Time, Edit_Account, Edit_Unitname, Edit_Username, FRule_ID ) values (%s, %s, %s, %s, %s,     %s, %s, %s, %s, %s,     %s, %s, %s, %s)",
						   
						   GetSQLValueString($_POST['FSeason_Week'], "int"),
						   GetSQLValueString($_POST['FSeason_Credit'], "int"),
						   GetSQLValueString($_POST['FSeason_Year'].$_POST['SeasonCate_Code'], "int"),
						   GetSQLValueString($_POST['FSeason_Year'], "int"),
						   GetSQLValueString($_POST['FSeasonCate_Name'], "text"),
						   GetSQLValueString($_POST['FCom_ID'], "int"),
						   GetSQLValueString($_POST['FCom_Name'], "text"),
						   GetSQLValueString($_POST['FCom_Code'], "text"),
						   GetSQLValueString($FSeason_Enable, "int"),					   
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"),
						   GetSQLValueString($_POST['FRule_ID'], "int"));
		 mysql_select_db($database_dbline, $dbline);
		 $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		 require_once('../../Include/Data_BrowseInsert.php');
		 $NewContent=$_POST['FSeason_Year'].$_POST['SeasonCate_Code']."/".$row_CateP['FCom_ID']."/".$_POST['FSeason_Year']."/".$_POST['FSeasonCate_Name']."/".$_POST['FCom_Name']."/".$FSeason_Enable."/".$_POST['FSeason_Week']."/".$_POST['FSeason_Credit']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];
		  
	}
	else{
		
		 
		 $NewContent=$_POST['FSeason_Year'].$_POST['SeasonCate_Code']."/".$_POST['FCom_ID']."/".$_POST['FSeason_Year']."/".$_POST['FSeasonCate_Name']."/".$_POST['FCom_Name']."/".$FSeason_Enable."/".$_POST['FSeason_Week']."/".$_POST['FSeason_Credit']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];
		 
		 $updateSQL = sprintf("UPDATE season_future SET  FSeason_Week=%s, FSeason_Credit=%s, FSeason_Code=%s, FSeason_Year=%s, FSeasonCate_Name=%s, FCom_Name=%s, FCom_Code=%s, FSeason_Enable=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s, FRule_ID=%s WHERE FSeason_ID=%s",
						   
						   GetSQLValueString($_POST['FSeason_Week'], "int"),
						   GetSQLValueString($_POST['FSeason_Credit'], "int"),
						   GetSQLValueString($_POST['FSeason_Year'].$_POST['SeasonCate_Code'], "int"),
						   GetSQLValueString($_POST['FSeason_Year'], "int"),
						   GetSQLValueString($_POST['FSeasonCate_Name'], "text"),
						   GetSQLValueString($_POST['FCom_Name'], "text"),
						   GetSQLValueString($_POST['FCom_Code'], "text"),
						   GetSQLValueString($FSeason_Enable, "int"),					   
						   GetSQLValueString($EditTime, "date"),
						   GetSQLValueString($_POST['Edit_Account'], "text"),
						   GetSQLValueString($_POST['Edit_Unitname'], "text"),
						   GetSQLValueString($_POST['Edit_Username'], "text"),
						   GetSQLValueString($_POST['FRule_ID'], "int"),
						   GetSQLValueString($_POST['ID'], "int"));
						   
	       
		  $PastContent=$row_CateP['FSeason_ID']."/".$row_CateP['FSeason_Code']."/".$row_CateP['FCom_ID']."/".$row_CateP['FSeason_Year']."/".$row_CateP['FSeasonCate_Name']."/".$row_CateP['FCom_Name']."/".$row_CateP['FSeason_Enable']."/".$row_CateP['FSeason_Week']."/".$row_CateP['FSeason_Credit']."/".$row_CateP['Edit_Time']."/".$row_CateP['Edit_Account']."/".$row_CateP['Edit_Unitname']."/".$row_CateP['Edit_Username'];
		
		  
		  $NewContent=$_POST['ID']."/".$_POST['FSeason_Year'].$_POST['SeasonCate_Code']."/".$row_CateP['FCom_ID']."/".$_POST['FSeason_Year']."/".$_POST['FSeasonCate_Name']."/".$_POST['FCom_Name']."/".$FSeason_Enable."/".$_POST['FSeason_Week']."/".$_POST['FSeason_Credit']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];
		  mysql_select_db($database_dbline, $dbline);
		  $Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	
	  	  require_once('../../Include/Data_BrowseUpdate.php');
		
	}

  
  
  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateOK";  
  mysql_free_result($CateP);
  header(sprintf("Location: %s", $updateGoTo));
  
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

  
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Error_Msg Error_Add" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料登錄失敗，已有重複</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
          <div class="Error_Msg UpdateError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料更新失敗</div>
          <div class="Error_Msg Error_Del" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料刪除失敗，已有線上報名紀錄</div>
      
      
      
      <?php if($row_Permission['Per_View'] == 1){ ?>
      
      <div align="center">
      <fieldset style="max-width:800px;">
        <legend> 修改投課<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></legend>
        <div class="Season_List">
        
        <table border="0" cellpadding="5" cellspacing="0" style="word-break:break-all;" class="stripe">  
        <tr class="TableBlock_shadow_Head_Back">
        <td class="middle center" width="9%">社區大學</td>
        <td class="middle center" width="9%">班季</td>
        <td class="middle center" width="12%">資料</td>  	
        <td class="middle center" width="10%">是否啟用</td>  				
        <td class="middle" width="10%">操作</td>
        </tr>
        <?php if ($totalRows_Cate > 0) { // Show if recordset not empty  		
				   $a=0;?>
        <?php do {$a++;?>
        <form name="form_Edit" id="form_Edit" method="POST" action="<?php echo $editFormAction; ?>">
        <tr>
        <td class="middle center" >				
        <?php  echo $row_Cate['Com_Name']; ?>
		</td>
        <td class="middle center" >		
        <input type="text" name="FSeason_Year" id="FSeason_Year" required value="<?php echo $row_Cate['FSeason_Year'];?>" size="5">	年
        <select name="SeasonCate_Code" id="SeasonCate_Code<?php echo $a;?>" onChange="Cate_Name<?php echo $a;?>()" required>
        <option value="">請選擇季別</option>
        <?php if($totalRows_Cate2>0){
			      do{?>
                  <option value="<?php echo $row_Cate2['SeasonCate_Code'];?>" <?php if(substr($row_Cate['FSeason_Code'],-1)==$row_Cate2['SeasonCate_Code']){echo 'selected';}?>><?php echo $row_Cate2['SeasonCate_Name'];?></option>
		    <?php }while($row_Cate2=mysql_fetch_assoc($Cate2));
			      if($totalRows_Cate2> 0) {
						mysql_data_seek($Cate2, 0);
						$row_Cate2 = mysql_fetch_assoc($Cate2);
				  }
			  }?>
        </select>
        
        <input name="FSeasonCate_Name" id="FSeasonCate_Name<?php echo $a;?>" type="hidden">
        <script type="text/javascript">
		Cate_Name<?php echo $a;?>();
		function Cate_Name<?php echo $a;?>(){
			document.getElementById("FSeasonCate_Name<?php echo $a;?>").value=document.getElementById("SeasonCate_Code<?php echo $a;?>").options[document.getElementById("SeasonCate_Code<?php echo $a;?>").selectedIndex].text; 
		}</script>
        </td>
        <td class="middle left" >	
        方式：&nbsp;<select name="FRule_ID" id="FRule_ID"><option value='1'>線上與現場</option></select>
       
        <input type="hidden" name="FSeason_Credit" id="FSeason_Credit"  value="<?php echo $row_Cate['FSeason_Credit'];?>" size="5">				
       	
        <input type="hidden" name="FSeason_Week" id="FSeason_Week" hidden value="<?php echo $row_Cate['FSeason_Week'];?>" size="5">	
        <br/>	
        </td>
        <td class="middle center" ><input name="FSeason_Enable" type="checkbox" value="1" <?php if($row_Cate['FSeason_Code']<>""){if($row_Cate['FSeason_Enable']==1){echo 'checked';}}else{?> checked="checked"<?php }?> /></td>
        <td class="middle" >
        <?php if($row_Permission['Per_Edit'] == 1){ ?>
        <input type="submit" value="更新" class="Button_Edit"/>
        <input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['FSeason_ID']; ?>">
        <input type="hidden" name="FCom_ID" id="FCom_ID" value="<?php echo $row_Cate['Com_ID']; ?>">
        <input type="hidden" name="FCom_Name" id="FCom_Name" value="<?php echo $row_Cate['Com_Name']; ?>">
        <input type="hidden" name="FCom_Code" id="FCom_Code" value="<?php echo $row_Cate['Com_Code']; ?>">
        <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
        <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
        <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
        <input type="hidden" name="MM_update" value="form_Edit" />
        <?php } ?>    
        
							 
		</form>     
        </td>
        </tr>
        <?php 
		       } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>                 
        <?php } // Show if recordset not empty ?>
        </table>
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
<?php
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
