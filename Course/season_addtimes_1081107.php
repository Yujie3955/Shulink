

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
						  $('.Season_Add .TimeStyle .picker_time').datetimepicker({
								format: 'HH:mm',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddSeason_StartandEnd();
						  });
						  
						  $("#Season_EndDate,#Season_EndTime,#Season_StartDate,#Season_StartTime").bind('input', function() {
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
						  $('.Group_Add .TimeStyle .picker_time').datetimepicker({
								format: 'HH:mm',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddGroup_StartandEnd();
						  });
						  $("#Season_GroupEndDate,#Season_GroupEndTime,#Season_GroupStartDate,#Season_GroupStartTime").bind('input', function() {
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
						  $('.Select_Add .TimeStyle .picker_time').datetimepicker({
								format: 'HH:mm',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddSelect_StartandEnd();
						  });						  
						  $("#Season_SelectEndDate,#Season_SelectEndTime,#Season_SelectStartDate,#Season_SelectStartTime").bind('input', function() {
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
						  $('.Pay_Add .TimeStyle .picker_time').datetimepicker({
								format: 'HH:mm',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddPay_StartandEnd();
						  });
						  $("#Season_PayEndDate,#Season_PayEndTime,#Season_PayStartDate,#Season_PayStartTime").bind('input', function() {
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
						  $('.Onsite_Add .TimeStyle .picker_time').datetimepicker({
								format: 'HH:mm',
								locale: 'zh-tw',
								showClear:true,
								showClose:false,
								useCurrent:false
								
						  }).on('dp.change', function (e) { 
						  	  AddOnsite_StartandEnd();
						  });
						  $("#Season_OnsiteEndDate,#Season_OnsiteEndTime,#Season_OnsiteStartDate,#Season_OnsiteStartTime").bind('input', function() {
							  AddOnsite_StartandEnd();						
						  });
						  
});
						  function AddSeason_StartandEnd(){	//上課日期
						  	  if(document.getElementById("Season_StartDate").value!="" && document.getElementById("Season_StartTime").value!="" && document.getElementById("Season_EndDate").value!="" && document.getElementById("Season_EndTime").value!=""){
										var StartTime=document.getElementById("Season_StartDate").value+" "+document.getElementById("Season_StartTime").value+":00";
										var EndTime=document.getElementById("Season_EndDate").value+" "+document.getElementById("Season_EndTime").value+":00";
										
										if((Date.parse(StartTime)).valueOf()<=(Date.parse(EndTime)).valueOf()){
											document.getElementById('Season_EndDate').style.backgroundColor='#D7FFD7';									
											document.getElementById('Season_EndTime').style.backgroundColor='#D7FFD7';
											$(".Season_Span .Msg_Date").hide(); 
											
											
										}
										else{
											document.getElementById('Season_EndDate').style.backgroundColor='#ffe1e1';
											document.getElementById('Season_EndTime').style.backgroundColor='#ffe1e1';
											$(".Season_Span .Msg_Date").show(); 
													
										}
										
							  }
							  
						  }
						  
						  
						  function AddGroup_StartandEnd(){//團體報名
							  if(document.getElementById("Season_GroupStartDate").value!="" && document.getElementById("Season_GroupStartTime").value!="" && document.getElementById("Season_GroupEndDate").value!="" && document.getElementById("Season_GroupEndTime").value!=""){
									var Group_StartTime=document.getElementById("Season_GroupStartDate").value+" "+document.getElementById("Season_GroupStartTime").value+":00";
									var Group_EndTime=document.getElementById("Season_GroupEndDate").value+" "+document.getElementById("Season_GroupEndTime").value+":00";
									
									if((Date.parse(Group_StartTime)).valueOf()<=(Date.parse(Group_EndTime)).valueOf()){
										document.getElementById('Season_GroupEndDate').style.backgroundColor='#D7FFD7';									
										document.getElementById('Season_GroupEndTime').style.backgroundColor='#D7FFD7';
										$(".Group_Span .Msg_Date").hide(); 
										
									}
									else{
										document.getElementById('Season_GroupEndDate').style.backgroundColor='#ffe1e1';
										document.getElementById('Season_GroupEndTime').style.backgroundColor='#ffe1e1';
										$(".Group_Span .Msg_Date").show(); 
												
									}									
							   }
						  
						  }
						  
						  
						  function AddSelect_StartandEnd(){	//線上報名
							  if(document.getElementById("Season_SelectStartDate").value!="" && document.getElementById("Season_SelectStartTime").value!="" && document.getElementById("Season_SelectEndDate").value!="" && document.getElementById("Season_SelectEndTime").value!=""){
									var Select_StartTime=document.getElementById("Season_SelectStartDate").value+" "+document.getElementById("Season_SelectStartTime").value+":00";
									var Select_EndTime=document.getElementById("Season_SelectEndDate").value+" "+document.getElementById("Season_SelectEndTime").value+":00";
									
									if((Date.parse(Select_StartTime)).valueOf()<=(Date.parse(Select_EndTime)).valueOf()){
										document.getElementById('Season_SelectEndDate').style.backgroundColor='#D7FFD7';									
										document.getElementById('Season_SelectEndTime').style.backgroundColor='#D7FFD7';
										$(".Select_Span .Msg_Date").hide(); 
										
									}
									else{
										document.getElementById('Season_SelectEndDate').style.backgroundColor='#ffe1e1';
										document.getElementById('Season_SelectEndTime').style.backgroundColor='#ffe1e1';
										$(".Select_Span .Msg_Date").show(); 
												
									}
									
							   }
						  
						  }
						  
						  
						  function AddPay_StartandEnd(){//繳費日期
							  if(document.getElementById("Season_PayStartDate").value!="" && document.getElementById("Season_PayStartTime").value!="" && document.getElementById("Season_PayEndDate").value!="" && document.getElementById("Season_PayEndTime").value!=""){
									var Pay_StartTime=document.getElementById("Season_PayStartDate").value+" "+document.getElementById("Season_PayStartTime").value+":00";
									var Pay_EndTime=document.getElementById("Season_PayEndDate").value+" "+document.getElementById("Season_PayEndTime").value+":00";
									
									if((Date.parse(Pay_StartTime)).valueOf()<=(Date.parse(Pay_EndTime)).valueOf()){
										document.getElementById('Season_PayEndDate').style.backgroundColor='#D7FFD7';									
										document.getElementById('Season_PayEndTime').style.backgroundColor='#D7FFD7';
										$(".Pay_Span .Msg_Date").hide(); 
										
									}
									else{
										document.getElementById('Season_PayEndDate').style.backgroundColor='#ffe1e1';
										document.getElementById('Season_PayEndTime').style.backgroundColor='#ffe1e1';
										$(".Pay_Span .Msg_Date").show(); 
												
									}									
							   }
						  	
						  }
						  
						  
						  function AddOnsite_StartandEnd(){	//現場報名
							  if(document.getElementById("Season_OnsiteStartDate").value!="" && document.getElementById("Season_OnsiteStartTime").value!="" && document.getElementById("Season_OnsiteEndDate").value!="" && document.getElementById("Season_OnsiteEndTime").value!=""){
									var Onsite_StartTime=document.getElementById("Season_OnsiteStartDate").value+" "+document.getElementById("Season_OnsiteStartTime").value+":00";
									var Onsite_EndTime=document.getElementById("Season_OnsiteEndDate").value+" "+document.getElementById("Season_OnsiteEndTime").value+":00";
									
									if((Date.parse(Onsite_StartTime)).valueOf()<=(Date.parse(Onsite_EndTime)).valueOf()){
										document.getElementById('Season_OnsiteEndDate').style.backgroundColor='#D7FFD7';									
										document.getElementById('Season_OnsiteEndTime').style.backgroundColor='#D7FFD7';
										$(".Onsite_Span .Msg_Date").hide(); 
										
									}
									else{
										document.getElementById('Season_OnsiteEndDate').style.backgroundColor='#ffe1e1';
										document.getElementById('Season_OnsiteEndTime').style.backgroundColor='#ffe1e1';
										$(".Onsite_Span .Msg_Date").show(); 
												
									}									
							   }
						 
						  }
/*用輸入的方法會判斷ED*/
 </script>                   