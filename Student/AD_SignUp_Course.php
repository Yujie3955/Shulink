<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php
require_once('../../Include/function_array_keys.php'); //插入陣列公式
$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$colname_Com="0";

if (isset($_GET['Com'])) {
  $colname_Com = $_GET['Com'];
}

$todaynow=date("Y-m-d");
$todays=date("Y-m-d H:i:s");



/*查詢此學員有幾個社區*/
$query_Member = sprintf("SELECT member.Com_ID, Com_Name, Member_ID FROM member inner join community on member.Com_ID = community.Com_ID where Member_Identity=%s and community.Com_IsPrivate<>1 and community.Com_IsSchool=1",GetSQLValueString($row_AdminMember['Member_Identity'],"text"));
$Member = mysql_query($query_Member, $dbline) or die(mysql_error());
$row_Member = mysql_fetch_assoc($Member);
$totalRows_Member = mysql_num_rows($Member);
$Member_IDS=0;
if(isset($colname_Com))
{
	$query_MemberID = sprintf("SELECT Member_ID FROM member inner join community on member.Com_ID = community.Com_ID where Member_Identity=%s and member.Com_ID = %s",GetSQLValueString($row_AdminMember['Member_Identity'],"text"),GetSQLValueString($colname_Com, "int"));
	$MemberID = mysql_query($query_MemberID, $dbline) or die(mysql_error());
	$row_MemberID = mysql_fetch_assoc($MemberID);
	$totalRows_MemberID = mysql_num_rows($MemberID);
	if($totalRows_MemberID>0){
		$Member_IDS=$row_MemberID['Member_ID'];
	}
	mysql_free_result($MemberID);
}
/*$Member_Com=array();//找尋此帳號有幾個社區
$Member_IDS=array();//找尋此帳號有幾個ID
$Separator_Com=explode(",",$row_Member['Com_ID']);
$Separator_ID=explode(";",$row_Member['Member_IDS']);
for($se=0;$se<count($Separator_Com);$se++){
	array_push($Member_Com,$Separator_Com[$se]);
	array_push($Member_IDS,$Separator_ID[$se]);
	
	}
@$keys = array_search($colname_Com, $Member_Com);//找尋值在陣列哪個位置*/


$query_Cate = "SELECT Season_SelectStart,Season_SelectEnd,Season_ID, Season_Code, Season_IsAll,Com_ID FROM season_last where Com_ID = ".$colname_Com." and Season_IsOnline=1";
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);



//加選課程計算
$query_Course = sprintf("SELECT SignupItem_ID FROM signup_choose_student where Member_Identity=%s and Signup_OrderNumber is null and Com_ID = %s and Season_ID=%s",GetSQLValueString($row_AdminMember['Member_Identity'],"text"),GetSQLValueString($colname_Com,"int"),GetSQLValueString($row_Cate['Season_ID'],"int"));
$Course = mysql_query($query_Course, $dbline) or die(mysql_error());
$row_Course = mysql_fetch_assoc($Course);
$totalRows_Course = mysql_num_rows($Course);
mysql_free_result($Course);





//判斷加選有哪些課程
$query_Sign = sprintf("SELECT Course_ID FROM signup_course where Member_ID=%s and Season_ID=%s",GetSQLValueString($Member_IDS,"int"),GetSQLValueString($row_Cate['Season_ID'],"int"));
$Sign = mysql_query($query_Sign, $dbline) or die(mysql_error());
$row_Sign = mysql_fetch_assoc($Sign);
$totalRows_Sign = mysql_num_rows($Sign);
$CourseRepeat=",".$row_Sign['Course_ID'].",";
mysql_free_result($Sign);

