<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php 
/*$modulename=explode("_",basename(__FILE__, ".php"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);
/*權限*/
/*$query_ModuleSet = "SELECT * FROM module_setting WHERE ModuleSetting_Name ='Offers' and ModuleSetting_Code='Student'";
$ModuleSet = mysql_query($query_ModuleSet, $dbline) or die(mysql_error());
$row_ModuleSet = mysql_fetch_assoc($ModuleSet);
$totalRows_ModuleSet = mysql_num_rows($ModuleSet);*/
?>
<?php

$colname_Data2="%";
if ((isset($_GET['Season_Code'])) && ($_GET['Season_Code'] != "")) {
$colname_Data2=$_GET['Season_Code'];
}
$colname_Data3="%";
if ((isset($_GET['Com_ID'])) && ($_GET['Com_ID'] != "")) {
$colname_Data3=$_GET['Com_ID'];
}
	
mysql_select_db($database_dbline, $dbline);
$query_Com = sprintf("SELECT * FROM season inner join community on season.Com_ID=community.Com_ID WHERE ifnull(season.Com_ID,'') Like %s  and season.Season_Code Like %s",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($colname_Data2, "text"));
$Com = mysql_query($query_Com, $dbline) or die(mysql_error());
$row_Com = mysql_fetch_assoc($Com);
$totalRows_Com = mysql_num_rows($Com);

$query_Cate = sprintf("SELECT Member_ID,Com_ID FROM member where Member_Identity = %s ",GetSQLValueString($row_AdminMember['Member_Identity'], "text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);
$Member_IDS=' (';
$xx=0;
if($totalRows_Cate>0){
	do{$xx++;
		if($xx==$totalRows_Cate){
			$Member_IDS.=" (Member_ID = ".$row_Cate['Member_ID'].") ";
			
		}else{
			$Member_IDS.=" (Member_ID = ".$row_Cate['Member_ID'].") or ";
			
		}
	}while($row_Cate = mysql_fetch_assoc($Cate));
}
else{
	$Member_IDS.=" (Member_ID = -1) ";
	
}
$Member_IDS.=' )';
mysql_free_result($Cate);





$query_Data = sprintf("SELECT signup_record.Course_ID, signup_record.SignupRecord_ID, signup_record.SignupRecord_Returns, course.Com_Name, course.Unit_Name, course.SeasonCate_Name, course.Season_Code, course.Season_Year, course.Course_Name, course.Course_Start1, course.Course_End1, course.Course_Day1, course.Teacher_UserName, case when course.CourseStatus_Name ='停招' then 1 else 0 end as Course_IsNotOpen, course.Course_Time, course.CourseKind_Name, course.Loc_Name, signup_record.Member_ID FROM signup_record inner join course on signup_record.Course_ID = course.Course_ID where $Member_IDS and ifnull(course.Com_ID,'') Like %s and ifnull(course.Season_Code,'') Like %s and SignupRecord_Returns=0 AND course.CourseStatus_Name<>'停招' order by course.Season_Code DESC, SignupRecord_ID asc",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($colname_Data2, "text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);




?>


<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<style type="text/css">
#JustPrint {display:none}
.Prints td {
    border: 1px solid black;
}
.Prints{
	border-collapse: collapse;
}
@media print {
#JustPrint { display:block; font:9pt verdana; letter-spacing:2px;}
#NoPrint {display:none}
}
</style>

</head>
<body>
<div>   
   
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
     <td> 
        <div align="center">   
         <input name="print_button" id="NoPrint" type="button" value="友善列印" onClick="javascript:prints()"/>
        <div class="PrintTitle"><?php echo '歷年研習記錄<br/>';if(isset($_GET['Com_ID'])&&$_GET['Com_ID']<>""){echo $row_Com['Com_Name'];}else{echo '全部社區大學';}echo " ";/*if(isset($_GET['Unit_ID'])&&$_GET['Unit_ID']<>""){echo $row_Unit['Unit_Name'];}else{echo '全部校區';}echo " ";*/if(isset($_GET['Season_Code'])&&$_GET['Season_Code']<>""){echo $row_Com['Season_Year'].$row_Com['SeasonCate_Name'];}else{echo '全部班季';}?></div>
          
      
      
        <table width="95%" border="0" cellpadding="5" cellspacing="0" class="Prints"> 
          <tr >
            <td class="middle center" width="8%">班季</td>
          	<td class="middle center" width="8%">社區大學</td>
            <td width="5%" class="middle center">校區</td>
            <td width="15%" class="center middle">課程</td>
            <td width="10%" class="center middle">類別</td>
            <td width="15%" class="center middle">上課時間</td>
            <td width="12%" class="center middle">地點</td>
            <td width="10%" class="center middle">講師</td>                    		
           
          </tr>
           <?php if ($totalRows_Data > 0) { // Show if recordset not empty ?>
			<?php do { ?>
              <tr>
                <td class="middle center"><?php echo $row_Data['Season_Year'].'年'.$row_Data['SeasonCate_Name']; ?></td>
              	<td class="middle center"><?php echo str_replace("社區大學",'',$row_Data['Com_Name']); ?></td>
                <td class="middle center"><?php echo str_replace("校區","",$row_Data['Unit_Name']); ?></td>
                <td class="middle Black">
                <a href="javascript:newin(900,700,'AD_Course_DetailAll.php?ID=<?php echo $row_Data['Course_ID']?>&MID=<?php echo $row_Data['Member_ID'];?>');"><?php echo $row_Data['Course_Name']; ?></a>
                </td>
                <td class="center middle">
                <?php echo $row_Data['CourseKind_Name']; ?>
                </td>
                
                <td class="center middle">
                <?php $weekname=explode(",","一,二,三,四,五,六,日"); if($row_Data['Course_Day1']<>""){ echo $row_Data['Course_Day1'];} echo $row_Data['Course_Time']; if($row_Data['Course_Start1']<>""){echo " ".date("H:i",strtotime($row_Data['Course_Start1']));} if($row_Data['Course_End1']<>""){echo "~".date("H:i",strtotime($row_Data['Course_End1']));}?></td>
                
                <td class="middle">
                <?php echo $row_Data['Loc_Name']; ?></td>
                <td class="middle"><?php  echo $row_Data['Teacher_UserName']; ?></td>
                
              </tr>
              <?php } while ($row_Data = mysql_fetch_assoc($Data)); ?>
            <?php } // Show if recordset not empty ?>
        </table>
          
      </div>

        </td>
      </tr>
    </table>
</div>   


<script type="text/javascript">

function prints() {	

print();

}
</script>
</body>
</html>
<?php
mysql_free_result($Data);
?>
<?php //require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php //require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>