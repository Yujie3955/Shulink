<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php
	/*if (($row_AdminMember['Teacher_Complete'] == 0) || ($row_AdminMember['Teacher_CompleteAll'] == 0)) {
  		$updateGoTo = "AD_Edit_Teacher.php";
  		header(sprintf("Location: %s", $updateGoTo));
		exit();
	}*/
?>
<?php
	if($_POST['sub_option']<>null){	//表單傳送
		//調出問卷資料(題目)
		$sql_query=sprintf("SELECT * FROM exam_topic WHERE ET_ExamID=%s ORDER BY ET_ID ASC",
			GetSQLValueString($_GET['que'], "int"));
		$que_dt=mysql_query($sql_query, $dbline) or die(mysql_error());
		$que_dt_row = mysql_fetch_assoc($que_dt);
		$totalque_dt_row= mysql_num_rows($que_dt);
		//答題次數
		$sql_query=sprintf("SELECT * FROM exam_result_count WHERE Add_LoginName=%s AND ER_ExamID=%s",
			GetSQLValueString($row_AdminMember['Member_ID'], "text"),
			GetSQLValueString($_POST['ER_ExamID'], "int")
		);
		$exam_count = mysql_query($sql_query, $dbline) or die(mysql_error());
		$exam_count_row = mysql_fetch_assoc($exam_count);
		$ans_count=0;											//答案計數器
		do{
			$ans=$_POST['opt_'.$ans_count];						//此題回答
			if($totalque_dt_row>0){
				if($que_dt_row['ET_Type2']=='多選'){				//遇到多選題時
					//判斷是否為陣列
					if(!empty($ans)){
						$user_ans=implode(";",array_filter($ans));
					}else{
						echo "<script> alert('您第題未填寫!!');document.location.href='AD_Exam_write.php?que=".$_GET['que']."';</script>";
						exit();
					}
				}elseif($que_dt_row['ET_Type2']=='敘述'||$que_dt_row['ET_Type2']=='單選'){
					$user_ans=$ans;
				}
				$sql_query=sprintf("INSERT INTO exam_result (ER_ExamID,ER_Exam,ET_Type,ET_Type2,ET_Content,ER_Type,ER_Name,ER_Time,Add_Time,Add_LoginName,Result_Ans,ER_C) VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
					GetSQLValueString($_GET['que'], "int"),
					GetSQLValueString($_POST['ER_Exam'], "text"),
					GetSQLValueString($que_dt_row['ET_Type'], "text"),
					GetSQLValueString($que_dt_row['ET_Type2'], "text"),
					GetSQLValueString($que_dt_row['ET_Content'], "text"),
					GetSQLValueString($_POST['ER_Type'], "text"),
					GetSQLValueString($_POST['ER_Name'], "text"),
					GetSQLValueString(date("Y-m-d h-i-s"), "date"),
					GetSQLValueString(date("Y-m-d h-i-s"), "date"),
					GetSQLValueString($_POST['Teacher_ID'], "text"),
					GetSQLValueString($user_ans, "text"),
					GetSQLValueString($exam_count_row['ER_Count']+1, "int")
				);
			}
			mysql_query($sql_query, $dbline);
			$ans_count++;
		}while($que_dt_row = mysql_fetch_assoc($que_dt));
		echo "<script>alert('已填入!!');document.location.href='AD_Exam_index.php'";
		echo "</script>";
	}
	//檢查是否有權限可以開啟此問卷
	$sql_query=sprintf("SELECT count(*) as hv, Exam_Title, Exam_ID FROM exam WHERE Exam_ID=%s AND Member_Flag like %s",
		GetSQLValueString($_GET['que'], "int"),
		GetSQLValueString('%'.'1'.'%', "text")
		);
	$ck_wri=mysql_query($sql_query, $dbline) or die(mysql_error());
	$ck_wri_row = mysql_fetch_assoc($ck_wri);
	if($ck_wri_row['hv']==1){
		$sql_query=sprintf("SELECT * FROM exam_topic WHERE ET_ExamID=%s ORDER BY ET_ID ASC",
			GetSQLValueString($_GET['que'], "int")
			);
		$exam = mysql_query($sql_query, $dbline) or die(mysql_error());
		$exam_row = mysql_fetch_assoc($exam);
		$totalRows_exam= mysql_num_rows($exam);
	}else{
		?><script>alert('您尚無觀看此問卷內容!!');document.location.href='AD_Exam_index.php';</script><?php
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
<script>
	function ckfrom(){
		const inputElement = document.getElementsByTagName('input');
		var cont=0;
		var cont2=0;
		var kinds=[];
		var kinds2=[];
		//指調checkbox(取得其name)
		for(var i=0;i<inputElement.length;i++){
			if(inputElement[i].type=='checkbox'){				//checkbox
				if(kinds.indexOf(inputElement[i].name)<0){
					kinds[cont]=inputElement[i].name;
					cont++;
				}	
			}
			if(inputElement[i].type=='radio'){					//radio 
				if(kinds.indexOf(inputElement[i].name)<0){
					kinds2[cont2]=inputElement[i].name;
					cont2++;
				}
			}
		}
		//判斷是否有勾選
		var cot=0;
		var cot2=0;
		for(var j=0;j<kinds.length;j++){
			var obj=document.getElementsByName(kinds[j]);
			for(var k=0;k<obj.length;k++){
				if(obj[k].checked==true){
					cot++;
					break;
				}
			}
		}
		for(var j=0;j<kinds2.length;j++){
			var obj2=document.getElementsByName(kinds2[j]);
			for(var k=0;k<obj2.length;k++){
				if(obj2[k].checked==true){
					cot2++;
					break;
				}
			}
		}
		if(cot<kinds.length){
			alert('您有多選題未作答喔!!');
			return false;
		}
		if(cot2<kinds2.length){
			alert('您有單選題未作答喔!!');
			return false;
		}
	}
</script>
<div>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="15%"><?php require_once('../../Include/Menu_AdminLeft_Student.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">問卷專區</div>
<input type="button" class="Button_Edit" value="回上一頁" onclick="document.location.href='AD_Exam_index.php'"><br><br>
		<?php if($ck_wri_row['hv']=='1'){ ?>
		<form action="" method="post" onsubmit="return ckfrom();">
		<?php if($totalRows_exam>0){?>
			<table width="95%" border="0" cellpadding="10" cellspacing="0" > 
				<?php
					$que_count=1;
					do{  
					
					?>
						<tr>
							<td width="70px"><b>第<?php echo $que_count; ?>題 <?php if($exam_row['ET_Type2']<>'敘述'){echo $exam_row['ET_Type2']."題:&nbsp;";} ?></b><?php echo $exam_row['ET_Content'];?></td>
						</tr>
						<tr>
						<tr>
						<td  colspan="2">
						<?php if($exam_row['ET_Pic']<>null){ ?>
							<a href="<?php echo $exam_row['ET_Pic'];?>" alt=""><img src="<?php echo $exam_row['ET_Pic'];?>" style='width:30%' ></a>
						<?php }?>
							<?php 
								//拆解選項字串
								$option=explode(";",$exam_row['Dynamic_Option']);
								$option_count=count($option);
								if($exam_row['ET_Type2']=='多選'){
									for($i=0;$i<$option_count;$i++){
							?>	
								<input type="checkbox" name='opt_<?php echo $que_count-1;?>[]' value="<?php echo $option[$i]; ?>" ><?php echo $option[$i]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
									}
								}elseif($exam_row['ET_Type2']=='單選'){
									for($i=0;$i<$option_count;$i++){
							?>
								<input type="radio"  name='opt_<?php echo $que_count-1;?>'><?php echo $option[$i]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
									}
								}elseif($exam_row['ET_Type2']=='敘述'){
							?>
								<textarea style="width:100%" name="opt_<?php echo $que_count-1;?>"></textarea>
							<?php
								}
							?>
							<hr>
							</td>
						</tr>
				<?php 		
							$que_count++;
					}while($exam_row = mysql_fetch_assoc($exam)); ?>
			</table>
			<br>
			<input type="hidden" name="ER_ExamID" value='<?php echo	$_GET['que']; ?>'>
			<input type="hidden" name="ER_Exam" value='<?php echo $ck_wri_row['Exam_Title']; ?>'>
			<input type="hidden" name="ER_Type" value='學員'>
			<input type="hidden" name="ER_Name" value='<?php echo $row_AdminMember['Member_UserName']; ?>'>
			<input name="Teacher_ID" type="hidden" id="Teacher_ID" value="<?php echo $row_AdminMember['Member_ID']; ?>">
			<!--<input type="hidden" name="Count_opt" value='<?php //echo $option_count; ?>'><!--問卷題數-->
			<!--<input type="hidden" name="ET_Type" value='<?php// echo $exam_row['ET_Type']; ?>'>-->
			<center>
			<input type="submit" class="Button_Edit" value="送出問卷" name="sub_option" onclick="if(confirm('確定送出問卷?')){return true;}else{return false;}">
			</center>
		<?php }?>
		</form>
		<?php }?>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>