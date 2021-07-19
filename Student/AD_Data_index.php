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


$colname_Data2="%";
if ((isset($_GET['Member_UserName'])) && ($_GET['Member_UserName'] != "")) {
$colname_Data2="%".$_GET['Member_UserName']."%";
}

//顯示資料多寡OP
if ((isset($_GET['Com_ID'])) && ($_GET['Com_ID'] != "")&&$row_AdminMember['Unit_Range']>=3) {
	$colname03_Unit=$_GET['Com_ID'];
}
else{
	$colname03_Unit=$colname03_Unit;
	}//顯示資料多寡END
	
mysql_select_db($database_dbline, $dbline);
if(isset($_GET['orders']) && preg_match("/年齡/i",$_GET['orders']) ){
	if(preg_match("/遞增/i",$_GET['orders'])){$orders='desc';}
	else{$orders="asc";}
	$query_Data = sprintf("SELECT * FROM member_list WHERE  Member_Show=1 and ifnull(Com_ID,'') like %s and (Member_Username like %s or Member_Identity Like %s) and Member_Audit=1 order by Member_Birthday ".$orders.", HEX(CONVERT(Member_UserName using big5)) asc",GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname_Data2, "text"),GetSQLValueString($colname_Data2, "text"));
}
else{
	$query_Data = sprintf("SELECT * FROM member_list WHERE  Member_Show=1 and ifnull(Com_ID,'') like %s and (Member_Username like %s or Member_Identity Like %s) and Member_Audit=1 order by Com_ID ASC,Member_ID DESC",GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname_Data2, "text"),GetSQLValueString($colname_Data2, "text"));
}

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

$query_ComName = sprintf("SELECT Com_Name, Com_ID FROM community order by Com_ID asc");
$ComName = mysql_query($query_ComName, $dbline) or die(mysql_error());
$row_ComName = mysql_fetch_assoc($ComName);
$totalRows_ComName = mysql_num_rows($ComName);
$Com_List=array();
if($totalRows_ComName>0){
   do{
	   if(!isset($Com_List[$row_ComName['Com_ID']])){
		   $Com_List[$row_ComName['Com_ID']]='';
		   $Com_List[$row_ComName['Com_ID']]=$row_ComName['Com_Name'];
	   }
   }while($row_ComName = mysql_fetch_assoc($ComName));	
}
mysql_free_result($ComName);


mysql_select_db($database_dbline, $dbline);
 if ($row_AdminMember['Unit_Range']>=3) {
$query_Cate = sprintf("SELECT * FROM community where Com_Enable=1 and Com_ID<>4 and Com_IsPrivate <> 1 order by Com_ID");	 
	 }
 else{
$query_Cate = sprintf("SELECT * FROM community where Com_Enable=1 and Com_ID<>4 and Com_IsPrivate <> 1 and Com_ID like %s order by Com_ID",GetSQLValueString($colname03_Unit, "text"));}
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$query_AreaData = sprintf("SELECT * FROM area");
$AreaData = mysql_query($query_AreaData, $dbline) or die(mysql_error());
$row_AreaData = mysql_fetch_assoc($AreaData);
$totalRows_AreaData = mysql_num_rows($AreaData);
$area_list=array();
if($totalRows_AreaData>0){
	do{
		if(!isset($area_list[$row_AreaData['County_ID']])){
			$area_list[$row_AreaData['County_ID']]='';
		}
		$area_list[$row_AreaData['County_ID']]=$row_AreaData['County_Name'];
	}while($row_AreaData = mysql_fetch_assoc($AreaData));

}mysql_free_result($AreaData);

if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
/*查詢刪除資料*/
if($_POST['button2']=="啟用"){
    $Other = "啟用".$row_Permission['ModuleSetting_Title']; }
else{
	$Other = "停用".$row_Permission['ModuleSetting_Title']; }
	
  require_once('../../Include/Data_BrowseDel.php');
