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


//顯示資料多寡OP
if ((isset($_GET['Com_ID'])) && ($_GET['Com_ID'] != "") && $row_AdminMember['Unit_Range']>=3) {
	$colname03_Unit=$_GET['Com_ID'];
}
else{
	$colname03_Unit=$colname03_Unit;
	}//顯示資料多寡END
	
mysql_select_db($database_dbline, $dbline);

	$query_Data = sprintf("SELECT * FROM member_mailing WHERE   ifnull(Com_ID,'') like %s order by Com_ID ASC, HEX(CONVERT(Member_UserName using big5))",GetSQLValueString($colname03_Unit, "text"));



$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);




mysql_select_db($database_dbline, $dbline);
 if ($row_AdminMember['Unit_Range']>=3) {
	 $query_Cate = sprintf("SELECT * FROM community where Com_Enable=1 and Com_ID<>4 and Com_IsPrivate <> 1 order by Com_ID");	 
	 }
 else{
	 $query_Cate = sprintf("SELECT * FROM community where Com_Enable=1 and Com_ID<>4 and Com_IsPrivate <> 1 and Com_ID like %s order by Com_ID",GetSQLValueString($colname03_Unit, "text"));
}
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


?>

<?php //require_once('../../Include/Html_Top_Common.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_WebSetting['WebSetting_Title']; ?><?php if(@$SystemName <> ""){echo ":::".$SystemName.":::";} ?></title>
<meta name="keywords" content="<?php echo $row_WebSetting['WebSetting_Keyword']; ?>">
<meta name="description" content="<?php echo $row_WebSetting['WebSetting_Description']; ?>">
<!--<link rel="icon" href="../../Theme/<?php echo $row_WebSetting['SkinMain_Code']; ?>/image/<?php echo $row_WebSetting['SkinMain_favicon_ico']; ?>" type="image/x-icon" />
<link rel="shortcut icon" href="../../Theme/<?php echo $row_WebSetting['SkinMain_favicon_ico']; ?>" type="image/x-icon" />-->
<link href="../../Css/Style.css" rel="stylesheet" type="text/css">
<script src="../../Theme/<?php echo $row_WebSetting['SkinMain_Code']; ?>/JS/jquery-latest.js"></script>
<!--[if IE]>
    <script src = "../../Js/fixIePlaceholder.js"></script>
<![endif]-->


<style type="text/css">
#JustPrint {display:none}
body{ font-size:10px; background-color:#FFFFFF;}
#smallsize{ font-size:10px;}
@media print {


#NoPrint,#NoPrint1 {display:none}
#JustPrint { display:block; font:8pt verdana; letter-spacing:2px;}
#smallsize{ font-size:10px;}
}
.PrintTable{ border-collapse:collapse; }
.PrintTable td,PrintTable th{ border:1px solid #333; font-family:'微軟正黑體',Verdana, Geneva, sans-serif; font-weight:100;}
</style>

</head>
<body>

<div>   
   
<div align="center" class="PrintTitle"><?php echo $row_ModuleSet['ModuleSetting_Title']?>郵寄清單<?php if(isset($_GET['Com_ID']) && $_GET['Com_ID']<>""){echo '_'.$row_Data['Com_Name'];}?></div>
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
      <td class="center"><img src="../../Icon/find.png" class="middle">      
      搜尋：<select name="Com_ID" id="Com_ID" >
        <option value="">:::全部:::</option>
        <?php do { ?>
        <option value="<?php echo $row_Cate['Com_ID']; ?>" <?php if (@$_GET['Com_ID'] == $row_Cate['Com_ID']) { echo "selected='selected'"; } ?>><?php echo $row_Cate['Com_Name']; ?></option>
        <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
      </select>
      <input type="submit" value="查詢" class="Button_General">
      <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General"></td>
      </tr>
    </table>
    </div>
    </form>
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
          
      
      
        <table width="95%" border="0" cellpadding="5" cellspacing="0" class="PrintTable" > 
          <tr >
            <td class="middle center" width="5%">序號</td>
            <td class="middle center" width="35%">姓名</td>
            <td class="middle center" width="60%">郵遞住址</td>            
          </tr>
           <?php if ($totalRows_Data > 0) { $a=0;// Show if recordset not empty    ?>
			<?php do {$a++; ?>
              <tr>
              	
                
                <td class="center middle Black">
                <?php echo $a; ?></td>
                <td class="center middle Black">
                <?php echo $row_Data['Member_UserName']; ?></td>
               
                
                <td class="middle  MainColor"><?php echo '['.$row_Data['Postal_Code'].']'.$row_Data['County_Cate'].$row_Data['County_Name'].$row_Data['Member_Address']; ?></td>
                
                 
               
              </tr>
              <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
            <?php } // Show if recordset not empty ?>
        </table>
         
      </div>
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
      <?php } ?>
   
    <br><br><br>
</div>      
</body>
</html>
<?php
mysql_free_result($Data);
mysql_free_result($Cate);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>