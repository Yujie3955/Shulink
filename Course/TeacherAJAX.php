<script type="text/javascript"> 

      $( "#together_teacher" ).select2({
	        placeholder: "請選擇講師",
			allowClear: true
	  });
	  $( "#Teacher_ID" ).select2({
		  placeholder: "請選擇講師",
		  allowClear: true
	  });	
function teacher_db_area(){
    <?php if(isset($row_Data['Teacher_ID']) && $row_Data['Teacher_ID']<>""){
	  $Teacher_IDList=explode(",",$row_Data['Teacher_ID']);
	  $Teacher_ID= $Teacher_IDList[0]; ?>
	  
          //$("#Teacher_ID").val(<?php echo $Teacher_ID;?>);	  
	  $("#Teacher_ID").select2();
          $("#Teacher_ID").val("<?php echo $Teacher_ID;?>").trigger('change');
	  Teacher1();
    <?php if(count($Teacher_IDList)>1){
			  for($tc=1;$tc<count($Teacher_IDList);$tc++){
				  mysql_select_db($database_dbline, $dbline);
				  $query_TeacherData = sprintf("SELECT * FROM teacher WHERE Teacher_ID =%s ",GetSQLValueString($Teacher_IDList[$tc], "int"));
				  $TeacherData = mysql_query($query_TeacherData, $dbline) or die(mysql_error());
				  $row_TeacherData= mysql_fetch_assoc($TeacherData);
				  $totalRows_TeacherData= mysql_num_rows($TeacherData);
				  
				  ?>
				  $("#Teachers_Area").append("<div class='t_area' style='display:inline-block; padding:5px; border:1px solid #ccc;'><a href='javscript:dt(this)'><img src='../../Icon/0.png'></a><span><?php echo $row_TeacherData["Teacher_UserName"]."(".$row_TeacherData['Teacher_Identity'].")";?><input type='hidden' name='Teachers[]' value='<?php echo $row_TeacherData["Teacher_UserName"]."(".$row_TeacherData['Teacher_Identity'].")";?>'><input type='hidden' name='TeachersID[]' value='<?php echo $row_TeacherData["Teacher_ID"];?>'></span></div>");
		  
	  	<?php     	  mysql_free_result($TeacherData);
		       	  }
	  }//count($Teacher_IDList)>1
	  }//isset($row_Data['Teacher_ID']) && $row_Data['Teacher_ID']<>""?>
	 
	  <?php if(isset($row_Data['Teacher_ID']) && $row_Data['Teacher_ID']<>""){?>
	  if($("#Teacher_ID2").length>0){
		  $("#Teacher_ID").select2("enable", false);
		  $("#Teacher_ID2").val($("#Teacher_ID option:selected").val());
		  $("#Teacher_Name2").val($("#Teacher_ID option:selected").text());
	  }
	  <?php }?>
     
}		
      $('#together_teacher_add').click(function(e){
		
		var new_t = $("#together_teacher").find("option:selected").text();
		var new_t_ary = new_t.split('(');
		var new_s = $( "#together_teacher" ).val();
		var new_s_text = $( "#together_teacher" ).find("option:selected").text();
		
	
		if(new_s != ''){
						
			$("#Teachers_Area").append("<div class='t_area' style='display:inline-block; padding:5px; border:1px solid #ccc;'><a href='javscript:dt(this)'><img src='../../Icon/0.png'></a><span>"+new_s_text+"<input type='hidden' name='Teachers[]' value='"+new_s_text+"'><input type='hidden' name='TeachersID[]' value='"+new_s+"'></span></div>");
			
		}else{
			alertify.alert('請先選取協同老師，或者輸入講師部分姓名');
		}
		
     });
     $('#Teachers_Area').on('click','.t_area a',function(){
		$(this).parent().remove();  
			
     });
	   
     function Teacher1(){
		$("#Teacher_Name").val($("#Teacher_ID option:selected").text());
     }
		
     
     
</script>