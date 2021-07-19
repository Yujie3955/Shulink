<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php //require_once('module_setting.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>線上選課確認加選</title>
</head>

<?php

if (isset($_GET['Add_Account'])) {
  $colname_Account = $_GET['Add_Account'];
}

if (isset($_GET['Season_ID'])) {
  $colname_Season = $_GET['Season_ID'];
}

if (isset($_GET['Com_ID'])) {
  $colname_Com = $_GET['Com_ID'];
}
if (isset($_GET['Season_Code'])) {
  $colname_SCode = $_GET['Season_Code'];
}
if (isset($_GET['Add_ID'])) {
  $colname_MemberID = $_GET['Add_ID'];
}
if (isset($_GET['Offers_OnUse'])) {
  $colname_OffersUse = $_GET['Offers_OnUse'];
}

$Sign_Com_ID=$colname_Com;
$Sign_Season_Code=$colname_SCode;
$Member_ID=$colname_MemberID;
require_once('../Sign/Sign_Rule_NewOrOld.php');


//搜尋報名費課程
$query_SignCost2 = sprintf("SELECT * FROM signup_sign where Com_ID=%s and Season_Code=%s and Member_ID=%s and SS_Enable=1", GetSQLValueString($colname_Com, "int"), GetSQLValueString($colname_SCode, "int"), GetSQLValueString($colname_MemberID, "int"));
$SignCost2 = mysql_query($query_SignCost2, $dbline) or die(mysql_error());
$row_SignCost2 = mysql_fetch_assoc($SignCost2);
$totalRows_SignCost2= mysql_num_rows($SignCost2);
$SignupRecord_ID='';
$SignUnit_ID='';
if($totalRows_SignCost2>0){
$SignupRecord_ID=$row_SignCost2['SignupRecord_ID'];
$SignUnit_ID=$row_SignCost2['Unit_ID'];
}
mysql_free_result($SignCost2);


 
$AddDate=(date("Ymd",strtotime(date("Y-m-d"))));//訂單編號規則
/*找尋選課清單、已一般課程為優先，主要是影響報名費在哪間*/

