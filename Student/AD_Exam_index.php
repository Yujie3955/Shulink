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
	$sql_query=sprintf("SELECT * FROM exam WHERE Member_Flag like %s AND Exam_Onuse=1 AND Add_LoginName is NOT NULL AND Exam_Online<%s ",
		GetSQLValueString("%1%", "text"),
		GetSQLValueString(date("Y-m-d"), "date")
		);
	$exam = mysql_query($sql_query, $dbline) or die(mysql_error());
	$exam_row = mysql_fetch_assoc($exam);
	$totalRows_exam= mysql_num_rows($exam);
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
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle">問卷專區</div>
<br>
		<table width="95%" border="0" cellpadding="10" cellspacing="0" class="stripe"> 
			<tr class="TableBlock_shadow_Head_Back">
				<td class="middle" width="20%">標題</td>
				<td class="middle" width="20%" >開始日期</td>
				<td class="middle" width="20%" >結束日期</td>
				<td class="middle" width="10%" >發布時間</td>
				<td class="middle center" width="8%" >發布人</td>
				<td class="middle" width="10%" >問卷狀態</td>
				<td class="middle" width="10%">作答次數</td>
				<td class="middle" width="10%" >操作</td>
			</tr>
			<?php do{ 
					if($totalRows_exam>0){
						$sql_query=sprintf("SELECT count(*) as hv FROM exam_topic WHERE ET_ExamID=%s",
							GetSQLValueString($exam_row['Exam_ID'], "int")
						);
						$option = mysql_query($sql_query, $dbline) or die(mysql_error());
						$option_row = mysql_fetch_assoc($option);
						$totalRows_option= mysql_num_rows($option);
						//答題次數
						$sql_query=sprintf("SELECT * FROM exam_result_count WHERE Add_LoginName=%s AND ER_ExamID=%s",
							GetSQLValueString($row_AdminMember['Member_ID'], "text"),
							GetSQLValueString($exam_row['Exam_ID'], "int")
							);
						//echo $sql_query;
						$exam_count = mysql_query($sql_query, $dbline) or die(mysql_error());
						$exam_count_row = mysql_fetch_assoc($exam_count);
						if($option_row['hv']>0){
			?>
					<tr>
						<td class="middle"><?php echo $exam_row['Exam_Title']; ?></td>
						<td class="middle"><?php echo $exam_row['Exam_Online']; ?></td>
						<td class="middle"><?php echo $exam_row['Exam_Offline']; ?></td>
						<td class="middle"><?php echo $exam_row['Add_Time']; ?></td>
						<td class="middle"><?php echo $exam_row['Add_UserName']; ?></td>
						<td class="middle">
						<?php if(date("Y-m-d")>$exam_row['Exam_Offline']){
									echo "<font color='red'>問卷已截止囉!!</font>";
									$open=0;
							}elseif(date("Y-m-d")<$exam_row['Exam_Online']){
									echo "<font color='red'>問卷尚未開啟!!</font>";
									$open=0;
							}else{
								echo "<font color='green'>問卷開啟填寫!!</font>";
								$open=1;
							}
						
						?>
						</td>
						<td class="middle"><?php if($exam_count_row['ER_Count']<>null){echo $exam_count_row['ER_Count'];}else{ echo '0';}; ?></td>
						<?php if($open<>0){ ?>
						<td class="middle"><input type="button" value="進入填寫"  class="Button_Edit" onclick="document.location.href='AD_Exam_write.php?que=<?php echo $exam_row['Exam_ID'];?>'" <?php if($open==0){echo 'DISABLED';} ?> ></td>
						<?php }else{ ?>
						<td class="middle"><input type="button" value="已截止"  class="Button_Edit" onclick="" <?php if($open==0){echo 'DISABLED';} ?> ></td>
						<?php }?>
					</tr>
				<?php	}
					}
				}while($exam_row = mysql_fetch_assoc($exam)); ?>
		</table>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>