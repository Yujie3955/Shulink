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
$maxRows_Cate = 10;
$pageNum_Cate = 0;
if (isset($_GET['pageNum_Cate'])) {
  $pageNum_Cate = $_GET['pageNum_Cate'];
}
$startRow_Cate = $pageNum_Cate * $maxRows_Cate;

mysql_select_db($database_dbline, $dbline);
$query_Cate = "SELECT * FROM offers_item ORDER BY OffersItem_ID ASC";
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
?>
<?php

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form_Edit")) {
	 mysql_select_db($database_dbline, $dbline);
     $query_CateP = sprintf("SELECT * FROM offers_Item WHERE OffersItem_ID = %s", GetSQLValueString($_POST['ID'], "int"));
     $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
     $row_CateP = mysql_fetch_assoc($CateP);
     $totalRows_CateP = mysql_num_rows($CateP);

	$Other="修改".$row_Permission['ModuleSetting_Title'];
	$EditTime=date("Y-m-d H:i:s");
	$updateSQL = sprintf("UPDATE offers_item SET OffersItem_Reason=%s, OffersItem_Money=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s WHERE OffersItem_ID=%s",
                       GetSQLValueString($_POST['Title'], "text"),
                       GetSQLValueString($_POST['OffersItem_Money'], "int"),                       
                       GetSQLValueString($EditTime, "date"),
                       GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));
					   

  
  $PastContent=$row_CateP['OffersItem_ID']."/".$row_CateP['OffersItem_Reason']."/".$row_CateP['OffersItem_Money']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$row_CateP['Edit_Time']."/".$row_CateP['Edit_Account']."/".$row_CateP['Edit_Unitname']."/".$row_CateP['Edit_Username'];
  
  $NewContent=$_POST['ID']."/".$_POST['Title']."/".$_POST['OffersItem_Money']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];
  
  mysql_select_db($database_dbline, $dbline);
  $Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());

  require_once('../../Include/Data_BrowseUpdate.php');
  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=UpdateOK";  
  mysql_free_result($CateP);
  header(sprintf("Location: %s", $updateGoTo));
  
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add")) {
	$Other="新增".$row_Permission['ModuleSetting_Title'];
	$AddTime=date("Y-m-d H:i:s");
	$insertSQL = sprintf("INSERT INTO offers_item (OffersItem_Reason, OffersItem_Money, Add_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Title'], "text"),
                       GetSQLValueString($_POST['OffersItem_Money'], "int"),                      
                       GetSQLValueString($AddTime, "date"),
                       GetSQLValueString($_POST['Add_Account'], "text"),
                       GetSQLValueString($_POST['Add_Unitname'], "text"),
                       GetSQLValueString($_POST['Add_Username'], "text"));
					   
	$NewContent=$_POST['Title']."/".$_POST['OffersItem_Money']."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username'];

  mysql_select_db($database_dbline, $dbline);
  $Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
  require_once('../../Include/Data_BrowseInsert.php');
 
  $insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddOK";  
  header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
  $Other="刪除".$row_Permission['ModuleSetting_Title'];
  require_once('../../Include/Data_BrowseDel.php');	
  $deleteSQL = sprintf("DELETE FROM offers_item WHERE OffersItem_ID=%s",
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_dbline, $dbline);
  $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
  
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

    <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td><input type="button" value="回記錄管理" class="Button_Add" onClick="location.href='AD_Offers_Index.php'"/> </td>      
      </tr>
    </table>
    </div>
    </form>
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
      
      <?php if($row_Permission['Per_Add'] == 1){ ?>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
        <div align="center">
          <fieldset style="max-width:800px;">
              <legend> 新增項目</legend>
              <div align="center">
			  <span class="FormTitle02"><?php echo $row_ModuleSet['ModuleSetting_SubName'];?>原因:<input name="Title" type="text" id="Title" required >
              <?php /*總額度:<input name="OffersItem_Money" type="number" id="OffersItem_Money" max="200" min="0" required>            (範圍:0~200) */?><input name="OffersItem_Money" type="hidden" id="OffersItem_Money" max="200" min="0" >
             </span>
              <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
              <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
              <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
              <input type="submit" value="確定新增" class="Button_Submit"/>  <input type="reset" value="重填" class="Button_General"/>
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
      <fieldset style="max-width:800px;">
        <legend> 修改/刪除項目</legend>
        
        <table border="0" cellpadding="5" cellspacing="0" class="stripe" > 
          <tr class="TableBlock_shadow_Head_Back">
            <td class="middle" width="10%"><?php echo $row_ModuleSet['ModuleSetting_SubName'];?>原因</td>
            <!--<td class="middle" width="10%">總額度</td>  -->       
            <?php if ($row_AdminMember['Unit_Range'] >= 2 ) { ?>
			<td class="middle center" width="8%">發布組別</td>
            <td class="middle center" width="8%">發布人</td>
			<?php } ?>
            <td class="middle" width="2%" colspan="2">操作</td>
            </tr>
           <?php if ($totalRows_Cate > 0) { // Show if recordset not empty ?>
			<?php do { ?>
             <form name="form_Edit" id="form_Edit" method="POST" action="<?php echo $editFormAction; ?>">
              <tr>
                         
                <td class="middle"><input name="Title" type="text" id="Title"  value="<?php echo $row_Cate['OffersItem_Reason']; ?>" required></td>
                <!--<td class="middle"><input name="OffersItem_Money" type="number" id="OffersItem_Money"  value="<?php echo $row_Cate['OffersItem_Money']; ?>" max="200" min="0"></td>--> <input name="OffersItem_Money" type="hidden" id="OffersItem_Money"  value="<?php echo $row_Cate['OffersItem_Money']; ?>" max="200" min="0">            
                <?php if ($row_AdminMember['Unit_Range'] >= 2) { ?>
                <td class="middle center"><?php echo $row_Cate['Add_Unitname']; ?></td>
                <td class="middle center"><?php echo $row_Cate['Add_Username']; ?></td>
                <?php } ?>
                
                <td class="middle">
          
                <?php if($row_Permission['Per_Edit'] == 1){ ?>
                    <input type="submit" value="更新" class="Button_Edit"/>
                    <input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['OffersItem_ID']; ?>">     
                    <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
                    <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
                    <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
                <?php } ?>
           
                </td>
                <input type="hidden" name="MM_update" value="form_Edit" />
                </form>
                
                <form name="form_Del" id="form_Del" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>">
                <td class="middle">
                 
                <?php if($row_Permission['Per_Del'] == 1){ ?>
                    <input type="submit" name="button2" id="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n<?php echo '原因:'.$row_Cate['OffersItem_Reason'].'/總額度:'.$row_Cate['OffersItem_Money']; ?>\n刪除後資料無法復原,確定要刪除嗎?'))">
                    <input type="hidden" name="Del" value="form_del">
                    <input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['OffersItem_ID']; ?>">
                    <input type="hidden" name="Title" id="Title" value="<?php echo $row_Cate['OffersItem_Reason']; ?>">
                 <?php } ?> 
                  
                </td>
                  </tr>
              </form>
             
              <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
             
            <?php } // Show if recordset not empty ?>
        </table>
        
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

?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
