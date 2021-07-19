<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php //require_once('module_setting.php'); ?>
<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

?>
<?php 

$colname_DataID = "-1";
if (isset($_GET['ID'])) {
  $colname_DataID = $_GET['ID'];
}

$colname03_Unit=$row_AdminMember['Com_ID'];
$colname_Unit4 = "%";
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
  $colname_Unit4 = $_GET['Unit_ID'];
 
}

mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM member_detail_student WHERE  Member_Show=1  and  Com_ID = %s and Member_Identity=%s",GetSQLValueString($row_AdminMember['Com_ID'], "int"),GetSQLValueString($row_AdminMember['Member_Identity'], "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);


$query_Cate = sprintf("SELECT * FROM community where  Com_Enable=1 and Com_ID <> 4 and Com_IsPrivate <> 1 order by Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate= mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


$query_County = "SELECT * FROM county Group by County_Cate order by County_ID ASC";
$County = mysql_query($query_County, $dbline) or die(mysql_error());
$row_County = mysql_fetch_assoc($County);
$totalRows_County = mysql_num_rows($County);


$query_Cate2 = "SELECT * FROM eduction  order by Edu_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);


$query_Cate3 = "SELECT * FROM job  order by Job_ID ASC";
$Cate3 = mysql_query($query_Cate3, $dbline) or die(mysql_error());
$row_Cate3 = mysql_fetch_assoc($Cate3);
$totalRows_Cate3 = mysql_num_rows($Cate3);

$Other = "新增學員基本資料(創建)";

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit")) {
	
    $AddTime=date("Y-m-d H:i:s");
	if($_POST['ResultValue']=='通過'){
		mysql_select_db($database_dbline, $dbline);
		$query_DataP = sprintf("SELECT * FROM member_detail_student where Member_Identity=%s order by Edit_Time DESC", GetSQLValueString($_POST['Member_Identity'], "text"));
		$DataP = mysql_query($query_DataP, $dbline) or die(mysql_error());
		$row_DataP = mysql_fetch_assoc($DataP);
		$totalRows_DataP = mysql_num_rows($DataP);
		
		require_once('../../Include/function_array_keys.php'); //插入陣列公式
		
		$updateSQL = sprintf("INSERT INTO member (
				  Com_ID, Member_UserName, Member_Country, Member_Enable, Member_Show, 
				  Foreigns, Member_Identity, Member_Password, Member_Sex, Member_Birthday,
				  Member_Type, Edu_ID, Job_ID, Member_Unitname, Member_Tel, 
				  Member_Phone, Postal_Code, County_ID, Member_Address, Member_Email, 
				  Emergency_Person, Emergency_Person_Tel, Emergency_Relation, Member_Inform, Add_Time, 
				  Edit_Time, Add_Account, Add_Unitname, Add_Username, Member_Rename, 
				  Member_CTel, Member_Area, Job_Title, Member_Card, Member_CardNo, 
				  Member_News, Member_Flag, Member_Isresident, Member_Isindigenous, Member_SignComID) VALUES (
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s, 
				  %s, %s, %s, %s, %s)",
                      
                       GetSQLValueString($_POST['Com_ID'], "int"),
                       GetSQLValueString($row_DataP['Member_UserName'], "text"),
                       GetSQLValueString($row_DataP['Member_Country'], "text"),
					   GetSQLValueString(1, "int"),
					   GetSQLValueString(1, "int"),
		       GetSQLValueString($row_DataP['Foreigns'],"int"),
                       GetSQLValueString($row_DataP['Member_Identity'], "text"),
                       GetSQLValueString($row_DataP['Member_Password'], "text"),
                       GetSQLValueString($row_DataP['Member_Sex'], "text"),
					   GetSQLValueString($row_DataP['Member_Birthday'], "date"),
					   GetSQLValueString("一般", "text"),
					   GetSQLValueString($row_DataP['Edu_ID'], "int"),
					   GetSQLValueString($row_DataP['Job_ID'], "int"),
					   GetSQLValueString($row_DataP['Member_Unitname'], "text"),
					   GetSQLValueString($row_DataP['Member_Tel'], "text"),
					   GetSQLValueString($row_DataP['Member_Phone'], "text"),
					   GetSQLValueString($row_DataP['Postal_Code'], "int"),
					   GetSQLValueString($row_DataP['County_ID'], "int"),	   
					   GetSQLValueString($row_DataP['Member_Address'], "text"),
					   GetSQLValueString($row_DataP['Member_Email'], "text"),
					   GetSQLValueString($row_DataP['Emergency_Person'], "text"),  
					   GetSQLValueString($row_DataP['Emergency_Person_Tel'], "text"),
					   GetSQLValueString($row_DataP['Emergency_Relation'], "text"),
					   GetSQLValueString($row_DataP['Member_Inform'],"int"),
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($AddTime, "date"),
                       GetSQLValueString($_POST['Add_Account'], "text"),
                       GetSQLValueString($_POST['Add_Unitname'], "text"),
                       GetSQLValueString($_POST['Add_Username'], "text"),
					   GetSQLValueString($row_DataP['Member_Rename'],"text"),
					   GetSQLValueString($row_DataP['Member_CTel'],"text"),
					   GetSQLValueString($row_DataP['Member_Area'],"text"),
					   GetSQLValueString($row_DataP['Job_Title'],"text"),
					   GetSQLValueString($row_DataP['Member_Card'],"text"),
					   GetSQLValueString($row_DataP['Member_CardNo'],"text"),
					   GetSQLValueString($row_DataP['Member_News'],"text"),
					   GetSQLValueString(0,"int"),
					   GetSQLValueString($row_DataP['Member_Isresident'],"text"),
					   GetSQLValueString($row_DataP['Member_Isindigenous'],"text"),
					   GetSQLValueString($row_DataP['Member_SignComID'],"text"));
	    
		//操作紀錄先寫欄位 OP
		require_once('../../Include/browse/member_array.php'); 
		$change=array("",$_POST["Com_ID"],$row_DataP["Member_Identity"],$row_DataP["Member_Password"],"1","1",$row_DataP["Member_UserName"],$row_DataP["Member_Sex"],$row_DataP["Member_Birthday"],$row_DataP["Member_Type"],$row_DataP["Edu_ID"],$row_DataP["Job_ID"],$row_DataP["Member_Unitname"],$row_DataP["Member_Tel"],$row_DataP["Member_Phone"],"",$row_DataP["Postal_Code"],$row_DataP["County_ID"],$row_DataP["Member_Address"],$row_DataP["Member_Email"],$row_DataP["Member_Audit"],$row_DataP["Emergency_Person"],$row_DataP["Emergency_Person_Tel"],$row_DataP["Emergency_Relation"],$row_DataP["Foreigns"],"",$AddTime,$_POST["Add_Account"],$_POST["Add_Unitname"],$_POST["Add_Username"],$AddTime,"","","");//學員記錄
						   
		$NewContent=print_r(array_fill_keys2($memberkeys,$change),true);
		//操作紀錄先寫欄位 ED	   
	
		mysql_select_db($database_dbline, $dbline);
		$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());	
		require_once('../../Include/Data_BrowseInsert.php');
		mysql_free_result($DataP);		
		$updateGoTo = "AD_Edit_Student.php?Msg=UpdateOK";  
		header(sprintf("Location: %s", $updateGoTo));
	}
	
}