$query_Data = sprintf("SELECT * FROM signup_choose where Member_Identity=%s and Season_ID=%s and Com_ID=%s and Signup_Status = '未結單' order by Add_Time ASC,Unit_ID ASC",GetSQLValueString($colname_Account,"text"),GetSQLValueString($colname_Season,"int"),GetSQLValueString($colname_Com,"int"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);



//搜尋課程有無繳過報名費 
if($row_Cate3['Rule_IsSignupAll']==1){	
	$query_SignCost = sprintf("SELECT * FROM signup_sign inner join member on member.Member_ID=signup_sign.Member_ID where SS_Enable= 1 and  signup_sign.Com_ID=%s and member.Member_Identity=%s and SS_Text=%s", GetSQLValueString($colname_Com, "int"), GetSQLValueString($colname_Account, "text"), GetSQLValueString("報名費", "text"));
}
else{
	$query_SignCost = sprintf("SELECT * FROM signup_sign inner join member on member.Member_ID=signup_sign.Member_ID where SS_Enable= 1 and  signup_sign.Com_ID=%s and signup_sign.Season_Code=%s and member.Member_Identity=%s and SS_Text=%s", GetSQLValueString($colname_Com, "int"), GetSQLValueString($colname_SCode, "int"), GetSQLValueString($colname_Account, "text"), GetSQLValueString("報名費", "text"));

}
$SignCost = mysql_query($query_SignCost, $dbline) or die(mysql_error());
$row_SignCost = mysql_fetch_assoc($SignCost);
$totalRows_SignCost = mysql_num_rows($SignCost);
if($totalRows_SignCost>0){$Signup_IsSignCost=0;}
else{$Signup_IsSignCost=1;}
mysql_free_result($SignCost);

//遠保OP
$Ins_Member_Birthday=$row_Data['Member_Birthday'];
$Ins_Member_Identity=$colname_Account;
$Ins_Season_Year=$row_Data['Season_Year'];
$Ins_Season_Code=$row_Data['Season_Code'];
require_once("../Sign/Insurance_Include.php");
//遠保ED

	/*搜尋訂單到幾號OP*/	
	
	$query_CateP = sprintf("SELECT max(Season_Count) as Season_Count FROM signup where Season_ID=%s",GetSQLValueString($colname_Season, "int"));
	$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
	$row_CateP = mysql_fetch_assoc($CateP);
	$totalRows_CateP = mysql_num_rows($CateP);
	
	/*搜尋選課清單，並計算總課程費用OP*/	
	
	$query_Money = sprintf("SELECT Sum(ifnull(SignupItem_Money,0)) as Sum_money,Sum(ifnull(SignupItem_Offers,0))as Sum_offers,Signup_ID FROM signup_choose where Member_Identity=%s and Signup_ID=%s group by Signup_ID order by Com_ID,Unit_ID ASC",GetSQLValueString($colname_Account,"text"),GetSQLValueString($row_Data['Signup_ID'],"int"));	
	$Money = mysql_query($query_Money, $dbline) or die(mysql_error());
	$row_Money = mysql_fetch_assoc($Money);
	$totalRows_Money = mysql_num_rows($Money);
	$Course_OfferMoney=0;
	
	/*判斷訂單到幾號*/
    if($row_CateP['Season_Count']<1){ $SeasonC='1' ;}else{ $SeasonC=$row_CateP['Season_Count']+1;}
	//判斷報名費寫在哪個學校，若課程清單皆為推廣，就不寫入
		
	if($totalRows_Cate4>0){$Sign_Cost=$row_Cate3['Rule_SignupOld'];$Signup_NewOrOld="舊生";}//新舊生費用
	else{$Sign_Cost=$row_Cate3['Rule_SignupNew'];$Signup_NewOrOld="新生";} 
	if($totalRows_SignCost<1){
		$Currect_Sign_Cost=$Sign_Cost;
	}
	else{
		$Currect_Sign_Cost=0;			
	}
	$Signup_UnitID=$row_Data['Unit_ID'];
	$Signup_Money=$row_Money['Sum_money']+$Currect_Sign_Cost+$Insurance_Money-$row_Money['Sum_offers'];

	/*訂單編號OP*/
	$query_SeasonData = sprintf("SELECT Season_PayCode, Season_Bank, Season_BankCode, Season_Transaction, Season_Fee, Season_ID, Com_ID, season.Season_Code, Season_PayStart, Season_PayEnd, Season_BankName, Season_BankAccount FROM season where Season_ID=%s ",GetSQLValueString($colname_Season,"int"));
	$SeasonData = mysql_query($query_SeasonData, $dbline) or die(mysql_error());
	$row_SeasonData = mysql_fetch_assoc($SeasonData);
	$totalRows_SeasonData = mysql_num_rows($SeasonData);	
	
	if($row_SeasonData['Season_Fee']<>"" && $Signup_Money > 0){//銀行自付手續費
		$Season_Fee=$row_SeasonData['Season_Fee'];
	}
	else{
		$Season_Fee=NULL;
	}
        $Signup_No=$AddDate.str_pad($row_Data['Signup_ID'],6,0,STR_PAD_LEFT);
	
	if($row_SeasonData['Season_Bank']<>""){//銀行ATM代碼
		$Season_Bank=$row_SeasonData['Season_Bank'];
	}
	else{
		$Season_Bank=NULL;
	}
	if($row_SeasonData['Season_BankCode']<>""){//銀行代收類別
		$Season_BankCode=$row_SeasonData['Season_BankCode'];
	}
	else{
		$Season_BankCode=NULL;
	}
	if($row_SeasonData['Season_Transaction']<>""){//銀行交易代號
		$Season_Transaction=$row_SeasonData['Season_Transaction'];
	}
	else{
		$Season_Transaction=NULL;
	}
	
	if($row_SeasonData['Season_BankName']<>""){//銀行自付手續費
		$Season_BankName=$row_SeasonData['Season_BankName'];
	}
	else{
		$Season_BankName=NULL;
	}
	if($row_SeasonData['Season_BankAccount']<>""){//銀行自付手續費
		$Season_BankAccount=$row_SeasonData['Season_BankAccount'];
	}
	else{
		$Season_BankAccount=NULL;
	}
	/*訂單編號ED*/	
	if(isset($_GET['Offers_Course'])){ $Signup_Remark=$_GET['Offers_Course'];}else{$Signup_Remark=NULL;}
	
	//判斷目前沒有訂單編號與帳號是自己的時
	/* 
	$updateSQL = sprintf("update signup set Season_Count=%s, Signup_OrderNumber=%s, Season_Bank=%s, Season_BankCode=%s,  Season_Transaction=%s, Season_Fee=%s, Season_BankName=%s, Season_BankAccount=%s, Signup_Money=%s, Signup_Remark=%s, Signup_PayDate=%s,  Signup_PayDeadline=%s, Signup_Status=%s, Unit_ID=%s, Signup_SignupCost=%s, Signup_NewOrOld=%s, Signup_IsSignCost=%s, SignupRecord_ID=%s, SignUnit_ID=%s, Signup_Isinsurance=%s, Insurance_Money=%s, Insurance_IsTeacher=%s  where Signup_ID=%s and Member_ID=%s and Signup_OrderNumber is null",
	                   GetSQLValueString($SeasonC, "int"),
					   GetSQLValueString($Signup_No, "text"),
					   GetSQLValueString($Season_Bank, "text"),
					   GetSQLValueString($Season_BankCode, "text"),
					   GetSQLValueString($Season_Transaction, "text"),
					   GetSQLValueString($Season_Fee, "text"),
					   GetSQLValueString($Season_BankName, "text"),
					   GetSQLValueString($Season_BankAccount, "text"),
					   GetSQLValueString($Signup_Money, "int"),
					   GetSQLValueString($Signup_Remark, "text"),
					   GetSQLValueString($row_Cate3['Season_PayStart'], "date"),
					   GetSQLValueString($row_Cate3['Season_PayEnd'], "date"),
					   
					   GetSQLValueString("已結單", "text"),
					   GetSQLValueString($Signup_UnitID, "int"),
					   GetSQLValueString($Sign_Cost, "int"),
					   GetSQLValueString($Signup_NewOrOld, "text"),
					   GetSQLValueString($Signup_IsSignCost, "int"),
					   GetSQLValueString($SignupRecord_ID, "text"),
					   GetSQLValueString($SignUnit_ID, "text"),
					   GetSQLValueString($Signup_Isinsurance, "int"),
					   GetSQLValueString($Insurance_Money, "int"),
					   GetSQLValueString($Insurance_IsTeacher, "int"),
	                   GetSQLValueString($row_Data['Signup_ID'], "int"),
					   GetSQLValueString($colname_MemberID, "text")
	);
	*/
	$updateSQL = sprintf("update signup set Season_Count=%s, Season_Bank=%s, Season_BankCode=%s,  Season_Transaction=%s, Season_Fee=%s, Season_BankName=%s, Season_BankAccount=%s, Signup_Money=%s, Signup_Remark=%s, Signup_PayDate=%s,  Signup_PayDeadline=%s, Unit_ID=%s, Signup_SignupCost=%s, Signup_NewOrOld=%s, Signup_IsSignCost=%s, SignupRecord_ID=%s, SignUnit_ID=%s, Signup_Isinsurance=%s, Insurance_Money=%s, Insurance_IsTeacher=%s  where Signup_ID=%s and Member_ID=%s and Signup_OrderNumber is null",
	                   GetSQLValueString($SeasonC, "int"),
					   //GetSQLValueString($Signup_No, "text"),
					   GetSQLValueString($Season_Bank, "text"),
					   GetSQLValueString($Season_BankCode, "text"),
					   GetSQLValueString($Season_Transaction, "text"),
					   GetSQLValueString($Season_Fee, "text"),
					   GetSQLValueString($Season_BankName, "text"),
					   GetSQLValueString($Season_BankAccount, "text"),
					   GetSQLValueString($Signup_Money, "int"),
					   GetSQLValueString($Signup_Remark, "text"),
					   GetSQLValueString($row_Cate3['Season_PayStart'], "date"),
					   GetSQLValueString($row_Cate3['Season_PayEnd'], "date"),
					   
					   //GetSQLValueString("已結單", "text"),
					   GetSQLValueString($Signup_UnitID, "int"),
					   GetSQLValueString($Sign_Cost, "int"),
					   GetSQLValueString($Signup_NewOrOld, "text"),
					   GetSQLValueString($Signup_IsSignCost, "int"),
					   GetSQLValueString($SignupRecord_ID, "text"),
					   GetSQLValueString($SignUnit_ID, "text"),
					   GetSQLValueString($Signup_Isinsurance, "int"),
					   GetSQLValueString($Insurance_Money, "int"),
					   GetSQLValueString($Insurance_IsTeacher, "int"),
	                   GetSQLValueString($row_Data['Signup_ID'], "int"),
					   GetSQLValueString($colname_MemberID, "text")
	);
    mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	$columns_data="signup";
	$columns_dataid="Signup_ID";
	$search_update_id=$row_Data['Signup_ID'];
	$_POST['ID']=$row_Data['Signup_ID'];
	$Other='更新線上報名訂單';
	require_once('../../Include/Data_Update_Content.php');
	require_once("../../Include/Data_BrowseUpdate.php");
	
	$updateSQL = sprintf("update signup_item set Signup_Status=%s, Signup_OrderNumber=%s where Signup_ID=%s ",
	            GetSQLValueString("已結單", "text"),
				GetSQLValueString($Signup_No, "text"),
				GetSQLValueString($row_Data['Signup_ID'], "int")
	);
    mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	$updateSQL = sprintf("update signup_itemmoney set Signup_Status=%s where Signup_ID=%s and Member_ID=%s ",
	            GetSQLValueString("已結單", "text"),
	            GetSQLValueString($row_Data['Signup_ID'], "int"),
				GetSQLValueString($colname_MemberID, "text")
	);
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
		
	//金流API
	require('POSTAPI.php');
	mysql_free_result($SeasonData);
	mysql_free_result($CateP);	
	mysql_free_result($Money);	
    mysql_free_result($Data);
	mysql_free_result($Cate3);
	
	//開啟支付連訂單
	//echo '<script>';
	//echo 'location.href="'.$payment_url.'"';
	//echo '</script>';
	//echo 
	//echo '789';
	//echo $payment_url;
	header(sprintf("Location: %s", $payment_url));
	/*
    $insertGoTo = "AD_Signup_Course.php?Msg=AddOK&Com=".$colname_Com;  
    header(sprintf("Location: %s", $insertGoTo));
	*/



?>
