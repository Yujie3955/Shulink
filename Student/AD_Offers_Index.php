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
$maxRows_Data = 10;
$pageNum_Data = 0;
if (isset($_GET['search_Count']) && is_numeric($_GET['search_Count']) == true) {
  		$maxRows_Data = $_GET['search_Count'];
	}else{
		$maxRows_Data = 10;
	}
	
if (isset($_GET['pageNum_Data'])) {
  $pageNum_Data = $_GET['pageNum_Data'];
}
$startRow_Data = $pageNum_Data * $maxRows_Data;
$colname_Data02="%";
if (isset($_GET['Member_Name']) && $_GET['Member_Name']<>"") {
  	$colname_Data02 = "%".$_GET['Member_Name']."%";
}
$colname_Data03="%";
if (isset($_GET['Items']) && $_GET['Items']<>"") {
  	$colname_Data03 = "%".$_GET['Items']."%";
}

mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM offers_list where Com_ID Like %s and (Member_UserName Like %s or Member_Identity Like %s) and Offers_Reason Like %s ORDER BY Offers_ID desc",GetSQLValueString($colname03_Unit,"text"),GetSQLValueString($colname_Data02,"text"),GetSQLValueString($colname_Data02,"text"),GetSQLValueString($colname_Data03,"text"));
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
$queryString_Data = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_Data") == false && stristr($param, "totalRows_Data") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_Data = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_Data = sprintf("&totalRows_Data=%d%s", $totalRows_Data, $queryString_Data);

//班季
$query_Cate = sprintf("SELECT * FROM season_new WHERE Com_ID Like %s order by Com_ID ASC", GetSQLValueString($colname03_Unit, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
//優惠項目
$query_CateItem = "SELECT * FROM offers_item where OffersItem_Reason <> '班代' and OffersItem_Reason <> '助教'  order by OffersItem_ID ASC";
$CateItem = mysql_query($query_CateItem, $dbline) or die(mysql_error());
$row_CateItem= mysql_fetch_assoc($CateItem);
$totalRows_CateItem = mysql_num_rows($CateItem);

//優惠項目
$query_CateItem2 = "SELECT * FROM offers_item order by OffersItem_ID ASC";
$CateItem2 = mysql_query($query_CateItem2, $dbline) or die(mysql_error());
$row_CateItem2= mysql_fetch_assoc($CateItem2);
$totalRows_CateItem2 = mysql_num_rows($CateItem2);
?>
<?php

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add")) {
	$Other="新增".$row_Permission['ModuleSetting_Title'];
	
	$AddTime=date("Y-m-d H:i:s");
	if(isset($_POST['Member_ID'])&&$_POST['Member_ID']<>''){
		if(isset($_POST['Offers_Reason2'])&&$_POST['Offers_Reason2']<>''){
			$Offers_Reason2='('.$_POST['Offers_Reason2'].')';
		}
		else{
			$Offers_Reason2='';
		}
		$insertSQL = sprintf("INSERT INTO offers (Member_ID, Offers_Money, Offers_Reason, Season_Code, Add_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['Member_ID'], "text"),
                       GetSQLValueString($_POST['Offers_Money'], "int"),
                       GetSQLValueString($_POST['Offers_Reason'].$Offers_Reason2, "text"),
					   GetSQLValueString($_POST['Season_Code'],"int"),
                       GetSQLValueString($AddTime, "date"),
                       GetSQLValueString($_POST['Add_Account'], "text"),
                       GetSQLValueString($_POST['Add_Unitname'], "text"),
                       GetSQLValueString($_POST['Add_Username'], "text"));
					   
		$NewContent=$_POST['Member_ID']."/".$_POST['Offers_Money']."/".$_POST['Offers_Reason'].$Offers_Reason2."/".$_POST['Season_Code']."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username'];

  		mysql_select_db($database_dbline, $dbline);
  		$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
   		$Response_ID=mysql_insert_id($dbline);
		
		$query_CateP = "SELECT Offers_No FROM offers where Season_Code='".$_POST['Season_Code']."' order by Offers_No desc";
		$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
		$row_CateP= mysql_fetch_assoc($CateP);
		$totalRows_CateP = mysql_num_rows($CateP);
		if($totalRows_CateP>0){
			$Offers_No=$row_CateP['Offers_No']+1;
		}
		else{
			$Offers_No=1;
		}
		mysql_free_result($CateP);
		
		$updateSQL=sprintf("update offers set Offers_AutoCode=%s,Offers_No=%s  where Offers_ID=%s",
					   GetSQLValueString($_POST['Season_Code'].str_pad($Offers_No,5,'0',STR_PAD_LEFT),"text"),
					   GetSQLValueString($Offers_No,"int"),
					   GetSQLValueString($Response_ID,"int"));
   
   
        require_once('../../Include/Data_BrowseInsert.php'); 
        $insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddOK";  
	}
	else{	
    $insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddError";  
	}
	header(sprintf("Location: %s", $insertGoTo));
}
if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
  $Other="刪除".$row_Permission['ModuleSetting_Title'];
  //檢查是否使用
  mysql_select_db($database_dbline, $dbline);
  $query_CateP = sprintf("SELECT * FROM offers WHERE Offers_ID=%s and Course_ID is null and SignupItem_ID is null and SignupRecord_ID is null", GetSQLValueString($_POST['ID'], "int"));
  $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
  $row_CateP = mysql_fetch_assoc($CateP);
  $totalRows_CateP = mysql_num_rows($CateP);
  if($totalRows_CateP>0){  
	  require_once('../../Include/Data_BrowseDel.php');
	  $deleteSQL = sprintf("DELETE FROM offers WHERE Offers_ID=%s and Course_ID is null and SignupItem_ID is null and SignupRecord_ID is null ",
						   GetSQLValueString($_POST['ID'], "int"));
	
	  mysql_select_db($database_dbline, $dbline);
	  $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
	  
	  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
  }
  else{
	  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelError";  
  } 
  mysql_free_result($CateP);
  header(sprintf("Location: %s", $updateGoTo));
}

