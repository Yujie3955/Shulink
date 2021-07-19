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
$query_Data = sprintf("SELECT * FROM member_detail_student WHERE  Member_Show=1  and  Com_ID = %s and Member_Identity=%s order by Edit_Time DESC,Add_Time DESC",GetSQLValueString($row_AdminMember['Com_ID'], "int"),GetSQLValueString($row_AdminMember['Member_Identity'], "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);


$query_Data2 = sprintf("SELECT Com_ID,Com_Name,Member_ID FROM member_detail_student WHERE  Member_Show=1 and Member_Identity=%s",GetSQLValueString($row_AdminMember['Member_Identity'], "text"));
$Data2 = mysql_query($query_Data2, $dbline) or die(mysql_error());
$row_Data2= mysql_fetch_assoc($Data2);
$totalRows_Data2 = mysql_num_rows($Data2);


$query_Cate = sprintf("SELECT * FROM Community where Com_ID Like %s and Com_Enable=1 and Com_ID <> 4 order by Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate= mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);


$query_County = "SELECT * FROM County Group by County_Cate order by County_ID ASC";
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



if(isset($_POST['Postal_Code'])&&$_POST['Postal_Code']<>""){
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





$Other = "修改學員資料";

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Form_Edit")) {
	
        $EditTime=date("Y-m-d H:i:s");
	require_once('../../Include/function_array_keys.php'); //插入陣列公式
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
	$Member_CTel=$_POST['Member_CTel']."#".$_POST['Member_CTel2'];
	if(isset($_POST['Ori_Member_Password']) && $_POST['Ori_Member_Password']==$_POST['Member_Password']){
		$Member_Password=$_POST['Ori_Member_Password'];
	}
	else{
		$Member_Password=md5($_POST['Member_Password']);
	}
	$updateSQL = sprintf("UPDATE member SET Member_Country=%s, Member_Rename=%s, Member_Password=%s, Postal_Code=%s, County_ID=%s, Member_Address=%s, Member_Email=%s, Member_Tel=%s, Member_Phone=%s, Member_CTel=%s, Edu_ID=%s, Member_Area=%s, Job_ID=%s, Job_Title=%s, Member_Unitname=%s, Member_Card=%s, Member_CardNo=%s, Emergency_Person=%s, Emergency_Person_Tel=%s, Emergency_Relation=%s, Member_News=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s, Edit_Username=%s where Member_Identity=%s",
                       GetSQLValueString($_POST['Member_Country'], "text"),
		       GetSQLValueString($_POST['Member_Rename'], "text"),
                       GetSQLValueString($Member_Password, "text"),
		       GetSQLValueString($Postal_Code, "int"),
					   GetSQLValueString($CountyID, "int"),	   
					   GetSQLValueString($_POST['Member_Address'], "text"),
					   GetSQLValueString($_POST['Member_Email'], "text"),
					   GetSQLValueString($_POST['Member_Tel'], "text"),
					   GetSQLValueString($_POST['Member_Phone'], "text"),
					   GetSQLValueString($Member_CTel, "text"),					   
					   GetSQLValueString($_POST['Edu_ID'], "int"),
					GetSQLValueString($_POST['Member_Area'], "text"),
					   GetSQLValueString($_POST['Job_ID'], "int"),
					GetSQLValueString($_POST['Job_Title'], "text"),
					   GetSQLValueString($_POST['Member_Unitname'], "text"),
						GetSQLValueString($Member_Card, "text"),
						GetSQLValueString($Member_CardNo, "text"),
					   GetSQLValueString($_POST['Emergency_Person'], "text"),  
					   GetSQLValueString($_POST['Emergency_Person_Tel'], "text"),
					   GetSQLValueString($_POST['Emergency_Relation'], "text"),
						GetSQLValueString($Member_News, "text"),
					   GetSQLValueString($EditTime, "date"),
					   GetSQLValueString($_POST['Edit_Account'], "text"),
                       GetSQLValueString($_POST['Edit_Unitname'], "text"),
                       GetSQLValueString($_POST['Edit_Username'], "text"),
                       GetSQLValueString($row_Data['Member_Identity'], "text"));
	//操作紀錄先寫欄位 OP
	//require_once('../../Include/browse/member_array.php'); 	
	$columns_data="member";
	$columns_dataid="Member_Identity";
	$search_insert_id=$row_Data['Member_Identity'];
	require_once('../../Include/Data_Update_PastContent.php'); 
	mysql_select_db($database_dbline, $dbline);
	$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	require_once('../../Include/Data_Update_NewContent.php'); 
	require_once('../../Include/Data_BrowseUpdate.php');
	
	$updateGoTo = "AD_Edit_Student.php?Msg=UpdateOK";  
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
?>

<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

 <!--驗證 OP-->
<?php require_once('../../Include/spry_style.php'); ?>
 <!--驗證 ED-->
<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>
<!--日期INPUT ED-->
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
     <input type="button" value="創建社區大學" class="Button_Add" onClick="location.href='AD_Add_Student.php'">
     <div align="center">
     <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div> 
     </div>
	
    
    <?php if ($totalRows_Data > 0) { // Show if recordset not empty ?>
    <form ACTION="<?php echo $editFormAction; ?>" name="Form_Edit" id="Form_Edit" method="POST">
    <div align="center">
      <?php require_once("form_student_self.php");?>
      <input name="ID" type="hidden" id="ID" value="<?php echo $row_Data['Member_ID']; ?>">
     
      <input name="Edit_Account" type="hidden" id="Edit_Account" value="<?php echo $row_AdminMember['Member_Identity']; ?>">
      <input name="Edit_Unitname" type="hidden" id="Edit_Unitname" value="<?php echo $row_AdminMember['Member_Unitname']; ?>">
      <input name="Edit_Username" type="hidden" id="Edit_Username" value="<?php echo $row_AdminMember['Member_UserName']; ?>">
      <input type="submit" value="確定修改" class="Button_Submit"/>
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
<script type="text/javascript" src="../../Modules/Student/js/jsfunction.js"></script>
<script type="text/javascript">
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
		if(xmlhttp_subitems.readyState==4&&xmlhttp_subitems.status==200){
			$("#County_Name").html(xmlhttp_subitems.responseText);
		}
			
	}
	
	xmlhttp_subitems.open("get", "cate_value.php?County_Cate=" + encodeURI(mainItemValue)+"&County_Name="+ encodeURI(mainItemValue2), true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
	
	
	
	
	}
	callPostal();
	function callPostal()
	{   if(document.getElementById("County_Name").value!=""){
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
		
		
		
		
		
	
				


 $(document).ready(function(event) {

    $('form[name=Form_Edit]').submit(function(event){
       
		
	    //設定CSS OP
		
		/*通訊OP*/
		if(document.getElementById("County_ID").value!=""){
			document.getElementById("County_ID").style.backgroundColor='#D7FFD7';
		    $("span.Msg_County_ID").hide();  
		}
		else{
			document.getElementById("County_ID").focus();
			document.getElementById("County_ID").style.backgroundColor='#ffe1e1';
			$("span.Msg_County_ID").show(); 
		}
		if(document.getElementById("Edu_ID").value!=""){
			document.getElementById("Edu_ID").style.backgroundColor='#D7FFD7';
		}
		else{
			document.getElementById("Edu_ID").focus();
			document.getElementById("Edu_ID").style.backgroundColor='#ffe1e1';
		}
		if(document.getElementById("Job_ID").value!=""){
			document.getElementById("Job_ID").style.backgroundColor='#D7FFD7';
		}
		else{
			document.getElementById("Job_ID").focus();
			document.getElementById("Job_ID").style.backgroundColor='#ffe1e1';
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
		/*通訊ED*/
		
		
		if(document.getElementById("Member_Password").value.length<5){
		    document.getElementById("Member_Password").focus();
			document.getElementById("Member_Password").style.backgroundColor='#ffe1e1';
			$("span.Password_Format").show();  
		 	
	        }/*else{
			 
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
		}
		if(document.getElementById("Member_Tel").value.length<9){
		    document.getElementById("Member_Tel").focus();
			document.getElementById("Member_Tel").style.backgroundColor='#ffe1e1';
			$("span.Msg_Tel").show(); 	
	    }
		if(document.getElementById("Emergency_Person_Tel").value.length<9){
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
		
		
		
        //add stuff here 判斷值
		
		if(document.getElementById("County_ID").value!="" && document.getElementById("County_Name").value!="" && document.getElementById("Member_Password").value.length>4 && (document.getElementById("Member_Tel").value!="" || document.getElementById("Member_Phone").value!="") && document.getElementById("Edu_ID").value!="" && document.getElementById("Job_ID").value!=""){	
			
		return true;
		}
		else if(document.getElementById("Member_Tel").value=="" && document.getElementById("Member_Phone").value==""){
					alert("請輸入行動電話或室內電話！");
					return false;
		}
		else{return false;}
		 
		 
		 
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
if(document.getElementById('user_location')){
	
	twfgck(document.getElementById('user_location'));
}
if(document.getElementById("Member_Card")){						
	chosecard(document.getElementById("Member_Card"));
}
</script>
</html>
<?php
mysql_free_result($Cate);
mysql_free_result($Data);
mysql_free_result($Cate2);
mysql_free_result($Cate3);
if($Postal_Code<>$row_Data['Postal_Code']){mysql_free_result($Area2);}
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>