//搜尋已成為正式的課程
$query_Sign2 = sprintf("SELECT group_concat(Course_ID) as Course_ID FROM signup_record where SignupRecord_Returns=0 and Member_ID=%s and Season_Code = %s group by Season_Code, Member_ID",GetSQLValueString($Member_IDS,"int"),GetSQLValueString($row_Cate['Season_Code'],"int"));
$Sign2 = mysql_query($query_Sign2, $dbline) or die(mysql_error());
$row_Sign2 = mysql_fetch_assoc($Sign2);
$totalRows_Sign2 = mysql_num_rows($Sign2);
$CourseRepeat2=",".$row_Sign2['Course_ID'].",";
mysql_free_result($Sign2);




//搜尋有無送出訂單
$query_RepeatSign = sprintf("select Com_ID from signup where Signup_OrderNumber is NOT null and Member_ID = %s and Season_ID =%s and Com_ID= %s",GetSQLValueString($Member_IDS,"int"),GetSQLValueString($row_Cate['Season_ID'],"int"),GetSQLValueString($colname_Com,"int"));
$RepeatSign = mysql_query($query_RepeatSign, $dbline) or die(mysql_error());
$row_RepeatSign = mysql_fetch_assoc($RepeatSign);
$totalRows_RepeatSign = mysql_num_rows($RepeatSign);


if($totalRows_RepeatSign>0){
	$row_RepeatCom=$row_RepeatSign['Com_ID'];
}
else{$row_RepeatCom=0;}
mysql_free_result($RepeatSign);
if($totalRows_Cate>0)
{
	$maxRows_Data = 10;
	$pageNum_Data = 0;
	if (isset($_GET['pageNum_Data'])) {
 		$pageNum_Data = $_GET['pageNum_Data'];
	}
	$startRow_Data = $pageNum_Data * $maxRows_Data;

	$colname_Unit="%";
	if (isset($_GET['Unit_ID']) && ($_GET['Unit_ID'] != "")) {
  		$colname_Unit = $_GET['Unit_ID'];
	}

	$colname_Title="%";
	if (isset($_GET['Course_Title'])&& ($_GET['Course_Title'] != "")) {
 		$colname_Title = "%".$_GET['Course_Title']."%";
	}
	//選課課程  
	mysql_select_db($database_dbline, $dbline);
	if($row_Cate['Season_IsAll']==1){
		$query_Data = sprintf("SELECT * FROM signup_onlinelist_all WHERE Com_ID = %s and ifnull(Unit_ID,'') like %s and  ifnull(Course_Name,'') like %s  ORDER BY Com_ID asc,Unit_ID asc,Add_Time asc,Course_ID asc",GetSQLValueString($colname_Com,"int"),GetSQLValueString($colname_Unit,"text"),GetSQLValueString($colname_Title,"text"),GetSQLValueString($row_RepeatCom,"int"));
	}else{
		$query_Data = sprintf("SELECT * FROM signup_onlinelist WHERE Com_ID = %s and ifnull(Unit_ID,'') like %s and  ifnull(Course_Name,'') like %s  ORDER BY Com_ID asc,Unit_ID asc,Add_Time asc,Course_ID asc",GetSQLValueString($colname_Com,"int"),GetSQLValueString($colname_Unit,"text"),GetSQLValueString($colname_Title,"text"),GetSQLValueString($row_RepeatCom,"int"));
	}
	$query_limit_Data = sprintf("%s LIMIT %d, %d", $query_Data, $startRow_Data, $maxRows_Data);
	$Data = mysql_query($query_limit_Data, $dbline) or die(mysql_error());
	$row_Data = mysql_fetch_assoc($Data);
		
	if (isset($_GET['totalRows_Data'])) {$totalRows_Data = $_GET['totalRows_Data'];} 
	else {
 		 $all_Data = mysql_query($query_Data);
 		 $totalRows_Data = mysql_num_rows($all_Data);
	}		 
	$totalPages_Data = ceil($totalRows_Data/$maxRows_Data)-1;
	
	//計算是否滿人
	if($row_Cate['Season_IsAll']==1){
		$query_CountOnline = sprintf("SELECT Course_ID,OnlineNum,AllNum FROM signup_countchoose_all where Com_ID=%s and Unit_ID Like %s",GetSQLValueString($colname_Com,"int"),GetSQLValueString($colname_Unit,"text"));
	}else{
		$query_CountOnline = sprintf("SELECT Course_ID,OnlineNum FROM signup_countchoose where Com_ID=%s and Unit_ID Like %s",GetSQLValueString($colname_Com,"int"),GetSQLValueString($colname_Unit,"text"));
	}
	$CountOnline = mysql_query($query_CountOnline, $dbline) or die(mysql_error());
	$row_CountOnline = mysql_fetch_assoc($CountOnline);
	$totalRows_CountOnline = mysql_num_rows($CountOnline);
	$CountCourseId=array();
	$CountCourseNum=array();	
	if($totalRows_CountOnline>0){
		
		do{
			array_push($CountCourseId,$row_CountOnline['Course_ID']);
			if($row_Cate['Season_IsAll']==1){
				array_push($CountCourseNum,$row_CountOnline['OnlineNum']+$row_CountOnline['AllNum']);
			}
			else{
				array_push($CountCourseNum,$row_CountOnline['OnlineNum']);
			}
		}while($row_CountOnline = mysql_fetch_assoc($CountOnline));
		$CountData=array_fill_keys2($CountCourseId,$CountCourseNum);
	
	}
	mysql_free_result($CountOnline);
	
}
else{
	$totalRows_Data=0;
}

