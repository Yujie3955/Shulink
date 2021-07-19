<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php //require_once('../../Include/DB_Admin.php'); ?>
<?php
	//取得回傳數值
	/*
		技術手冊之38頁(PDF檔案)
			冊頁之29頁(實際頁數)
		
		4.13通知部分
		notify_message ={	
			"order_id":"151113456789","pay_type":"ATM","amount":"99","fee":null,"cr
			eate_date":144775205 1,"confirm_date":null,"status": 3 ,"payment_info":{"virtual_account:1
			234567891234567","bank_code":"412","expire_date":"15678989678"
		}
		目前使用ATM轉帳方式
	*/

	//收費回傳指令
	$notify_message = $_POST['notify_message'];
	//抓取目前回傳order_ID
	foreach($notify_message as $Key=>$value){
		${$Key}=$value;
	}

	//ID資料
	/*
		$OrderID_Data共分四個部分
		需要使用explode('_')拆解
		$OrderID_Data[0]	=  隨機時戳ID
		$OrderID_Data[1]	=  網站系統產之帳單編號
		$OrderID_Data[2]	=  SignUp_ID(報名表ID)
		$OrderID_Data[3]	=  Member_ID(學員編號)
	*/
	$OrderID_Data=explode('_',$Order_ID);

	//回傳紀錄
	$Able=array(
		'SignUpReturn_Memo','Order_ID1','Order_ID2','SignUp_ID','Add_Time'
	);
	$Pas=array(
		//第一行
		GetSQLValueString($notify_message, "text"),
		GetSQLValueString($OrderID_Data[0], "text"),
		GetSQLValueString($OrderID_Data[1], "text"),
		GetSQLValueString($OrderID_Data[2], "text"),
		GetSQLValueString(date('Y-m-d H:i:s'), "date")
	);
	$SQL_Test="INSERT INTO signup_return (".implode(',',$Able).") VALUES(".implode(',',$Pas).")";
	$Result = mysql_query($SQL_Test, $dbline) or die(mysql_error());
	
	
	//正式結單(需要帶回 Signup_OrderNumber欄位、Signup_Status欄位) 
	$updateSQL = sprintf("update signup set Signup_OrderNumber=%s,Signup_Status=%s WHERE Signup_ID=%s",
		GetSQLValueString($OrderID_Data[1], "text"),
		GetSQLValueString("已結單", "text"),
		GetSQLValueString($OrderID_Data[2], "int")
	);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());

	$updateSQL = sprintf("update signup_item set Signup_Status=%s, Signup_OrderNumber=%s where Signup_ID=%s ",
		GetSQLValueString("已結單", "text"),
		GetSQLValueString($OrderID_Data[1], "text"),
		GetSQLValueString($OrderID_Data[2], "int")
	);
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	
	$updateSQL = sprintf("update signup_itemmoney set Signup_Status=%s where Signup_ID=%s and Member_ID=%s ",
		GetSQLValueString("已結單", "text"),
		GetSQLValueString($OrderID_Data[2], "int"),
		GetSQLValueString($OrderID_Data[3], "text")
	);
	mysql_select_db($database_dbline, $dbline);
	$Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());
	
?>