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


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "Multi_Edit")) {
	$EditTime=date("Y-m-d H:i:s");
	$str_url='';
	if(isset($_GET['Checks1']) && $_GET['Checks1']<>""){$str_url.="&Checks1=".$_GET['Checks1'];}
	if(isset($_GET['Checks2']) && $_GET['Checks2']<>""){$str_url.="&Checks2=".$_GET['Checks2'];}
	if(isset($_GET['Checks3']) && $_GET['Checks3']<>""){$str_url.="&Checks3=".$_GET['Checks3'];}
	if(isset($_GET['Season_Code']) && $_GET['Season_Code']<>""){$str_url.="&Season_Code=".$_GET['Season_Code'];}
	if(isset($_GET['Unit_ID']) && $_GET['Unit_ID']<>""){$str_url.="&Unit_ID=".$_GET['Unit_ID'];}
	if(isset($_GET['Course_Title']) && $_GET['Course_Title']<>""){$str_url.="&Course_Title=".$_GET['Course_Title'];}

	if(isset($_POST['CourseIDs']) && $_POST['CourseIDs'][0]<>""){
		for($c1=0;$c1<count($_POST['CourseIDs']);$c1++){
			$query_CateP = sprintf("SELECT * FROM course WHERE Course_NO=%s and Season_Code=%s and Com_ID=%s and Course_ID <> %s",GetSQLValueString($_POST['Course_NO'][$_POST['CourseIDs'][$c1]], "text"),GetSQLValueString($_POST['Season_Code'][$_POST['CourseIDs'][$c1]], "text"),GetSQLValueString($_POST['Com_ID'][$_POST['CourseIDs'][$c1]], "text"),GetSQLValueString($_POST['CourseIDs'][$c1], "text"));
			$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
			$row_CateP = mysql_fetch_assoc($CateP);
			$totalRows_CateP = mysql_num_rows($CateP);
			
			if($totalRows_CateP>0){
				?><script type="text/javascript">
				alert("課編<?php echo $row_CateP['Course_NO'];?>重覆，將不更新！");
				</script>
				<?php
				
			}
			else{
				$updateSQL=sprintf("update course set Course_NO=%s, Edit_Time=%s, Edit_Account=%s, Edit_Unitname=%s,  Edit_Username=%s where Course_ID=%s",
									   GetSQLValueString($_POST['Course_NO'][$_POST['CourseIDs'][$c1]], "text"),
									   
									   GetSQLValueString($EditTime, "date"),
									   GetSQLValueString($row_AdminMember['Account_Account'], "text"),
									   GetSQLValueString($row_AdminMember['Account_JobName'], "text"),
									   GetSQLValueString($row_AdminMember['Account_UserName'], "text"),
									   GetSQLValueString($_POST['CourseIDs'][$c1], "int"));
				mysql_select_db($database_dbline, $dbline);
				$Result1 = mysql_query($updateSQL, $dbline) or die(mysql_error());
			
			}
			mysql_free_result($CateP);	
		}
		?>
		<script type="text/javascript">
				alert("更新完成！");
				</script>
		<?php 
	}
}

$maxRows_Data = 10;
$pageNum_Data = 0;
if (isset($_GET['search_Count']) && is_numeric($_GET['search_Count']) == true) {
  		$maxRows_Data = $_GET['search_Count'];
	}else{
		$maxRows_Data = 10;
	}
if (isset($_GET['pageNum_Data'])) {
  $pageNum_Data = $_GET['pageNum_Data'];
}
$startRow_Data = $pageNum_Data * $maxRows_Data;



//搜班季
$colname02_Data = "%";
if ((isset($_GET['Season_Code'])) && ($_GET['Season_Code'] != "")) {
  $colname02_Data = $_GET['Season_Code'];
}
//搜課程名稱
$colname03_Data = "%";
if ((isset($_GET['Course_Title'])) && ($_GET['Course_Title'] != "")) {
  $colname03_Data = "%".$_GET['Course_Title']."%";
}
//搜學校
$colname04_Data=$colname02_Unit;
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
  $colname04_Data = $_GET['Unit_ID'];
}

