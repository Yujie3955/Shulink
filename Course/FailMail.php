<?php
//date_default_timezone_set('Asia/Taipei');
$mail= new PHPMailer();
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
// Server 資訊
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->SMTPDebug  = 1;//偵錯模式 可以看到詳細錯誤
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->CharSet = "utf-8";

//寄件者資訊(START)-----------------------------------------------------------------------------
// 登入
$Sender_UserName=$row_WebSetting['Email_Email'];
$Sender_Password=$row_WebSetting['Email_Password'];

$Sender_Name=$row_WebSetting['WebSetting_Title'];
//$Sender_Name='新竹社區大學系統平台';

$mail->Username = $Sender_UserName;                                             //帳號
$mail->Password = $Sender_Password;                                             //密碼
// 寄件者
$mail->From = $Sender_UserName;                                                 //寄件者信箱
$mail->FromName = $Sender_Name;                                                 //寄件者姓名
//寄件者資訊(END)-----------------------------------------------------------------------------

//收件者資訊(START)-----------------------------------------------------------------------------
$Recipient_UserName='wangyujie0955191327@gmail.com';
//$Recipient_UserName=$Arr_TeacherData[$Teachers_ID[$i]];
$name='';
//收件者資訊(END)-----------------------------------------------------------------------------
// 郵件資訊(START)----------------------------------------------------------------------------
$mail->Subject =$row_Data['Course_Name']."-課程".$Audit."審核修改通知";          //設定郵件標題
//$mail->Subject ='測試寄信環境功能';
$mail->IsHTML(true);  
                                                         						//設定郵件內容為HTML
$Content= <<<EOF
	{$row_Data['Course_Name']}課程{$Audit}審核修改通知<br>
	原因:{$_POST['Course_Check1Remark']}
EOF;

// 郵件資訊(END)----------------------------------------------------------------------------
//寄信函式(START)-----------------------------------------------------------------------------
function send_mail($mail_address, $name, $body){
	global $mail;
	$mail->Body = $body;
	$mail->ClearAddresses();
	$mail->AddAddress($mail_address,$name); //新稱收件者 (郵件及名稱)
	$mail->AddReplyTo($webmaster_email,"Squall.f");
	//$mail->AddCC("some_other one@gmail.com", "Someone"); // 新稱副本收件者
	if(!$mail->Send()) {
		echo "Error: " . $mail->ErrorInfo . "\n";
	} else {
		echo "Send To: " . $mail_address . "\n";
	}
}
//寄信函式(End)-----------------------------------------------------------------------------
send_mail($Recipient_UserName, $name, $Content);
?>