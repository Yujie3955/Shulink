<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php

$currentPage = $_SERVER["PHP_SELF"];
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$todaynow=date("Y-m-d");
$colname_ID=0;
if (isset($_GET['ID'])) {
  $colname_ID = $_GET['ID'];
}
$colname_Com=0;
if (isset($_GET['Com'])) {
  $colname_Com = $_GET['Com'];
}
$todays=date("Y-m-d H:i:s");
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT Season_SelectStart,Season_SelectEnd, Season_Fee,Season_ID, season_last.Season_Code  FROM season_last where Season_ID =".$colname_ID." and Com_ID =".$colname_Com." and Season_SelectStart<=%s and Season_SelectEnd>=%s and Season_IsOnline=1",GetSQLValueString($todaynow,"date"),GetSQLValueString($todaynow,"date"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);





//查詢此MEMBER的ID
$query_Member = sprintf("SELECT Com_ID,  Member_ID FROM member where Member_Identity=%s and Com_ID=".$colname_Com,GetSQLValueString($row_AdminMember['Member_Identity'],"text"));
$Member = mysql_query($query_Member, $dbline) or die(mysql_error());
$row_Member = mysql_fetch_assoc($Member);
$totalRows_Member = mysql_num_rows($Member);

$Member_IDS=$row_Member['Member_ID'];//找尋此帳號有幾個ID
mysql_free_result($Member);




/*刪除OP*/
if ((isset($_POST['ID'])) && ($_POST['ID'] != "") && (isset($_POST['Del']))) {
						   

    $deleteSQL = sprintf("DELETE FROM signup_item WHERE SignupItem_ID=%s ",
                       GetSQLValueString($_POST['SignupItem_ID'], "int"));

    mysql_select_db($database_dbline, $dbline);
    $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 

    $deleteSQL = sprintf("DELETE FROM signup_itemmoney WHERE SignupItem_ID=%s ",
                       GetSQLValueString($_POST['SignupItem_ID'], "int"));

    mysql_select_db($database_dbline, $dbline);
    $Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
	$Has_Choose=0;
	//共有多少人選這門課
    	mysql_select_db($database_dbline, $dbline);
	$query_Data2 = sprintf("SELECT Signup_ID FROM signup_choose_onuse where Course_ID=%s",GetSQLValueString($_POST['ID'],"int"));
	$Data2 = mysql_query($query_Data2, $dbline) or die(mysql_error());
	$row_Data2 = mysql_fetch_assoc($Data2);
	$totalRows_Data2 = mysql_num_rows($Data2);	
	mysql_free_result($Data2);
	$Has_Choose=$totalRows_Data2;

	

	
    	mysql_select_db($database_dbline, $dbline);
	$query_CateP = sprintf("SELECT Season_IsAll FROM course inner join season_last on season_last.Season_Code=course.Season_Code and season_last.Com_ID=course.Com_ID where Course_ID=%s",GetSQLValueString($_POST['ID'],"int"));
	$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
	$row_CateP = mysql_fetch_assoc($CateP);
	$totalRows_CateP = mysql_num_rows($CateP);
	
	if($row_CateP['Season_IsAll']==1){
		$query_Data2 = sprintf("SELECT Course_ID FROM signup_countall where Course_ID=%s ",GetSQLValueString($_POST['ID'],"int"));
		$Data2 = mysql_query($query_Data2, $dbline) or die(mysql_error());
		$row_Data2 = mysql_fetch_assoc($Data2);
		$totalRows_Data2 = mysql_num_rows($Data2);	
		mysql_free_result($Data2);
		$Has_Choose=$Has_Choose+$totalRows_Data2;
	}
	
	
	//搜索這門課
	if($row_CateP['Season_IsAll']==1){
		$query_Data0 = sprintf("SELECT (Course_Online+Course_OnlineAdd+Course_OnSite+Course_OnSiteAdd) as Course_OnlineRemaining FROM course where Course_ID=%s",GetSQLValueString($_POST['ID'],"int"));
	}
	else{
		$query_Data0 = sprintf("SELECT (Course_Online+Course_OnlineAdd) as Course_OnlineRemaining FROM course where Course_ID=%s",GetSQLValueString($_POST['ID'],"int"));
	}
	$Data0 = mysql_query($query_Data0, $dbline) or die(mysql_error());
	$row_Data0 = mysql_fetch_assoc($Data0);
	$totalRows_Data0 = mysql_num_rows($Data0);
	$Course_OnlineRemaining=$row_Data0['Course_OnlineRemaining'];
        $updateSQL = sprintf("update course set Course_OnlineRemaining=%s where Course_ID=%s",
                       GetSQLValueString($Course_OnlineRemaining-$Has_Choose, "int"),
					   GetSQLValueString($_POST['ID'], "int")); 
	mysql_select_db($database_dbline, $dbline);
	$Result2 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	
	
	mysql_free_result($Data0);
	
    $updateGoTo = @$_SERVER["PHP_SELF"]."?ID=".$colname_ID."&Com=".$_POST['Com_ID'];
    header(sprintf("Location: %s", $updateGoTo));
	

}/*刪除END*/	   