if($colname_Com<>0){
if($row_Cate['Season_IsAll']==1){
	$query_Cate2 = "SELECT distinct Unit_ID,Unit_Name FROM signup_onlinelist_all where Com_ID =".$colname_Com." order by Unit_ID ASC";
}
else{
	$query_Cate2 = "SELECT distinct Unit_ID,Unit_Name FROM signup_onlinelist where Com_ID =".$colname_Com." order by Unit_ID ASC";
}
$Cate2 = mysql_query($query_Cate2, $dbline) or die(mysql_error());
$row_Cate2 = mysql_fetch_assoc($Cate2);
$totalRows_Cate2 = mysql_num_rows($Cate2);
}


?>
<?php
#	BuildNav for Dreamweaver MX v0.2
#              10-02-2002
#	Alessandro Crugnola [TMM]
#	sephiroth: alessandro@sephiroth.it
#	http://www.sephiroth.it
#	
#	Function for navigation build ::
function buildNavigation($pageNum_Recordset1,$totalPages_Recordset1,$prev_Recordset1,$next_Recordset1,$separator=" | ",$max_links=10, $show_page=true)
{
                GLOBAL $maxRows_Data,$totalRows_Data;
	$pagesArray = ""; $firstArray = ""; $lastArray = "";
	if($max_links<2)$max_links=2;
	if($pageNum_Recordset1<=$totalPages_Recordset1 && $pageNum_Recordset1>=0)
	{
		if ($pageNum_Recordset1 > ceil($max_links/2))
		{
			$fgp = $pageNum_Recordset1 - ceil($max_links/2) > 0 ? $pageNum_Recordset1 - ceil($max_links/2) : 1;
			$egp = $pageNum_Recordset1 + ceil($max_links/2);
			if ($egp >= $totalPages_Recordset1)
			{
				$egp = $totalPages_Recordset1+1;
				$fgp = $totalPages_Recordset1 - ($max_links-1) > 0 ? $totalPages_Recordset1  - ($max_links-1) : 1;
			}
		}
		else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1+1;
		}
		if($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$_get_vars = '';			
			if(!empty($_GET) || !empty($HTTP_GET_VARS)){
				$_GET = empty($_GET) ? $HTTP_GET_VARS : $_GET;
				foreach ($_GET as $_get_name => $_get_value) {
					if ($_get_name != "pageNum_Data") {
						$_get_vars .= "&$_get_name=$_get_value";
					}
				}
			}
			$successivo = $pageNum_Recordset1+1;
			$precedente = $pageNum_Recordset1-1;
			$firstArray = ($pageNum_Recordset1 > 0) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_Data=$precedente$_get_vars\">$prev_Recordset1</a>" :  "$prev_Recordset1";
			# ----------------------
			# page numbers
			# ----------------------
			for($a = $fgp+1; $a <= $egp; $a++){
				$theNext = $a-1;
				if($show_page)
				{
					$textLink = $a;
				} else {
					$min_l = (($a-1)*$maxRows_Data) + 1;
					$max_l = ($a*$maxRows_Data >= $totalRows_Data) ? $totalRows_Data : ($a*$maxRows_Data);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext/26);
				if ($theNext != $pageNum_Recordset1)
				{
					$pagesArray .= "";
					$pagesArray .= "<input type=\"button\" value=\"$textLink\" class=\"Navi_NoUse\" onClick=\"location.href='$_SERVER[PHP_SELF]?pageNum_Data=$theNext$_get_vars&#content'\">" . ($theNext < $egp-1 ? $separator : "");
				} else {
					$pagesArray .= "<input type=\"button\" value=\"$textLink\" class=\"Navi_Use\">"  . ($theNext < $egp-1 ? $separator : "");
				}
			}
			$theNext = $pageNum_Recordset1+1;
			$offset_end = $totalPages_Recordset1;
			$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? "<a href=\"$_SERVER[PHP_SELF]?pageNum_Data=$successivo$_get_vars&#content\">$next_Recordset1</a>" : "$next_Recordset1";
		}
	}
	return array($firstArray,$pagesArray,$lastArray);
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
        <td width="15%"><?php require_once('../../Include/Menu_AdminLeft_Student.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> 線上選課</div>
	<?php if(@$_GET['Login'] == "success"){ ?>
	<script language="javascript">
	function Success_Login(){
		$('.Success_Login').fadeIn(1000).delay(2000).fadeOut(1000);
	}
	setTimeout(Success_Login,0);
    </script>
    <?php } ?>
    <?php if(@$_GET['Msg'] == "AddOK"){ ?>
	<script language="javascript">
	function AddOK(){
		$('.Success_Add').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddOK,0);
    </script>

	<?php } ?>    
    <div align="center">   
        <div class="Success_Msg Success_Login" style="display:none;"><br/><img src="../../Icon/accept.gif" alt="成功訊息" class="middle">您已成功登入<br/><br/></div>
        <div class="Success_Msg Success_Add" style="display:none;"><br/><img src="../../Icon/accept.gif" alt="成功訊息" class="middle">選課成功<br/><br/></div></div>
    <!--選課內容OP-->
    <div align="center">
    <?php 
		  do{?>
          	<div style="display:inline-block;"><input type="button" value="<?php echo $row_Member["Com_Name"] ?>" class="Button_Add" onClick="location.href='AD_SignUp_Course.php?Com=<?php echo $row_Member["Com_ID"]; ?>'"/><br/><br/>
            </div>
    <?php }while($row_Member=mysql_fetch_assoc($Member));
	      mysql_free_result($Member);//釋放資料
	if(isset($_GET['Com'])){ 
	?>
        <div align="center">
        <?php 
              //if($totalRows_Data>0&&$row_RepeatCom<>$colname_Com){	
		if($totalRows_Data>0){	
			  
				  if($totalRows_Cate>0 && strtotime($row_Cate['Season_SelectEnd'])>=strtotime($todays) && strtotime($row_Cate['Season_SelectStart'])<=strtotime($todays)){
					  $Season_ID=$row_Data['Season_ID'];
					  ?>
                        <!--步驟紀錄OP-->                                           
						<div style="max-width:600px;" align="center">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td width="45">&nbsp;</td>  
                        <td class="step_bg center" width="40"><img src="../../Image/act1.png" width="40"></td>
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_line center" width="40"><img src="../../Image/actno2.png" width="40"></td>  
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_bgend center" width="40"><img src="../../Image/actno3.png" width="40"></td>  
                        <td width="45">&nbsp;</td>     
                        </tr>                        
                        <tr>     
                        <td colspan="3" class="step_font center" style="padding:0px 10px 10px 5px;">&nbsp;&nbsp;&nbsp;選課</td>
                        <td colspan="3" class="stepno_font center" style="padding:0px 15px 10px 30px;">確認課程並送出選課單</td>
                        <td colspan="3" class="stepno_font cneter" style="padding:0px 0px 10px 5px;">前往列印繳費單列印單據並繳費</td>
                        </tr>
                        </table>
                        </div>                   
                        <!--步驟紀錄 ED-->
                   
	    <?php	           //搜尋OP
						   if($colname_Com<>0 && $totalRows_Cate2>0){?>
								<form ACTION="<?php echo @$_SERVER["PHP_SELF"];?>" name="form_search"  method="GET" class="right">
								<input type="hidden" value="<?php if ($colname_Com<>0){echo $colname_Com;}?>" name="Com" id="Com">
								<select name="Unit_ID" id="Unit_ID" >
									<option value="">:::全部:::</option>
									<?php do { ?>
									<option value="<?php echo $row_Cate2['Unit_ID']; ?>" <?php if (@$_GET['Unit_ID'] == $row_Cate2['Unit_ID']) { echo "selected='selected'"; } ?>><?php echo $row_Cate2['Unit_Name']?></option>
									<?php } while ($row_Cate2 = mysql_fetch_assoc($Cate2)); ?>
								 </select>
								 <input type="text" name="Course_Title" id="Course_Title" value="<?php echo @$_GET['Course_Title']; ?>" placeholder="請輸入課程名稱關鍵字"> <input type="submit" value="查詢" class="Button_General">
								 <input type="button" value="全部顯示"  onClick="location.href='<?php echo $_SERVER["PHP_SELF"];?><?php if ($colname_Com<>0){echo '?Com='.$colname_Com;}?>'"  class="Button_General">
								 </form> 
								 <?php mysql_free_result($Cate2);//釋放資料?>
					 <?php }//if($colname_Com<>0 && $totalRows_Cate2>0)
					       //搜尋ED
				  ?>
                 
              	 <div id="AddOK" style="height:30px; font-size:15px; letter-spacing:2px;"><?php echo '你已加選'.$totalRows_Course.'門課,';?><a href="AD_Signup_Detail.php?ID=<?php echo $Season_ID;?>&Com=<?php echo $_GET['Com'];?>">點擊此處查看明細</a><br/>
                 </div>
				
                 <table width="98%" cellpadding="5" cellspacing="0" border="0" class="stripe">
                 <tr class="TableBlock_shadow_Head_Back">
                  <!--<td width="5%" class="middle center">編號</td>-->
                 <td width="8%" class="middle center">校區</td>
                 <td width="15%" class="middle">名稱</td>
                 <td width="20%" class="center middle">上課時間</td>
                 <td width="20%" class="middle">地點</td>
                 <td width="15%" class="middle">講師</td> 
		 <td width="10%" class="center middle" nowrap>學分費</td>
		 <td width="10%" class="center middle" nowrap>雜費</td>
		 <td width="10%" class="center middle" nowrap>保證金</td>		
		 <td width="10%" class="center middle" nowrap>總金額</td>            
                 <td width="15%" class="middle">操作</td>                  
                 </tr>
        <?php 	 $a=0;
		         do { $a++;?>
                 <tr>
                  <!--<td class="middle center"><?php echo substr(str_replace($row_Data['Season_Code'],"",$row_Data['Course_NO']),0);?></td>-->
                  <td class="middle center"><?php echo mb_substr($row_Data['Unit_Name'],0,2,'utf-8');?></td>
                  <td class="middle"><a href="javascript:newin(900,700,'AD_Course_Detail.php?ID=<?php echo $row_Data['Course_ID'];?>')" ><?php echo mb_substr($row_Data['Course_Name'],0,10,'utf-8');if(mb_strlen($row_Data['Course_Name'],'utf-8')>10){echo '...';}?></a></td>
                  
		 
                  <td class="center middle"><?php $weekname=explode(",","一,二,三,四,五,六,日");
               if($row_Data['Course_Day1']<>""){echo $row_Data['Course_Day1'];}if($row_Data['Course_Time']<>""){echo $row_Data['Course_Time'];}echo " ".date("H:i",strtotime($row_Data['Course_Start1']))."~".date("H:i",strtotime($row_Data['Course_End1']));?></td>
               
                  <td class="middle"><?php echo mb_substr($row_Data['Loc_Name'],0,10,"utf-8");if(mb_strlen($row_Data['Loc_Name'],'utf-8')>10){echo '...';}?></td>
                  <td class="middle"><?php echo $row_Data['Teacher_UserName'];?></td>   
		  <td class="middle center" nowrap><?php echo "<font color='#db2400' style='font-weight:bold;'>";if($row_Data['CO_Sale']==0 || $row_Data['Credit_Money']==0){echo '0';}else{echo ceil($row_Data['Credit_Money']*$row_Data['CO_Sale']);}echo "</font>";?></td>        
		  <td class="middle center" nowrap><?php echo "<font color='#db2400' style='font-weight:bold;'>".$row_Data['Credit2_Money']."</font>";?></td>        
		  <td class="middle center" nowrap><?php echo "<font color='#db2400' style='font-weight:bold;'>".$row_Data['Pro_Money']."</font>";?></td>        
		  <td class="middle center" nowrap><?php echo "<font color='#db2400' style='font-weight:bold;'>".$row_Data['Course_Money']."</font>";?></td>              
                  <td class="middle"><input value="<?php echo $row_Data['Course_ID'];?>" type="hidden"  class="Course_ID<?php echo $a;?>" id="Course_ID<?php echo $a;?>"><input value="<?php if($row_Data['Course_Money']<>0){echo $row_Data['Course_Money'];}else{echo '0';}?>" type="hidden"  class="SignupItem_Money<?php echo $a;?>" id="SignupItem_Money<?php echo $a;?>"><input value="<?php echo $row_AdminMember['Member_Identity'];?>" type="hidden"  class="Add_Account" id="Add_Account"><input value="<?php echo $row_AdminMember['Member_UserName'];?>" type="hidden"  class="Add_Username" id="Add_Username"><input value="<?php echo $row_Data['Season_ID'];?>" type="hidden"  class="Season_ID<?php echo $a;?>" id="Season_ID<?php echo $a;?>" ><input value="<?php echo $row_Data['Season_Code'];?>" type="hidden"  class="Season_Code<?php echo $a;?>" id="Season_Code<?php echo $a;?>" ><input value="<?php echo $row_Data['Unit_ID'];?>" type="hidden"  class="Unit_ID<?php echo $a;?>" id="Unit_ID<?php echo $a;?>" ><input value="<?php echo $row_Data['Com_ID'];?>" type="hidden"  class="Com_ID<?php echo $a;?>" id="Com_ID<?php echo $a;?>" >
            
                  <?php if (preg_match("/".",".$row_Data['Course_ID'].","."/i", $CourseRepeat)) {echo '<font color="#009933" style="font-weight:bold;">已加選</font>';}elseif(preg_match("/".",".$row_Data['Course_ID'].","."/i", $CourseRepeat2)){echo '<font color="#009933" style="font-weight:bold;">已報名成功</font>';}
                        else{?>
        <?php 			if(isset($CountData[$row_Data['Course_ID']])) { 
								if($row_Cate['Season_IsAll']==1){
			                    	$OnlineTotalNoNum=($row_Data['Course_OnSite']+$row_Data['Course_Online']+$row_Data['Course_OnSiteAdd']+$row_Data['Course_OnlineAdd'])-$CountData[$row_Data['Course_ID']];

								}
								else{
									$OnlineTotalNoNum=($row_Data['Course_Online']+$row_Data['Course_OnlineAdd'])-$CountData[$row_Data['Course_ID']];

								}
                                    if($OnlineTotalNoNum<=0){echo ' <font color="#db2400" style="font-weight:bold;">已滿額</font>';}
                                    else{?><input value="加選" type="button"  class="Button_Submit" onClick="callbyAJAX('<?php echo $row_Data['Course_ID'];?>','<?php echo $row_Data['Credit_Money']?>','<?php echo $row_AdminMember['Member_Identity']?>','<?php echo $row_AdminMember['Member_UserName']?>','<?php echo $row_Data['Season_ID']?>','<?php echo $row_Data['Com_ID'];?>','<?php echo $row_Data['Unit_ID'];?>','<?php echo $row_Data['Season_Code'];?>','<?php echo $row_Data['Credit2_Money']?>','<?php echo $row_Data['Pro_Money'];?>')" id="button_<?php echo $row_Data['Course_ID']?>"><font color="#009933" style="font-weight:bold;display:none;" id="Already_Add<?php echo $row_Data['Course_ID'];?>">已加選</font><?php 
                                    }
                                }
                                /*else{
					$OnlineTotalNoNum=($row_Data['Course_Online']+$row_Data['Course_OnlineAdd']);
	                                if($OnlineTotalNoNum<=0){echo '<font color="#db2400" style="font-weight:bold;">已滿額</font>';}
	                                else{?><input value="加選" type="button"  class="Button_Submit" onClick="callbyAJAX('<?php echo $row_Data['Course_ID'];?>','<?php echo $row_Data['Course_Money']?>','<?php echo $row_AdminMember['Member_Identity']?>','<?php echo $row_AdminMember['Member_UserName']?>','<?php echo $row_Data['Season_ID']?>','<?php echo $row_Data['Com_ID'];?>','<?php echo $row_Data['Unit_ID'];?>','<?php echo $row_Data['Season_Code'];?>')" id="button_<?php echo $row_Data['Course_ID']?>"><font color="#009933" style="font-weight:bold;display:none;" id="Already_Add<?php echo $row_Data['Course_ID'];?>">已加選</font><?php 
	                                }
                                }*/
								
                        }?>
                   
                  </td>
                 </tr>
                 
          <?php  } while ($row_Data = mysql_fetch_assoc($Data)); 
		  mysql_free_result($Data);?>
                 </table>
                 <br>
                 <?php 
				 # variable declaration
				 $prev_Data = "<input type='button' value='上一頁' class='Button_General'>";
				 $next_Data = "<input type='button' value='下一頁' class='Button_General'>";
				 $separator = " ";
				 $max_links = 15;
				 $pages_navigation_Data = buildNavigation($pageNum_Data,$totalPages_Data,$prev_Data,$next_Data,$separator,$max_links,true);
				 print $pages_navigation_Data[0]; 
				 ?>
           <?php print $pages_navigation_Data[1]; ?> <?php print $pages_navigation_Data[2]; ?>  
                 <br/><br/><input value="下一步" onClick="location.href='AD_Signup_Detail.php?ID=<?php echo $Season_ID;?>&Com=<?php echo $_GET['Com'];?>'" type="button" class="Button_Submit"> 



<script type="text/javascript">
function callbyAJAX(course_id,credit_money,add_account,add_username,season_id,com_id,unit_id,season_code,credit2_money,pro_money){

	var mainItemValue = course_id;
	var mainItemValue2 = credit_money;
	var mainItemValue3 = add_account;
	var mainItemValue4 = add_username;
	var mainItemValue5 = season_id;
	var mainItemValue6 = com_id;
	var mainItemValue7 = unit_id;
	var mainItemValue8 = season_code;
	var mainItemValue9 = credit2_money;
	var mainItemValue10 = pro_money;
	if (window.XMLHttpRequest) {xmlhttp_subitems = new XMLHttpRequest()} 
	else {  xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");}
	xmlhttp_subitems.onreadystatechange = function() { //alert(xmlhttp_subitems.responseText);
		if (xmlhttp_subitems.readyState==4 && xmlhttp_subitems.status==200){
			document.getElementById("AddOK").innerHTML = xmlhttp_subitems.responseText;
			if(document.getElementById('Ins_OK'+course_id)&&document.getElementById('Ins_OK'+course_id).value=="true"){
				$('#button_'+course_id).hide();
				$('#Already_Add'+course_id).show();
				
			}
										
		}
	}
	xmlhttp_subitems.open("get", "signup_value.php?Course_ID=" + encodeURI(mainItemValue)+"&Credit_Money="+encodeURI(mainItemValue2)+"&Add_Account="+encodeURI(mainItemValue3)+"&Add_Username="+encodeURI(mainItemValue4)+"&Season_ID="+encodeURI(mainItemValue5)+"&Season_Code="+encodeURI(mainItemValue8)+"&Com_ID="+encodeURI(mainItemValue6)+"&Unit_ID="+encodeURI(mainItemValue7)+"&Credit2_Money="+encodeURI(mainItemValue9)+"&Pro_Money="+encodeURI(mainItemValue10), true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
				
}
</script>  






                  <?php 
   				 	}//$totalRows_Cate>0 && strtotime($row_Cate['Season_SelectEnd']." 17:00:00")>=strtotime($todays) && strtotime($row_Cate['Season_SelectStart']." 08:00:00")<=strtotime($todays)
	                else{ ?><br><br><br>
                    		<div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 未開放本季選課時間</div>    
			 <?php  } 
			  
              }//$totalRows_Data>0
	      else if(isset($_GET['Course_Title'])&&$_GET['Course_Title']<>""){?>
               		<div class="Error_Msg Error_Code center"> 此社區大學無此課程。</div>    
          <?php			  
	      }
	      else{ 
			if(@$_GET['Com']<>""){?><br><br><br>
		                        <!--步驟紀錄OP-->                                           
					<div style="max-width:650px;" align="center">
		                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
		                        <tr>
		                        <td width="45">&nbsp;</td>  
		                        <td class="step_bg center" width="40"><img src="../../Image/act1.png" width="40"></td>
		                        <td class="step_line" width="40">&nbsp;</td>
		                        <td class="step_line" width="40">&nbsp;</td>
		                        <td class="step_line center" width="40"><img src="../../Image/actno2.png" width="40"></td>  
		                        <td class="step_line" width="40">&nbsp;</td>
		                        <td class="step_line" width="40">&nbsp;</td>
		                        <td class="step_bgend center" width="40"><img src="../../Image/actno3.png" width="40"></td>  
		                        <td width="45">&nbsp;</td> 
		                        </tr>                        
		                        <tr>     
		                        <td colspan="3" class="step_font center" style="padding:0px 10px 10px 5px;">&nbsp;&nbsp;&nbsp;選課</td>
		                        <td colspan="3" class="stepno_font center" style="padding:0px 15px 10px 30px;">確認課程並送出選課單</td>
		                        <td colspan="3" class="stepno_font cneter" style="padding:0px 0px 10px 5px;">前往列印繳費單列印單據並繳費</td>
		                        </tr>
		                        </table>
		                        </div>  
		                        <br/>                  
		                        <!--步驟紀錄 ED-->
		                    	<div class="Error_Msg Error_Code" style=" padding-left:10px;max-width:650px; text-align:left; line-height:20px;"> 1. 此社區大學若已送出選課單，請至列印繳費單頁面，於繳費日期內列印繳費單並繳費。<br>2. 若需再加選課程，請至現場報名加選。</div> 
           		<?php    
			}
		        else{echo '<div class="Error_Msg Error_Code center"> 請先選擇社區大學</div>';}  
	      }?>
                       
    </div>
    <!--選課內容END-->
    <?php  
	}//if(isset($_GET['COM_ID']))
	else{ ?><br><br><br>
   		<div align="center"> 請先選擇社區大學</div>    
<?php   }?> 
  
     
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

?>
<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>