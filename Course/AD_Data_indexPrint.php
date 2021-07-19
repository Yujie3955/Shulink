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
//搜班季
	
$query_Cate = sprintf("SELECT * FROM seasoncate ORDER BY SeasonCate_Code ASC");
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


$query_Cate2 = sprintf("SELECT * FROM community where Com_Enable=1 and ifnull(Com_ID,'') Like %s and Com_ID<>4 and Com_IsPrivate <> 1 ORDER BY Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

if(isset($_POST['Com_ID'])&&$_POST['Com_ID']<>"")
{
	$query_CateP = "SELECT * FROM community where Com_ID=".$_POST['Com_ID']." and Com_IsPrivate <> 1";
	$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
	$row_CateP = mysql_fetch_assoc($CateP);
	$totalRows_CateP= mysql_num_rows($CateP);
    if($totalRows_CateP>0 && $row_CateP['Com_SignFile']<>"")
	{   
	    mysql_free_result($CateP);
		$insertGoTo = '../College/'.$row_CateP['Com_SignFile']."?Com=".$row_CateP['Com_ID']."&Unit=".$_POST['Unit_ID']."&Year=".$_POST['Season_Year']."&Code=".$_POST['SeasonCate_Code'];   
  		header(sprintf("Location: %s", $insertGoTo));
		
	}
	else{
		mysql_free_result($CateP);
		$insertGoTo = "AD_Data_IndexPrint.php?Msg=AddError";  
		header(sprintf("Location: %s", $insertGoTo));
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
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">選課報名表</div>
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
        <br/>
        <div align="center">   
         
           <div class="Error_Msg AddError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 此社區大學無報名表格式！</div>
        </div> 
        <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="post" target="_blank">
        <div align="center">
        <input name="Season_Year" type="text" size="3" required/>年
        <select name="SeasonCate_Code" id="SeasonCate_Code" required="required">
        <?php do { ?>
        <option value="<?php echo $row_Cate['SeasonCate_Code']; ?>" <?php if (@$_GET['SeasonCate_Code'] == $row_Cate['SeasonCate_Code']) { echo "selected='selected'"; } ?>><?php echo $row_Cate['SeasonCate_Name']; ?></option>
        <?php } while ($row_Cate = mysql_fetch_assoc($Cate)); ?>
        </select>
        <select name="Com_ID" id="Com_ID" required onChange="callbyAJAX()">
        
         <option value="">請選擇...</option>       
        <?php do { ?>
        <option value="<?php echo $row_Cate2['Com_ID']; ?>" ><?php echo $row_Cate2['Com_Name']; ?></option>
        <?php 
			} while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
      </select> 
			
      &nbsp;
      <select name="Unit_ID" id="Unit_ID" required onChange="UnitCode()">      <option value="">請選擇...</option> 
      </select>
      <input name="Unit_Code" type="hidden" id="Unit_Code">
      <input name="Unit_Range" id="Unit_Range" type="hidden" value="<?php echo $colname02_Unit;?>">
       <script type="text/javascript">
	   function callbyAJAX(){
			// mainItemValue 代表 option value, 其值對應到 printing p_id
			
			var mainItemValue = document.getElementById("Com_ID").value;
			var mainItemValue2 = document.getElementById("Unit_Range").value;
	
			if (window.XMLHttpRequest) 
			{
		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp_subitems = new XMLHttpRequest();
							} 
			else 
			{  
		// code for IE6, IE5
				xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");
				
			}
			xmlhttp_subitems.onreadystatechange = function() 
			{
				document.getElementById("Unit_ID").innerHTML = xmlhttp_subitems.responseText;
			}
			
	
			xmlhttp_subitems.open("get", "cate_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Unit_Range="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems.send();
			
	
      }
	   </script>
        <input type="submit" value="產出報名表" class="Button_General">
        </div>
        </form> 
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
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_PermissionCate.php'); ?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>