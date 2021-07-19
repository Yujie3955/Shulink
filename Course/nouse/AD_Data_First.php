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
<?php require_once('../../Include/Permission_Cate.php'); ?>
<?php require_once('../../include/Permission.php');?>

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



//搜班季
$colname02_Data = "%";
if ((isset($_GET['Season_Code'])) && ($_GET['Season_Code'] != "")) {
  $colname02_Data = $_GET['Season_Code'];
}
//搜課程名稱
$colname03_Data = "%";
if ((isset($_GET['Course_Title'])) && ($_GET['Course_Title'] != "")) {
  $colname03_Data = "%".$_GET['Course_Title']."%";
}
//搜學校
$colname04_Data=$colname02_Unit;
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
  $colname04_Data = $_GET['Unit_ID'];
}

$colname05_Data='%';
if ((isset($_GET['Checks1'])) && ($_GET['Checks1'] != "")) {
  $colname05_Data = $_GET['Checks1'];
}
$colname06_Data='%';
if ((isset($_GET['Checks2'])) && ($_GET['Checks2'] != "")) {
  $colname06_Data = $_GET['Checks2'];
}
$colname07_Data='%';
if ((isset($_GET['Checks3'])) && ($_GET['Checks3'] != "")) {
  $colname07_Data = $_GET['Checks3'];
}



