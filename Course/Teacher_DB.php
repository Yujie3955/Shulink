<script type="text/javascript">
<?php if(isset($teacher_modes) && $teacher_modes=='edits'){?>
teacher_db();
<?php }?>
function teacher_db(){	
	var string=document.getElementById("Season_Area").value;
	var mainItemValue = new Array();
	mainItemValue = string.split("/");	
	<?php if($teacher_modes=='adds'){?>
		var mainItemValue2=mainItemValue[4];//Com_ID
	<?php }
	      elseif($teacher_modes=='edits'){?>
	        var mainItemValue2=mainItemValue[3];//Com_ID
	<?php }?>
	if(mainItemValue2!=""){
		
		if (window.XMLHttpRequest){
			xmlhttp_teacher1 = new XMLHttpRequest();
		} 
		else {  
			xmlhttp_teacher1 = new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp_teacher1.onreadystatechange = function() {
			if (xmlhttp_teacher1.readyState==4 && xmlhttp_teacher1.status==200){
				document.getElementById("Teacher_ID").innerHTML = xmlhttp_teacher1.responseText;
				document.getElementById("together_teacher").innerHTML = xmlhttp_teacher1.responseText;	
				<?php if(isset($teacher_modes) && $teacher_modes=='edits'){?>
				teacher_db_area();
				<?php }?>
			}		
		}
		xmlhttp_teacher1.open("get", "teacher_db_value.php?Com_ID=" + encodeURI(mainItemValue2), true);
		xmlhttp_teacher1.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
		xmlhttp_teacher1.send();
	}
			 
}
</script>


