<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_student.php'); ?>

<?php
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$todaynow=date("Y-m-d");

if (isset($_GET['No'])) {
  $colname_No = $_GET['No'];
}
if (isset($_GET['Com_ID'])) {
  $colname_Com = $_GET['Com_ID'];
}
mysql_select_db($database_dbline, $dbline);


/*$query_Data = sprintf("SELECT *, Sum(ifnull(PR_Credits,0)) as SUM_PR_Credits, Sum(ifnull(PR_OriCredits,0)) as SUM_PR_OriCredits FROM prints_record left join prints on prints.Print_No=prints_record.Print_No inner join season on season.Season_Code=prints_record.Season_Code and season.Com_ID=prints_record.Com_ID inner join course on course.Course_ID=prints_record.Course_ID where prints_record.Signup_OrderNumber=%s and prints_record.Com_ID = %s and prints_record.Pass_Enable=1 Group by prints_record.Signup_ID,prints_record.SignupItem_ID Order by prints_record.Add_Time ASC ,prints_record.PR_ID asc",GetSQLValueString($colname_No,"text"),GetSQLValueString($colname_Com,"text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);



if($totalRows_Data>0 && $row_Data['PR_ID']<>0){
	//報名費
	$query_SignData = sprintf("SELECT * FROM signup_sign where Signup_ID=%s and Com_ID = %s",GetSQLValueString($row_Data['Signup_ID'],"int"),GetSQLValueString($row_Data['Com_ID'],"int"));
	$SignData = mysql_query($query_SignData, $dbline) or die(mysql_error());
	$row_SignData = mysql_fetch_assoc($SignData);
	$totalRows_SignData = mysql_num_rows($SignData);
}
else{*/
	$query_Data = sprintf("SELECT *  FROM signup_choose where Signup_OrderNumber=%s and Com_ID=%s",GetSQLValueString($colname_No,"text"),GetSQLValueString($colname_Com,"text"));
	$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
	$row_Data = mysql_fetch_assoc($Data);
	$totalRows_Data = mysql_num_rows($Data);
	$Signup_Isinsurance=$row_Data['Signup_Isinsurance'];	
	$Insurance_Money=$row_Data['Insurance_Money'];

//}
//if(isset($totalRows_SignData) && $totalRows_SignData<1){
	$query_SignData2 = sprintf("SELECT * FROM signup where Signup_ID=%s and Com_ID = %s and Signup_IsSignCost=1 ",GetSQLValueString($row_Data['Signup_ID'],"int"),GetSQLValueString($row_Data['Com_ID'],"int"));
	$SignData2 = mysql_query($query_SignData2, $dbline) or die(mysql_error());
	$row_SignData2 = mysql_fetch_assoc($SignData2);
	$totalRows_SignData2 = mysql_num_rows($SignData2);
	
//}




?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

</head>
<body>

<div>   
   
       
   
       <?php if($totalRows_Data>0){ ?>
       <?php if($row_AdminMember['Member_Identity']==$row_Data['Member_Identity']){ ?>
       <?php require_once('../Sign/Online_Course_list.php');?>
       <?php }else{ ?><br><br><br>
   		 <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您無全流覽此資料</div>  
  	  <?php  } ?>
       <?php }else{ ?><br><br><br>
           <div align="center">無資料</div>
       <?php  } ?>

</div>  
     

<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php
mysql_free_result($Data);
/*if($totalRows_Data>0 && $row_Data['PR_ID']<>0){
mysql_free_result($SignData);
}*/
if(isset($totalRows_SignData) && $totalRows_SignData<1){
	mysql_free_result($SignData2);
}
?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
