<div align="center">
    <table width="99%" border="0" cellpadding="5" cellspacing="2" style="max-width:100%;">
     
      <tr>
      <td colspan="2" class="CourseTable_Title"><?php echo $row_Data['Season_Year'].'-'.$row_Data['SeasonCate_Name']."&nbsp;".$row_Data['Course_NO'].'-'.$row_Data['Course_Name'].''?></td>
      </tr>
      <tr>  
	<td colspan="2" style="padding:0px;">
		<table width="100%" cellpadding="5" cellspacing='2' border='0' >
		<tr>
		<td width="24%" class="center middle fontWB" ><?php echo $row_TeacherData['Teacher_UserName'];?>&nbsp;講師</td>
		<td width="76%"><ol style="width:100%; padding:0px; margin:0px; line-height:30px;">
				<li class="fontWB table-grey">現職：</li><li><?php echo $row_TeacherData['Job_Title'];?></li>
				<li class="fontWB">簡介：</li><li><?php echo $row_TeacherData['Teacher_Experience'];if(preg_match("/簡歷二/i",$row_Data['Course_TeacherResume'])){echo "<br/>".$row_TeacherData['Teacher_Experience2'];}?></li>
				<li class="fontWB table-grey">擁有證照：</li><li><?php echo $row_TeacherData['Teacher_License'];?></li>
				<li class="fontWB">個人專長：</li><li><?php echo $row_TeacherData['Teacher_Profession'];?></li>
				</ol>
		</td>
		</tr>
		<tr>
		<td width="24%" class="right middle fontWB Course_Subtitle" >上課日期：</td>	
		<td class="left middle fontW"><?php echo $row_Data['Course_StartDay'].'(第一週)，共'.$row_Data['SeasonWeek_Name'];?></td>
		</tr>
		<tr>
		<td width="24%" class="right middle fontWB Course_Subtitle" >上課時間：</td>	
		<td class="left middle fontW table-grey"><?php echo '每'.$row_Data['Course_Day1'].'&nbsp;'.$row_Data['Course_Time'].'&nbsp;';if($row_Data['Course_Start1']<>""){echo date("H點i分",strtotime($row_Data['Course_Start1']));}if($row_Data['Course_End1']<>""){echo '~'.date("H點i分",strtotime($row_Data['Course_End1']));}?></td>
		</tr>
		<tr> 
		<td width="24%" class="right middle fontWB Course_Subtitle" >上課地點：</td>	
		<td class="left middle fontW"><?php echo $row_Data['Loc_Name'].'('.$row_Data['Loc_Address'].')';?></td>
		</tr>
		</table>
	</td>   
      </tr>        
      <tr>
      <td colspan="2" class="CourseTable_Title"><img src="../../Icon/TagWhite.png" width="30">&nbsp;課程基本資訊</td>
      </tr>   
	<?php $f_c=0;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>" >課程類別：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['CourseKind_Name'].'-'.$row_Data['CourseKindCate_Name'];?></td>
      </tr> 
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>" >課程相關照片：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>">
<?php 
	if($row_Data['Course_Pic']<>""){
		$pic=explode(";",$row_Data['Course_Pic']);
		$pictext=explode(";",$row_Data['Course_PicText']);
		for($i=0;$i<count($row_Data['Course_Pic']);$i++){?>
		<a href="../../UpLoad/Course/<?php echo $pic[$i];?>" title="另開新視窗-<?php echo $pictext[$i];?>">
		<img src="../../UpLoad/Course_s/<?php echo $pic[$i];?>" title="<?php echo $pictext[$i];?>" width='150'>
		</a>
<?php
		}
	}
?>	
      </td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">課程影片介紹：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Youtube'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">課程理念：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Idea'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">課程目標：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Aim'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">上課方式：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Method'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">評量方式：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Evaluation'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">學員自備事項：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Condition'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>" nowrap>這門課適合對象：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Limit'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">參考書目：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Books'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">數位教材連結：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_ItemWeb'];?></td>
      </tr>
	<?php $f_c++;?>      
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">招生人數：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Max'];?>人</td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">招生狀態：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['CourseStatus_Name'];?></td>
      </tr>
	<?php $f_c++;?>
	<?php $course_credit_money=$row_Data['Credit_Money']*$row_Data['CO_Sale'];
	      if($course_credit_money>0){$course_credit_money=ceil($course_credit_money);}?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">學分費：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Course_Credit'].'學分，'.$row_Data['Credit_Money'].'元';if($row_Data['CO_Sale']<>"1"){echo '【折扣後只需要'.$course_credit_money.'元】';}?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">雜費：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Credit2_Name'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">其他費用一：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo '報名費：新生收取200元<br/>學生證遺失補收報名費200元<br/>學生團體意外保險費：春200元、秋200元、暑100元(一學期僅繳一次)';?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td width="10%" class="left middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>">其他費用二：</td>
      <td class="fontW top <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_Data['Credit2_Name'];?></td>
      </tr>
	<?php $f_c++;?>
      <tr>
      <td colspan="2" class="CourseTable_Title"><img src="../../Icon/TagWhite.png" width="30">&nbsp;課程大綱</td>
      </tr>
      <tr>
	<td colspan="2" style="padding:0px;">
		<table width="100%" cellpadding="5" cellspacing='2' border='0' >
		<tr>
		<th width="13%" class="middle Course_Subtitle fontWB">週次</th>
		<th width="35%" class="middle Course_Subtitle fontWB">課程主題</th>
		<th width="52%" class="middle Course_Subtitle fontWB">課程內容</th>
		</tr>
		<?php
		$query_ScheduleData = sprintf("SELECT * from course_schedule where Course_ID=%s ",GetSQLValueString($row_Data['Course_ID'], "int"));
		$ScheduleData = mysql_query($query_ScheduleData, $dbline) or die(mysql_error());
		$row_ScheduleData = mysql_fetch_assoc($ScheduleData);
		$totalRows_ScheduleData = mysql_num_rows($ScheduleData);
		if($totalRows_ScheduleData>0){
			$f_c=0;
			do{ $f_c++;?>
			<tr>
			<td class="middle fontW <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo $row_ScheduleData['CourseSchedule_Name'];?></th>
			<td class="middle fontWB <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo nl2br($row_ScheduleData['CourseSchedule_Theme']);?></th>
			<td class="middle fontW <?php if(($f_c%2)==1){echo 'table-grey';}?>"><?php echo nl2br($row_ScheduleData['CourseSchedule_Content']);?></th>
			</tr>
		<?php   }while($row_ScheduleData = mysql_fetch_assoc($ScheduleData));
		}?>
	</td>
      </tr>      
    </table>
   
     
    </div>