$query_Data = sprintf("SELECT signup_item.SignupItem_Offers, signup_item.SignupItem_Money, signup_item.Course_ID, signup_item.SignupItem_ID, signup.Signup_ID, signup.Season_ID, signup.Season_Count, signup.Signup_Money, signup.Member_ID,
signup.Signup_Status, signup.Signup_OrderNumber, signup.Signup_Virtual, signup.Signup_PayDeadline, signup.Signup_PayDate,
signup.Signup_Pipeline, signup.Signup_SignupCost, signup.Signup_NewOrOld, signup.Com_ID, season_last.Season_Year, season_last.SeasonCate_Name, course.Season_Code, course.Course_NO, course.Course_Name, course.Unit_Name, course.Com_Name,
course.Loc_Name, course.Course_Start1, course.Course_End1, course.Course_Day1, course.Course_Time, course.Teacher_UserName, unit.Unit_Code,
case when course.CO_Sale=0 then 1 else 0 end as Course_Free, CO_Sale, course.Course_Money, signup_item.Add_Time, course.Unit_ID, season_last.Rule_ID, member.Member_Type, signup.Unit_ID AS Signup_UnitID, season_last.Season_Start, member.Member_Identity, signup.Member_UserName, member.Member_Birthday, season_last.Season_Benefit, signup_item.SignupItem_Remark, signup.Season_Fee FROM signup_item INNER JOIN signup ON signup.Signup_ID = signup_item.Signup_ID INNER JOIN season_last ON season_last.Season_ID = signup.Season_ID AND season_last.Com_ID = signup.Com_ID INNER JOIN course ON course.Course_ID = signup_item.Course_ID INNER JOIN unit ON unit.Unit_ID = course.Unit_ID
INNER JOIN member ON member.Member_ID = signup.Member_ID where member.Member_Identity=%s and signup.Season_ID=%s and signup.Signup_OrderNumber is null and signup.Com_ID=%s GROUP BY course.Course_ID, member.Member_Identity order by signup.Com_ID ASC, Signup_UnitID ASC , signup_item.Add_Time ASC,signup_item.Course_ID ASC",GetSQLValueString($row_AdminMember['Member_Identity'],"text"),GetSQLValueString($colname_ID,"int"),GetSQLValueString($colname_Com,"int"));
//echo $query_Data;
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);


$Sign_Com_ID=$colname_Com;
$Sign_Season_Code=$row_Cate['Season_Code'];
$Member_ID=$Member_IDS;
require_once("../Sign/Sign_Rule_NewOrOld.php");