/*刪除*/
if($_POST['button2']=="啟用"){
     $deleteSQL = sprintf("Update member set Member_Enable=1 WHERE Member_ID=%s",
                       GetSQLValueString($_POST['ID'], "int"));
}
else{
	 $deleteSQL = sprintf("Update member set Member_Enable=0 WHERE Member_ID=%s",
                       GetSQLValueString($_POST['ID'], "int"));
					   }
					   
  mysql_select_db($database_dbline, $dbline);
  $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
  $updateGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK";
  header(sprintf("Location: %s", $updateGoTo));
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
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']?>管理</div>
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


    <?php if($row_Permission['Per_View'] == 1){ ?>
    <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
      <?php if($row_Permission['Per_Add'] == 1){ ?>
      <input type="button" value="新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?>" class="Button_Add" onClick="location.href='AD_Data_Add.php'"/>
 <?php } ?>
      <?php if($row_Permission['Per_View']==1){?>
      <input type="button" value="審核未過" class="Button_Add" onClick="location.href='AD_Data_Check.php'"/>
      <input type="button" value="資料未完整" class="Button_Add" onClick="location.href='AD_Data_Noalready.php'"/>
      <?php /*<input type="button" value="郵寄清單" class="Button_Add" onClick="window.open('AD_Data_Mail.php')"/>*/?>
      <?php }?>
    </td>
      <td class="right"><img src="../../Icon/find.png" class="middle">      
      <select name="Com_ID" id="Com_ID" >
        <option value="">:::全部:::</option>
        <?php do { ?>
        <option value="<?php echo $row_Cate['Com_ID']; ?>" <?php if (@$_GET['Com_ID'] == $row_Cate['Com_ID']) { echo "selected='selected'"; } ?>><?php echo mb_substr($row_Cate['Com_Name'],0,3,"utf-8") ?></option>
        <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
      </select>
      <select name="orders" id="orders" >
        <option value="">:::年齡排序:::</option>
        <option value="年齡遞增" <?php if (isset($_GET['orders'])&&$_GET['orders'] == "年齡遞增") { echo "selected='selected'"; } ?>>年齡遞增</option>
        <option value="年齡遞減" <?php if (isset($_GET['orders'])&&$_GET['orders'] == "年齡遞減") { echo "selected='selected'"; } ?>>年齡遞減</option>
      </select>
      <div class="display-inline">姓名、身分證:</div><input type="text" name="Member_UserName" id="Member_UserName" value="<?php echo @$_GET['Member_UserName']; ?>" placeholder="請輸入姓名、身分證關鍵字"> <input type="submit" value="查詢" class="Button_General">
         <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General"></td>
      </tr>
    </table>
    </div>
    </form>
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
          
      
      
        <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
          <tr class="TableBlock_shadow_Head_Back">
          	<td class="middle center" width="15%">社區大學</td>
            <td class="middle center" width="5%">ID</td>     
            <td class="middle center" width="5%">學號</td>          
            <td class="middle center" width="15%">姓名</td>
            <td class="middle center" width="10%">身分證</td>            
            <td class="middle center" width="10%">身分別</td>          
            <td class="middle center" width="10%">生日</td>  	
            <td class="middle center" width="20%">電話</td>        
            <td class="middle center" width="10%">鄉鎮市區</td>	   
            <td class="middle center" width="10%">EMAIL</td>		
            <td class="middle center" width="8%">年齡</td>	
            <td class="middle center" width="8%">啟用</td>		
            <td class="middle center" width="20%">操作</td>
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
//年齡
function birthday($birthday){ 
$age = strtotime($birthday); 
if($age === false){ 
return false; 
} 
list($y1,$m1,$d1) = explode("-",date("Y-m-d",$age)); 
$now = strtotime("now"); 
list($y2,$m2,$d2) = explode("-",date("Y-m-d",$now)); 
$age = $y2 - $y1; 
if((int)($m2.$d2) < (int)($m1.$d1)) 
$age -= 1; 
return $age; 
} 

?>
		 
			<?php do { ?>
              <tr>
              	<td class="middle center"><?php if(isset($Com_List[$row_Data['Com_ID']])){echo $Com_List[$row_Data['Com_ID']];} ?></td>
                <td class="center middle Black">
                <?php echo $row_Data['Member_ID']; ?>
                </td>
                <td class="center middle Black">
                <?php echo $row_Data['Member_No']; ?>
                </td>
                
                <td class="center middle Black">
                <?php echo $row_Data['Member_UserName']; ?></td>
                <td class="center middle">
                <?php echo $row_Data['Member_Identity']; ?></td>
                
                <td class="middle center MainColor"><?php echo $row_Data['Member_Type']; ?></td>
                <td class="middle center MainColor"><?php echo $row_Data['Member_Birthday']; ?></td>
                <td class="middle center MainColor"><?php if($row_Data['Member_Tel']<>""){echo $row_Data['Member_Tel'];} if($row_Data['Member_Phone']<>"" && $row_Data['Member_Tel']<>""){echo "、".$row_Data['Member_Phone'];}elseif($row_Data['Member_Tel']==""){echo $row_Data['Member_Phone'];} ?></td>
                <td class="middle center MainColor"><?php if(isset($area_list[$row_Data['County_ID']]) && $area_list[$row_Data['County_ID']]<>""){echo $area_list[$row_Data['County_ID']];} ?></td>
		<td class="middle center MainColor"><?php echo $row_Data['Member_Email']; ?></td>
                <td class="middle center MainColor"><?php if($row_Data['Member_Birthday']<>""){echo birthday($row_Data['Member_Birthday']);} ?></td>
                <td class="middle center" ><img src="../../Icon/<?php echo $row_Data['Member_Enable'];?>.png" /></td>
                
                 
                <td class="middle">
                
              
                  <form name="form_Del" id="form_Del" method="POST" action="<?php echo @$_SERVER["PHP_SELF"];?>" class="center">
                    <?php if(($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Edit'] == 1)||($row_Permission['Per_Edit'] == 1&&$row_AdminMember['Account_Account']==$row_Data['Add_Account'])){ ?>
                    <input type="button" value="修改" class="Button_Edit" onClick="location.href='AD_Data_Edit.php?ID=<?php echo $row_Data['Member_ID']; ?><?php if(isset($_GET['Com_ID']) && $_GET['Com_ID']<>""){echo '&SCom='.$_GET['Com_ID']; }if(isset($_GET['orders']) && $_GET['orders']<>""){echo '&SOrders='.$_GET['orders']; }if(isset($_GET['Member_UserName']) && $_GET['Member_UserName']<>""){echo '&MUser='.$_GET['Member_UserName'];}if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo '&Pages='.$_GET['pageNum_Data'];}?>'"/>
                    <?php } ?>
                    <?php if(($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Del'] == 1)||($row_Permission['Per_Del'] == 1&&$row_AdminMember['Account_Account']==$row_Data['Add_Account'])){ ?>
                    <input type="submit" name="button2" id="button2" value="<?php if($row_Data['Member_Enable']==1){echo '停用';}else{echo '啟用';} ?>"  class="Button_Del" onClick="return(confirm('您即將停用以下資料\n<?php echo "[".mb_substr($row_Data['Com_Name'],0,5,"utf-8").":".$row_Data['Member_UserName']."]"; ?>?'))">
                    <?php } ?>
                    <input type="button" value="報名" class="Button_Edit" onClick="location.href='../Sign/AD_Data_Add2.php?ids=<?php echo $row_Data['Member_Identity']; ?>&Com=<?php echo $row_Data['Com_ID']; ?>&p=s<?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo '&pageNum_Data='. $_GET['pageNum_Data'];}if(isset($_GET['totalRows_Data']) && $_GET['totalRows_Data']<>""){echo '&totalRows_Data='. $_GET['totalRows_Data'];}if(isset($_GET['Com_ID']) && $_GET['Com_ID']<>""){echo '&PComID='. $_GET['Com_ID'];}if(isset($_GET['orders']) && $_GET['orders']<>""){echo '&orders='. $_GET['orders'];}if(isset($_GET['Member_UserName']) && $_GET['Member_UserName']<>""){echo '&Member_UserName='. $_GET['Member_UserName'];}?>'"/>
                    <input type="button" value="學員資料" class="Button_Edit" onClick="location.href='AD_Data_Detail.php?ID=<?php echo $row_Data['Member_ID'];?>&Com_ID=<?php if(isset($_GET['Com_ID']) && $_GET['Com_ID']<>""){echo $_GET['Com_ID'];}?><?php if(isset($_GET['orders']) && $_GET['orders']<>""){echo "&orders=".$_GET['orders'];}?><?php if(isset($_GET['Member_UserName']) && $_GET['Member_UserName']<>""){echo "&Member_UserName=".$_GET['Member_UserName'];}?><?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo "&pageNum_Data=".$_GET['pageNum_Data'];}?>'"/>
                    <input type="hidden" name="Del" value="form_del">
                    <input type="hidden" name="ID" id="ID" value="<?php echo $row_Data['Member_ID']; ?>">
                    
                    <input type="hidden" name="Title" id="Title" value="<?php echo $row_Data['Member_UserName']; ?>">
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
                    	<?php if (isset($_GET['Com_ID'])) {  ?><input name="Com_ID" type="hidden" value="<?php echo $_GET['Com_ID']; ?>"/><?php }  ?>
                    	<?php if (isset($_GET['Member_UserName'])) {  ?><input name="Member_UserName" type="hidden" value="<?php echo $_GET['Member_UserName']; ?>"/><?php }  ?>
                        <?php if (isset($_GET['orders'])) {  ?><input name="orders" type="hidden" value="<?php echo $_GET['orders']; ?>"/><?php }  ?>
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
	</center>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php
mysql_free_result($Data);
mysql_free_result($Cate);
?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>