$query_Data = sprintf("SELECT * FROM course_list WHERE ifnull(Season_Code,'') Like %s and (ifnull(CourseKind_Name,'') Like %s or ifnull(Course_Name,'') Like %s) and ifnull(Com_ID,'') like %s and ifnull(Unit_ID,'') like %sand Course_Check1 like %s and Course_Check2 like %s and Course_Check3 like %s ORDER BY Season_Code DESC, Add_Time DESC, Course_ID DESC",GetSQLValueString($colname02_Data, "text"),GetSQLValueString($colname03_Data, "text"),GetSQLValueString($colname03_Data, "text"), GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname04_Data, "text"), GetSQLValueString($colname05_Data, "text"), GetSQLValueString($colname06_Data, "text"), GetSQLValueString($colname07_Data, "text"));
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
	
	
$query_Cate = sprintf("SELECT distinct course_list.Season_Code FROM course_list where Com_ID like %s ORDER BY course_list.Season_Code ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

//看到範圍
if($row_AdminMember['Unit_Range']>=3){
$query_Cate2 = "SELECT * FROM unit_detail where Unit_IsSchool=1 ORDER BY Com_ID ASC,Unit_ID ASC";
}
else{
$query_Cate2 = "SELECT * FROM unit_detail where Com_ID like '".$colname03_Unit."' and Unit_ID like '".$colname02_Unit."' and Unit_IsSchool=1 ";	
	}
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

 
if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
	$Other = "刪除".$row_Permission['ModuleSetting_Title']; 	
	//查詢線上選課
	mysql_select_db($database_dbline, $dbline);
	$query_DelData0= sprintf("SELECT * from signup_item WHERE Course_ID=%s",GetSQLValueString($_POST['ID'], "int"));
	$DelData0 = mysql_query($query_DelData0, $dbline) or die(mysql_error());
	$row_DelData0= mysql_fetch_assoc($DelData0);
	$totalRows_DelData0= mysql_num_rows($DelData0);
	
	//查詢正式名單
	$query_DelData1= sprintf("SELECT * from signup_record WHERE Course_ID=%s",GetSQLValueString($_POST['ID'], "int"));
	$DelData1 = mysql_query($query_DelData1, $dbline) or die(mysql_error());
	$row_DelData1= mysql_fetch_assoc($DelData1);
	$totalRows_DelData1= mysql_num_rows($DelData1);

	if($totalRows_DelData0<1 && $totalRows_DelData1<1)
	{
		/*查詢刪除資料*/
		$query_DelData = sprintf("SELECT * from course_detail WHERE Course_ID=%s",GetSQLValueString($_POST['ID'], "int"));
		$DelData = mysql_query($query_DelData, $dbline) or die(mysql_error());
		$row_DelData= mysql_fetch_assoc($DelData);
		$totalRows_DelData= mysql_num_rows($DelData);
		
		if($totalRows_DelData>0){
			$PastContent=$row_DelData['Course_ID']."/".$row_DelData['Com_ID']."/".$row_DelData['Unit_ID']."/".$row_DelData['Season_Code']."/".$row_DelData['CourseKind_ID']."/".$row_DelData['CourseKind_Name']."/".$row_DelData['Course_NO']."/".$row_DelData['Course_Name']."/".$row_DelData['Season_Credit']."/".$row_DelData['Course_Free']."/".$row_DelData['Loc_ID']."/".$row_DelData['Loc_Name']."/".$row_DelData['Season_Week']."/".$row_DelData['Course_Day']."/".$row_DelData['Course_Start']."/".$row_DelData['Course_End']."/".$row_DelData['Course_Hour']."/".$row_DelData['Teacher_ID']."/".$row_DelData['Course_Aims']."/".$row_DelData['Course_Summary']."/".$row_DelData['Course_Evaluation']."/".$row_DelData['Course_Photo']."/".$row_DelData['Course_Files']."/".$row_DelData['Course_Min']."/".$row_DelData['Course_Max']."/".$row_DelData['Course_Online']."/".$row_DelData['Course_OnSite']."/".	$row_DelData['Course_Reserve']."/".$row_DelData['Course_Private']."/".$row_DelData['Course_OnlineRemaining']."/".$row_DelData['Course_OnSiteRemaining']."/".$row_DelData['Course_ReserveRemaining']."/".$row_DelData['Course_Require']."/".$row_DelData['Course_Book']."/".$row_DelData['Course_Item']."/".$row_DelData['Course_Benefit']."/".$row_DelData['Course_Audit']."/".$row_DelData['Course_Pay']."/".$row_DelData['Course_Schedule']."/".	
	$row_DelData['Course_NOCount']."/".$row_DelData['Course_OnlineAdd']."/".$row_DelData['Course_OnSiteAdd']."/".$row_DelData['Course_ReserveAdd']."/".$row_DelData['Add_Time']."/".$row_DelData['Add_Account']."/".$row_DelData['Add_Unitname']."/".$row_DelData['Add_Username']."/".$row_DelData['Edit_Time']."/".$row_DelData['Edit_Account']."/".$row_DelData['Edit_Unitname']."/".$row_DelData['Edit_Username']."/講師:".$row_DelData['Teacher_UserName']."(".$row_DelData['Teacher_ID'].")是否保留名額:".$row_DelData['Course_IsReserve'];
			
		}
		
		$deleteSQL = sprintf("DELETE FROM teacher_record WHERE Course_ID=%s",
						   GetSQLValueString($_POST['ID'], "int"));
		$deleteSQL2 = sprintf("DELETE FROM course WHERE Course_ID=%s",
						   GetSQLValueString($_POST['ID'], "int"));
		$deleteSQL3 = sprintf("DELETE FROM course_pay WHERE Course_ID=%s",
						   GetSQLValueString($_POST['ID'], "int"));
	
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
		mysql_select_db($database_dbline, $dbline);
		$Result2 = mysql_query($deleteSQL2, $dbline) or die(mysql_error()); 
		mysql_select_db($database_dbline, $dbline);
		$Result3 = mysql_query($deleteSQL3, $dbline) or die(mysql_error()); 
		require_once('../../Include/Data_BrowseDel.php'); 
		mysql_free_result($DelData);
		mysql_free_result($DelData0); 
		mysql_free_result($DelData1); 
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
		header(sprintf("Location: %s", $updateGoTo));
		 
		}
	else{
		mysql_free_result($DelData0); 
		mysql_free_result($DelData1); 
		$updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelError";
		header(sprintf("Location: %s", $updateGoTo));
	}

}


