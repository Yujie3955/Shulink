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

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php 
require_once('../../Include/Permission.php');

$colname_Unit4 = "%";
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
  $colname_Unit4 = $_GET['Unit_ID'];
 
}
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT * FROM Community where Com_ID Like %s and Com_Enable=1 and Com_IsPrivate<>1 and Com_ID <> 4 order by Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate= mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$CountyID="";

if(isset($_POST['Postal_Code'])&&$_POST['Postal_Code']<>""){
mysql_select_db($database_dbline, $dbline);
$query_Area2 = "SELECT * FROM area where Postal_Code=".$_POST['Postal_Code'];
$Area2 = mysql_query($query_Area2, $dbline) or die(mysql_error());
$row_Area2 = mysql_fetch_assoc($Area2);
$totalRows_Area2= mysql_num_rows($Area2);
$CountyID=$row_Area2['County_ID'];	
}

?>
<?php
$Other = "新增".$row_Permission['ModuleSetting_Title'];

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add")) {
	$AddTime=date("Y-m-d H:i:s");
	//搜索身分有無使用
	mysql_select_db($database_dbline, $dbline);
	$query_CateP = sprintf("SELECT * FROM member_detail_student where Member_Identity=%s order by Edit_Time DESC,Add_Time DESC, Member_ID DESC",GetSQLValueString($_POST['Member_Identity'], "text"));
	$CateP= mysql_query($query_CateP, $dbline) or die(mysql_error());
	$row_CateP = mysql_fetch_assoc($CateP);
	$totalRows_CateP = mysql_num_rows($CateP);
	if($totalRows_CateP>0 && $_POST['RepeatM']=="無重複"){
		$insertGoTo="AD_Data_Add.php?Msg=Error&ids=".$_POST['Member_Identity']."&Com=".$_POST['Com_ID'];
		header(sprintf("Location: %s", $insertGoTo));	
		exit();
		}
	else{
		//搜索本區是否建置過
		mysql_select_db($database_dbline, $dbline);
		$query_CatePR = sprintf("SELECT * FROM member_detail_student where Member_Identity=%s and Com_ID =%s",GetSQLValueString($_POST['Member_Identity'], "text"),GetSQLValueString($_POST['Com_ID'], "int"));
		$CatePR= mysql_query($query_CatePR, $dbline) or die(mysql_error());
		$row_CatePR = mysql_fetch_assoc($CatePR);
		$totalRows_CatePR = mysql_num_rows($CatePR);
		
		if($totalRows_CatePR>0){
			$insertGoTo="AD_Data_Add.php?Msg=Error&ids=".$_POST['Member_Identity']."&Com=".$_POST['Com_ID'];
			header(sprintf("Location: %s", $insertGoTo));	
			exit();
		}
		else{
			
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
		  	if(isset($_POST['Ori_Member_Password']) && $_POST['Ori_Member_Password']==$_POST['Member_Password']){
				$Member_Password=$_POST['Ori_Member_Password'];
			}
			else{
				$Member_Password=md5($_POST['Member_Password']);
			}
			$insertSQL = sprintf("INSERT INTO member (
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
                       GetSQLValueString($_POST['Title'], "text"),
		       GetSQLValueString($Member_Country, "text"),
					   GetSQLValueString(1, "int"),
					   GetSQLValueString(1, "int"),
					   GetSQLValueString($_POST['Foreigns'],"int"),
                       GetSQLValueString($_POST['Member_Identity'], "text"),
                       GetSQLValueString($Member_Password, "text"),
                       GetSQLValueString($_POST['Member_Sex'], "text"),
					   GetSQLValueString($_POST['Member_Birthday'], "date"),
					   GetSQLValueString($_POST['Member_Type'], "text"),
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
					   GetSQLValueString($AddTime, "date"),
					   GetSQLValueString($AddTime, "date"),
                       GetSQLValueString($_POST['Add_Account'], "text"),
                       GetSQLValueString($_POST['Add_Unitname'], "text"),
                       GetSQLValueString($_POST['Add_Username'], "text"),
									   GetSQLValueString($Member_Rename, "text"),
									   GetSQLValueString($Member_CTel, "text"),
									   GetSQLValueString($_POST['Member_Area'], "text"),
									   GetSQLValueString($_POST['Job_Title'], "text"),
									   GetSQLValueString($Member_Card, "text"),
									   GetSQLValueString($Member_CardNo, "text"),
									   GetSQLValueString($Member_News, "text"),
									   GetSQLValueString(0, "int"),
									   GetSQLValueString($Member_Isresident, "int"),
									   GetSQLValueString($Member_Isindigenous, "int"),
									   GetSQLValueString($row_CateP['Member_SignComID'], "text"));
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
			
			
			$NewContent=$_POST['Com_ID']."/".$_POST['Title']."/"."1"."/"."1"."/".@$_POST['Foreigns']."/".$_POST['Member_Identity']."/".$_POST['Member_Password']."/".$_POST['Member_Sex']."/".$_POST['Member_Birthday']."/".$_POST['Member_Type']."/".$_POST['Edu_ID']."/".$_POST['Job_ID']."/".$_POST['Member_Unitname']."/".$_POST['Member_Tel']."/".$_POST['Member_Phone']."/".$_POST['Postal_Code']."/".$CountyID."/".$_POST['Member_Address']."/".$_POST['Member_Email']."/".$_POST['Emergency_Person']."/".$_POST['Emergency_Person_Tel']."/".$_POST['Emergency_Relation']."/".@$_POST['Member_Inform']."/".$_POST['Member_Remark']."/".@$_POST['Member_Audit']."/".$AddTime."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username'];
			require_once('../../Include/Data_BrowseInsert.php');
			
			if($totalRows_CateP>0){
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
						GetSQLValueString($AddTime, "date"),
			                       GetSQLValueString($_POST['Add_Account'], "text"),
			                       GetSQLValueString($_POST['Add_Unitname'], "text"),
			                       GetSQLValueString($_POST['Add_Username'], "text"),
                      			       GetSQLValueString($_POST['Member_Identity'], "text"));
			
					   
					 
			$PastContent=$row_CateP['Member_ID']."/".$row_CateP['Com_ID']."/".$row_CateP['Member_Identity']."/".$row_CateP['Member_Password']."/".$row_CateP['Member_Enable']."/".$row_CateP['Member_Show']."/".$row_CateP['Member_UserName']."/".$row_CateP['Member_Sex']."/".$row_CateP['Member_Birthday']."/".$row_CateP['Member_Type']."/".$row_CateP['Edu_ID']."/".$row_CateP['Job_ID']."/".$row_CateP['Member_Unitname']."/".$row_CateP['Member_Tel']."/".$row_CateP['Member_Phone']."/".$row_CateP['5Postal_Code']."/".$row_CateP['Postal_Code']."/".$row_CateP['County_ID']."/".$row_CateP['Member_Address']."/".$row_CateP['Member_Email']."/".$row_CateP['Member_Audit']."/".$row_CateP['Emergency_Person']."/".$row_CateP['Emergency_Person_Tel']."/".$row_CateP['Emergency_Relation']."/".$row_CateP['Foreigns']."/".$row_CateP['Member_Inform']."/".$row_CateP['Member_Position']."/".$row_CateP['Member_Remark']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$row_CateP['Edit_Time']."/".$row_CateP['Edit_Account']."/".$row_CateP['Edit_Unitname']."/".$row_CateP['Edit_Username'];
			
			$NewContent=$row_CateP['Member_ID']."/".$row_CateP['Com_ID']."/".$_POST['Member_Identity']."/".$_POST['Member_Password']."/".$row_CateP['Member_Enable']."/".$row_CateP['Member_Show']."/".$_POST['Title']."/".$_POST['Member_Sex']."/".$_POST['Member_Birthday']."/".$_POST['Member_Type']."/".$_POST['Edu_ID']."/".$_POST['Job_ID']."/".$_POST['Member_Unitname']."/".$_POST['Member_Tel']."/".$_POST['Member_Phone']."/".$row_CateP['5Postal_Code']."/".$_POST['Postal_Code']."/".$CountyID."/".$_POST['Member_Address']."/".$_POST['Member_Email']."/".@$_POST['Member_Audit']."/".$_POST['Emergency_Person']."/".$_POST['Emergency_Person_Tel']."/".$_POST['Emergency_Relation']."/".$row_CateP['Foreigns']."/".@$_POST['Member_Inform']."/".$row_CateP['Member_Position']."/".$_POST['Member_Remark']."/".$row_CateP['Add_Time']."/".$row_CateP['Add_Account']."/".$row_CateP['Add_Unitname']."/".$row_CateP['Add_Username']."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username'];	
			
			mysql_select_db($database_dbline, $dbline);
			$Result2 = mysql_query($updateSQL, $dbline) or die(mysql_error());
			require_once('../../Include/Data_BrowseUpdate.php');
			}
			
			
			
			}//判斷本區是否建置過END
			mysql_free_result($CatePR);
	
    $insertGoTo = "AD_Data_Index.php?Msg=AddOK";  
    header(sprintf("Location: %s", $insertGoTo));
	
	}//判斷身分有無使用END
	mysql_free_result($CateP);
}//表單結束
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
		<?php  /* ?>
        <td width="20%">
      </td>
	  <?php */ ?>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> 新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?></div>
    <?php if($row_Permission['Per_Add'] == 1){ ?>
   		<?php if(@$_GET['Msg'] == "Error"){ ?>
		<script language="javascript">
		function AddError(){
			$('.Error_Add').fadeIn(1000).delay(2000);			
		}
		setTimeout(AddError,0);
   		</script>
		<?php } ?> 
        <div align="center">   
          <div class="Error_Msg Error_Add" style="display:none; line-height:25px;"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 此身分在新增過程中，已有人先建立成功。<br/>請再次檢查身分。</div>
        </div>
        <br/>
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
    <div align="center">
    <!--檢查身分是否註冊過OP-->
    <table cellpadding="5" cellspacing="0" border="0" style="max-width:800px;">
    <tr>
    <td class="right FormTitle02"><font color="red">*</font>所屬校別:</td>
    <td><select name="Com_ID" id="Com_ID" required onChange="CheckAgain();">
              <option value="">請選擇校別...</option>
               <?php do{?>
			  <option value="<?php echo $row_Cate['Com_ID']?>" <?php if(isset($_GET['Com'])&&$_GET['Com']==$row_Cate['Com_ID']){echo 'selected';}?>><?php echo $row_Cate['Com_Name']?></option>
			   <?php }while($row_Cate=mysql_fetch_assoc($Cate))?>
        </select>
	    <?php mysql_free_result($Cate);?>
    </td>
    </tr>
    <tr>
     <td class="right FormTitle02" ><font color="red">*</font>國籍:</td>
             <td class="middle"><select name="user_location" id="user_location" onload="twfgck();" onchange="twfgck(this);"  required>
			　	<option value="1" >臺灣</option>
				<option value="2" >國外</option>
			</select>
            <input type="hidden" name="Foreigns" id="Foreigns" ></td>
    </tr>
    <tr>
    <td class="FormTitle02" nowrap><font color="red">*</font><span class="tw">身分證字號</span><span class="fg" style="display:none;">居留證編號</span>(帳號):</td>
    <td><input type="text" name="Member_Identity" id="Member_Identity" required onKeyUp="CheckAgain();" style="width:200px;" value="<?php echo @$_GET['ids'];?>"> 
        <input type="button" value="檢查身分" class="Button_General" onClick="callByAJAX2();">
        
    </td>
    </tr>
    
    </table>
    <div id="RepeatAccount"></div>
    <br/>
    </div>
    <!--檢查身分是否註冊過ed-->
    
    
    </form>
    <?php }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
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
<script type="text/javascript" src="js/jsfunction.js"></script>
<script type="text/javascript">

 $(document).ready(function(event) {
	
    $('form[name=Form_Add]').submit(function(event){
		if(document.getElementById('RepeatM')){
			if(document.getElementById('RepeatM').value=='無重複' || document.getElementById('RepeatM').value=='無重複1'){
				
				
				//設定CSS OP
				
				//通訊OP
				if(document.getElementById("County_ID").value!=""){
					document.getElementById("County_ID").style.backgroundColor='#D7FFD7';
					$("span.Msg_County_ID").hide();  
				}
				else{
					document.getElementById("County_ID").focus();
					document.getElementById("County_ID").style.backgroundColor='#ffe1e1';
					$("span.Msg_County_ID").show(); 
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
				//通訊ED
				
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
				/*if(document.getElementById("Member_Tel").value.length!=0 && document.getElementById("Member_Tel").value.length<9){
					document.getElementById("Member_Tel").focus();
					document.getElementById("Member_Tel").style.backgroundColor='#ffe1e1';
					$("span.Msg_Tel").show(); 		    
					
				}
				if(document.getElementById("Emergency_Person_Tel").value.length!=0 && document.getElementById("Emergency_Person_Tel").value.length<9){
					document.getElementById("Emergency_Person_Tel").focus();
					document.getElementById("Emergency_Person_Tel").style.backgroundColor='#ffe1e1';
					$("span.Msg_ETel").show(); 			    
				   
				}*/
				if(document.getElementById("Member_Birthday").value=="")
				{
					document.getElementById("Member_Birthday").focus();
					document.getElementById("Member_Birthday").style.backgroundColor='#ffe1e1';
					$("span.Msg_Date").show(); 
						
				}
				/*if(document.getElementById("Member_Phone").value.length!=0 && document.getElementById("Member_Phone").value.length<10)
				{
					document.getElementById("Member_Phone").focus();
					document.getElementById("Member_Phone").style.backgroundColor='#ffe1e1';
					$("span.Msg_Phone").show();		   
				   
				}*/
				if(!document.getElementById("RepeatM")||document.getElementById("RepeatM").value=='重複'){
					document.getElementById("Member_Identity").focus();
					document.getElementById("Member_Identity").style.backgroundColor='#ffe1e1';	
				}
				else{
					document.getElementById("Member_Identity").style.backgroundColor='#D7FFD7';
				}
				
				
				//add stuff here 判斷值
				if( document.getElementById("Member_Password").value.length>4 && (document.getElementById("Member_Tel").value!="" || document.getElementById("Member_Phone").value!="" ) && (document.getElementById("Emergency_Person_Tel").value!="") && document.getElementById("Title").value!="" && document.getElementById("Member_Birthday").value!="" ){
					return true;
				}
				else if(document.getElementById("Member_Tel").value=="" && document.getElementById("Member_Phone").value==""){
					alert("請輸入行動電話或室內電話！");
					return false;
				}
				else{
					return false;//判斷格式和必填
				}
				
				
			}
			else{
				return false;//判斷為無重複
			}
		}
		else{
			
			return false;
			//判斷是否檢查
		}
		 
		 
		 
    });
});	


	
//檢查身分證OP			
function callByAJAX2(){
	
	
	
        if(document.getElementById("Com_ID").value==""){
		document.getElementById("RepeatAccount").innerHTML = "請選擇校別";
		}
	else{
		// mainItemValue 代表 option value, 其值對應到 printing p_
		document.getElementById("Member_Identity").value=document.getElementById("Member_Identity").value.toUpperCase();//轉大寫
		//全形轉半形OP	
		var text = document.getElementById("Member_Identity").value;
		var asciiTable = "!\"#$%&\’()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";
		var big5Table = "%uFF01%u201D%uFF03%uFF04%uFF05%uFF06%u2019%uFF08%uFF09%uFF0A%uFF0B%uFF0C%uFF0D%uFF0E%uFF0F%uFF10%uFF11%uFF12%uFF13%uFF14%uFF15%uFF16%uFF17%uFF18%uFF19%uFF1A%uFF1B%uFF1C%uFF1D%uFF1E%uFF1F%uFF20%uFF21%uFF22%uFF23%uFF24%uFF25%uFF26%uFF27%uFF28%uFF29%uFF2A%uFF2B%uFF2C%uFF2D%uFF2E%uFF2F%uFF30%uFF31%uFF32%uFF33%uFF34%uFF35%uFF36%uFF37%uFF38%uFF39%uFF3A%uFF3B%uFF3C%uFF3D%uFF3E%uFF3F%u2018%uFF41%uFF42%uFF43%uFF44%uFF45%uFF46%uFF47%uFF48%uFF49%uFF4A%uFF4B%uFF4C%uFF4D%uFF4E%uFF4F%uFF50%uFF51%uFF52%uFF53%uFF54%uFF55%uFF56%uFF57%uFF58%uFF59%uFF5A%uFF5B%uFF5C%uFF5D%uFF5E";
		var result = "";
		for (var i = 0; i < text.length; i++) {
			var val = escape(text.charAt(i));
			var j = big5Table.indexOf(val);
			result += (((j > -1) && (val.length == 6)) ? asciiTable.charAt(j / 6) : text.					charAt(i));
		}
		document.getElementById("Member_Identity").value = result;
		//全形轉半形END
		var mainItemValue = document.getElementById("Member_Identity").value; 
		var mainItemValue2 = document.getElementById("Com_ID").value; 
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
				$("#RepeatAccount").html(xmlhttp_subitems.responseText);
				
				if(document.getElementById("RepeatM").value!="重複"){
					var firstOpen = true;
		
					//css OP			
			     		
					
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
					twfgck_check();
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
							 
							 if(document.getElementById("RepeatM").value=="無重複1"){
							 	 
							   callByAJAX3();
							   
							   callPostal();
							   
							  }
							
							 document.getElementById("CheckOK").style.display="";
							 
							 document.getElementById("Member_Identity").style.backgroundColor="#D7FFD7";
							 				
							 date_check();
							 return true;
						 }
						 else{
							 document.getElementById("CheckOK").style.display="none";
							 document.getElementById("RepeatAccount").innerHTML ='無此身分證字號';
							 document.getElementById("Member_Identity").style.backgroundColor="#ffe1e1";
							
							 return false; 
									 
						 }
						
						  
						
					 }
					 else{//當勾選僑生
						 if(document.getElementById("Member_Identity").value.length<8){
							 document.getElementById("RepeatAccount").innerHTML ="格式錯誤，至少八碼！"; 
							 document.getElementById("CheckOK").style.display="none";
							 }
						 else{
						 
						   if(document.getElementById("RepeatM").value=="無重複1"){
							   callByAJAX3();
							   callPostal();
						   }
						   date_check();
						   document.getElementById("CheckOK").style.display="";						
						 }				  
					  }//Foreigns.checked end
					  
				
				}//檢查是否重複 end
				else{
				}
				
		    }
		
	}
	
	
	xmlhttp_subitems.open("get", "account_value.php?Member_Identity=" + encodeURI(mainItemValue)+"&Com_ID="+encodeURI(mainItemValue2)+'&forms_type=add', true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
	

	}
			
}	

    function CheckAgain(){document.getElementById("CheckOK").style.display="none";document.getElementById("RepeatAccount").innerHTML="請先檢查身分，已確認是否已註冊過";}
