<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin.php'); ?>
<?php	
	//綠界使用
	header("Content-Type:text/html; charset=utf-8");
	//檢查付款方式
	function PayWay($Pay){
		switch($Pay){
			case 'Credit':			//信用卡及銀聯卡
			return 1;
			break; 
			case 'UnionPay':		//銀聯卡(需申請開通)
			return 2;
			break;
			case 'WebATM':			//網路ATM
			return 3;
			break;
			case 'ATM':				//自動櫃員機
			return 4;
			break;
			case 'CVS':				//超商代碼
			return 5;
			break;
			case 'BARCODE':			//超商條碼
			return 6;
			break;
			case 'ALL':				//不指定付款方式
			return 7;
			break;
		}
	}
	//檢查碼
	function generate($arParameters=array(),$HashKey ='',$HashIV='',$encType=0){ 
		$sMacValue = '' ; 
		if(isset($arParameters)){
			// arParameters 為傳出的參數，並且做字母 A-Z 排序 
			unset($arParameters['CheckMacValue']);   
			uksort($arParameters, array('ECPay_CheckMacValue','merchantSort')); // 組合字串 
			$sMacValue = 'HashKey=' . $HashKey ; 	
			foreach($arParameters as $key => $value){ 
				$sMacValue.='&'.$key.'='.$value ; 
			}
			$sMacValue.='&HashIV='.$HashIV ;   
			// URL Encode 編碼     
			$sMacValue = urlencode($sMacValue);  
			// 轉成小寫 
			$sMacValue = strtolower($sMacValue);   
			// 取代為與 dotNet 相符的字元 
			$sMacValue = str_replace('%2d', '-', $sMacValue); 
			$sMacValue = str_replace('%5f', '_', $sMacValue); 
			$sMacValue = str_replace('%2e', '.', $sMacValue); 
			$sMacValue = str_replace('%21', '!', $sMacValue); 
			$sMacValue = str_replace('%2a', '*', $sMacValue); 
			$sMacValue = str_replace('%28', '(', $sMacValue); 
			$sMacValue = str_replace('%29', ')', $sMacValue); 
			// 編碼 
			switch ($encType){
				case ECPay_EncryptType::ENC_SHA256:
				// SHA256 編碼 
				$sMacValue = hash('sha256', $sMacValue); break; 
				case ECPay_EncryptType::ENC_MD5: 
				default: 
				// MD5 編碼 
				$sMacValue = md5($sMacValue); 
			}
			$sMacValue = strtoupper($sMacValue); 
		}
		return $sMacValue; 
	}
	
	$Error=0;
	//API導入檔案
	//必填項目
	$Arr_Required=array(
		'MerchantID'=>$MerchantID='3063113',																						//特店編號(由綠界提供)
		'MerchantTradeNo'=>$MerchantTradeNo=$Signup_No,																				//特店交易編號(由特店提供)
		'MerchantTradeDate'=>$MerchantTradeDate=date('Y/m/d H:i:s'),																//特店交易時間
		'PaymentType'=>$PaymentType='aio',																							//交易類型
		'TotalAmount'=>$TotalAmount=$Signup_Money,																					//交易金額
		'TradeDesc'=>$TradeDesc=urlencode('課程結帳'),																				//交易描述
		'ItemName'=>$ItemName='課程報名費',																							//商品名稱
		//等單位通知ST
		'ReturnURL'=>$ReturnURL='https://hccu.eduweb.tw/Modules/Student/RetuAPI.php',												//付款完成通知回傳網址(尚未訂定)			
		'ChoosePayment'=>$ChoosePayment='ATM',								 														//選擇預設付款方式
		'CheckMacValue'=>$CheckMacValue=generate($arParameters=array(),$HashKey ='pfAPTiawzg93FG7y',$HashIV='WYPmbUwcUCqIU7xm'),	//檢查碼
		//等單位通知END
		'EncryptType'=>$EncryptType='1'																								//CheckMacValue加密類型
	);
	foreach($Arr_Required as $Required_Key=>$Required_Value){
		if(!isset($Required_Value) || trim($Required_Value)=="" || $Required_Value==null){
			echo '必填項目:('.$Required_Key.")-不可輸入空值<br>";
			$Error++;
		}
	}
	//非必填
	$Arr_NoRequired=array(
		'StoreID'=>$StoreID='',							//特店旗下店舖代號
		'ClientBackURL'=>$ClientBackURL='',				//Client端返回特店的按鈕連結
		'ItemURL'=>$ItemURL='',							//商品銷售網址
		'Remark'=>$Remark='',							//備註欄位
		'ChooseSubPayment'=>$ChooseSubPayment='',		//付款子項目
		'OrderResultURL'=>$OrderResultURL='',			//Client端回傳付款結果網址
		'NeedExtraPaidInfo'=>$NeedExtraPaidInfo='',		//是否需要額外的付款資訊
		'DeviceSource'=>$DeviceSource='',				//裝置來源
		'IgnorePayment'=>$IgnorePayment='',				//隱藏付款方式
		'PlatformID'=>$PlatformID='',					//特約合作平台商代號(由綠界提供)
		'InvoiceMark'=>$InvoiceMark='',					//電子發票開立註記
		'CustomField1'=>$CustomField1='',				//自訂名稱欄位1
		'CustomField2'=>$CustomField2='',				//自訂名稱欄位2
		'CustomField3'=>$CustomField3='',				//自訂名稱欄位3
		'CustomField4'=>$CustomField4='',				//自訂名稱欄位4
		'ExpireDate'=>$ExpireDate='',					//允許繳費有效天數
		'PaymentInfoURL'=>$PaymentInfoURL='',			//Server端回傳付款相關資訊
		'ClientRedirectURL'=>$ClientRedirectURL=''		//Client端回傳付款相關資訊
	);
?>
<?php
if($Error<=0){ 
//判斷有無錯誤值
	//自動POST
?>
<form action="" method="post" id='Form_API'>
	<?php 
		foreach($Arr_Required as $Key=>$Value){ 
	?>
		<input type="hidden" name="<?php echo $Key; ?>" id="<?php echo $key; ?>" value="<?php echo $Value; ?>">
	<?php 
		} 
	?> 
	<?php 
		foreach($Arr_NoRequired as $Key=>$Value){
	?>
		<input type="hidden" name="<?php echo $Key; ?>" id="<?php echo $key; ?>" value="<?php echo $Value; ?>">
	<?php
		}
	?>
	<?php 
		if(PayWay($ChoosePayment)==6){ 
			//ATM
	?>
		<input type="hidden" name="BankCode" id="BankCode" value="">
		<input type="hidden" name="vAccount" id="vAccount" value="<?php echo $Signup_No; ?>">
		<input type="hidden" name="ExpireDate" id="ExpireDate" value="<?php //echo ; ?>">
	<?php 
		}
	?>
	<?php /* ?>
	<input type="hidden" name="MM_Insert" id="MM_Insert" value="Form_API">
	<?php */ ?>
</form>
<script>
	//自動POST值
	var Form_API = document.getElementById('Form_API');
	Form_API.submit();
</script>
<?php } ?>