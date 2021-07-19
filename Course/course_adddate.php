<script type="text/javascript">
$(function () {			
	$('.Course_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
	}).on('dp.change', function (e) { 
	    AddCourse_StartandEnd();	
	});
	
	
	
	$("#Course_EndDate,#Course_StartDate").bind('input', function() {
		AddCourse_StartandEnd();
	});
						  
						  
						 
						  
});

function AddCourse_StartandEnd(){	//上課日期
						  	  if(document.getElementById("Course_StartDate").value!=""  && document.getElementById("Course_EndDate").value!="" ){
										var StartTime=document.getElementById("Course_StartDate").value+" "+"00:00:00";
										var EndTime=document.getElementById("Course_EndDate").value+" "+"00:00:00";
										
										if((Date.parse(StartTime)).valueOf()<=(Date.parse(EndTime)).valueOf()){
											document.getElementById('Course_EndDate').style.backgroundColor='#D7FFD7';									
											
											$(".Course_Span .Msg_Date").hide(); 
											
											
										}
										else{
											document.getElementById('Course_EndDate').style.backgroundColor='#ffe1e1';
											
											$(".Course_Span .Msg_Date").show(); 
													
										}
										
							  }
							  
}
						  
						  
						 
/*用輸入的方法會判斷ED*/
 </script>                   