//搜尋課程有無繳過報名費
if($row_Cate3['Rule_IsSignupAll']==1){
	$query_SignCost = sprintf("SELECT * FROM signup_sign inner join member on member.Member_ID=signup_sign.Member_ID where SS_Enable= 1 and  signup_sign.Com_ID=%s and member.Member_Identity=%s and SS_Text=%s", GetSQLValueString($colname_Com, "int"), GetSQLValueString($row_AdminMember['Member_Identity'], "text"), GetSQLValueString("報名費", "text"));
}
else{
	$query_SignCost = sprintf("SELECT * FROM signup_sign inner join member on member.Member_ID=signup_sign.Member_ID where SS_Enable= 1 and  signup_sign.Com_ID=%s and signup_sign.Season_Code=%s and member.Member_Identity=%s and SS_Text=%s", GetSQLValueString($colname_Com, "int"), GetSQLValueString($row_Cate['Season_Code'], "int"), GetSQLValueString($row_AdminMember['Member_Identity'], "text"), GetSQLValueString("報名費", "text"));
}
$SignCost = mysql_query($query_SignCost, $dbline) or die(mysql_error());
$row_SignCost = mysql_fetch_assoc($SignCost);
$totalRows_SignCost = mysql_num_rows($SignCost);

mysql_free_result($SignCost);

//身分優惠
$P_Sale=1;
$query_PayCate = sprintf("SELECT * FROM pay where Season_Code = %s and Com_ID=%s and P_Enable=1 and P_Cate=2 order by P_Cate desc", GetSQLValueString($row_Data['Season_Code'], "int"), GetSQLValueString($row_Data['Com_ID'], "int"));
$PayCate = mysql_query($query_PayCate, $dbline) or die(mysql_error());
$row_PayCate = mysql_fetch_assoc($PayCate);
$totalRows_PayCate = mysql_num_rows($PayCate);		
if($totalRows_PayCate>0){
	do{
		$P_List=explode("折-",$row_PayCate['P_Text']);
		if(isset($P_List[1]) && $P_List[1]<>""){
			$P_Detail=$P_List[1];
		}
		else{
			$P_Detail=$P_List[0];
		}

		if($P_Detail==$row_Data['Member_Type']){
			$P_Sale=($row_PayCate['P_Sale']-1);
		}
	}while($row_PayCate = mysql_fetch_assoc($PayCate));
}
mysql_free_result($PayCate);