?>

<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<script src="../../SpryAssets/SpryValidationSelect.js" type="text/javascript"></script>
<link href="../../SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<link href="../../SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="../../SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
 
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
 <div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">基本資料管理</div>
 <?php if(@$_GET['Msg'] == "UpdateOK"){ ?>
	<script language="javascript">
	function UpdateOK(){
		$('.UpdateOK').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(UpdateOK,0);
    </script>
<?php } ?>
<div align="center">
  <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div> 
   </div>
	
    <input value='回上頁' onClick="location.href='AD_Edit_Student.php'" type="button" class="Button_Add"/>
    <?php if ($totalRows_Data > 0) { // Show if recordset not empty ?>
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit" id="Form_Edit" method="POST">
    <div align="center">
       <table width="95%" border="0" cellpadding="5" cellspacing="2" style="max-width:800px;">
      <tr>
      <td class="right FormTitle02" width="20%"><font color="red">*</font>所屬社區大學:</td>
      <td colspan="3" class="middle">
      <input type="hidden" name="Member_Account" id="Member_Account" value="<?php echo $row_AdminMember['Member_Identity'];?>">
      <select name="Com_ID" id="Com_ID" required onChange="CheckAgain();">
                      <option value="">請選擇社區大學...</option>
                      <?php do{?>
					  <option value="<?php echo $row_Cate['Com_ID']?>"><?php echo $row_Cate['Com_Name']?></option>
					  <?php }while($row_Cate=mysql_fetch_assoc($Cate))?></select></td>
      </tr>
      <tr>
      <td id="ContentArea" class="center" colspan="3">
      </td>
      </tr>
      
          
    </table>
    <script type="text/javascript">
	$( document ).ready(function() {
        CheckAgain();
    });
 
	function CheckAgain()
	{
	document.getElementById("CheckOK").style.display="none";
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var mainItemValue = document.getElementById("Com_ID").value;  
	var mainItemValue2 = document.getElementById("Member_Account").value; 	
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
		if(xmlhttp_subitems.readyState==4&&xmlhttp_subitems.status==200){
			$("#ContentArea").html(xmlhttp_subitems.responseText);
			if(document.getElementById("ResultValue").value=="通過"){
				document.getElementById("CheckOK").style.display="";
			}
			else{
				document.getElementById("CheckOK").style.display="none";
			}
		}
	}
	
	xmlhttp_subitems.open("get", "member_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Member_Identity="+ encodeURI(mainItemValue2), true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
	}

    </script>
      <input name="ID" type="hidden" id="ID" value="<?php echo $row_Data['Member_ID']; ?>">
     
      <input name="Add_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Member_Identity']; ?>">
      <input name="Add_Unitname" type="hidden" id="Edit_Unitname" value="學員">
      <input name="Add_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Member_UserName']; ?>">
      <br/>
      <input type="submit" value="確定創建" class="Button_Submit" id="CheckOK"/>
    </div>
    <input type="hidden" name="MM_update" value="Form_Edit" />
    
    </form>
	<?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權修改此資料</div>    
    <?php } ?>
    
    

        </td>
      </tr>
    </table>
    <br><br><br>
</div>      

<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
<?php require_once('../../Tools/JQFileUpLoad/UpLoadFile_BulletinJSCSS.php'); ?>
</body>

</html>
<?php
mysql_free_result($Cate);
mysql_free_result($Data);
mysql_free_result($County);
mysql_free_result($Cate2);
mysql_free_result($Cate3);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>

