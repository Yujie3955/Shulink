<div align="center">
    <table width="95%" border="0" cellpadding="5" cellspacing="2" style="max-width:800px;">
     
      <tr>
      <td class="right FormTitle02" width="10%"> 班季:</td>
      <td class="middle">
      <?php echo $row_Data['Season_Year']."年度".$row_Data['SeasonCate_Name']; ?>
      
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"> 社區大學:</td>
      <td class="middle">
      
	  <?php echo $row_Data['Com_Name']; ?>
			
    
    <input type="hidden" value="<?php echo $row_Data['Com_ID'];?>" name="Com_ID" id="Com_ID">
		&nbsp;
      <font class="FormTitle02">是否放網路報名：</font><?php if($row_Data['CourseTeacher_Private']==1){echo '否';}else{echo '是';}?>
      &nbsp; <font class="FormTitle02">分校</font>:
       <?php echo $row_Data['Unit_Name'];?>
      <input value="<?php echo $row_Data['Unit_ID'];?>" name="Unit_Value"id="Unit_Value" type="hidden">
      
       
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"> 課程名稱:</td>
      <td width="90%" class="middle">
        
       <?php echo $row_Data['CourseTeacher_Name'];?>
      </td>
      </tr> 
      <tr>
      <td class="right FormTitle02"> 類別:</td>
      <td class="middle">
       <?php echo $row_Data['CourseKind_Name'];?>&nbsp;&nbsp;&nbsp;
       <font style="display:inline-block;">
       <font class="FormTitle02">學分:</font>
       <?php echo $row_Data['Season_Credit'];?>
       </font>&nbsp;&nbsp;&nbsp;
       
       <font style="display:inline-block;">
       <font class="FormTitle02">授課時數:</font>&nbsp;
       <?php echo $row_Data['CourseTeacher_Hour'];?>
       </font>&nbsp;
       <font style="display:inline-block;">
       <font class="FormTitle02">是否有公民素養週:</font>&nbsp;
       <?php if($row_Data['CourseTeacher_IsCWeek']==1){echo '是';}else{echo '否';}?>
       </font>
      
			

     
      </td></tr> 
      <tr>
      <td class="right FormTitle02"> 班別性質:</td>
      <td class="middle">
      <?php if($row_Data['CourseTeacher_IsCredit']==1){echo '學分班';}else{echo '非學分班';}?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"> 地點:</td>
      <td class="middle">
     
       <?php echo $row_Data['Loc_Name'];?>
      
     &nbsp; <font class="FormTitle02">週數/堂數:</font>&nbsp;<?php echo $row_Data['Season_Week'];?>
     <input type="hidden" name="Season_Week" id="Season_Week" value="<?php echo $row_Data["Season_Week"];?>">
      </td></tr> 
       
      <tr>
      <td class="right FormTitle02"> 講師:</td>
      <td class="middle">
      <?php $TeachersName=explode(",",$row_Data['Teacher_UserName']);
	  
	   ?><?php echo $TeachersName[0];?><br/>
    
     
       </td>
      </tr>
     
    
     <tr>
     <td class="right FormTitle03" nowrap> 授課日期:</td>
     <td class="middle"><?php echo $row_Data['CourseTeacher_StartDate'].' 至 '.$row_Data['CourseTeacher_EndDate'];?></td>
     </tr>
      <tr><td class="right FormTitle03" nowrap> 授課時間:</td>
      <td class="middle">
        <font style="float:left;">
       
       <?php $weekname=explode(",","一,二,三,四,五,六,日");
	  if($row_Data['CourseTeacher_Day']<>""){ echo "星期".$weekname[$row_Data['CourseTeacher_Day']-1];}?></font>
       &nbsp;<font  style="float:left;">
       &nbsp;<?php echo str_pad(date("H",strtotime($row_Data['CourseTeacher_Start'])),2,'0',STR_PAD_LEFT);?>&nbsp;時&nbsp;<?php echo str_pad(date("i",strtotime($row_Data['CourseTeacher_Start'])),2,'0',STR_PAD_LEFT);?>&nbsp;分&nbsp;~&nbsp;<?php echo str_pad(date("H",strtotime($row_Data['CourseTeacher_End'])),2,'0',STR_PAD_LEFT);?>&nbsp;時&nbsp;<?php echo str_pad(date("i",strtotime($row_Data['CourseTeacher_End'])),2,'0',STR_PAD_LEFT);?>&nbsp;分&nbsp;</font>
       <font style="display:inline-block;">
       <font class="FormTitle02">授課區段:</font>&nbsp;
       <?php echo $row_Data['CourseTeacher_Time'];?>
       </font>
       <div style="clear:both">&nbsp;</div>
        </td></tr>
        <tr>
      <td class="right FormTitle02"> 招生人數(最高):</td>
      <td class="middle"> 
        <?php if($row_Data['CourseTeacher_Max']<>""){?>
      <?php echo $row_Data['CourseTeacher_Max'];?><?php }else{echo '0';}?>人
     
        &nbsp;
        <font style="display:inline-block;">
          <font class="FormTitle02">開班人數(最低):</font>&nbsp;
         <?php if($row_Data['CourseTeacher_Min']<>""){?>
        <?php echo $row_Data['CourseTeacher_Min'];?><?php }else{echo '0';}?>人
       
        </font>
      </td>
      </tr> 
      <tr>
      <td class="right FormTitle02"> 助教人員:</td>
      <td class="middle">
      <?php if($row_Data['CourseTeacher_Assistant']<>""){echo $row_Data['CourseTeacher_Assistant'];}else{echo '無';}?>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"> 選課條件:</td>
      <td class="middle">
      <pre><?php echo $row_Data['CourseTeacher_Require'];?></pre>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"> 課程對象:</td>
      <td class="middle">
      <pre><?php echo $row_Data['CourseTeacher_Object'];?></pre>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02">課程簡介:</td>
      <td class="middle">
      <pre><?php echo $row_Data['CourseTeacher_Summary'];?></pre>
      </td>
      </tr>
      <tr>
      <td class="right FormTitle02"> 新舊投課類別:</td>
      <td class="middle">
      <?php  if($row_Data['CourseTeacher_Repeat']==1){echo '舊講師，【續開】';}
             elseif($row_Data['CourseTeacher_Repeat']==2){echo '舊講師，【加開】初、中或高階';}
             elseif($row_Data['CourseTeacher_Repeat']==3){echo '舊講師，新投課(非原教授課目)';}
             elseif($row_Data['CourseTeacher_Repeat']==4){echo '新講師，新投課';}?></td>
      </tr>
       <tr>
      <td class="right FormTitle02">教學進度表:  <?php $Count_Table=explode(",;,",$row_Data['CourseTeacher_Schedule']);
	 ?>
        <input type="hidden" name="CourseID" id="CourseID" value="<?php echo $row_Data['CourseTeacher_ID'];?>">
        </td>
      <td id="ContentTable">
    
         <script type="text/javascript">
		 tableopen();
		 
		 function tableopen(){
			 // mainItemValue 代表 option value, 其值對應到 printing p_id
			var mainItemValue = document.getElementById("Season_Week").value;
			var mainItemValue2 = document.getElementById("CourseID").value;
	
			if (window.XMLHttpRequest) 
			{
		// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp_subitems_table = new XMLHttpRequest();
			} 
			else 
			{  
		// code for IE6, IE5
				xmlhttp_subitems_table = new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp_subitems_table.onreadystatechange = function() 
			{
				document.getElementById("ContentTable").innerHTML = xmlhttp_subitems_table.responseText;
			
			}
	
			xmlhttp_subitems_table.open("get", "table_teachervalue_view.php?CourseTeacher_Week=" + encodeURI(mainItemValue)+"&CourseTeacher_ID="+encodeURI(mainItemValue2), true);
			xmlhttp_subitems_table.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
			xmlhttp_subitems_table.send();
			 
			 }
         </script>
      
      
      
      </td>
      </tr>
      
      <tr>
      <td class="right FormTitle02"> 評語:</td>
      <td class="middle">
      <pre><?php echo $row_Data['CourseTeacher_Reviews'];?></pre></td>
      </tr>
      <tr>      
          <td class="right FormTitle02" >審核狀態:</td>
          <td  class="middle"> 
     
      <?php if($row_Data['CourseTeacher_Pass']==1){echo '已審核';}elseif($row_Data['CourseTeacher_Pass']==2){echo '保留';}else{echo '未審';}?>
      
      
          
</td>
      </tr> 
       
    </table>
   
    </div>