?>


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>


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
        <td width="15%"><?php  require_once('../../Include/Menu_AdminLeft.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']?>管理：初審<?php if(isset($_GET['Checks']) && $_GET['Checks']==0){echo '未審區';}elseif(isset($_GET['Checks']) && $_GET['Checks']==1){echo '審核通過';}elseif(isset($_GET['Checks']) && $_GET['Checks']==2){echo '保留區';}?></div>
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
<?php if(@$_GET['Msg'] == "DelError"){ ?>
	<script language="javascript">
	function DelError(){
		$('.DelError').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(DelError,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "AddError"){ ?>
	<script language="javascript">
	function AddError(){
		$('.AddError').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddError,0);
    </script>
<?php } ?>


    <?php if($row_Permission['Per_View'] == 1){ ?>
    <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
     
      <?php if($row_PermissionCate['Per_View']==1){?>
      
      <input type="button" value="未審核" class="Button_Add" onClick="location.href='AD_Data_First.php?Checks=0'"/>
      <input type="button" value="審核通過" class="Button_Add" onClick="location.href='AD_Data_First.php?Checks=1'"/>
      <input type="button" value="保留區" class="Button_Add" onClick="location.href='AD_Data_First.php?Checks=2'"/>
      <input type="button" value="歷史課程" class="Button_Add" onClick="location.href='AD_Data_FirstH.php'"/>
      <?php }?></td>
      <td class="right"><img src="../../Icon/find.png" class="middle">      
      <select name="Season_Code" id="Season_Code" >
        <option value="">:::全部:::</option>
        <?php if($totalRows_Cate>0){
				  do { ?>
			<option value="<?php echo $row_Cate['Season_Code']; ?>" <?php if (isset($_GET['Season_Code'])&&$_GET['Season_Code'] == $row_Cate['Season_Code']) { echo "selected='selected'"; } ?>><?php if(substr($row_Cate['Season_Code'],-1,1)=="1"){echo substr_replace($row_Cate['Season_Code'],'春季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="2"){echo substr_replace($row_Cate['Season_Code'],'夏季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="3"){echo substr_replace($row_Cate['Season_Code'],'秋季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="4"){echo substr_replace($row_Cate['Season_Code'],'冬季班',-1);}  ?></option>
			<?php } while ($row_Cate = mysql_fetch_assoc($Cate));
		      } ?>
      </select>
      <select name="Unit_ID" id="Unit_ID" >
        <option value="">:::全部:::</option>
        <?php do { ?>
        <option value="<?php echo $row_Cate2['Unit_ID']; ?>" <?php if (isset($_GET['Unit_ID'])&&$_GET['Unit_ID'] == $row_Cate2['Unit_ID']) { echo "selected"; } ?>><?php echo $row_Cate2['Unit_Name'];?></option>
        <?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
      </select>
      
      	 <div class="display-inline">標題、類別:</div><input type="text" name="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Title" id="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Title" value="<?php echo @$_GET['Course_Title']; ?>" placeholder="請輸入標題/類別關鍵字"> <input type="submit" value="查詢" class="Button_General">
         <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General"></td>
      </tr>
    </table>
    </div>
    </form>
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg DelError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料刪除失敗，此課程已有學員報名</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
           <div class="Error_Msg AddError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料登錄失敗！</div>
          
      
      
        <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
          <tr class="TableBlock_shadow_Head_Back">
          	<td class="middle center" width="8%">班季</td>
            <td class="middle center" width="10%">分校</td>
            <td class="middle center" width="15%">名稱</td>
            <td class="middle center" width="15%">類別</td>
            <td class="middle center" width="8%">類型</td>
            <td class="middle center" width="8%">上課時間</td>
           
			<td class="middle center" width="10%">老師</td>
            <td class="middle center" width="8%">初審</td>
	    <td class="middle center" width="8%">複審</td>
	    <td class="middle center" width="8%">決審</td>
			
            <td class="middle center" width="22%">操作</td>
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
              	<td class="middle center">
				<?php echo $row_Data['Season_Year'].$row_Data['SeasonCate_Name'];?></td>
                <td class="middle center">
				 <?php echo $row_Data['Com_Name'].$row_Data['Unit_Name'];?>
                </td>
                <td class="middle Black">
                <a href="javascript:newin(900,700,'AD_Data_Detail.php?ID=<?php echo $row_Data['Course_ID'];?>')"><?php echo mb_substr($row_Data['Course_Name'],0,12,"utf8");if(mb_strlen($row_Data['Course_Name'],'utf-8')>12){echo '...';}?></a>
                </td>
                <td class="middle center MainColor">
                <?php echo $row_Data['CourseKind_Name'];?>
                </td>
                <td class="middle center MainColor">
                <?php if($row_Data['Course_Free']==1){echo '推廣';}elseif($row_Data['Course_Free']==2){echo '非正規教育認證課程';}else{echo '一般';}?>
                </td>
                <td class="middle center"><?php echo $row_Data['Course_Day1'].$row_Data['Course_Time'];echo date("H:i",strtotime($row_Data['Course_Start1']))."~".date("H:i",strtotime($row_Data['Course_End1'])); ?></td>
       			
                <td class="middle center"><?php echo str_replace(",",",<br/>",$row_Data['Teacher_UserName'])."&nbsp;"; ?></td>
                <td class="middle center">
		<?php if($row_Data['Course_Check1']==1){echo '通過';}elseif($row_Data['Course_Check1']==2){echo '不通過';}elseif($row_Data['Course_Check1']==3){echo '修正通過';}else{echo '待審';}?></td>
		<td class="middle center">
		<?php if($row_Data['Course_Check2']==1){echo '通過';}elseif($row_Data['Course_Check2']==2){echo '不通過';}elseif($row_Data['Course_Check2']==3){echo '修正通過';}else{echo '待審';}?></td>
		<td class="middle center">
		<?php if($row_Data['Course_Check3']==1){echo '通過';}elseif($row_Data['Course_Check3']==2){echo '不通過';}elseif($row_Data['Course_Check3']==3){echo '修正通過';}else{echo '待審';}?></td>
                 
                <td class="middle">
                
              
                  <form name="form_Del" id="form_Del" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>" class="center">
                    <?php if(($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Edit'] == 1&&$row_Permission['Per_Pass'] == 1)){ ?>
                    <input type="button" value="修改" class="Button_Edit" onClick="location.href='AD_Data_Edit.php?ID=<?php echo $row_Data['Course_ID']; ?>'"/>
                    <?php } ?>
                    <?php if(($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Edit'] == 1&&$row_Permission['Per_Pass'] == 1)){ ?>
                    <input type="button" value="名額管理" class="Button_Edit" onClick="location.href='AD_Data_Edit_t.php?ID=<?php echo $row_Data['Course_ID']; ?>&Season_Code=<?php if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $_GET['Season_Code'];}?>&Unit_ID=<?php if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $_GET['Unit_ID'];}?>&Course_Title=<?php if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){echo $_GET['Course_Title'];}?>&pageNum_Data=<?php if(isset($_GET['pageNum_Data'])&&$_GET['pageNum_Data']<>""){echo $_GET['pageNum_Data'];}?>'"/>
                    <?php } ?>
                    <?php if(($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Del'] == 1&&$row_Permission['Per_Pass'] == 1)){ ?>
                    <input type="submit" name="button2" id="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n<?php echo  $row_Data['Course_Name']; ?>\n刪除後資料無法復原,確定要刪除嗎?'))">
                    <?php } ?>
                    <input type="hidden" name="Del" value="form_del">
                    <input type="hidden" name="ID" id="ID" value="<?php echo $row_Data['Course_ID']; ?>">
                    <input type="hidden" name="Title" id="Title" value="<?php echo $row_Data['Course_Name']; ?>">
                  </form>
              
                </td>
              </tr>
              <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
            <?php } // Show if recordset not empty ?>
        </table>
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
                    	<?php 
						if (isset($_GET['Unit_ID'])) {  ?><input name="Unit_ID" type="hidden" value="<?php echo $_GET['Unit_ID']; ?>"/><?php }  ?>
                    	<?php if (isset($_GET['Course_Title'])) {  ?><input name="Course_Title" type="hidden" value="<?php echo $_GET['Course_Title']; ?>"/><?php }  ?>
                        <?php if (isset($_GET['Season_Code'])) {  ?><input name="Season_Code" type="hidden" value="<?php echo $_GET['Season_Code']; ?>"/><?php }  ?>
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
      </div>
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
      <?php } ?>
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
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_PermissionCate.php'); ?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>