<?php require_once('../../../Connections/dbline.php');?>
<?php require_once('../../../Include/web_set.php'); ?>
<?php require_once('../../../Include/DB_Admin.php'); ?>
<?php require_once('../../../Include/Permission.php'); ?>

<?php
header("Content-Type: text/html; charset=UTF-8");
require_once("../../../Include/ComUnit_Self.php");

$colname_Data="%";
if(isset($_GET['T']) && $_GET['T']<>""){
$colname_Data="%".$_GET['T']."%";
}
$colname_ID="-1";
if(isset($_GET['UID']) && $_GET['UID']<>""){
	$colname_ID=$_GET['UID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM location_detail WHERE Com_ID Like %s and Unit_ID Like %s and Unit_ID = %s and Loc_Name like %s ORDER BY Com_ID ASC,Loc_ID ASC", GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname02_Unit, "text"), GetSQLValueString($colname_ID, "int"), GetSQLValueString($colname_Data, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);



$modulename=array();
$modulename[1]="Area";
$Code="Course";

$query_Permission = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString($modulename[1], "text"));
$Permission = mysql_query($query_Permission, $dbline) or die(mysql_error());
$row_Permission = mysql_fetch_assoc($Permission);
$totalRows_Permission= mysql_num_rows($Permission);

$query_Permission2 = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString("Room", "text"));
$Permission2 = mysql_query($query_Permission2, $dbline) or die(mysql_error());
$row_Permission2 = mysql_fetch_assoc($Permission2);
$totalRows_Permission2= mysql_num_rows($Permission2);
$Room_View=$row_Permission2['Per_View'];
mysql_free_result($Permission2);

require_once('../module_setting.php');
?>

<div class="div_table" style="width:100%;"> 
          <div class="TableBlock_shadow_Head_Back div_tr">
           <div class="middle center div_td">編號</div>
            <div class="middle div_td">社區大學&學習中心</div>
            <div class="middle div_td"><?php echo $row_ModuleSet['ModuleSetting_SubName'];?>名稱</div>
          
            <div class="middle div_td">啟用</div>
           
            <?php if ($row_AdminMember['Unit_Range'] >= 2 ) { ?>
	    <div class="middle center div_td">發布組別</div>
            <div class="middle center div_td">發布人</div>
	    <?php } ?>
            <div class="middle div_td">操作</div>
            </div>
           <?php if ($totalRows_Cate > 0) { $a=0;// Show if recordset not empty ?>
	   <?php do { $a++;if($a%2==1){$str_bg='table-grey';}else{$str_bg='';}?>
              <form name="Form_Edit" id="Form_Edit" method="POST" class="div_tr" >
                <div class="middle center div_td <?php echo $str_bg;?>"><?php echo $row_Cate['Loc_ID']; ?></div>
                <div class="middle div_td <?php echo $str_bg;?>">				
                  <?php echo mb_substr(substr($row_Cate['Com_Name'],0,strpos($row_Cate['Com_Name'],"社區")),0,5,"utf-8");echo $row_Cate['Unit_Name']; ?>
                </div>
                <div class="middle div_td <?php echo $str_bg;?>"><input name="Title" type="text" id="Title" size="6" value="<?php echo $row_Cate['Loc_Name']; ?>" style="width:300px;"></div>
                <div class="middle div_td <?php echo $str_bg;?>"><input name="Loc_Enable" type="checkbox" id="Loc_Enable" size="5"  <?php if($row_Cate['Loc_Enable']=="1"){?> checked="checked"<?php }?> ></div>
                <?php if ($row_AdminMember['Unit_Range'] >= 2) { ?>
                <div class="middle center div_td <?php echo $str_bg;?>"><?php if($row_Cate['Edit_Unitname']){echo $row_Cate['Edit_Unitname'];}else{echo $row_Cate['Add_Unitname'];} ?></div>
                <div class="middle center div_td <?php echo $str_bg;?>"><?php if($row_Cate['Edit_Username']){echo $row_Cate['Edit_Username'];}else{echo $row_Cate['Add_Username'];} ?></div>
                <?php } ?>
                <div class="middle div_td <?php echo $str_bg;?>">
                <?php if($row_Permission['Per_Edit'] == 1){ ?>
                    <input name="button1" type="submit" value="更新" class="Button_Edit"/>
                <?php } ?>           
                <input type="hidden" name="MM_update" value="Form_Edit" />
                <input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['Loc_ID']; ?>">   
                <input type="hidden" name="UID" id="UID" value="<?php echo $row_Cate['Unit_ID']; ?>">                    
                <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
                <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
                <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">           
                <?php if($row_Permission['Per_Del'] == 1){ ?>
                    <input type="submit" name="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n<?php echo $row_Cate['Loc_Name']; ?>\n刪除後資料無法復原,確定要刪除嗎?'))">
		    <input name="Del_Title" type="hidden" id="Del_Title" value="<?php echo $row_Cate['Loc_Name']; ?>">
                 <?php } ?> 
		<?php if($Room_View==1){?>
                <input type="button" value="教室管理" class="Button_Edit" onclick="location.href='AD_Room_Index.php?UID=<?php echo $row_Cate['Unit_ID'];?>&LID=<?php echo $row_Cate['Loc_ID'];?>'"/>
		<?php }?>
                </div>
                </form>
              <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
            <?php } // Show if recordset not empty ?>
</div>
<?php 
mysql_free_result($Cate);
?>
<?php require_once('../../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../zz_module_setting.php'); ?>
<?php require_once('../zz_Admin_Permission.php'); ?>
