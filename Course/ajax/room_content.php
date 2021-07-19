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
if(isset($_GET['LID']) && $_GET['LID']<>""){
	$colname_ID=$_GET['LID'];
}
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM room_detail WHERE Com_ID Like %s and Unit_ID Like %s and Loc_ID = %s and Room_Name like %s ORDER BY Com_ID ASC,Loc_ID ASC", GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname02_Unit, "text"), GetSQLValueString($colname_ID, "int"), GetSQLValueString($colname_Data, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$query_Cate2 = sprintf("SELECT * FROM credit2 WHERE Com_ID Like %s and Credit2_Enable=1 ORDER BY Com_ID ASC,Credit2_Sort ASC", GetSQLValueString($row_Cate['Com_ID'], "text"));
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

$modulename=array();
$modulename[1]="Room";
$Code="Course";

$query_Permission = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString($modulename[1], "text"));
$Permission = mysql_query($query_Permission, $dbline) or die(mysql_error());
$row_Permission = mysql_fetch_assoc($Permission);
$totalRows_Permission= mysql_num_rows($Permission);


require_once('../module_setting.php');
?>

<div class="div_table" style="width:100%;"> 
          <div class="TableBlock_shadow_Head_Back div_tr">
           <div class="middle center div_td">編號</div>
            <div class="middle div_td"><?php echo $row_ModuleSet['ModuleSetting_SubName'];?>名稱</div>
            <div class="middle div_td">雜費</div>
	    <div class="middle div_td">排序</div>
	    <div class="middle div_td">人數</div>
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
                <div class="middle center div_td <?php echo $str_bg;?>"><?php echo $row_Cate['Room_ID']; ?></div>
               
                <div class="middle div_td <?php echo $str_bg;?>"><input name="Title" type="text" id="Title" size="6" value="<?php echo $row_Cate['Room_Name']; ?>" style="width:300px;"></div>
		<div class="middle div_td <?php echo $str_bg;?>"><select name="Credit2_Area" id="Credit2_Area<?php echo $row_Cate['Room_ID'];?>" required onchange="Credit2_Check('<?php echo $row_Cate['Room_ID'];?>')"><option value="<?php echo $row_Cate['Credit2_ID'].';'.$row_Cate['Credit2_Money'];?>"><?php echo $row_Cate['Credit2_Name']?></option><?php if($totalRows_Cate2>0){do{?><option value="<?php echo $row_Cate2['Credit2_ID'].';'.$row_Cate2['Credit2_Money'];?>"><?php echo $row_Cate2['Credit2_Name']?></option><?php }while($row_Cate2=mysql_fetch_assoc($Cate2));mysql_data_seek($Cate2, 0);$row_Cate2 = mysql_fetch_assoc($Cate2);}?></select>
		<input name="Credit2_ID" type="hidden" id="Credit2_ID<?php echo $row_Cate['Room_ID'];?>" value="<?php echo $row_Cate['Credit2_ID'];?>">
		<input name="Credit2_Name" type="hidden" id="Credit2_Name<?php echo $row_Cate['Room_ID'];?>" value="<?php echo $row_Cate['Credit2_Name'];?>">
		<input name="Credit2_Money" type="hidden" id="Credit2_Money<?php echo $row_Cate['Room_ID'];?>" value="<?php echo $row_Cate['Credit2_Money'];?>">
		</div>
                <div class="middle div_td <?php echo $str_bg;?>"><input name="Room_Sort" type="number" id="Room_Sort" style="width:75px;"  value="<?php echo $row_Cate['Room_Sort'];?>" ></div>
                <div class="middle div_td <?php echo $str_bg;?>">上限:<input name="Room_Max" type="number" id="Room_Max" style="width:75px;"  value="<?php echo $row_Cate['Room_Max'];?>" ><br/>下限:<input name="Room_Min" type="number" id="Room_Min" style="width:75px;"  value="<?php echo $row_Cate['Room_Min'];?>" ></div>
                <div class="middle div_td <?php echo $str_bg;?>"><input name="Room_Enable" type="checkbox" id="Room_Enable" size="5"  <?php if($row_Cate['Room_Enable']=="1"){?> checked="checked"<?php }?> ></div>
                <?php if ($row_AdminMember['Unit_Range'] >= 2) { ?>
                <div class="middle center div_td <?php echo $str_bg;?>"><?php if($row_Cate['Edit_Unitname']){echo $row_Cate['Edit_Unitname'];}else{echo $row_Cate['Add_Unitname'];} ?></div>
                <div class="middle center div_td <?php echo $str_bg;?>"><?php if($row_Cate['Edit_Username']){echo $row_Cate['Edit_Username'];}else{echo $row_Cate['Add_Username'];} ?></div>
                <?php } ?>
                <div class="middle div_td <?php echo $str_bg;?>">
                <?php if($row_Permission['Per_Edit'] == 1){ ?>
                    <input name="button1" type="submit" value="更新" class="Button_Edit"/>
                <?php } ?>           
                <input type="hidden" name="MM_update" value="Form_Edit" />
                <input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['Room_ID']; ?>">   
                <input type="hidden" name="LID" id="LID" value="<?php echo $row_Cate['Loc_ID']; ?>">  
                <input type="hidden" name="UID" id="UID" value="<?php echo $row_Cate['Unit_ID']; ?>">                    
                <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
                <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
                <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">           
                <?php if($row_Permission['Per_Del'] == 1){ ?>
                    <input type="submit" name="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n<?php echo $row_Cate['Room_Name']; ?>\n刪除後資料無法復原,確定要刪除嗎?'))">
		    <input name="Del_Title" type="hidden" id="Del_Title" value="<?php echo $row_Cate['Room_Name']; ?>">
                 <?php } ?> 
		
                </div>
                </form>
              <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
            <?php } // Show if recordset not empty ?>
</div>
<?php 
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../zz_module_setting.php'); ?>
<?php require_once('../zz_Admin_Permission.php'); ?>