//檢查身分證END	
	

//未註冊使用的住址OP

   function callByAJAX()
	{	
	
       
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var mainItemValue = document.getElementById("County_ID").value;  
	
	if (window.XMLHttpRequest) 
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_subitems_area = new XMLHttpRequest();
	} 
	else 
	{  
		// code for IE6, IE5
		xmlhttp_subitems_area = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp_subitems_area.onreadystatechange = function() 
	{
		if (xmlhttp_subitems_area.readyState==4 && xmlhttp_subitems_area.status==200){
			$("#County_Name").html(xmlhttp_subitems_area.responseText);
		}
			
	}
	
	xmlhttp_subitems_area.open("get", "cate_value.php?County_Cate=" + encodeURI(mainItemValue), true);
	xmlhttp_subitems_area.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems_area.send();

	}
	
	
	
//未註冊使用的住址END


	function callByAJAX3()
	{
	// var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2", {validateOn:["blur", "change"]});
        // var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3", {validateOn:["blur", "change"]});
       
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var mainItemValue = document.getElementById("County_ID").value;  
	var mainItemValue2 = document.getElementById("CountyNameData").value; 
	
	if (window.XMLHttpRequest) 
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_subitems3 = new XMLHttpRequest();
	} 
	else 
	{  
		// code for IE6, IE5
		xmlhttp_subitems3 = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp_subitems3.onreadystatechange = function() 
	{
		if (xmlhttp_subitems3.readyState==4 && xmlhttp_subitems3.status==200){
			$("#County_Name").html(xmlhttp_subitems3.responseText);
		}
	}
	
	xmlhttp_subitems3.open("get", "cate_value.php?County_Cate=" + encodeURI(mainItemValue)+"&County_Name="+ encodeURI(mainItemValue2), true);
	xmlhttp_subitems3.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems3.send();

	}
	function callPostal()
	{   
		if(document.getElementById("County_Name").value!=""){
			
			document.getElementById("Postal").innerHTML=document.getElementById("County_Name").value;
			document.getElementById("Postal_Code").value=document.getElementById("County_Name").value;
	        }
		else{
	        	if(document.getElementById("Postal_CodeOrigin") && document.getElementById("Postal_CodeOrigin").value!=""){
	   	 		document.getElementById("Postal").innerHTML=document.getElementById("Postal_CodeOrigin").value;
				document.getElementById("Postal_Code").value=document.getElementById("Postal_CodeOrigin").value;
			}
		}
	}
	function resetall(){
		document.getElementById("Postal").innerHTML="";
	    document.getElementById("Postal_Code").value="";

		}
		
//住址ED

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
</body>
</html>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php if(isset($_POST['Postal_Code'])&&$_POST['Postal_Code']<>""){mysql_free_result($Area2);}?>
	
