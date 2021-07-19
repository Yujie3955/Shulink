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


$colname_Data2="%";
if ((isset($_GET['Member_UserName'])) && ($_GET['Member_UserName'] != "")) {
$colname_Data2="%".$_GET['Member_UserName']."%";
}

//顯示資料多寡OP
if ((isset($_GET['Com_ID'])) && ($_GET['Com_ID'] != "")&&$row_AdminMember['Unit_Range']>=3) {
	$colname03_Unit=$_GET['Com_ID'];
}
else{
	$colname03_Unit=$colname03_Unit;
	}//顯示資料多寡END
	
$colname_DataID = "-1";
if (isset($_GET['ID'])) {
  $colname_DataID = $_GET['ID'];
}
$query_Data = sprintf("SELECT Member_Identity FROM member WHERE  Member_ID = %s ",GetSQLValueString($colname_DataID, "text"));
	  
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);
if($totalRows_Data>0){
	$colname_Identity=$row_Data['Member_Identity'];
}
else{
	$colname_Identity='-1';
}
$query_MemberData = sprintf("SELECT Member_ID, Com_ID, Member_UserName FROM member WHERE  Member_Identity = %s ",GetSQLValueString($colname_Identity, "text"));
	  
$MemberData = mysql_query($query_MemberData, $dbline) or die(mysql_error());
$row_MemberData = mysql_fetch_assoc($MemberData);
$totalRows_MemberData = mysql_num_rows($MemberData);
$str_sql='';
if($totalRows_MemberData>0){
	$str_sql.=' and (';
	$i=0;
	do{
		$i++;
		if($i==1){
			$str_sql.=" (signup_record.Member_ID='".$row_MemberData['Member_ID']."' and course.Com_ID='".$row_MemberData['Com_ID']."') ";	
		}
		else{
			$str_sql.=" or (signup_record.Member_ID='".$row_MemberData['Member_ID']."' and course.Com_ID='".$row_MemberData['Com_ID']."') ";	
		}
	}while($row_MemberData = mysql_fetch_assoc($MemberData));
	$str_sql.=' )';
}
else{
	$str_sql=' and signup_record.Member_ID=-1';
}




?>


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

