<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/DB_Admin.php'); ?>
<?php 
require_once('../../include/Permission.php');
require_once('../../Include/ComUnit_Self.php');
$modes_true=0;
mysql_select_db($database_dbline, $dbline);
if (isset($_GET['MID']) && $_GET['MID']==1) {//學程
	$modes_true=1;
  	$query_Area = sprintf("SELECT * FROM course_program where Com_ID = %s and CourseProgram_Enable=1  order by CourseProgram_Sort ASC", GetSQLValueString($colname03_Unit, "text"));
	$Area = mysql_query($query_Area, $dbline) or die(mysql_error());
	$row_Area = mysql_fetch_assoc($Area);
	$totalRows_Area = mysql_num_rows($Area);

	echo "<select name='CourseProgram_Name'><option value=''>請選擇...</option>";
	if($totalRows_Area>0){
		do{
				echo "<option value='".$row_Area['CourseProgram_Name']."' >";
				echo $row_Area['CourseProgram_Name'];
				echo "</option>";
		}while($row_Area = mysql_fetch_array($Area));
	}
	echo '</select>';
	mysql_free_result($Area);

}
else if (isset($_GET['MID']) && $_GET['MID']==2) {//新舊課程類別
	$modes_true=1;
	$query_Cate = "SELECT * FROM course_repeat where CourseRepeat_Enable=1  ORDER BY CourseRepeat_Sort ASC";
	$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
	$row_Cate = mysql_fetch_assoc($Cate);
	$totalRows_Cate = mysql_num_rows($Cate);
	?>
	<select name="CourseRepeat_Name" id="CourseRepeat_Name" required>
	<option value="">請選擇...</option>
	<?php if($totalRows_Cate>0){
		do{?>
	<option value="<?php echo $row_Cate['CourseRepeat_Name'];?>" >
	<?php echo $row_Cate['CourseRepeat_Name'];?></option>
	<?php 	}while($row_Cate = mysql_fetch_assoc($Cate));
	      }mysql_free_result($Cate);?>
	</select>
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==3) {//前台週課表課程狀態
	$modes_true=1;
	$query_Cate = "SELECT * FROM course_status where CourseStatus_Enable=1  ORDER BY CourseStatus_Sort ASC";
	$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
	$row_Cate = mysql_fetch_assoc($Cate);
	$totalRows_Cate = mysql_num_rows($Cate);
	?>
	<select name="CourseStatus_Name" id="CourseStatus_Name" required>
	<option value="">請選擇...</option>
	<?php if($totalRows_Cate>0){
		do{?>
	<option value="<?php echo $row_Cate['CourseStatus_Name'];?>" >
	<?php echo $row_Cate['CourseStatus_Name'];?></option>
	<?php 	}while($row_Cate = mysql_fetch_assoc($Cate));
	      }mysql_free_result($Cate);?>
	</select>
	<?php
}
else if (isset($_GET['MID']) && $_GET['MID']==4) {//開課日期
	$modes_true=1;
	?>
	<div class="DateStyle">
	       <div class='input-group date picker_date' >
	       <input type='text' name="Course_StartDay" id="Course_StartDay" data-format="yyyy/MM/dd" class="form-control" value="<?php if(isset($row_Data['Course_StartDay']) && $row_Data['Course_StartDay']<>''){echo date('Y/m/d',strtotime($row_Data['Course_StartDay'])); } ?>" required/>
		
	       <span class="input-group-addon">
	               <span class="glyphicon glyphicon-calendar"></span>
	       </span>
	       </div>
	</div>
	<input type='text' name="Course_StartWeek" id="Course_StartWeek"  style="width:70px;"  readonly required/>
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==5) {//課程分類
	$modes_true=1;
	?>
	<select name="CourseKind_NameArea" id="CourseKind_NameArea" onchange="Call_CourseKinde_Code();"  required>
	<option value="">請選擇...</option>
	</select>
	<input name="CourseKind_Name" id="CourseKind_Name" type="hidden">
	<select name="CourseKindCate_Name" id="CourseKindCate_Name" required>
	<option value="">請選擇...</option>
	</select><input name=="CourseKind_Code" id="CourseKind_Code" type="hidden">

	<?php 
  
}
else if (isset($_GET['MID']) && $_GET['MID']==6) {//課程屬性
	$modes_true=1;
	$query_Cate = sprintf("SELECT * FROM course_property where CourseProperty_Enable=1  order by CourseProperty_Sort ASC");
	$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
	$row_Cate = mysql_fetch_assoc($Cate);
	$totalRows_Cate = mysql_num_rows($Cate);
	?>
	<select name="CourseProperty_Name" id="CourseProperty_Name" required>
	<option value="">請選擇...</option>
	<?php if($totalRows_Cate>0){
		do{?>
		<option value="<?php echo $row_Cate['CourseProperty_Name']?>" ><?php echo $row_Cate['CourseProperty_Name']?></option>
	<?php
		}while($row_Cate = mysql_fetch_assoc($Cate));
	      }mysql_free_result($Cate);?>
	</select>
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==7) {//特別標註
	$modes_true=1;
  	$query_Cate = "SELECT * FROM course_special where CourseSpecial_Enable=1  ORDER BY CourseSpecial_Sort ASC";
	$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
	$row_Cate = mysql_fetch_assoc($Cate);
	$totalRows_Cate = mysql_num_rows($Cate);
	?>
	<select name="Course_Special" id="Course_Special" required>
	<option value="">請選擇...</option>
	<?php if($totalRows_Cate>0){
		do{?>
		<option value="<?php echo $row_Cate['CourseSpecial_Name']?>" ><?php echo $row_Cate['CourseSpecial_Name']?></option>
	<?php
		}while($row_Cate = mysql_fetch_assoc($Cate));
	      }mysql_free_result($Cate);?>
	</select>
	<?php
}
else if (isset($_GET['MID']) && $_GET['MID']==8) {//初審
	$modes_true=1;?>
	<select name="Course_Check1_List" id="Course_Check1">
	<option value="1;通過">通過</option>
	<option value="2;不通過">不通過</option>
	<option value="3;修正通過">修正通過</option>
	</select>
	不通過原因：<input name="Course_Check1Remark" id="Course_Check1Remark" type="text">
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==9) {//複審
	$modes_true=1;?>
	<select name="Course_Check2_List" id="Course_Check2">
	<option value="1;通過">通過</option>
	<option value="2;不通過">不通過</option>
	<option value="3;修正通過">修正通過</option>
	</select>	
	不通過原因：<input name="Course_Check2Remark" id="Course_Check2Remark" type="text">
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==10) {//決審
	$modes_true=1;?>
	<select name="Course_Check3_List" id="Course_Check3">
	<option value="1;通過">通過</option>
	<option value="2;不通過">不通過</option>
	<option value="3;修正通過">修正通過</option>
	</select>
	不通過原因：<input name="Course_Check3Remark" id="Course_Check3Remark" type="text">
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==11) {//課程折扣
	$modes_true=1;?>
	<select name="Course_COTextArea" id="Course_COTextArea" onchange="call_Course_COText();"  required>
	<option value="">請選擇...</option>
	</select>
	<input name="CO_Text" id="CO_Text" type="hidden">
	<input name="CO_Sale" id="CO_Sale" type="hidden">
	<?php
  
}
else if (isset($_GET['MID']) && $_GET['MID']==12) {//保證金
	$modes_true=1;
	?>
	<select name="Pro_Money" id="Pro_Money" required>
	<option value="">請選擇...</option>
	<option value="0" <?php if(isset($row_Data['Pro_Money']) && $row_Data['Pro_Money']=='0'){echo 'selected';}?>>0</option>
	<option value="2000" <?php if(isset($row_Data['Pro_Money']) && $row_Data['Pro_Money']=='2000'){echo 'selected';}?>>2000</option>
	<option value="1000" <?php if(isset($row_Data['Pro_Money']) && $row_Data['Pro_Money']=='1000'){echo 'selected';}?>>1000</option>
	</select><font style="font-weight:bold;">元</font>
	<?php
}
if($modes_true==1){
?>&nbsp;&nbsp;<input name="submit_mode" value="確定修改" type="submit" class="Button_Submit"><input type="hidden" name="MM_update" id="MM_update" value="Multi_Edit"><?php
}

?>
