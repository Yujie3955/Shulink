<?php
ini_set('display_errors','1');
error_reporting(E_ALL);
?>
<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php
	//PCHome支付連
	//$Signup_ID = $row_Data['Signup_ID'];
	$Signup_ID = '4';
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
	
	//抓取token
	include "../../Tools/PChomePay//vendor/autoload.php";














?>