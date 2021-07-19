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


$colname_Unit4 = "%";
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
  $colname_Unit4 = $_GET['Unit_ID'];
 
}

mysql_select_db($database_dbline, $dbline);
$query_Data = sprintf("SELECT * FROM member_detail_student WHERE  Member_Show=1  and  Com_ID like %s and Member_ID=%s order by Com_ID ASC",GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname_DataID, "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);


mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM Community where Com_ID Like %s and Com_Enable=1 and Com_ID <> 4 order by Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate= mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

mysql_select_db($database_dbline, $dbline);
$query_County = "SELECT * FROM County Group by County_Cate order by County_ID ASC";
$County = mysql_query($query_County, $dbline) or die(mysql_error());
$row_County = mysql_fetch_assoc($County);
$totalRows_County = mysql_num_rows($County);

mysql_select_db($database_dbline, $dbline);
$query_Cate2 = "SELECT * FROM eduction  order by Edu_ID ASC";
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

mysql_select_db($database_dbline, $dbline);
$query_Cate3 = "SELECT * FROM job  order by Job_ID ASC";
$Cate3 = mysql_query($query_Cate3, $dbline) or die(mysql_error());
$row_Cate3 = mysql_fetch_assoc($Cate3);
$totalRows_Cate3 = mysql_num_rows($Cate3);



if(isset($_POST['Postal_Code'])&&$_POST['Postal_Code']<>""){
mysql_select_db($database_dbline, $dbline);
$query_Area2 = "SELECT * FROM area where Postal_Code=".$_POST['Postal_Code'];
$Area2 = mysql_query($query_Area2, $dbline) or die(mysql_error());
$row_Area2 = mysql_fetch_assoc($Area2);
$totalRows_Area2= mysql_num_rows($Area2);
$CountyID=$row_Area2['County_ID'];
$Postal_Code=$_POST['Postal_Code'];


}
else{
	$Postal_Code=$row_Data['Postal_Code'];
	$CountyID=$row_Data['County_ID'];
	}





$Other = "修改".$row_Permission['ModuleSetting_Title'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit")) {
	//網頁
	$str_url='';
	if(isset($_GET['SCom']) && $_GET['SCom']<>""){$str_url.='&Com_ID='.$_GET['SCom']; }
	if(isset($_GET['SOrders']) && $_GET['SOrders']<>""){$str_url.='&orders='.$_GET['SOrders']; }
	if(isset($_GET['MUser']) && $_GET['MUser']<>""){$str_url.='&Member_UserName='.$_GET['MUser'];}
	if(isset($_GET['Pages']) && $_GET['Pages']<>""){$str_url.='&pageNum_Data='.$_GET['Pages'];}
	$EditTime=date("Y-m-d H:i:s");
	if(isset($_POST['user_location']) && $_POST['user_location']==2){
		$Member_Country=$_POST['Member_Country'];
		$Member_Rename=$_POST['Member_Rename'];
	}
	else{
		$Member_Country="臺灣";
		$Member_Rename=NULL;
	}
	if(isset($_POST['Member_Card']) && $_POST['Member_Card']<>""){
		$Member_Card=$_POST['Member_Card'];  
		$Member_CardNo=$_POST['Member_CardNo'];  
	}
	else{
		$Member_Card=NULL;  
		$Member_CardNo=NULL;
	}
	if(isset($_POST['Member_News'])){
		$Member_News=join(";",$_POST['Member_News']);
	}
	else{
		$Member_News=NULL;
	}
		  
	if((isset($_POST['Member_Isresident']) && $_POST['Member_Isresident']==1) && (isset($_POST['user_location']) && $_POST['user_location']==2)){
		$Member_Isresident=1;
	}
	else{
		$Member_Isresident=0;
	}
	if(isset($_POST['Member_Isindigenous']) && $_POST['Member_Isindigenous']==1){
		$Member_Isindigenous=1;
	}
	else{
		$Member_Isindigenous=0;
	}
	$Member_CTel=$_POST['Member_CTel']."#".$_POST['Member_CTel2'];
	$Member_Position='';
	if(isset($_POST['classck']) && $_POST['classck']<>""){
		if($Member_Position==''){
			$Member_Position.=';'.$_POST['classck'].';';
		}
		
	}
	if(isset($_POST['vtck']) && $_POST['vtck']<>""){
		if($Member_Position==''){
			$Member_Position.=';'.$_POST['vtck'].';';
		}
		else{
			$Member_Position.=$_POST['vtck'].';';
		}
	}
	if(isset($_POST['Member_Isidentity']) && $_POST['Member_Isidentity']==1){
		$Member_Isidentity=1;
	}
	else{
		$Member_Isidentity=0;
	}
	if(isset($_POST['Member_Ispic']) && $_POST['Member_Ispic']==1){
		$Member_Ispic=1;
	}
	else{
		$Member_Ispic=0;
	}
	if(isset($_POST['Member_IsCard']) && $_POST['Member_IsCard']==1){
		$Member_IsCard=1;
	}
	else{
		$Member_IsCard=0;
	}
	if(isset($_POST['Member_Isgov']) && $_POST['Member_Isgov']==1){
		$Member_Isgov=1;
	}
	else{
		$Member_Isgov=0;
	}
	if(isset($_POST['Member_Audit']) && $_POST['Member_Audit']==1){
		$Member_Audit=1;
	}
	else{
		$Member_Audit=0;
	}
	if(isset($_POST['Ori_Member_Password']) && $_POST['Ori_Member_Password']==$_POST['Member_Password']){
		$Member_Password=$_POST['Ori_Member_Password'];
	}
	else{
		$Member_Password=md5($_POST['Member_Password']);
	}
	$updateSQL2 = sprintf("update member set Member_Position=%s, Member_Isidentity=%s, Member_Ispic=%s, Member_IsCard=%s, Member_Isgov=%s, Member_Audit=%s, Member_Type=%s where Member_ID=%s",
	                       			
			                       GetSQLValueString($Member_Position, "text"),
			                       GetSQLValueString($Member_Isidentity, "text"),
			                       GetSQLValueString($Member_Ispic, "text"),
			                       GetSQLValueString($Member_IsCard, "text"),
			                       GetSQLValueString($Member_Isgov, "text"),
			                       GetSQLValueString($Member_Audit, "text"),
					       GetSQLValueString($_POST['Member_Type'], "text"),
			                       GetSQLValueString($_POST['ID'], "text"));
	mysql_select_db($database_dbline, $dbline);
	$Result2 = mysql_query($updateSQL2, $dbline) or die(mysql_error());
        
	$updateSQL = sprintf("update member set Member_UserName=%s, Member_Identity=%s, Member_Country=%s, Foreigns=%s, Member_Password=%s, Member_Sex=%s, Member_Birthday=%s, Edu_ID=%s, Job_ID=%s, Member_Unitname=%s, Member_Tel=%s, Member_Phone=%s, Postal_Code=%s, County_ID=%s, Member_Address=%s, Member_Email=%s, Emergency_Person=%s, Emergency_Person_Tel=%s, Emergency_Relation=%s, Member_Inform=%s, Member_Remark=%s, Member_Rename=%s, Member_CTel=%s, Member_Area=%s, Job_Title=%s, Member_Card=%s, Member_CardNo=%s, Member_News=%s, Member_Isresident=%s, Member_Isindigenous=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s, Member_Flag=0 where Member_Identity=%s",
	                       			GetSQLValueString($_POST['Title'], "text"),
						GetSQLValueString($_POST['Member_Identity'], "text"),
						GetSQLValueString($Member_Country, "text"),
						GetSQLValueString($_POST['Foreigns'],"int"),
	                       			GetSQLValueString($Member_Password, "text"),
	                       			GetSQLValueString($_POST['Member_Sex'], "text"),
						   GetSQLValueString($_POST['Member_Birthday'], "date"),
						   GetSQLValueString($_POST['Edu_ID'], "int"),
						   GetSQLValueString($_POST['Job_ID'], "int"),
						   GetSQLValueString($_POST['Member_Unitname'], "text"),
						   GetSQLValueString($_POST['Member_Tel'], "text"),
						   GetSQLValueString($_POST['Member_Phone'], "text"),
						   GetSQLValueString($_POST['Postal_Code'], "int"),
						   GetSQLValueString($CountyID, "int"),	   
						   GetSQLValueString($_POST['Member_Address'], "text"),
						   GetSQLValueString($_POST['Member_Email'], "text"),
						   GetSQLValueString($_POST['Emergency_Person'], "text"),  
						   GetSQLValueString($_POST['Emergency_Person_Tel'], "text"),
						   GetSQLValueString($_POST['Emergency_Relation'], "text"), 
						   GetSQLValueString($_POST['Member_Inform'],"int"),
						   GetSQLValueString($_POST['Member_Remark'], "text"), 
						   GetSQLValueString($Member_Rename, "text"),
						GetSQLValueString($Member_CTel, "text"),
						GetSQLValueString($_POST['Member_Area'], "text"),
						GetSQLValueString($_POST['Job_Title'], "text"),
						GetSQLValueString($Member_Card, "text"),
						GetSQLValueString($Member_CardNo, "text"),
						GetSQLValueString($Member_News, "text"),
						GetSQLValueString($Member_Isresident, "int"),
						GetSQLValueString($Member_Isindigenous, "int"),
						GetSQLValueString($EditTime, "date"),
			                       GetSQLValueString($_POST['Edit_Account'], "text"),
			                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
			                       GetSQLValueString($_POST['Edit_Username'], "text"),			                       
			                       GetSQLValueString($_POST['Member_OIdentity'], "text"));
	
	
	$PastContent=$row_Data['Member_ID']."/".$row_Data['Com_ID']."/".$row_Data['Member_Identity']."/".$row_Data['Member_Password']."/".$row_Data['Member_Enable']."/".$row_Data['Member_Show']."/".$row_Data['Member_UserName']."/".$row_Data['Member_Sex']."/".$row_Data['Member_Birthday']."/".$row_Data['Member_Type']."/".$row_Data['Edu_ID']."/".$row_Data['Job_ID']."/".
$row_Data['Member_Unitname']."/".$row_Data['Member_Tel']."/".$row_Data['Member_Phone']."/".
$row_Data['5Postal_Code']."/".$row_Data['Postal_Code']."/".$row_Data['County_ID']."/".
$row_Data['Member_Address']."/".$row_Data['Member_Email']."/".$row_Data['Member_Audit']."/".
$row_Data['Emergency_Person']."/".$row_Data['Emergency_Person_Tel']."/".$row_Data['Emergency_Relation']."/".$row_Data['Foreigns']."/".@$row_Data['Member_Inform']."/".$row_Data['Member_Position']."/".$row_Data['Member_Remark']."/".$row_Data['Add_Time']."/".$row_Data['Add_Account']."/".$row_Data['Add_Unitname']."/".$row_Data['Add_Username']."/".$row_Data['Edit_Time']."/".$row_Data['Edit_Account']."/".$row_Data['Edit_Unitname']."/".$row_Data['Edit_Username'];
	
	$NewContent=$row_Data['Member_ID']."/".$row_Data['Com_ID']."/".$_POST['Member_Identity']."/".$_POST['Member_Password']."/".$row_Data['Member_Enable']."/".$row_Data['Member_Show']."/".$_POST['Title']."/".$_POST['Member_Sex']."/".$_POST['Member_Birthday']."/".$_POST['Member_Type']."/".$_POST['Edu_ID']."/".$_POST['Job_ID']."/".
$_POST['Member_Unitname']."/".$_POST['Member_Tel']."/".$_POST['Member_Phone']."/".
$row_Data['5Postal_Code']."/".$_POST['Postal_Code']."/".$CountyID."/".
$_POST['Member_Address']."/".$_POST['Member_Email']."/".@$_POST['Member_Audit']."/".
$_POST['Emergency_Person']."/".$_POST['Emergency_Person_Tel']."/".$_POST['Emergency_Relation']."/".$row_Data['Foreigns']."/".@$row_Data['Member_Inform']."/".$row_Data['Member_Position']."/".$row_Data['Member_Remark']."/".$row_Data['Add_Time']."/".$row_Data['Add_Account']."/".$row_Data['Add_Unitname']."/".$row_Data['Add_Username']."/".$EditTime."/".$_POST['Edit_Account']."/".$_POST['Edit_Unitname']."/".$_POST['Edit_Username'];


	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	
	
	
	require_once('../../Include/Data_BrowseUpdate.php');

	$updateGoTo = "AD_Data_Index.php?Msg=UpdateOK".$str_url;  
	header(sprintf("Location: %s", $updateGoTo));
}
//學區
	$query_RecCate = "SELECT StudyArea_ID,StudyArea_Name FROM study_area WHERE StudyArea_Enable=1";
	$RecCate = mysql_query($query_RecCate, $dbline) or die(mysql_error());
	$learnlocationnumber=mysql_num_rows($RecCate);
	while($row = mysql_fetch_array($RecCate)){
		$learnlocation_name[]=$row['StudyArea_Name'];
		$learnlocation_id[]=$row['StudyArea_ID'];
	}
	mysql_free_result($RecCate);
//身分別
$query_TypeData = sprintf("SELECT * FROM member_type inner join season_new on member_type.Season_Code=season_new.Season_Code and member_type.Com_ID=season_new.Com_ID where member_type.Com_ID =%s  order by MemberType_ID ASC",GetSQLValueString($row_Data['Com_ID'], "int"));
$TypeData = mysql_query($query_TypeData, $dbline) or die(mysql_error());
$row_TypeData = mysql_fetch_assoc($TypeData);
$totalRows_TypeData = mysql_num_rows($TypeData);
?>

<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>


<?php require_once('../../Tools/JQFileUpLoad/JQFileUpLoad.php'); ?>
<script src="../../ckeditor/ckeditor.js"></script>
<!--驗證CSS OP-->
<?php require_once('../../Include/spry_style.php'); ?>
<!--驗證CSS ED-->
<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>
<!--[if lt IE 9]>
 <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--日期INPUT ED-->
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
<div >   
	<center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
 <div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> 修改<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></div>
    
	<?php if($row_Permission['Per_Edit'] == 1){ ?>
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
       <input type="button" value="取消" class="Button_General" onClick="location.href='AD_Data_Index.php?<?php if(isset($_GET['SCom']) && $_GET['SCom']<>""){echo '&Com_ID='.$_GET['SCom']; }if(isset($_GET['SOrders']) && $_GET['SOrders']<>""){echo '&orders='.$_GET['SOrders']; }if(isset($_GET['MUser']) && $_GET['MUser']<>""){echo '&Member_UserName='.$_GET['MUser'];}if(isset($_GET['Pages']) && $_GET['Pages']<>""){echo '&pageNum_Data='.$_GET['Pages'];}?>'"/>
      </td>
      </tr>
    </table>
    <?php if ($totalRows_Data > 0) { // Show if recordset not empty ?>
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit" id="Form_Edit" method="POST">
    <div align="center">
      
      <?php 	$forms_type="edit";
		require_once("form_student_edit.php");?>
      <br/>
      <input name="ID" type="hidden" id="ID" value="<?php echo $row_Data['Member_ID']; ?>">
     
      <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
      <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
      <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
      <input type="submit" value="確定修改" class="Button_Submit" id="CheckOK" style="display:none;"/>  <input type="button" value="取消" class="Button_General" onClick="location.href='AD_Data_Index.php?<?php if(isset($_GET['SCom']) && $_GET['SCom']<>""){echo '&Com_ID='.$_GET['SCom']; }if(isset($_GET['SOrders']) && $_GET['SOrders']<>""){echo '&orders='.$_GET['SOrders']; }if(isset($_GET['MUser']) && $_GET['MUser']<>""){echo '&Member_UserName='.$_GET['MUser'];}if(isset($_GET['Pages']) && $_GET['Pages']<>""){echo '&pageNum_Data='.$_GET['Pages'];}?>'"/>
      
    </div>
    <input type="hidden" name="MM_update" value="Form_Edit" />
    
    </form>
	<?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無權修改此資料</div>    
    <?php } ?>
    
    
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能修改權限</div>    
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

<script type="text/javascript" src="../../Modules/Student/js/jsfunction.js"></script>
<script type="text/javascript">



 /*檢查身分證OP*/	
	callByAJAX_Identity();	
	function callByAJAX_Identity(){
		 
		// mainItemValue 代表 option value, 其值對應到 printing p_id
        if(document.getElementById("Member_Identity").value==""){
			document.getElementById("RepeatAccount").innerHTML = "請輸入身分證字號";
		}
	    else{
			
			document.getElementById("Member_Identity").value=document.getElementById("Member_Identity").value.toUpperCase();//轉大寫
			/*全形轉半形OP*/	
			var text = document.getElementById("Member_Identity").value;
			var asciiTable = "!\"#$%&\’()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
			var big5Table = "%uFF01%u201D%uFF03%uFF04%uFF05%uFF06%u2019%uFF08%uFF09%uFF0A%uFF0B%uFF0C%uFF0D%uFF0E%uFF0F%uFF10%uFF11%uFF12%uFF13%uFF14%uFF15%uFF16%uFF17%uFF18%uFF19%uFF1A%uFF1B%uFF1C%uFF1D%uFF1E%uFF1F%uFF20%uFF21%uFF22%uFF23%uFF24%uFF25%uFF26%uFF27%uFF28%uFF29%uFF2A%uFF2B%uFF2C%uFF2D%uFF2E%uFF2F%uFF30%uFF31%uFF32%uFF33%uFF34%uFF35%uFF36%uFF37%uFF38%uFF39%uFF3A%uFF3B%uFF3C%uFF3D%uFF3E%uFF3F%u2018%uFF41%uFF42%uFF43%uFF44%uFF45%uFF46%uFF47%uFF48%uFF49%uFF4A%uFF4B%uFF4C%uFF4D%uFF4E%uFF4F%uFF50%uFF51%uFF52%uFF53%uFF54%uFF55%uFF56%uFF57%uFF58%uFF59%uFF5A%uFF5B%uFF5C%uFF5D%uFF5E";
			var result = "";
			for (var i = 0; i < text.length; i++) {
				var val = escape(text.charAt(i));
				var j = big5Table.indexOf(val);
				result += (((j > -1) && (val.length == 6)) ? asciiTable.charAt(j / 6) : text.charAt(i));
		    }
			document.getElementById("Member_Identity").value = result;
			/*全形轉半形END*/
			
			var mainItemValue = document.getElementById("Member_Identity").value;
			var mainItemValue2 = document.getElementById("Member_OIdentity").value;
			
			if (window.XMLHttpRequest){
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_ids = new XMLHttpRequest();
			} 
			else{  
					// code for IE6, IE5
					xmlhttp_subitems_ids = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp_subitems_ids.onreadystatechange = function() 
			{
				if (xmlhttp_subitems_ids.readyState==4 && xmlhttp_subitems_ids.status==200){
					$("#RepeatAccount").html(xmlhttp_subitems_ids.responseText);
					if(document.getElementById('user_location')){
						twfgck(document.getElementById('user_location'));
					}
					if(document.getElementById("Member_Card")){						
						chosecard(document.getElementById("Member_Card"));
					}
					if(document.getElementById("RepeatM").value!="重複"){
						if(document.getElementById("Foreigns").value!=1){
								var id=document.getElementById("ids").value.toUpperCase();
								var reg=/^[A-Z]{1}[1-2]{1}[0-9]{8}$/;  //身份證的正規表示式
								if(reg.test(id)){
									checkTwID(id);
								}
								else{
									document.getElementById("RepeatAccount").innerHTML ='基本格式錯誤';
									document.getElementById("Member_Identity").style.backgroundColor="#ffe1e1";
									return false; 
								}								
								 if (id.search(/^[A-Z](1|2)\d{8}$/i) == -1) {
										  document.getElementById("RepeatAccount").innerHTML ='基本格式錯誤';
										  document.getElementById("Member_Identity").style.backgroundColor="#ffe1e1";
										  return false; 
								 }									  
								 else if(checkTwID(id)==true){
										  document.getElementById("CheckOK").style.display="";
										  document.getElementById("Member_Identity").style.backgroundColor="#D7FFD7";
										  return true;
										  
								 }
								 else{
										  document.getElementById("CheckOK").style.display="none";
										  document.getElementById("RepeatAccount").innerHTML ='無此身分證字號';
										  document.getElementById("Member_Identity").style.backgroundColor="#ffe1e1";
										  return false;
									 
									 
								 }
														  
						}
						else{//當是僑生時
							if(document.getElementById("Member_Identity").value.length<8){
								document.getElementById("RepeatAccount").innerHTML ="格式錯誤，身分證至少八碼！"; 
								document.getElementById("CheckOK").style.display="none";
								document.getElementById("Member_Identity").style.backgroundColor="#ffe1e1";
							}
							else{
								document.getElementById("CheckOK").style.display="";
								document.getElementById("Member_Identity").style.backgroundColor="#D7FFD7";
							}
						}/*Foreigns.checked end*/
					}/*檢查重複 end*/
					else{
						document.getElementById("Member_Identity").style.backgroundColor="#ffe1e1";
						return false; 
					}
				}
			}
			xmlhttp_subitems_ids.open("get", "account_value_edit.php?Member_Identity=" + encodeURI(mainItemValue)+"&Member_OIdentity="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems_ids.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems_ids.send();
	     }
				
	}
	function CheckAgain(){
		document.getElementById("CheckOK").style.display="none";
		document.getElementById("RepeatAccount").innerHTML="請先檢查身分，已確認是否已註冊過";		
		}
/*檢查身分證END*/	 
	 
	callByAJAX();
   	function callByAJAX()
	{
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var mainItemValue = document.getElementById("County_ID").value;  
	var mainItemValue2 = document.getElementById("CountyNameData").value; 
	

	//alert("main_id="+mainItemValue);
	//var str = ("main_id=" + mainItemValue);
	
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
		if (xmlhttp_subitems.readyState==4 && xmlhttp_subitems.status==200){
			$("#County_Name").html(xmlhttp_subitems.responseText);
		}
			
	}
	
	xmlhttp_subitems.open("get", "cate_value.php?County_Cate=" + encodeURI(mainItemValue)+"&County_Name="+ encodeURI(mainItemValue2), true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
	
	
	
	
	}
	callPostal();
	function callPostal()
	{   
		if(document.getElementById("County_Name").value!=""){
			document.getElementById("Postal").innerHTML=document.getElementById("County_Name").value;
			document.getElementById("Postal_Code").value=document.getElementById("County_Name").value;}else{
		    <?php if($row_Data['Postal_Code']<>""){?>
		    document.getElementById("Postal").innerHTML=<?php echo $row_Data['Postal_Code'];?>;
			document.getElementById("Postal_Code").value=<?php echo $row_Data['Postal_Code'];?>;
			<?php }?>
			}
		
	}	
	function resetall(){
		document.getElementById("Postal").innerHTML="";
		document.getElementById("Postal_Code").value="";

	}
		
$(document).ready(function() {
	           
			 
			 $('#Member_Password').on("change", function () {
				  if(document.getElementById("Member_Password").value.length>4){
					  document.getElementById('Member_Password').style.backgroundColor='#D7FFD7';
					  $("span.Password_Format").hide(); 
				  }
				  else{
					  document.getElementById('Member_Password').style.backgroundColor='#ffe1e1';
					  $("span.Password_Format").show(); 
				  }
			 });
			 $('#County_ID').on("change", function () {
				 var county_text=document.getElementById("County_ID").value
				  if(county_text.replace(/\s+/g, "")!=""){
					  document.getElementById('County_ID').style.backgroundColor='#D7FFD7';
					  $("span.Msg_County_ID").hide(); 
				  }
				  else{
					  document.getElementById('County_ID').style.backgroundColor='#ffe1e1';
					  $("span.Msg_County_ID").show(); 
				  }
			 });
			 $('#County_Name').on("change", function () {
				 var countyname_text=document.getElementById("County_Name").value
				  if(countyname_text.replace(/\s+/g, "")!=""){
					  document.getElementById('County_Name').style.backgroundColor='#D7FFD7';
					  $("span.Msg_County_Name").hide(); 
				  }
				  else{
					  document.getElementById('County_Name').style.backgroundColor='#ffe1e1';
					  $("span.Msg_County_Name").show(); 
				  }
			 });
			 /*$('#Member_Tel').on("change", function () {
				 
				 
				  if(document.getElementById("Member_Tel").value.length>8){
					  document.getElementById('Member_Tel').style.backgroundColor='#D7FFD7';
					  $("span.Msg_Tel").hide(); 
				  }
				  else{
					  document.getElementById('Member_Tel').style.backgroundColor='#ffe1e1';
					  $("span.Msg_Tel").show(); 
				  }
			 });
			 $('#Emergency_Person_Tel').on("change", function () {
				 
				 
				  if(document.getElementById("Emergency_Person_Tel").value.length>8){
					  document.getElementById('Emergency_Person_Tel').style.backgroundColor='#D7FFD7';
					  $("span.Msg_ETel").hide(); 
				  }
				  else{
					  document.getElementById('Emergency_Person_Tel').style.backgroundColor='#ffe1e1';
					  $("span.Msg_ETel").show(); 
				  }
			 });
			 $('#Member_Phone').on("change", function () {
				 
				 
				  if(document.getElementById("Member_Phone").value.length>9){
					  document.getElementById('Member_Phone').style.backgroundColor='#D7FFD7';
					  $("span.Msg_Phone").hide(); 
				  }
				  else{
					  document.getElementById('Member_Phone').style.backgroundColor='#ffe1e1';
					  $("span.Msg_Phone").show(); 
				  }
			 });*/
			 
			//css END 
});
			


 $(document).ready(function(event) {

    $('form[name=Form_Edit]').submit(function(event){
        if(document.getElementById('RepeatM')){
			if(document.getElementById('RepeatM').value=='無重複'){
				//設定CSS OP
				 
				
				if(document.getElementById("County_ID").value!=""){
					document.getElementById("County_ID").style.backgroundColor='#D7FFD7';
					$("span.Msg_County_ID").hide();  
				}
				else{
					document.getElementById("County_ID").focus();
					document.getElementById("County_ID").style.backgroundColor='#ffe1e1';
					$("span.Msg_County_ID").show(); 
				}
				if(document.getElementById("Member_Type").value!=""){
					document.getElementById("Member_Type").style.backgroundColor='#D7FFD7';  
				}
				else{
					document.getElementById("Member_Type").focus();
					document.getElementById("Member_Type").style.backgroundColor='#ffe1e1';
				}
				if(document.getElementById("County_Name").value!=""){
					document.getElementById("County_Name").style.backgroundColor='#D7FFD7';
					$("span.Msg_County_Name").hide();
				}
				else{
					document.getElementById("County_Name").focus();
					document.getElementById("County_Name").style.backgroundColor='#ffe1e1';
					$("span.Msg_County_Name").show(); 
				}
				if(document.getElementById("Title").value==""){
					alert("請輸入姓名");
					document.getElementById("Title").focus();
					document.getElementById("Title").style.backgroundColor='#ffe1e1';
				}
				
				if(document.getElementById("Member_Password").value.length<5){
					document.getElementById("Member_Password").focus();
					document.getElementById("Member_Password").style.backgroundColor='#ffe1e1';
					$("span.Password_Format").show(); 
				}
				/*else{
					var re = /[a-zA-Z0-9]{5,}/g;
					var val = document.getElementById("Member_Password").value;
					var result = re.exec(val);
					if(result == null || result != val || val.search(/[0-9]/g) == -1 || val.search(/[a-zA-Z]/g) == -1)
					{
						document.getElementById("Member_Password").focus();
					        $("span.Password_Format").show(); 
					        document.getElementById("Member_Password").style.backgroundColor='#ffe1e1';
					        return false;
					}
					else{
						document.getElementById("Member_Password").style.backgroundColor='#D7FFD7';
						$("span.Password_Format").hide(); 
					}
				}*/
				/*if(document.getElementById("Member_Tel").value.length!=0 && document.getElementById("Member_Tel").value.length<9){
					document.getElementById("Member_Tel").focus();
					document.getElementById("Member_Tel").style.backgroundColor='#ffe1e1';
					$("span.Msg_Tel").show();
				}
				if( document.getElementById("Emergency_Person_Tel").value.length!=0 && document.getElementById("Emergency_Person_Tel").value.length<9){
					document.getElementById("Emergency_Person_Tel").focus();
					document.getElementById("Emergency_Person_Tel").style.backgroundColor='#ffe1e1';
					$("span.Msg_ETel").show(); 	
				}
				
				if(document.getElementById("Member_Phone").value.length!=0 && document.getElementById("Member_Phone").value.length<10)
				{
					document.getElementById("Member_Phone").focus();
					document.getElementById("Member_Phone").style.backgroundColor='#ffe1e1';
					$("span.Msg_Phone").show();	
				}*/
				if(!document.getElementById("RepeatM")||document.getElementById("RepeatM").value=='重複'){
					document.getElementById("Member_Identity").style.backgroundColor='#ffe1e1';			
				}else{
					document.getElementById("Member_Identity").style.backgroundColor='#D7FFD7';				
				}
				
				
				
				
				
				//add stuff here 判斷值
				
				if(document.getElementById("Member_Password").value.length>4 && (document.getElementById("Member_Tel").value!="" || document.getElementById("Member_Phone").value!="") && document.getElementById("Emergency_Person_Tel").value!="" && document.getElementById("Title").value!="" && document.getElementById("Member_Birthday").value!=""  && document.getElementById("Member_Type").value!=""){	
					
					return true;
				}
				else if(document.getElementById("Member_Tel").value=="" && document.getElementById("Member_Phone").value==""){
					alert("請輸入行動電話或室內電話！");
					return false;
				}
				else{
					return false;
				}
		    }
			else{
				return false;/*判斷為無重複*/
			}
		}
		else{
			
			return false;
			/*判斷是否檢查*/
		}
		 
		 
    });
});	
date_check();

function date_check(){
 	$('.picker_date').datetimepicker({
		format: 'YYYY/MM/DD',
		locale: 'zh-tw',
		showClear:true,
		showClose:false,	
		useCurrent:false,	
		viewMode: "years",
		minDate:moment("1911/01/01"),
		maxDate:moment((new Date().getFullYear())+"/12/31")
				
	});  	
}

</script>
<script src="search_yearsjs.php" type="text/javascript"></script>
</html>
<?php
mysql_free_result($Cate);
mysql_free_result($Data);
//mysql_free_result($County);
mysql_free_result($Cate2);
mysql_free_result($Cate3);
if($Postal_Code<>$row_Data['Postal_Code']){mysql_free_result($Area2);}
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
