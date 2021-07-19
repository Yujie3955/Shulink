<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php
	//PCHome支付連
	$Signup_ID = $row_Data['Signup_ID'];
	//查詢課程
	//找尋所有報名課程ID
	$SQL_SignUpItem=sprintf("SELECT * FROM signup_item WHERE Signup_ID=%s",
		GetSQLValueString($Signup_ID, "int")
	); 
	$SignUpItem = mysql_query($SQL_SignUpItem, $dbline) or die(mysql_error());
	$row_SignUpItem = mysql_fetch_assoc($SignUpItem);
	$totalRows_SignUpItem = mysql_num_rows($SignUpItem);
	if($totalRows_SignUpItem>0){
		$Arr_CourseID=array();
		do{
			array_push($Arr_CourseID,$row_SignUpItem['Course_ID']);
		}while($row_SignUpItem = mysql_fetch_assoc($SignUpItem));
	}
	
	//找尋課程資料
	$FindCourse=array();
	$Main=array();
	for($i=0;$i<count($Arr_CourseID);$i++){
		$SQL_Course=sprintf("SELECT * FROM course WHERE Course_ID=%s",
			GetSQLValueString($Arr_CourseID[$i], "int")
		);
		$Course = mysql_query($SQL_Course, $dbline) or die(mysql_error());
		$row_Course = mysql_fetch_assoc($Course);
		$totalRows_Course = mysql_num_rows($Course);
		$FindCourse['name']=$row_Course['Course_Name'];
		$FindCourse['url']='url'.$row_Course['Course_Name'];
		array_push($Main,$FindCourse);
	}
	/*
		訂單產生抓取流程
		1.先抓取token(由支付連端給予)
		2.HTTP Method:POST傳輸(須帶入)
		3.傳輸取得token後，並產生訂單請求給予支付連
		4.支付連取得訂單請求後，由支付連產生虛擬帳號，並且回傳訂單[網址]給予消費者連結繳費頁面
		5.消費者繳完費用後，會有回傳通知，則使用之前POST過去之回傳URL接收(RetuAPI.php)
	*/
	//1.抓取token(ST)-------------------------------------------------------------------------------------------------------
	//取得新的 token, 如果 token 還在有效期內的話請不要重複取得
	$str_url = 'https://api.pchomepay.com.tw/v1/token';
	/*
	$headers = array( 
		'Content-Type:application/json', 
		//將帳號密碼以 base64 encode 後帶在 header 中取得 token
		'Authorization: Basic '.base64_encode("7ECF295360E69A0EE93900EFE6C4:ptO78y0WTgMwffmDjurrFsU7lXDvw_o2uRLg9mWi")
	);
	*/
	$headers[] = "Content-Type:application/json";
	$headers[] = "Authorization: Basic ".base64_encode("7ECF295360E69A0EE93900EFE6C4:ptO78y0WTgMwffmDjurrFsU7lXDvw_o2uRLg9mWi");
	
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 對認證證書來源的檢查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 從證書中檢查SSL加密演算法是否存在
	
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL,$str_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, null); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); //本機加
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //本機加
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
	
	$result = curl_exec($ch); 
	
	$token = json_decode($result); 
	$str_url = 'https://api.pchomepay.com.tw/v1/payment'; 
	$headers = array( 
	    'Content-Type:application/json', 
		'pcpay-token:'.$token->token
	);
	//商品陣列
	$Arr_Commodity=array();
	//商品課程
	for($i=0;$i<count($Arr_CourseID);$i++){
		$SQL_CourseData=sprintf("SELECT * FROM course WHERE Course_ID=%s",
			GetSQLValueString($Arr_CourseID[$i], "int")
		);
		$CourseData = mysql_query($SQL_CourseData, $dbline) or die(mysql_error());
		$row_CourseData = mysql_fetch_assoc($CourseData);
		$totalRows_CourseData = mysql_num_rows($CourseData);
		$NowCourseName=$row_CourseData['Course_Name'];
		array_push($Arr_Commodity,array('name'=>$NowCourseName,'url'=>'#'));
	}
	$Arr_ChoseCommodity = array(
		"order_id" 		=> 	uniqid().'_'.$Signup_No."_".$Signup_ID."_".$colname_MemberID,	//唯一值+帳單編號
		"pay_type" 		=>	array('ATM'),							//付款方式
		"amount"   		=>	$Signup_Money,
		"return_url" 	=>	"https://hccu.eduweb.tw/Modules/Student/AD_Signup_Course.php",
		"items"			=>	 $Arr_Commodity
	);
	$requestPayload=json_encode($Arr_ChoseCommodity);
	echo $requestPayload;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_URL,$str_url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $requestPayload); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
	$result = curl_exec($ch); 
	curl_close($ch);
	$payment_Data=json_decode($result);
	foreach($payment_Data as $key=>$value){
		${$key}=$value;
	}
?>