

<script type="text/javascript">
$(function () {			
						  $('.Public_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  });
						  $('.Public_Add .TimeStyle .picker_time').datetimepicker({
								format: 'HH:mm',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  });
						  
						  $('.Season_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddSeason_StartandEnd();
						  });
						  
						  
						  $("#Season_EndDate,#Season_StartDate").bind('input', function() {
							  AddSeason_StartandEnd();
						  });
						  
						  
						  $('.Group_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddGroup_StartandEnd();
						  });
						  $("#Season_GroupEndDate,#Season_GroupStartDate").bind('input', function() {
							  AddGroup_StartandEnd();
						  }); 
						  
						  
						  $('.Select_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddSelect_StartandEnd();
						  });
						  				  
						  $("#Season_SelectEndDate,#Season_SelectStartDate").bind('input', function() {
							  AddSelect_StartandEnd();
						  });
						  
						  
						  $('.Pay_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddPay_StartandEnd();
						  });
						  
						  $("#Season_PayEndDate,#Season_PayStartDate").bind('input', function() {
							  AddPay_StartandEnd();
						  }); 
						  
						  
						  $('.Onsite_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddOnsite_StartandEnd();
						  });
						 
						  $("#Season_OnsiteEndDate,#Season_OnsiteStartDate").bind('input', function() {
							  AddOnsite_StartandEnd();						
						  });
						
						  $('.CastCourse_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddCastCourse_StartandEnd();
						  });
						  $("#Season_CastCourseEndDate,#Season_CastCourseStartDate").bind('input', function() {
							  AddCastCourse_StartandEnd();
						  }); 

						  $('.Review_Add .DateStyle .picker_date').datetimepicker({
								format: 'YYYY/MM/DD',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddReview_StartandEnd();
						  });
						  $("#Season_ReviewEndDate,#Season_ReviewStartDate").bind('input', function() {
							  AddReview_StartandEnd();
						  }); 
						  
});
						  function AddSeason_StartandEnd(){	//上課日期
						  	  if(document.getElementById("Season_StartDate").value!=""  && document.getElementById("Season_EndDate").value!=""){
									var StartTime=document.getElementById("Season_StartDate").value+" 00:00:00";
									var EndTime=document.getElementById("Season_EndDate").value+" 23:59:59";
										
									if((Date.parse(StartTime)).valueOf()<=(Date.parse(EndTime)).valueOf()){
										document.getElementById('Season_EndDate').style.backgroundColor='#D7FFD7';
										$(".Season_Span .Msg_Date").hide(); 
									}
									else{
										document.getElementById('Season_EndDate').style.backgroundColor='#ffe1e1';
										$(".Season_Span .Msg_Date").show(); 
									}
							  }
						  }
						  
						  function AddGroup_StartandEnd(){//團體報名
							  if(document.getElementById("Season_GroupStartDate").value!="" && document.getElementById("Season_GroupEndDate").value!="" ){
									var Group_StartTime=document.getElementById("Season_GroupStartDate").value+" 00:00:00";
									var Group_EndTime=document.getElementById("Season_GroupEndDate").value+" 23:59:59";
									
									if((Date.parse(Group_StartTime)).valueOf()<=(Date.parse(Group_EndTime)).valueOf()){
										document.getElementById('Season_GroupEndDate').style.backgroundColor='#D7FFD7';
										$(".Group_Span .Msg_Date").hide(); 
									}
									else{
										document.getElementById('Season_GroupEndDate').style.backgroundColor='#ffe1e1';
										$(".Group_Span .Msg_Date").show(); 
									}									
							   }
						  }
						  
						  
						  function AddSelect_StartandEnd(){	//線上報名
							  if(document.getElementById("Season_SelectStartDate").value!=""  && document.getElementById("Season_SelectEndDate").value!="" ){
									var Select_StartTime=document.getElementById("Season_SelectStartDate").value+" 00:00:00";
									var Select_EndTime=document.getElementById("Season_SelectEndDate").value+" 23:59:59";
									
									if((Date.parse(Select_StartTime)).valueOf()<=(Date.parse(Select_EndTime)).valueOf()){
										document.getElementById('Season_SelectEndDate').style.backgroundColor='#D7FFD7';
										$(".Select_Span .Msg_Date").hide(); 
									}
									else{
										document.getElementById('Season_SelectEndDate').style.backgroundColor='#ffe1e1';
										$(".Select_Span .Msg_Date").show(); 
									}
							   }
						  }
						  
						  function AddPay_StartandEnd(){//繳費日期
							  if(document.getElementById("Season_PayStartDate").value!="" && document.getElementById("Season_PayEndDate").value!="" ){
									var Pay_StartTime=document.getElementById("Season_PayStartDate").value+" 00:00:00";
									var Pay_EndTime=document.getElementById("Season_PayEndDate").value+" 23:59:59";
									
									if((Date.parse(Pay_StartTime)).valueOf()<=(Date.parse(Pay_EndTime)).valueOf()){
										document.getElementById('Season_PayEndDate').style.backgroundColor='#D7FFD7';
										$(".Pay_Span .Msg_Date").hide(); 
									}
									else{
										document.getElementById('Season_PayEndDate').style.backgroundColor='#ffe1e1';
										$(".Pay_Span .Msg_Date").show(); 
									}									
							   }
						  }
						  
						  function AddOnsite_StartandEnd(){	//現場報名
							  if(document.getElementById("Season_OnsiteStartDate").value!="" && document.getElementById("Season_OnsiteEndDate").value!=""){
									var Onsite_StartTime=document.getElementById("Season_OnsiteStartDate").value+" 00:00:00";
									var Onsite_EndTime=document.getElementById("Season_OnsiteEndDate").value+" 23:59:59";
									
									if((Date.parse(Onsite_StartTime)).valueOf()<=(Date.parse(Onsite_EndTime)).valueOf()){
										document.getElementById('Season_OnsiteEndDate').style.backgroundColor='#D7FFD7';
										$(".Onsite_Span .Msg_Date").hide(); 
										
									}
									else{
										document.getElementById('Season_OnsiteEndDate').style.backgroundColor='#ffe1e1';
										$(".Onsite_Span .Msg_Date").show(); 
												
									}									
							   }
						 
						  }
						  function AddCastCourse_StartandEnd(){//投課報名
							  if(document.getElementById("Season_CastCourseStartDate").value!="" && document.getElementById("Season_CastCourseEndDate").value!="" ){
									var CastCourse_StartTime=document.getElementById("Season_CastCourseStartDate").value+" 00:00:00";
									var CastCourse_EndTime=document.getElementById("Season_CastCourseEndDate").value+" 23:59:59";
									
									if((Date.parse(CastCourse_StartTime)).valueOf()<=(Date.parse(CastCourse_EndTime)).valueOf()){
										document.getElementById('Season_CastCourseEndDate').style.backgroundColor='#D7FFD7';
										$(".CastCourse_Span .Msg_Date").hide(); 
									}
									else{
										document.getElementById('Season_CastCourseEndDate').style.backgroundColor='#ffe1e1';
										$(".CastCourse_Span .Msg_Date").show(); 
									}									
							   }
						  }
						  function AddReview_StartandEnd(){//課審報名
							  if(document.getElementById("Season_ReviewStartDate").value!="" && document.getElementById("Season_ReviewEndDate").value!="" ){
									var Review_StartTime=document.getElementById("Season_ReviewStartDate").value+" 00:00:00";
									var Review_EndTime=document.getElementById("Season_ReviewEndDate").value+" 23:59:59";
									
									if((Date.parse(Review_StartTime)).valueOf()<=(Date.parse(Review_EndTime)).valueOf()){
										document.getElementById('Season_ReviewEndDate').style.backgroundColor='#D7FFD7';
										$(".Review_Span .Msg_Date").hide(); 
									}
									else{
										document.getElementById('Season_ReviewEndDate').style.backgroundColor='#ffe1e1';
										$(".Review_Span .Msg_Date").show(); 
									}									
							   }
						  }
/*用輸入的方法會判斷ED*/
 </script>                   