//遠保OP
$Ins_Member_Birthday=$row_Data['Member_Birthday'];
$Ins_Member_Identity=$row_AdminMember['Member_Identity'];
$Ins_Season_Year=$row_Data['Season_Year'];
$Ins_Season_Code=$row_Data['Season_Code'];
require_once("../Sign/Insurance_Include.php");
//遠保ED

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
    <div align="center">   
        <div class="Success_Msg Success_Login" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 您已成功登入</div></div>
       
   <?php if($totalRows_Cate>0 && strtotime($row_Cate['Season_SelectEnd'])>=strtotime($todays) && strtotime($row_Cate['Season_SelectStart'])<=strtotime($todays)){ ?>
	
    <!--選課內容OP-->
  
  <fieldset>
   <?php if($totalRows_Data>0){ ?>
  <legend><?php echo $row_Data['Season_Year']."年度".$row_Data['SeasonCate_Name'];?>選課明細表:</legend>
   
    <div align="center">
                        <!--步驟紀錄OP-->                                           
						<div style="max-width:600px;" align="center">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                        <td width="45">&nbsp;</td>  
                        <td class="step_bg center" width="40"><img src="../../Image/actno1.png" width="40"></td>
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_line center" width="40"><img src="../../Image/act2.png" width="40"></td>  
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_line" width="40">&nbsp;</td>
                        <td class="step_bgend center" width="40"><img src="../../Image/actno3.png" width="40"></td>  
                        <td width="45">&nbsp;</td> 
                        </tr>                        
                        <tr>     
                        <td colspan="3" class="stepno_font center" style="padding:0px 10px 10px 5px;">&nbsp;&nbsp;&nbsp;選課</td>
                        <td colspan="3" class="step_font center" style="padding:0px 15px 10px 30px;">確認課程並送出選課單</td>
                        <td colspan="3" class="stepno_font cneter" style="padding:0px 0px 10px 5px;">前往列印繳費單列印單據並繳費</td>
                        </tr>
                        </table>
                        </div>                   
                        <!--步驟紀錄 ED-->
    <div id="AddOK" style="height:30px; font-size:15px; letter-spacing:2px;"><?php echo '你已加選'.$totalRows_Data.'門課,';?><a href="AD_Signup_Course.php?Com=<?php echo $_GET['Com'];?>">點擊此處繼續加選</a></div>
  
      <table width="98%" cellpadding="5" cellspacing="0" border="0">
      
        <tr class="TableBlock_shadow_Head_Back">
          <!--<td width="5%" class="middle center">編號</td>-->
          <td width="8%" class="middle center">校區</td>
          <td width="15%" class="middle">名稱</td>   
          <td width="15%" class="center middle">上課時間</td>
          <td width="15%" class="middle">地點</td>
          <td width="15%" class="middle">講師</td>
          <td width="10%" class="center middle" nowrap>學分費</td>
	  <td width="10%" class="center middle" nowrap>雜費</td>
	  <td width="10%" class="center middle" nowrap>保證金</td>		
	  <td width="10%" class="center middle" nowrap>總金額</td> 
          <td width="15%" class="middle">操作</td>
          
        </tr>
    <?php  $Total_Money=0;
	       $a=0;$Course_OfferMoney=0;
		   $Course_IDS=array();/*存入優惠的課程ID*/
		   $SignupItem_IDS=array();/*存入優惠的線上報名名細ID*/
		   $AStart=array();
		   $AEnd=array();
		   $ADay=array();
		   if($row_Cate['Season_Fee']<>"" && $row_Cate['Season_Fee']>0){
			   $Season_Fee=$row_Cate['Season_Fee'];
		   }
		   else{
			   $Season_Fee=0;
		   }
		   
		   do { 
		        $a++; 
			$IsRepeat_Time=0;
			$Course_NowDay=$row_Data['Course_Day1'];
			$Course_NowStart=strtotime($row_Data['Course_Start1']);
			$Course_NowEnd=strtotime($row_Data['Course_End1']);

			$query_CourseDay2 = sprintf("SELECT Course_Day1, Course_Time, course.Course_Start1, course.Course_End1 FROM signup_record INNER JOIN course ON course.Course_ID = signup_record.Course_ID INNER JOIN member ON member.Member_ID = signup_record.Member_ID where course.Season_Code=%s and member.Member_Identity=%s and SignupRecord_Returns=0 and course.Course_Day1=%s ", GetSQLValueString($row_Data['Season_Code'] , "int"), GetSQLValueString($row_AdminMember['Member_Identity'], "text"), GetSQLValueString($row_Data['Course_Day1'] , "text"));
			$CourseDay2 = mysql_query($query_CourseDay2, $dbline) or die(mysql_error());
			$row_CourseDay2 = mysql_fetch_assoc($CourseDay2);
			$totalRows_CourseDay2 = mysql_num_rows($CourseDay2);
		
			if($totalRows_CourseDay2>0){
				do{
					$Already_Start=strtotime($row_CourseDay2['Course_Start1']);
					$Already_End=strtotime($row_CourseDay2['Course_End1']);
					if(($Already_Start>=$Course_NowStart && $Course_NowEnd>$Already_End)||
					   ($Already_Start<=$Course_NowStart && $Already_End>$Course_NowStart)||
					   ($Already_Start<$Course_NowEnd && $Already_End>=$Course_NowEnd)){
								$IsRepeat_Time=1;
					}
				}while($row_CourseDay2 = mysql_fetch_assoc($CourseDay2));
			}
			mysql_free_result($CourseDay2);
			if($AStart<>"" || $AEnd<>""){
				foreach($AStart as $start_key=>$start_value){
					$Already_Start=strtotime($start_value);
					$Already_End=strtotime($AEnd[$start_key]);
					if($row_Data['Course_Day1']==$ADay[$start_key]){
						if(($Already_Start>=$Course_NowStart && $Course_NowEnd>$Already_End)||
						   ($Already_Start<=$Course_NowStart && $Already_End>$Course_NowStart)||
						   ($Already_Start<$Course_NowEnd && $Already_End>=$Course_NowEnd)){
									$IsRepeat_Time=1;
						}
					}
				}
			}
			array_push($AStart,$row_Data['Course_Start1']);
			array_push($AEnd,$row_Data['Course_End1']);
			array_push($ADay,$row_Data['Course_Day1']);
				
				
		   
		   ?>
        <tr>
          <!--<td class="middle center" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo substr(str_replace($row_Data['Season_Code'],"",$row_Data['Course_NO']),0);?></td>-->
          <td class="middle center" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo mb_substr($row_Data['Unit_Name'],0,2,'utf-8');?></td>
          <td class="middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><a href="javascript:newin(900,700,'AD_Course_Detail.php?ID=<?php echo $row_Data['Course_ID'];?>')" ><?php echo mb_substr($row_Data['Course_Name'],0,8,'utf-8');if(mb_strlen($row_Data['Course_Name'],'utf-8')>8){echo '...';}?></a></td>
           
          <td class="center middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?> <?php if($IsRepeat_Time==1){?>style="background-color:#db2400; color:#fff; font-weight:bold;"<?php }?>><?php if($IsRepeat_Time==1){?><font style="padding:5px; background-color:#db2400; color:#fff; font-weight:bold; border-radius:3px; font-family:'微軟正黑體';">(衝堂)</font><?php }?><?php $weekname=explode(",","一,二,三,四,五,六,日");
	if($row_Data['Course_Day1']<>""){   echo $row_Data['Course_Day1'];}	if($row_Data['Course_Time']<>""){ echo $row_Data['Course_Time'];}echo " ".date("H:i",strtotime($row_Data['Course_Start1']))."~".date("H:i",strtotime($row_Data['Course_End1']));?></td>
    	 
          <td class="middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?> ><?php echo mb_substr($row_Data['Loc_Name'],0,10,"utf-8");if(mb_strlen($row_Data['Loc_Name'],'utf-8')>10){echo '...';}?></td>
          <td class="middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo $row_Data['Teacher_UserName'];?></td>
	  <?php 
	  $query_PayData2 = "SELECT * from signup_itemmoney where Course_ID='".$row_Data['Course_ID']."' and Member_ID='".$row_Member['Member_ID']."' and SignupItem_ID=".$row_Data['SignupItem_ID'];
	
	  $PayData2 = mysql_query($query_PayData2, $dbline) or die(mysql_error());
	  $row_PayData2= mysql_fetch_assoc($PayData2);
	  $totalRows_PayData2 = mysql_num_rows($PayData2);
	  $PayList=array();
	  if($totalRows_PayData2>0){	
		do{
			if(!isset($PayList[$row_PayData2['CP_Text']])){
				$PayList[$row_PayData2['CP_Text']]=0; 
			}
			//echo $PayList[$row_PayData2['CP_Text']].'/'.$row_PayData2['CP_OriMoney'].'<br/>';
			$PayList[$row_PayData2['CP_Text']]=$PayList[$row_PayData2['CP_Text']]+$row_PayData2['CP_OriMoney']; 
		}while($row_PayData2= mysql_fetch_assoc($PayData2));
	  }
	  mysql_free_result($PayData2);
	  $Course_Money	=0; 

	//學分費優惠項目
	if($row_Data['CO_Sale']=='1'){ 
		
		if(isset($PayList['學分費'])){$Course_OfferMoney=$Course_OfferMoney-$PayList['學分費']*$P_Sale;}
	
	}
	
?>
          <td class="middle right" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo "<font color='#db2400' style='font-weight:bold;'>";if(!isset($PayList['學分費'])){echo '0';}else{echo $PayList['學分費'];$Course_Money=$Course_Money+$PayList['學分費'];}echo "</font>";?></td>
          <td class="middle right" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo "<font color='#db2400' style='font-weight:bold;'>";if(!isset($PayList['雜費'])){echo '0';}else{echo $PayList['雜費'];$Course_Money=$Course_Money+$PayList['雜費'];}echo "</font>";?></td>
          <td class="middle right" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo "<font color='#db2400' style='font-weight:bold;'>";if(!isset($PayList['課程保證金'])){echo '0';}else{echo $PayList['課程保證金'];$Course_Money=$Course_Money+$PayList['課程保證金'];}echo "</font>";?></td>
          <td class="middle right" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo "<font color='#db2400' style='font-weight:bold;'>".$Course_Money."</font>";?></td>
          <td class="middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>>
          <form name="form_Del" id="form_Del" method="POST" action="<?php echo $editFormAction;?>" class="center">
          <input value="<?php echo $row_Data['Course_ID'];?>"  type="hidden"  name="ID" id="ID<?php echo $a;?>">
          <input value="<?php echo $Member_IDS;?>" type="hidden"  name="Add_Account" id="Add_Account">
          <input value="<?php echo $row_Data['SignupItem_ID'];?>" type="hidden"  name="SignupItem_ID" id="SignupItem_ID<?php echo $a;?>">
        <input value="<?php echo $row_Data['Season_ID'];?>" type="hidden"  name="Season_ID" id="Season_ID<?php echo $a;?>" >
        <input value="<?php echo $row_Data['Com_ID'];?>" type="hidden"  name="Com_ID" id="Com_ID<?php echo $a;?>" >
        <input value="<?php echo $row_Data['Unit_ID'];?>" type="hidden"  name="Unit_ID" id="Unit_ID<?php echo $a;?>" > <input type="hidden" name="Del" value="form_del">
          <input value="取消" type="submit"  class="Button_Submit"  id="button1" onClick="return(confirm('確定刪除「<?php echo $row_Data['Course_Name'];?>」嗎？刪除後無法復原'))"></form></td>
		  </tr>
		  
       
       <?php 
	   $Total_Money=$Total_Money+$row_Data['Course_Money'];
	   $Total_Offers=0;/*總優惠額度宣告*/	
	   if($a==$totalRows_Data){
		  if($Total_Money>0){
			  if($Course_OfferMoney<>0){?>
				<tr>
					<td colspan="8" class="right" >-身份優惠(<?php echo $row_Data['Member_Type'];?>):</td>
					<td class="right"><?php echo $Course_OfferMoney;?></td>
			         <td></td>
				</tr>
<?php

			  }
			  if($Signup_Isinsurance>0){?>
				<tr>
					<td colspan="8" class="right" >+保險費:</td>
					<td class="right"><?php echo $Insurance_Money;?></td>
			         <td></td>
				</tr>
<?php				
			  }
			  
			  if($totalRows_SignCost<1){
				  if($totalRows_Cate4>0){$Sign_Cost=$row_Cate3['Rule_SignupOld']; }
				  else{$Sign_Cost=$row_Cate3['Rule_SignupNew'];}
			  
			  
	    ?>				
				
       				 <tr>
					<td colspan="8" class="right" >+報名費用:</td>
					<td class="right"><?php echo $Sign_Cost;?></td>
			         <td></td>
				</tr>
				
        
                    <?php }
			  else{
				  $Sign_Cost=0;
			  }
			  if($Season_Fee>0){ ?>
		            <tr>
		            <td colspan="8" class="right" >+銀行繳款單據手續費:</td>
		            <td class="right"><?php 
		            echo $Season_Fee; ?></td>
		            <td></td>
		            </tr>
		    <?php }?>
       
        
  <?php   	}
         	else{
			$Sign_Cost=0;
		}?>
		<tr>
		<td colspan="8" class="right" style="border-top:1px solid;">合計:</td>
		<td class="right" style="border-top:1px solid;"><font id="Money_Result" class="right"><?php echo $Total_Money+$Sign_Cost+$Insurance_Money+@$Season_Fee-$Course_OfferMoney;?></font><font id="OffersMoney_Result" style="display:none;"><?php echo $Total_Money+$Sign_Cost+$Insurance_Money-$Total_Offers+@$Season_Fee-$Course_OfferMoney;?></font></td>
        <td style="border-top:1px solid; "></td>
		</tr>
<?php      }		
	  } while ($row_Data = mysql_fetch_assoc($Data)); 
	           mysql_free_result($Cate3); 
mysql_free_result($Data);
			   ?>
        </table>
        <script type="text/javascript">
		$(document).ready(function(e) {
			if(document.getElementById("Offers_Use")){
				Offers_Check();
			}
        });
		
		function Offers_Check(){
			if($("#Offers_Use").prop("checked")==true){
				document.getElementById('Offers_OnUse').value='1';
				document.getElementById('Offers_Area').style.display='table-row';
				document.getElementById('OffersMoney_Result').style.display='inline-block';
				document.getElementById('Money_Result').style.display='none';
				
			}
			else{
				document.getElementById('Offers_OnUse').value='0';				
				document.getElementById('Offers_Area').style.display='none';
				document.getElementById('OffersMoney_Result').style.display='none';
				document.getElementById('Money_Result').style.display='inline-block';
			}
		}
		
		</script>
      
   
<div align="left">※ 注意事項說明：<br/>
1. 報名流程：加選課程 > 確認選課明細 > 點選確定選課完成 > 繳費完畢才算完成報名作業！<br/>
2. 送出選課單前請先務必確認選課明細中之課程時間及地點。<br/>
<font color="#db2400">3. 送出選課單後，線上訂單"未繳費課程"若要再加選，將在繳費期限截止後才可重新加選。</font><br/>
<font color="#db2400">4. 課程加選完請在當天產生訂單，若未產生將於隔日自動取消。</font><br/>
5. 選課完成後請點選下方「確定選課完成」按鈕，未點選者代表未送出選課單。<br/><br/>

<form action="Signup_Currect.php" method="get">
<div align="center">
<input type="hidden" name="Add_Account" value="<?php echo $row_AdminMember['Member_Identity'];?>">
<input type="hidden" name="Add_ID" value="<?php echo $Member_IDS;?>">
<?php if(isset($final)&&count($final)>0){?>
         <input type="hidden" name="Offers_Course" value="<?php echo $str_print1;?>">
         <input type="hidden" name="Offers_Money" value="<?php echo $str_print1_2;?>">
<?php }?>
<?php /*if(isset($Course_IDS)&&count($Course_IDS)>0){ 
            $str_print2='';
			$str_print3=''; 
			for($i=0;$i<count($Course_IDS);$i++){
				if($i==count($Course_IDS)-1){$strs1='';}else{$strs1='nn';}
				$str_print2.=$Course_IDS[$i].$strs1;
				$str_print3.=$SignupItem_IDS[$i].$strs1;
			}
?>
         <input type="hidden" name="Course_IDS" value="<?php echo $str_print2;?>">
         <input type="hidden" name="SignupItem_IDS" value="<?php echo $str_print3;?>">
<?php }*/?>
<input name="Offers_OnUse" id="Offers_OnUse" type="hidden" value='0'>
<input type="hidden" name="Com_ID" value="<?php echo $_GET['Com'];?>">
<input type="hidden" name="Season_ID" value="<?php echo $row_Cate['Season_ID'];?>">
<input type="hidden" name="Season_Code" value="<?php echo $row_Cate['Season_Code'];?>">
<input value="上一步" type="button" onClick="location.href='AD_Signup_Course.php?Com=<?php echo $_GET['Com'];?>';" class="Button_General">
<input type="submit" id="turn_button<?php echo $row_Cate['Season_ID'];?>" onClick="return(cpp<?php echo $row_Cate['Season_ID'];?>());"  value="我已閱讀注意事項，送出選課單" class="Button_Submit">
<script type="text/javascript">
								function cpp<?php echo $row_Cate['Season_ID'];?>(){
									var string=confirm("您是否確定選擇以上課程？送出後將無法修改");
									if(string==true){
										$('#turn_button<?php echo $row_Cate['Season_ID'];?>').hide();										
										return true;							
										
									}
									else{return false;}									 
								
								}
								</script>
</div> 
</form>
   
      </div>    
       
      </div>
      
      <?php }else{echo '目前您還未加選課程,<a href="AD_Signup_Course.php?Com='.$_GET['Com'].'">點擊此處開始加選</a>';}?>
      </fieldset>
      
      <?php 	  
	  //報名成功課程
	    $query_CourseOnsite = sprintf("SELECT course.Course_Name, course.Unit_Name,case when course.CO_Sale=0 then 1 else 0 end as Course_Free, course.Course_Day1, course.Teacher_UserName, course.Loc_Name, course.Course_Start1, course.Course_Time, course.Course_End1, course.Course_ID  FROM signup_record INNER JOIN course ON course.Course_ID = signup_record.Course_ID INNER JOIN member ON member.Member_ID = signup_record.Member_ID where course.Season_Code=%s and SignupRecord_Returns=0 and member.Member_Identity=%s ", GetSQLValueString($row_Cate['Season_Code'] , "int"), GetSQLValueString($row_AdminMember['Member_Identity'], "text"));
		$CourseOnsite = mysql_query($query_CourseOnsite, $dbline) or die(mysql_error());
		$row_CourseOnsite = mysql_fetch_assoc($CourseOnsite);
		$totalRows_CourseOnsite = mysql_num_rows($CourseOnsite);
		if($totalRows_CourseOnsite>0){
	  ?>
      <fieldset class="fieldset_Area">
      <legend class="legend_Area">報名成功課程</legend>
      <table width="98%" cellpadding="5" cellspacing="0" border="0">
      
        <tr class="TableBlock_shadow_Head_Back">
          <!--<td width="5%" class="middle center">編號</td>-->
          <td width="8%" class="middle center">校區</td>
          <td width="10%" class="middle">名稱</td>
          <td width="15%" class="center middle">上課時間</td>
          <td width="15%" class="middle">地點</td>
          <td width="15%" class="middle">講師</td>
         
          
        </tr>
        <?php $a=0;
		      do{$a++;?>
        <tr>
          <!--<td width="5%" class="middle center">編號</td>-->
          <td class="middle center" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo mb_substr($row_CourseOnsite['Unit_Name'],0,2,'utf-8');?></td>
          <td width="10%" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?> class="middle"><a href="javascript:newin(900,700,'AD_Course_Detail.php?ID=<?php echo $row_CourseOnsite['Course_ID'];?>')" ><?php echo mb_substr($row_CourseOnsite['Course_Name'],0,8,'utf-8');if(mb_strlen($row_CourseOnsite['Course_Name'],'utf-8')>8){echo '...';}?></a></td>
          
          <td class="center middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php $weekname=explode(",","一,二,三,四,五,六,日");
	if($row_CourseOnsite['Course_Day1']<>""){   echo $row_CourseOnsite['Course_Day1'];}	if($row_CourseOnsite['Course_Time']<>""){ echo $row_CourseOnsite['Course_Time'];}echo " ".date("H:i",strtotime($row_CourseOnsite['Course_Start1']))."~".date("H:i",strtotime($row_CourseOnsite['Course_End1']));?></td>
    	 
          <td class="middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?> ><?php echo mb_substr($row_CourseOnsite['Loc_Name'],0,10,"utf-8");if(mb_strlen($row_CourseOnsite['Loc_Name'],'utf-8')>10){echo '...';}?></td>
          <td class="middle" <?php if($a%2==1){echo 'bgcolor="#EEEEEE"';}?>><?php echo $row_CourseOnsite['Teacher_UserName'];?></td>
          
          
        </tr>
        <?php }while($row_CourseOnsite = mysql_fetch_assoc($CourseOnsite));
		?>
      </table>
      </fieldset>
      <?php }mysql_free_result($CourseOnsite);?>
      
<!--選課內容END-->
    <?php  }else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 選課時間尚未開放</div>    
    <?php  }?>
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