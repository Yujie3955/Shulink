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
	*/
	$notify_message = $_POST['notify_message'];
	$SQL_Test=sprintf("INSERT INTO signup_return (SignUpReturn_Memo,Add_Time) VALUES(%s,%s)",
		GetSQLValueString($notify_message, "text"),
		GetSQLValueString(date('Y-m-d H:i:s'), "date")
	);
	$Result = mysql_query($SQL_Test, $dbline) or die(mysql_error());
	

	
	//if(isset($_POST['order_id'])){
		/*
		$order_id			=$_POST['order_id'];
		$amount				=$_POST['amount'];
		$pay_type	 		=$_POST['pay_type'];
		$trade_amount 		=$_POST['trade_amount'];
		$platform_amount  	=$_POST['platform_amount'];
		$pp_fee   			=$_POST['pp_fee'];
		$pay_date 			=$_POST['pay_date'];
		$actual_pay_date	=$_POST['actual_pay_date'];
		$fail_date			=$_POST['fail_date'];
		$status				=$_POST['status'];
		$status_code		=$_POST['status_code'];
		$items				=$_POST['items'];
		$payment_info		=$_POST['payment_info'];
		$available_date		=$_POST['available_date'];
		*/
		/*
		$order_id = $_POST['order_id'];			//訂單編號
		$payment_url = $_POST['payment_url'];	//繳款帳號
		$SQL_Update=sprintf('INSERT INTO signup_order(Order_ID,Payment_Url) VALUES(%s,%s)',
			GetSQLValueString($order_id,'text'),
			GetSQLValueString($payment_url,'text')
		);
		$Result = mysql_query($SQL_Update, $dbline) or die(mysql_error());
		*/
	//}
	
?>