<!--最新消息TAB OP-->
<link href="../../css/TabStyle.css" rel="stylesheet" type="text/css">
<!--最新消息TAB END-->

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
<div>   
	<center>
    <table width="90%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']?>管理</div>
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

    <div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2">
      <tr>
      <td>
      
      <input type="button" value="回上頁" class="Button_Add" onClick="location.href='AD_Data_<?php if(isset($_GET['P']) && $_GET['P']=='Check'){echo 'Check';}else{echo 'Index';}?>.php?Com_ID=<?php if(isset($_GET['Com_ID']) && $_GET['Com_ID']<>""){echo $_GET['Com_ID'];}?><?php if(isset($_GET['orders']) && $_GET['orders']<>""){echo "&orders=".$_GET['orders'];}?><?php if(isset($_GET['Member_UserName']) && $_GET['Member_UserName']<>""){echo "&Member_UserName=".$_GET['Member_UserName'];}?><?php if(isset($_GET['pageNum_Data']) && $_GET['pageNum_Data']<>""){echo "&pageNum_Data=".$_GET['pageNum_Data'];}?>'"/>
      
      
    </td>
      <td class="right">     
     </td>
      </tr>
    </table>
    </div>
    <?php if($row_Permission['Per_View'] == 1){ ?>
   
    
            
      <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
      <div style="padding-left:13px;"> 
      
      <!--授課OP-->
      
      <div class="news" style="position:relative;z-index:5;float:left;">
           <div class="tab" align="left" >
           <ul>
           <li class="current" id="AreaItem_Area1"><div><a href="javascript:showNewsArea('Area1','0','#db2400')">研習紀錄</a></div></li>
           </ul>
      </div>
      <?php
      mysql_select_db($database_dbline, $dbline);
	  $query_CourseData = sprintf("SELECT * FROM signup_record inner join course on course.Course_ID=signup_record.Course_ID WHERE  SignupRecord_Returns=0 and course.CourseStatus_Name<>'停招' and course.Unit_ID Like %s and course.Com_ID Like %s $str_sql order by course.Season_Code desc, course.Com_ID ASC, course.Unit_ID asc ",GetSQLValueString($colname02_Unit, "text"), GetSQLValueString($colname03_Unit, "text"));
	  
	  $CourseData = mysql_query($query_CourseData, $dbline) or die(mysql_error());
	  $row_CourseData = mysql_fetch_assoc($CourseData);
	  $totalRows_CourseData = mysql_num_rows($CourseData);
	 
	 
	  ?>
      <div class="body SelfTableClear BlockBig BlueBlock" id="Area_Area1" style='display:block;float:none; width:100%;' >
          <div class="WhiteBlock">
          <br/>
          <?php  if($totalRows_CourseData>0 ){?>
          <table width="98%" border="0" cellpadding="5" cellspacing="0" class="stripe"> 
          <tr class="TableBlock_shadow_Head_Back">
            <td class="middle center" width="8%">班季</td>
          	<td class="middle center" width="5%">社區大學</td>
            <td width="5%" class="middle center">分校</td>
            <td width="15%" class="middle">課程</td>
            <td width="10%" class="center middle">類別</td>
            <td width="15%" class="center middle">上課時間</td>            
            <td width="12%" class=" middle">地點</td>
            <td width="12%" class="middle">講師</td>   
            <td width="12%" class="middle">學員</td>      
          </tr>
          <?php
			  	do{?>
          <tr >
                <td class="middle center"><?php echo $row_CourseData['Season_Year'].'年'.$row_CourseData['SeasonCate_Name']?></td>
              	<td class="middle center"><?php echo $row_CourseData['Com_Name'];?></td>
                <td class="middle center"><?php echo $row_CourseData['Unit_Name'];?></td>
                <td class="middle Black">
                <a href="javascript:newin(900,700,'AD_Course_DetailAll.php?ID=<?php echo $row_CourseData['Course_ID'];?>&MID=<?php echo $row_CourseData['Member_ID'];?>');"><?php echo $row_CourseData['Course_Name'];?></a>
                </td>
                <td class="center middle">
                <?php echo $row_CourseData['CourseKind_Name'];?></td>
                
                <td class="center middle">
                <?php $weekname=explode(",","一,二,三,四,五,六,日"); if($row_CourseData['Course_Day1']<>""){ echo $row_CourseData['Course_Day1'];} echo $row_CourseData['Course_Time']; if($row_CourseData['Course_Start1']<>""){echo " ".date("H:i",strtotime($row_CourseData['Course_Start1']));} if($row_CourseData['Course_End1']<>""){echo "~".date("H:i",strtotime($row_CourseData['Course_End1']));}?></td>
                
                <td class="middle">
                <?php echo $row_CourseData['Loc_Name']; ?></td>
                <td class="middle"><?php  echo $row_CourseData['Teacher_UserName']; ?></td>
                <td class="middle"><?php  echo $row_CourseData['Member_UserName']; ?></td>
                
               
              </tr>
              <?php }while($row_CourseData = mysql_fetch_assoc($CourseData));
		  ?>
              </table>
              <?php }else{echo '<div align="center">無研習紀錄</div>';}?>
              <br/>
          </div>
      </div>
      <?php mysql_free_result($CourseData);?>
      <!--授課ED-->
      <script type="text/javascript">
				 
				 var currentNewsArea = 'Area1';
				 function showNewsArea(area,CateID,Color){
				   if(area!=currentNewsArea){
					 $("#Area_"+area).toggle();
					 $("#AreaItem_"+area).addClass("current");
					 document.getElementById("AreaItem_"+area).style.background = Color;
					
				   
					 $("#Area_"+currentNewsArea).toggle();
					 $("#AreaItem_"+currentNewsArea).removeClass("current");
					 document.getElementById("AreaItem_"+currentNewsArea).style.background = "#dfdfdf";
				   
					 currentNewsArea = area;
				   }
				 }
			   
	   </script>
      
      </div>
      
          
		   
      </div>
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
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

</html>
<?php
mysql_free_result($MemberData);
?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>