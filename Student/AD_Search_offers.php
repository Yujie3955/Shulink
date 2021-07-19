<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php 
$modulename=explode("_",basename(__FILE__, ".php"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);
/*權限*/
$query_ModuleSet = "SELECT * FROM module_setting WHERE ModuleSetting_Name ='Offers' and ModuleSetting_Code='Student'";
$ModuleSet = mysql_query($query_ModuleSet, $dbline) or die(mysql_error());
$row_ModuleSet = mysql_fetch_assoc($ModuleSet);
$totalRows_ModuleSet = mysql_num_rows($ModuleSet);
?>
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
if ((isset($_GET['Season_Code'])) && ($_GET['Season_Code'] != "")) {
$colname_Data2=$_GET['Season_Code'];
}
$colname_Data3="%";
if ((isset($_GET['Com_ID'])) && ($_GET['Com_ID'] != "")) {
$colname_Data3=$_GET['Com_ID'];
}



$query_Cate = sprintf("SELECT Member_ID,Com_ID FROM member where Member_Identity = %s ",GetSQLValueString($row_AdminMember['Member_Identity'], "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
$Member_IDS=' (';
$xx=0;
if($totalRows_Cate>0){
	do{$xx++;
		if($xx==$totalRows_Cate){
			$Member_IDS.=" (Member_ID = ".$row_Cate['Member_ID'].") ";
			
		}else{
			$Member_IDS.=" (Member_ID = ".$row_Cate['Member_ID'].") or ";
			
		}
	}while($row_Cate = mysql_fetch_assoc($Cate));
}
else{
	$Member_IDS.=" (Member_ID = -1) ";
	
}
$Member_IDS.=' )';


$query_Season = sprintf("SELECT Season_Year,SeasonCate_Name,Season_Code FROM offers_list where $Member_IDS Group by offers_list.Season_Code order by offers_list.Season_Code DESC,Com_ID ASC");
$Season = mysql_query($query_Season, $dbline) or die(mysql_error());
$row_Season = mysql_fetch_assoc($Season);
$totalRows_Season = mysql_num_rows($Season);

$query_Com = sprintf("SELECT Com_ID,Com_Name FROM offers_list where $Member_IDS and ifnull(Season_Code,'') like %s Group by Com_ID order by Com_ID ASC",GetSQLValueString($colname_Data2, "text"));
$Com = mysql_query($query_Com, $dbline) or die(mysql_error());
$row_Com = mysql_fetch_assoc($Com);
$totalRows_Com = mysql_num_rows($Com);

	
mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM offers_list where $Member_IDS and ifnull(Com_ID,'') Like %s and ifnull(Season_Code,'') Like %s order by offers_list.Season_Code DESC,Com_ID ASC",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($colname_Data2, "text"));
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
        <td width="19%"><?php require_once('../../Include/Menu_AdminLeft_Student.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']?>查詢</div>
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
      <td>      
      </td>
      <td class="right"><img src="../../Icon/find.png" class="middle">    
      班季：<select name="Season_Code" id="Season_Code" >
        <option value="">:::全部:::</option>
        <?php if($totalRows_Season>0){
			     do { ?>
        <option value="<?php echo $row_Season['Season_Code']; ?>" <?php if (isset($_GET['Season_Code'])&&$_GET['Season_Code'] == $row_Season['Season_Code']) { echo "selected='selected'"; } ?>><?php echo $row_Season['Season_Year'].'年'.$row_Season['SeasonCate_Name']; ?></option>
        <?php 	 }while ($row_Season = mysql_fetch_assoc($Season));
		      }
		      mysql_free_result($Season);  ?>
      </select>
     社區大學:<select name="Com_ID" id="Com_ID" >
        <option value="">:::全部:::</option>
        <?php if($totalRows_Com>0){
			     do { ?>
        <option value="<?php echo $row_Com['Com_ID']; ?>" <?php if (isset($_GET['Com_ID'])&&$_GET['Com_ID'] == $row_Com['Com_ID']) { echo "selected='selected'"; } ?>><?php echo $row_Com['Com_Name']; ?></option>
        <?php } while ($row_Com = mysql_fetch_assoc($Com));
		        
			  }
		      mysql_free_result($Com);  ?>
      </select>
     <input type="submit" value="查詢" class="Button_General">
     <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General">
      </td>
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
            <td class="middle center" width="15%">班季</td>
          	<td class="middle center" width="15%">社區大學</td>
            <td class="middle center" width="5%">ID</td>           
            <td class="middle center" width="15%">姓名</td>
            <td class="middle center" width="10%">優惠額度</td>
            <td class="middle" width="20%">優惠原因</td>  
            <td class="middle" width="20%">使用狀況</td>           		
           
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
                <td class="middle center"><?php echo $row_Data['Season_Year'].'年'.$row_Data['SeasonCate_Name']; ?></td>
              	<td class="middle center"><?php echo $row_Data['Com_Name']; ?></td>
                <td class="center middle Black">
                <?php echo $row_Data['Member_ID']; ?>
                </td>
                
                <td class="center middle Black">
                <?php echo $row_Data['Member_UserName']; ?></td>
                
                <td class="center middle">
                <?php echo $row_Data['Offers_Money']; ?></td>
                <td class="middle"><?php echo $row_Data['Offers_Reason']; ?></td>
                <td class="middle">
                <?php if($row_Data['Offers_Money']>0){if($row_Data['Course_ID']==""&&$row_Data['SignupItem_ID']==""&&$row_Data['SignupRecord_ID']==""){echo '未使用';}else{echo '已使用';}}?>
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

        </td>
      </tr>
    </table>
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