$colname05_Data='%';
if (isset($_GET['Checks1']) && $_GET['Checks1'] <> "") {
  $colname05_Data = $_GET['Checks1']; 
}
$colname06_Data='%';
if (isset($_GET['Checks2']) && $_GET['Checks2'] <> "") {
  $colname06_Data = $_GET['Checks2'];
}
$colname07_Data='%';
if (isset($_GET['Checks3']) && $_GET['Checks3'] <> "") {
  $colname07_Data = $_GET['Checks3'];
}
$is_teacher="";
if(isset($_GET["IsT"]) && $_GET["IsT"]==1){
	$is_teacher=" and Course_IsTeacher=1";
}
$query_Data = sprintf("SELECT * FROM course_list WHERE ifnull(Season_Code,'') Like %s and (ifnull(CourseKind_Name,'') Like %s or ifnull(Course_Name,'') Like %s) and ifnull(Com_ID,'') like %s and ifnull(Unit_ID,'') like %s and Course_Check1 like %s and Course_Check2 like %s and Course_Check3 like %s and Course_Pass=1 ".$is_teacher."  ORDER BY Season_Code DESC, Add_Time DESC, Course_ID DESC",GetSQLValueString($colname02_Data, "text"),GetSQLValueString($colname03_Data, "text"),GetSQLValueString($colname03_Data, "text"), GetSQLValueString($colname03_Unit, "text"), GetSQLValueString($colname04_Data, "text"), GetSQLValueString($colname05_Data, "text"), GetSQLValueString($colname06_Data, "text"), GetSQLValueString($colname07_Data, "text"));
$query_limit_Data = sprintf("%s LIMIT %d, %d", $query_Data, $startRow_Data, $maxRows_Data);
$Data = mysql_query($query_limit_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);

if (isset($_GET['totalRows_Data'])) {
  $totalRows_Data = $_GET['totalRows_Data'];
} else {
  $all_Data = mysql_query($query_Data);
  $totalRows_Data = mysql_num_rows($all_Data);
}
$totalPages_Data = ceil($totalRows_Data/$maxRows_Data)-1;
$queryString_Data = "";
	if (!empty($_SERVER['QUERY_STRING'])) {
		$params = explode("&", $_SERVER['QUERY_STRING']);
		$newParams = array();
		foreach ($params as $param) {
			if (stristr($param, "pageNum_Data") == false && stristr($param, "totalRows_Data") == false) {
				array_push($newParams, $param);
			}
		}
		if (count($newParams) != 0) {
			$queryString_Data = "&" . htmlentities(implode("&", $newParams));
		}
	}
	$queryString_Data = sprintf("&totalRows_Data=%d%s", $totalRows_Data, $queryString_Data);
	
	
