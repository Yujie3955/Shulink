<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin.php'); ?>
<?php 
	//$Table='api_return';
	//require_once('../../Include/ShowTableAble.php');
	//社大API回傳
	if(isset($_POST) && trim($_POST)<>""){
		
		$TableName='api_return';
		//抓取當前是否有資料庫
		$SQL_TableData = "show table status";
		$TableData = mysql_query($SQL_TableData, $dbline) or die(mysql_error());
		$row_TableData = mysql_fetch_assoc($TableData);
		$totalRows_TableData = mysql_num_rows($TableData);
		if($totalRows_TableData>0){
			$Arr_Table=array();
			do{
				array_push($Arr_Table,$row_TableData['Name']);
			}while($row_TableData = mysql_fetch_assoc($TableData));
		}
		$Able=array(
			'MerchantID','MerchantTradeNo','StoreID',
			'RtnCode','RtnMsg','TradeNo','TradeAmt','PaymentDate',
			'PaymentType','PaymentTypeChargeFee','TradeDate',
			'SimulatePaid','CustomField1','CustomField2',
			'CustomField3','CustomField4','CheckMacValue'
		);
		$Pas=array(
			//第一行
			GetSQLValueString($_POST['MerchantID'], "text"),
			GetSQLValueString($_POST['MerchantTradeNo'], "text"),
			GetSQLValueString($_POST['StoreID'], "text"),
			//第二行
			GetSQLValueString($_POST['RtnCode'], "int"),
			GetSQLValueString($_POST['RtnMsg'], "text"),
			GetSQLValueString($_POST['TradeNo'], "text"),
			GetSQLValueString($_POST['TradeAmt'], "int"),
			GetSQLValueString($_POST['PaymentDate'], "date"),
			//第三行
			GetSQLValueString($_POST['PaymentType'], "text"),
			GetSQLValueString($_POST['PaymentTypeChargeFee'], "int"),
			GetSQLValueString($_POST['TradeDate'], "date"),
			//第四行
			GetSQLValueString($_POST['SimulatePaid'], "int"),
			GetSQLValueString($_POST['CustomField1'], "text"),
			GetSQLValueString($_POST['CustomField2'], "text"),
			//第五行
			GetSQLValueString($_POST['CustomField3'], "text"),
			GetSQLValueString($_POST['CustomField4'], "text"),
			GetSQLValueString($_POST['CheckMacValue'], "text")
		);
		$SQL_Insert = sprintf("INSERT INTO api_return (".implode(',',$Able).")VALUES(".implode(',',$Pas).")");
		$Result = mysql_query($SQL_Insert, $dbline) or die(mysql_error());
	}
	//回傳值
	//print_r($_POST);