?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Include/spry_style.php'); ?>
<!--Autocomplete JQUERY OP-->
<link rel="stylesheet" href="../../Tools/Autocomplete/jquery-ui.css">
<link rel="stylesheet" href="../../Tools/Autocomplete/style.css">
<script src="../../Tools/Autocomplete/jquery-1.12.4.js"></script>
<script src="../../Tools/Autocomplete/jquery-ui_1.12.1.js"></script>
<!--Autocomplete JQUERY ED-->
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

        
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg Error_Del" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料刪除失敗，此優惠已使用。</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Error_Msg Error_Add" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料登錄失敗</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
        
        <div align="center">
        <table width="95%" border="0" cellpadding="5" cellspacing="2">
          <tr>
          <td>          
          <input name="" type="button" value="項目管理" onClick="location.href='AD_OffersItem_Index.php'" class="Button_Add" />   
          <?php if($row_Permission['Per_Add'] == 1){ ?>
          <input name="" type="button" value="新增班代" onClick="location.href='AD_Offers_Index2.php'" class="Button_Add" />
	  <input name="" type="button" value="新增助教" onClick="location.href='AD_Offers_IndexTeach.php'" class="Button_Add" />
          <?php }?>         
          </td>
          <td class="right"></td>
          </tr>
        </table>
        </div>
       
      <!--新增OP-->
      <?php if($row_Permission['Per_Add'] == 1){ ?>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
        <div align="center">
        <fieldset>
              <legend> 新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?>-志工</legend>
              <div align="center">
              <div align="left" style="max-width:900px;">	
              <span class="FormTitle02 display-inline">班季:
              <select id="Season_Area" name="Season_Area" onChange="callbyAJAX();">
              <?php do{?>
			  <option value="<?php echo $row_Cate['Season_Code'].'/'.$row_Cate['Com_ID'];?>"><?php echo $row_Cate['Season_Year'].'年'.$row_Cate['SeasonCate_Name'].' / '.$row_Cate['Com_Name'];?></option>
              <?php }while($row_Cate=mysql_fetch_assoc($Cate));?>
              </select>	
              <div id="Member_ListArea"></div>
              
              </span>  
              <span class="FormTitle02 display-inline">學員:
              <div class="ui-widget display-inline">      
              <input id="Member_Name" name="Member_Name" onChange="Member_Check()" type="text" required>
              <span id="Member_Area"></span>
              <div id="Msg_Member"></div>
              </div>
              </span>
              
              <span class="FormTitle02 display-inline">優惠項目:
              <select name="Offers_Area" id="Offers_Area" onChange="Offers_Check()" required>              
              <?php do{?>
			  <option value="<?php echo $row_CateItem['OffersItem_Money'].'/'.$row_CateItem['OffersItem_Reason'];?>"><?php echo $row_CateItem['OffersItem_Reason'];?></option>
              <?php }while($row_CateItem=mysql_fetch_assoc($CateItem));?>
              
              </select></span>
              <span class="FormTitle02 display-inline">備註:
              <input id="Offers_Reason2" name="Offers_Reason2"  type="text">
              </span>
              <span class="FormTitle02 display-inline" id="Offers_MoneyArea" style="display:none;">
              </span>
              </div>
              <br/>              
              <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
              <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
              <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
              <input type="submit" value="確定新增" class="Button_Submit"/>  <input type="reset" value="重填" class="Button_General"/>
              <input name="Season_Code" id="Season_Code" type="hidden">
              <input name="Title" id="Title" type="hidden">
              <input name="Offers_Money" id="Offers_Money" type="hidden">
              <input name="Offers_Reason" id="Offers_Reason" type="hidden">
              </div>                           
              <?php require_once('StudentAJAX.php'); ?>
        </fieldset>
          
        <input type="hidden" name="MM_insert" value="Form_Add" />
        </div>
      </form>   
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
      <?php } ?>
      <!--新增END-->
      <!--修改刪除OP-->
      <?php if($row_Permission['Per_View'] == 1){ ?>
      
      <div align="center">
      <fieldset>
        <legend> 修改/刪除<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></legend>
        <div align="center">
        <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td></td>
      <td class="right"><img src="../../Icon/find.png" class="middle">      
      <select name="Items" id="Items" style="width:auto;">
      <option value="">請選擇項目</option>
      <?php if($totalRows_CateItem2>0){
		       do{?>
			   <option value="<?php echo $row_CateItem2['OffersItem_Reason']?>" <?php if(isset($_GET['Items']) && $_GET['Items']==$row_CateItem2['OffersItem_Reason']){echo 'selected';}?>><?php echo $row_CateItem2['OffersItem_Reason']?></option>
	     <?php }while($row_CateItem2= mysql_fetch_assoc($CateItem2));
		 
		    }
			mysql_free_result($CateItem2);?>
      </select>
      <div class="display-inline">學員姓名、身分證:</div><input type="text" name="Member_Name" id="Member_Name" value="<?php echo @$_GET['Member_Name']; ?>" placeholder="請輸入學員姓名或身分證關鍵字"> <input type="submit" value="查詢" class="Button_General">
         <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General"></td>
      </tr>
    </table>
    </div>
    </form>
        <table border="0" cellpadding="5" cellspacing="0" class="stripe" width="98%"> 
          <tr class="TableBlock_shadow_Head_Back">
            <td class="middle" width="10%">班季</td>
            <td class="middle" width="10%">學員</td>
            <td class="center middle" width="5%">優惠額度</td>
            <td class="middle" width="15%">優惠原因</td>
            <td class="middle" width="8%">使用狀況</td>
            <?php if ($row_AdminMember['Unit_Range'] >= 2 ) { ?>
			<td class="middle center" width="8%">發布組別</td>
            <td class="middle center" width="8%">發布人</td>
			<?php } ?>
            <td class="middle" width="5%">操作</td>
            </tr>
           <?php if ($totalRows_Data > 0) { // Show if recordset not empty 
		   //分頁功能OP
		 
				$now_page = 0; //取得當前頁數
				if (isset($_GET['pageNum_Data']) && $_GET['pageNum_Data'] != "") { $now_page = $_GET['pageNum_Data']; }
				$min_page = max(0,$now_page-5);
				$max_page = min($totalPages_Data, $now_page+5);
				if (($now_page < 7) && ($totalPages_Data > 10)) {
					$min_page = 0;
					$max_page = 11;
				}	   
		   //分頁功能END
		   ?>
			<?php do { ?>
             
              <tr>
                <td class="middle">				
                  <?php echo $row_Data['Season_Year'].'年'.$row_Data['SeasonCate_Name']."(".mb_substr( $row_Data['Com_Name'],0,3,"utf-8").")"; ?></td>
                <td class="middle">				
                  <?php echo $row_Data['Member_UserName']."(".$row_Data['Member_Identity'].")"; ?>
                </td>
               
                <td class="center middle"><?php echo $row_Data['Offers_Money']; ?></td>
                <td class="middle"><?php echo $row_Data['Offers_Reason']; ?></td>
                <td class="middle"><?php if($row_Data['Course_ID']==""&&$row_Data['SignupItem_ID']==""&&$row_Data['SignupRecord_ID']==""){echo '未使用';}else{echo '已使用';}?></td>                
                <?php if ($row_AdminMember['Unit_Range'] >= 2) { ?>
                <td class="middle center"><?php echo $row_Data['Add_Unitname']; ?></td>
                <td class="middle center"><?php echo $row_Data['Add_Username']; ?></td>
                <?php } ?>       
                
                
                <td class="middle">                
                <?php if($row_Permission['Per_Del'] == 1 &&(($row_Data['Course_ID']==""&&$row_Data['SignupItem_ID']==""&&$row_Data['SignupRecord_ID']==""))){ ?>
                	<form name="form_Del" id="form_Del" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>"> 
                    <?php if($row_Data['Activity_ID']==""){?>
                    <input type="submit" name="button2" id="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n<?php echo $row_Data['Season_Year'].'年'.$row_Data['SeasonCate_Name'].":".$row_Data['Member_UserName']."(".$row_Data['Member_Identity'].")";?>\n<?php echo "優惠原因:".$row_Data['Offers_Reason']; ?>\n刪除後資料無法復原,確定要刪除嗎?'))" >
                    <?php }
					elseif($row_Data['Activity_ID']<>""){?>
					<input type="button" value="記錄管理" onClick="location.href='../Activity/AD_Student_Index.php?ID=<?php echo $row_Data['Activity_ID']?>';" class="Button_Edit"><?php }	?>
                    
                    <input type="hidden" name="Del" value="form_del">
                    <input type="hidden" name="ID" id="ID" value="<?php echo $row_Data['Offers_ID']; ?>">
                    <input type="hidden" name="Title" id="Title" value="<?php echo $row_Data['Season_Year'].'年'.$row_Data['SeasonCate_Name'].'優惠記錄'; ?>">
                    </form> 
                 <?php } ?>               
                </td>
             </tr>
              
             
            <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
             
            <?php } // Show if recordset not empty ?>
        </table>
        </div>
        <br>
            <!--分頁OP-->
            <div align="center">
        	<form id="search_Count" name="search_Count" method="get" action="" class="center">
        		每頁筆數：<select id="search_Count" name="search_Count" onChange="this.form.submit()">
	                    	<option value="10">10</option>
	                        <option value="20" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 20) { echo "selected='selected'"; } ?>>20</option>
	                        <option value="50" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 50) { echo "selected='selected'"; } ?>>50</option>
	                        <option value="100" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 100) { echo "selected='selected'"; } ?>>100</option>
                    	</select>
                    	<?php if (isset($_GET['Com_ID'])) {  ?><input name="Com_ID" type="hidden" value="<?php echo $_GET['Com_ID']; ?>"/><?php }  ?>
                    	<?php if (isset($_GET['Member_UserName'])) {  ?><input name="Member_UserName" type="hidden" value="<?php echo $_GET['Member_UserName']; ?>"/><?php }  ?>
        	</form>		
		    <table border="0">
                    <tr>
                        <td>
                        <?php if ($pageNum_Data > 0) { // Show if not first page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, 0, $queryString_Data); ?>'" class="gotopage Button_General" type="button"  value="第一頁" name="b1">
                        <?php } // Show if not first page ?>
                        <?php if ($pageNum_Data > 0) { // Show if not first page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, max(0, $pageNum_Data - 1), $queryString_Data); ?>'" class="gotopage Button_General" type="button"  value="上一頁" name="b2">
                        <?php } // Show if not first page ?>
                        <?php for($ii=@$min_page;$ii<=@$max_page;$ii++){ ?>
                            <?php if ($ii == @$now_page) { ?>
                                <span class="nowpage"><input  class="gotopage Navi_Use" value="<?php echo ($ii+1); ?>" type="button"></span>
                            <?php } else { ?>
                                <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, $ii, $queryString_Data); ?>'" class="gotopage Navi_NoUse" value="<?php echo ($ii+1); ?>" type="button">
                            <?php } ?>
                        <?php } ?>
                        <?php if ($pageNum_Data < $totalPages_Data) { // Show if not last page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, min($totalPages_Data, $pageNum_Data + 1), $queryString_Data); ?>'" class="gotopage Button_General" value="下一頁" type="button">
                        <?php } // Show if not last page ?>
                        <?php if ($pageNum_Data < $totalPages_Data) { // Show if not last page ?>
                           <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, $totalPages_Data, $queryString_Data); ?>'" class="gotopage Button_General" value="最後一頁" type="button">
                        <?php } // Show if not last page ?>
                        </td>
                    </tr>
             </table>
             <br /><br />
             No. <?php echo ($startRow_Data + 1) ?> ~ <?php echo min($startRow_Data + $maxRows_Data, $totalRows_Data) ?> 共 <?php echo $totalRows_Data ?> 筆資料
             </div>
             <!--分頁END-->
        
        </fieldset>
        </div>
    
       
        <?php }else{ ?><br><br><br>
        <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
        <?php } ?>
      <!--修改刪除END-->
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
mysql_free_result($Data);

?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