$query_Cate = sprintf("SELECT distinct course_list.Season_Code FROM course_list where Com_ID like %s ORDER BY course_list.Season_Code ASC",GetSQLValueString($colname03_Unit, "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

//看到範圍
if($row_AdminMember['Unit_Range']>=3){
$query_Cate2 = "SELECT * FROM unit_detail where Unit_IsSchool=1 ORDER BY Com_ID ASC,Unit_ID ASC";
}
else{
$query_Cate2 = "SELECT * FROM unit_detail where Com_ID like '".$colname03_Unit."' and Unit_ID like '".$colname02_Unit."' and Unit_IsSchool=1 ";	
	}
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);

$query_ComCate = sprintf("SELECT * FROM community where Com_IsSchool=1 and Com_IsPrivate=0 and Com_Enable=1 and Com_ID like %s ORDER BY Com_ID ASC",GetSQLValueString($colname03_Unit, "text"));
$ComCate = mysql_query($query_ComCate, $dbline) or die(mysql_error());
$row_ComCate = mysql_fetch_assoc($ComCate);
$totalRows_ComCate = mysql_num_rows($ComCate);




?>


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

<!--日期INPUT OP-->
<link href="../../Tools/bootstrap-datepicker-master/tt/css/bootstrap-datetimepicker.css" rel="stylesheet">
<script src="../../Tools/bootstrap-datepicker-master/tt/js/moment-with-locales.js"></script>
<script src="../../Tools/bootstrap-datepicker-master/tt/js/bootstrap-datetimepicker.js"></script>
<!--日期INPUT ED-->
</head>
<body>
<!-- Body Top Start -->
<?php require_once('../../Include/Admin_Body_Top.php'); ?>
<?php  require_once('../../Include/Menu_AdminLeft.php'); ?>
<!-- Body Top End -->
<!--Body menu top Start-->
<?php //require_once('../../Include/Admin_menu_upon.php'); ?>
<!--Body menu top End-->
<!--Body Layout up Start-->
<?php //require_once('../../Include/Admin_Body_Layout_up.php'); ?>
<!--Body Layout up End-->
<div>   	
    <center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']?>管理：<?php if(isset($_GET['Checks']) && $_GET['Checks']==0){echo '未審區';}elseif(isset($_GET['Checks']) && $_GET['Checks']==1){echo '審核通過';}elseif(isset($_GET['Checks']) && $_GET['Checks']==2){echo '保留區';}?></div>
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

     <?php if($row_Permission['Per_Add'] == 1){ ?>
     新增課程：<select name="Add_Com_Course" id="Add_Com_Course">
	<option value=''>請選擇</option>
	<?php if($totalRows_ComCate>0){
		do{?>
	<option value="<?php echo $row_ComCate['Com_ID'];?>"><?php echo $row_ComCate['Com_Name'];?></option>
        <?php 	}while($row_ComCate=mysql_fetch_assoc($ComCate));
		mysql_data_seek($ComCate,0);
		$row_ComCate=mysql_fetch_assoc($ComCate);
		}?>
     </select>
     <input type="button" value="新增課程" onclick="addcourse_com();" class="Button_Add">
     <script type="text/javascript">
	function addcourse_com(){
	    if($("#Add_Com_Course").val()!=""){
	    	location.href='AD_Data_Add.php?Com_ID='+$("#Add_Com_Course option:selected").val();
	    }else{alert("請選擇新增哪個社區大學的課程！")}
	}
     </script>
     <?php }?>
    
    <?php if($row_Permission['Per_View'] == 1){ ?>
    <form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET">
    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
	
		
      
      <?php if($row_PermissionCate['Per_View']==1){?>   
      <input type="button" value="歷史課程" class="Button_Add" onClick="location.href='AD_Data_History.php<?php if(isset($_GET["IsT"]) && $_GET["IsT"]==1){echo "?IsT=".$_GET["IsT"];}?>'"/>
      <input type="button" value="課審審查表單" class="Button_Add" onClick="window.open('AD_Data_ExcelGov.php')"/>
      <?php }?></td>
      <td class="right"><img src="../../Icon/find.png" class="middle">   
      初審:<select name="Checks1">
      <option value="">全部</option>
      <option value="1" <?php if(isset($_GET['Checks1']) && $_GET['Checks1']=='1'){echo 'selected';}?>>通過</option>
      <option value="2" <?php if(isset($_GET['Checks1']) && $_GET['Checks1']=='2'){echo 'selected';}?>>未通過</option>      
      <option value="0" <?php if(isset($_GET['Checks1']) && $_GET['Checks1']=='0'){echo 'selected';}?>>待審</option>
      </select>
      複審:<select name="Checks2">
      <option value="">全部</option>
      <option value="1" <?php if(isset($_GET['Checks2']) && $_GET['Checks2']=='1'){echo 'selected';}?>>通過</option>
      <option value="2" <?php if(isset($_GET['Checks2']) && $_GET['Checks2']=='2'){echo 'selected';}?>>未通過</option>      
      <option value="0" <?php if(isset($_GET['Checks2']) && $_GET['Checks2']=='0'){echo 'selected';}?>>待審</option>
      </select>
      決審:<select name="Checks3">
      <option value="">全部</option>
      <option value="1" <?php if(isset($_GET['Checks3']) && $_GET['Checks3']=='1'){echo 'selected';}?>>通過</option>
      <option value="2" <?php if(isset($_GET['Checks3']) && $_GET['Checks3']=='2'){echo 'selected';}?>>未通過</option>      
      <option value="0" <?php if(isset($_GET['Checks3']) && $_GET['Checks3']=='0'){echo 'selected';}?>>待審</option>
      </select>   
      班季:<select name="Season_Code" id="Season_Code" >
        <option value="">:::全部:::</option>
        <?php if($totalRows_Cate>0){
				  do { ?>
			<option value="<?php echo $row_Cate['Season_Code']; ?>" <?php if (isset($_GET['Season_Code'])&&$_GET['Season_Code'] == $row_Cate['Season_Code']) { echo "selected='selected'"; } ?>><?php if(substr($row_Cate['Season_Code'],-1,1)=="1"){echo substr_replace($row_Cate['Season_Code'],'春季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="2"){echo substr_replace($row_Cate['Season_Code'],'夏季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="3"){echo substr_replace($row_Cate['Season_Code'],'秋季班',-1);}if(substr($row_Cate['Season_Code'],-1,1)=="4"){echo substr_replace($row_Cate['Season_Code'],'冬季班',-1);}  ?></option>
			<?php } while ($row_Cate = mysql_fetch_assoc($Cate));
		      } ?>
      </select>
      <select name="Unit_ID" id="Unit_ID" >
        <option value="">:::全部:::</option>
        <?php do { ?>
        <option value="<?php echo $row_Cate2['Unit_ID']; ?>" <?php if (isset($_GET['Unit_ID'])&&$_GET['Unit_ID'] == $row_Cate2['Unit_ID']) { echo "selected"; } ?>><?php echo $row_Cate2['Unit_Name'];?></option>
        <?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
      </select>
      
      	 <div class="display-inline">標題、類別:</div><input type="text" name="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Title" id="<?php echo $row_ModuleSet['ModuleSetting_Code']; ?>_Title" value="<?php echo @$_GET['Course_Title']; ?>" placeholder="請輸入標題/類別關鍵字"> <input type="submit" value="查詢" class="Button_General">
         <input type="button" value="全部顯示"  onClick="location.href='<?php echo @$_SERVER["PHP_SELF"];?>'"  class="Button_General">
			<?php if (isset($_GET['IsT'])) {  ?><input name="IsT" type="hidden" value="<?php echo $_GET['IsT']; ?>"/><?php }  ?></td>
      </tr>
    </table>
    </div>
    </form>
    
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg DelError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料刪除失敗，此課程已有學員報名</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
           <div class="Error_Msg AddError" style="display:none;"><img src="../../Icon/delete.gif" alt="成功訊息" class="middle"> 資料登錄失敗！</div>
          
      
 <form name="Multi_Edit" id="M<ulti_Edit" method="post">   
<?php /*<input name="Course_CheckOn" id="Course_CheckOn" value="全勾選" type="button">
<input name="Course_CheckOff" id="Course_CheckOff" value="全取消" type="button">*/?>
<?php if($row_AdminMember['Unit_Range']>="1"&&$row_Permission['Per_Edit'] == 1&&$row_Permission['Per_Pass'] == 1){ ?>
<input name="submit1" type="submit" value="確定修改課程編號" style="display:inline-block" class="Button_Submit">
<input name="MM_update" type="hidden" value="Multi_Edit">
<?php }?>
        <table width="95%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
          <tr class="TableBlock_shadow_Head_Back">
          	<td class="middle center" width="8%">班季</td>
	    <td class="middle center" width="15%">社區大學</td>
<td class="middle center" width="2%"></td>
            <td class="middle center" width="15%">原始課程編號</td>
            <td class="middle center" width="15%">預編課程編號</td>
            <td class="middle center" width="15%">前期課程編號</td>
            <td class="middle center" width="15%">名稱</td>
            <td class="middle center" width="15%">大分類</td>
            <td class="middle center" width="8%">子分類</td>
            <td class="middle center" width="8%">上課時間</td>
           
	    <td class="middle center" width="8%">學習中心</td>
            <td class="middle center" width="10%">老師</td>

	    <td class="middle center" width="8%">招數</td>
	    <td class="middle center" width="8%">報數</td>
	    <td class="middle center" width="8%">繳數</td>

            <td class="middle center" width="8%">初審</td>
	    <td class="middle center" width="8%">複審</td>
	    <td class="middle center" width="8%">決審</td>
			
          </tr>
           <?php if ($totalRows_Data > 0) { // Show if recordset not empty 
		   //分頁功能OP
		 
				$now_page = 0; //取得當前頁數
				if (isset($_GET['pageNum_Data']) && $_GET['pageNum_Data'] != "") { $now_page = $_GET['pageNum_Data']; }
				$min_page = max(0,$now_page-5);
				$max_page = min($totalPages_Data, $now_page+5);
				if (($now_page < 7) && ($totalPages_Data > 10)) {
					$min_page = 0;
					$max_page = 11;
				}	   
		   //分頁功能END
		   ?>
			<?php do { ?>
              <tr>
              	<td class="middle center">
				<?php echo $row_Data['Season_Year'].$row_Data['SeasonCate_Name'];?></td>
                <td class="middle center">
				 <?php echo $row_Data['Com_Name'];?>
                </td>
		<td class="middle center">
				 <?php /*<input type="checkbox" name="CourseIDs[]" id="CourseIDs<?php echo $row_Data['Course_ID'];?>" class="CheckCourse" value="<?php echo $row_Data['Course_ID'];?>">	*/?>
				 <input type="hidden" name="CourseIDs[]" id="CourseIDs<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Course_ID'];?>">	
				 <input type="hidden" name="Season_Code[<?php echo $row_Data['Course_ID'];?>]" id="Season_Code<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Season_Code'];?>">
				 <input type="hidden" name="Com_ID[<?php echo $row_Data['Course_ID'];?>]" id="Com_ID<?php echo $row_Data['Course_ID'];?>" value="<?php echo $row_Data['Com_ID'];?>">			 
                </td>
                <td class="middle center">
				 <?php echo $row_Data['Course_SetNO'];?>
                </td>
                <td class="middle center">
		<input name="Course_NO[<?php echo $row_Data['Course_ID'];?>]" type="text" value="<?php echo $row_Data['Course_NO'];?>">
                </td>
                <td class="middle center">
				 <?php echo $row_Data['Course_OriNO'];?>
                </td>
                <td class="middle Black">
                <a href="javascript:newin(900,700,'AD_Data_Detail.php?ID=<?php echo $row_Data['Course_ID'];?>')"><?php echo mb_substr($row_Data['Course_Name'],0,12,"utf8");if(mb_strlen($row_Data['Course_Name'],'utf-8')>12){echo '...';}?></a>
                </td>
                <td class="middle center MainColor">
                <?php echo $row_Data['CourseKind_Name'];?>
                </td>
                <td class="middle center MainColor">
                <?php echo $row_Data['CourseKindCate_Name']?>
                </td>
                <td class="middle center"><?php echo $row_Data['Course_Day1'].$row_Data['Course_Time'].date("H:i",strtotime($row_Data['Course_Start1']))."~".date("H:i",strtotime($row_Data['Course_End1'])); ?></td>
       			
                <td class="middle center"><?php echo str_replace(",",",<br/>",$row_Data['Teacher_UserName'])."&nbsp;"; ?></td>
                <td class="middle center"><?php echo $row_Data['Unit_Name']; ?></td>

<?php

	//選課人次
/*$query_SignCate = sprintf("select a.Course_ID, sum(Course_Num) as Course_Num from (SELECT
count(Course_ID) as Course_Num,
signup_itemmoney.Course_ID
FROM
signup_itemmoney
inner join signup on signup.Signup_ID=signup_itemmoney.Signup_ID and signup_itemmoney.Signup_Status <> '已繳費'
where signup_itemmoney.CP_Text='學分費' and signup_itemmoney.Course_ID=%s
group by signup_itemmoney.Course_ID,signup_itemmoney.Member_ID
union all select 
count(Course_ID) as Course_Num,
signup_record_count.Course_ID
FROM
signup_record_count 
where signup_record_count.Course_ID=%s
group by signup_record_count.Course_ID
) as a
where Course_ID=%s
group by a.Course_ID",GetSQLValueString($row_Data['Course_ID'], "int"),GetSQLValueString($row_Data['Course_ID'], "int"),GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate = mysql_query($query_SignCate, $dbline) or die(mysql_error());
$row_SignCate = mysql_fetch_assoc($SignCate);
$totalRows_SignCate = mysql_num_rows($SignCate);*/
$query_SignCate = sprintf("SELECT
count(Course_ID) as Course_Num,
signup_itemmoney.Course_ID
FROM
signup_itemmoney
inner join signup on signup.Signup_ID=signup_itemmoney.Signup_ID and signup_itemmoney.Signup_Status <> '已繳費'
where signup_itemmoney.CP_Text='學分費' and signup_itemmoney.Course_ID=%s
group by signup_itemmoney.Course_ID,signup_itemmoney.Member_ID
",GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate = mysql_query($query_SignCate, $dbline) or die(mysql_error());
$row_SignCate = mysql_fetch_assoc($SignCate);
$totalRows_SignCate = mysql_num_rows($SignCate);
$SignupNum=0;//選課人次
if($totalRows_SignCate>0){
	$SignupNum=$SignupNum+$row_SignCate['Course_Num'];//選課人次
}
mysql_free_result($SignCate);
$query_SignCate = sprintf("select 
count(b.Course_ID) as Course_Num,
b.Course_ID
FROM
(
SELECT
SUBSTRING_INDEX(
		SUBSTRING_INDEX(
			signup_record_alist.Course_ID,
			';',
			numbers.Number
		),
		';',
		- 1
	)  Course_ID,
signup_record_alist.SignupRecord_ID,
signup_record_alist.Member_ID,
signup_record_alist.Season_Code
FROM
	numbers
INNER JOIN signup_record_alist ON CHAR_LENGTH(signup_record_alist.Course_ID) - CHAR_LENGTH(REPLACE(signup_record_alist.Course_ID,';',''))>= (numbers.Number - 1)
where Course_ID=%s
ORDER BY
	SignupRecord_ID ,
	Number 
) as b
where b.Course_ID=%s
group by b.Course_ID
",GetSQLValueString($row_Data['Course_ID'], "int"),GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate = mysql_query($query_SignCate, $dbline) or die(mysql_error());
$row_SignCate = mysql_fetch_assoc($SignCate);
$totalRows_SignCate = mysql_num_rows($SignCate);
if($totalRows_SignCate>0){
	$SignupNum=$SignupNum+$row_SignCate['Course_Num'];//選課人次
}
mysql_free_result($SignCate);

$query_SignCate2 = sprintf("select ifnull(prints_record_people.People,0) as PayNum from prints_record_people 
where prints_record_people.Course_ID=%s
group by prints_record_people.Course_ID",GetSQLValueString($row_Data['Course_ID'], "int"));
$SignCate2 = mysql_query($query_SignCate2, $dbline) or die(mysql_error());
$row_SignCate2 = mysql_fetch_assoc($SignCate2);
$totalRows_SignCate2 = mysql_num_rows($SignCate2);

$PayNum=0;//繳費
if($totalRows_SignCate2>0){
	$PayNum=$row_SignCate2['PayNum'];
}
mysql_free_result($SignCate2);
?>
	   	<td class="middle center"><?php echo $row_Data['Course_Max'];?></td>
	    	<td class="middle center"><?php echo $SignupNum;?></td>
	    	<td class="middle center"><?php echo $PayNum;?></td>

                <td class="middle center">
		<?php if($row_Data['Course_Check1']==1){echo '通過';}elseif($row_Data['Course_Check1']==2){echo '不通過';}elseif($row_Data['Course_Check1']==3){echo '修正通過';}else{echo '待審';}?></td>
		<td class="middle center">
		<?php if($row_Data['Course_Check2']==1){echo '通過';}elseif($row_Data['Course_Check2']==2){echo '不通過';}elseif($row_Data['Course_Check2']==3){echo '修正通過';}else{echo '待審';}?></td>
		<td class="middle center">
		<?php if($row_Data['Course_Check3']==1){echo '通過';}elseif($row_Data['Course_Check3']==2){echo '不通過';}elseif($row_Data['Course_Check3']==3){echo '修正通過';}else{echo '待審';}?></td>
                 
                
              </tr>
              <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
            <?php } // Show if recordset not empty ?>
        </table>
</form>
<script type="text/javascript">
$("#Course_CheckOn").on('click',function(){
	$(".CheckCourse").prop("checked",true);
});
$("#Course_CheckOff").on('click',function(){
	$(".CheckCourse").prop("checked",false);
});
</script>

         
		  <!--分頁OP-->
          <div align="center">
        	<form id="search_Count" name="search_Count" method="get" action="" class="center">
        		每頁筆數：<select id="search_Count" name="search_Count" onChange="this.form.submit()">
	                    	<option value="10">10</option>
	                        <option value="20" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 20) { echo "selected='selected'"; } ?>>20</option>
	                        <option value="50" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 50) { echo "selected='selected'"; } ?>>50</option>
	                        <option value="100" <?php if (isset($_GET['search_Count']) && $_GET['search_Count'] == 100) { echo "selected='selected'"; } ?>>100</option>
                    	</select>
                    	<?php 
						if (isset($_GET['Unit_ID'])) {  ?><input name="Unit_ID" type="hidden" value="<?php echo $_GET['Unit_ID']; ?>"/><?php }  ?>
                    	<?php if (isset($_GET['Course_Title'])) {  ?><input name="Course_Title" type="hidden" value="<?php echo $_GET['Course_Title']; ?>"/><?php }  ?>
                        <?php if (isset($_GET['Season_Code'])) {  ?><input name="Season_Code" type="hidden" value="<?php echo $_GET['Season_Code']; ?>"/><?php }  ?>
			<?php if (isset($_GET['Checks1'])) {  ?><input name="Checks1" type="hidden" value="<?php echo $_GET['Checks1']; ?>"/><?php }  ?>
			<?php if (isset($_GET['Checks2'])) {  ?><input name="Checks2" type="hidden" value="<?php echo $_GET['Checks2']; ?>"/><?php }  ?>
			<?php if (isset($_GET['Checks3'])) {  ?><input name="Checks3" type="hidden" value="<?php echo $_GET['Checks3']; ?>"/><?php }  ?>
			<?php if (isset($_GET['IsT'])) {  ?><input name="IsT" type="hidden" value="<?php echo $_GET['IsT']; ?>"/><?php }  ?>
        	</form>		
		   <table border="0">
                    <tr>
                        <td>
                        <?php if ($pageNum_Data > 0) { // Show if not first page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, 0, $queryString_Data); ?>'" class="gotopage Button_General" type="button"  value="第一頁" name="b1">
                        <?php } // Show if not first page ?>
                        <?php if ($pageNum_Data > 0) { // Show if not first page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, max(0, $pageNum_Data - 1), $queryString_Data); ?>'" class="gotopage Button_General" type="button"  value="上一頁" name="b2">
                        <?php } // Show if not first page ?>
                        <?php for($ii=@$min_page;$ii<=@$max_page;$ii++){ ?>
                            <?php if ($ii == @$now_page) { ?>
                                <span class="nowpage"><input  class="gotopage Navi_Use" value="<?php echo ($ii+1); ?>" type="button"></span>
                            <?php } else { ?>
                                <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, $ii, $queryString_Data); ?>'" class="gotopage Navi_NoUse" value="<?php echo ($ii+1); ?>" type="button">
                            <?php } ?>
                        <?php } ?>
                        <?php if ($pageNum_Data < $totalPages_Data) { // Show if not last page ?>
                            <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, min($totalPages_Data, $pageNum_Data + 1), $queryString_Data); ?>'" class="gotopage Button_General" value="下一頁" type="button">
                        <?php } // Show if not last page ?>
                        <?php if ($pageNum_Data < $totalPages_Data) { // Show if not last page ?>
                           <input onClick="location.href='<?php printf("%s?pageNum_Data=%d%s", @$currentPage, $totalPages_Data, $queryString_Data); ?>'" class="gotopage Button_General" value="最後一頁" type="button">
                        <?php } // Show if not last page ?>
                        </td>
                    </tr>
                </table>
                <br /><br />
                No. <?php echo ($startRow_Data + 1) ?> ~ <?php echo min($startRow_Data + $maxRows_Data, $totalRows_Data) ?> 共 <?php echo $totalRows_Data ?> 筆資料
                </div>
                <!--分頁END-->  
      </div>
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
      <?php } ?>
        </td>
      </tr>
    </table>
	 </center>
    <br><br><br>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php
mysql_free_result($Data);
mysql_free_result($Cate);
mysql_free_result($Cate2);
?>
<?php require_once('../../Include/zz_Admin_PermissionCate.php'); ?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>