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
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM unit_detail WHERE Com_ID Like %s and Unit_ID Like %s and Unit_IsSchool=1 and Unit_Name like %s ORDER BY Com_ID ASC,Unit_ID ASC", GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname02_Unit, "text"), GetSQLValueString($colname_Data, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$query_County = "SELECT * FROM County Group by County_Cate order by County_ID ASC";
$County = mysql_query($query_County, $dbline) or die(mysql_error());
$row_County = mysql_fetch_assoc($County);
$totalRows_County = mysql_num_rows($County);

$query_Area3 = sprintf("SELECT * FROM area order by County_ID ASC");
$Area3 = mysql_query($query_Area3, $dbline) or die(mysql_error());
$row_Area3 = mysql_fetch_assoc($Area3);
$totalRows_Area3 = mysql_num_rows($Area3);


$modulename=array();
$modulename[1]="Unit";
$Code="Course";

$query_Permission = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString($modulename[1], "text"));
$Permission = mysql_query($query_Permission, $dbline) or die(mysql_error());
$row_Permission = mysql_fetch_assoc($Permission);
$totalRows_Permission= mysql_num_rows($Permission);

$query_Permission2 = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString("Area", "text"));
$Permission2 = mysql_query($query_Permission2, $dbline) or die(mysql_error());
$row_Permission2 = mysql_fetch_assoc($Permission2);
$totalRows_Permission2= mysql_num_rows($Permission2);
$Area_View=$row_Permission2['Per_View'];
mysql_free_result($Permission2);

require_once('../module_setting.php');
?>

<div class="div_table"> 
          <div class="TableBlock_shadow_Head_Back div_tr">
            <div class="middle center div_td" >編號</div>
            <div class="middle div_td"  nowrap>社區大學</div>
            <div class="middle div_td" >標題</div>
            <div class="middle div_td" >描述</div>
            <div class="middle div_td" >所屬區域</div>
           
            <?php if ($row_AdminMember['Unit_Range'] >= 2 ) { ?>
	    <div class="middle center div_td">發布組別</div>
            <div class="middle center div_td">發布人</div>
	    <?php } ?>
            <div class="middle div_td">操作</div>
            </div>
           <?php if ($totalRows_Cate > 0) { $a=0;// Show if recordset not empty  ?>
			<?php do { $a++;if($a%2==1){$str_bg='table-grey';}else{$str_bg='';}?>
              
              <form name="form_Edit" id="form_Edit" method="POST" class="div_tr">
	        
                <div class="middle center div_td <?php echo $str_bg;?>" ><?php echo $row_Cate['Unit_ID']; ?></div>
                <div class="middle div_td <?php echo $str_bg;?>" ><?php echo mb_substr(substr($row_Cate['Com_Name'],0,strpos($row_Cate['Com_Name'],"社區")),0,5,"utf-8"); ?></div>
		
		<div class="middle div_td <?php echo $str_bg;?>" ><input name="Title" type="text" id="Title" size="6" value="<?php echo $row_Cate['Unit_Name']; ?>" style="width:150px;"></div>               
                <div class="middle div_td <?php echo $str_bg;?>" ><input name="Unit_Remark" type="text" id="Unit_Remark" size="6" value="<?php echo $row_Cate['Unit_Remark']; ?>" style="width:250px;"></div>
		<div class="middle div_td <?php echo $str_bg;?>" width="10%" >郵遞區號：<span id="Postal<?php echo $row_Cate['Unit_ID'];?>"><?php echo $row_Cate['Unit_Postal'];?></span><input type="hidden" id="Postal_Code<?php echo $row_Cate['Unit_ID'];?>" name="Postal_Code" value="<?php echo $row_Cate['Unit_Postal'];?>"><br/>
		<select name="County_ID" id="County_ID<?php echo $row_Cate['Unit_ID'];?>" onChange="callByAJAX<?php echo $row_Cate['Unit_ID'];?>();">
		<option value="">請選擇縣市...</option>
		<?php if($totalRows_County>0){
		       do{
				   ?><option value="<?php echo $row_County['County_Cate'];?>" <?php if($row_Cate['Unit_CountyCate']==$row_County['County_Cate']){?>selected<?php }?>><?php echo $row_County['County_Cate'];?></option>
		<?php }while($row_County = mysql_fetch_assoc($County));
			if($totalRows_County> 0) {
				mysql_data_seek($County, 0);
				$row_County = mysql_fetch_assoc($County);
			}
			}?>
		</select>
		<select name="County_Name" id="County_Name<?php echo $row_Cate['Unit_ID'];?>" onChange="callPostal<?php echo $row_Cate['Unit_ID'];?>();">
      		<option value="" >:::請選擇區域:::</option>
      		<?php if($totalRows_Area3>0){
		       do{?><option value="<?php echo $row_Area3['Postal_Code'];?>" <?php if($row_Cate['Unit_Postal']==$row_Area3['Postal_Code']){?>selected<?php }?>><?php echo $row_Area3['County_Name'];?></option>
		 <?php }while($row_Area3 = mysql_fetch_assoc($Area3));
	  		if($totalRows_Area3> 0) {
				mysql_data_seek($Area3, 0);
				$row_Area3 = mysql_fetch_assoc($Area3);
			}
	  	     }?>
		</select>
		</div>
                
                <?php if ($row_AdminMember['Unit_Range'] >= 2) { ?>
                <div class="middle center div_td <?php echo $str_bg;?>" ><?php echo $row_Cate['Add_Unitname']; ?></div>
                <div class="middle center div_td <?php echo $str_bg;?>" ><?php echo $row_Cate['Add_Username']; ?></div>
                <?php } ?>                
                <div class="middle div_td <?php echo $str_bg;?>" >  
       
                <?php if($row_Permission['Per_Edit'] == 1){ ?>
                    <input type="submit" value="更新" class="Button_Edit" name="button1" id="button1"/>     
                 <?php } ?>
              
               
               
                
               	
                  
                <?php if($row_Permission['Per_Del'] == 1){ ?>
                    <input type="submit" name="button2" id="button2" value="刪除"  class="Button_Del" onClick="return(confirm('您即將刪除以下資料\n<?php echo $row_Cate['Unit_Name']; ?>\n刪除後資料無法復原,確定要刪除嗎?'))">
                 <?php } ?> 
               
   		    <input type="hidden" name="MM_update" value="Form_Edit" />
		<input type="hidden" name="ID" id="ID" value="<?php echo $row_Cate['Unit_ID']; ?>">
		<input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
               	<input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
         	<input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">		
         	<input name="Del_Title" type="hidden" id="Del_Title" value="<?php echo $row_Cate['Unit_Name']; ?>">

                   <?php if($Area_View==1){?>
               <input type="button" value="分區管理" class="Button_Edit" onclick="location.href='AD_Area_Index.php?UID=<?php echo $row_Cate['Unit_ID'];?>'"/>
		   <?php }?>
		
                  </div>
	      </form> 
             
              <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
             
            <?php } // Show if recordset not empty ?>
</div>
<?php 
mysql_free_result($Cate);
mysql_free_result($Area3);
mysql_free_result($County);
?>
<?php require_once('../../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../zz_module_setting.php'); ?>
<?php require_once('../zz_Admin_Permission.php'); ?>
