<script type="text/javascript">
  var x=0;//判斷Teacher1_Check次數
  var x2=0;//判斷Teacher2_Check次數
  var x3=0;//判斷Teacher3_Check次數
  var x4=0;//判斷Teacher4_Check次數
  var x5=0;//判斷Teacher5_Check次數
  Teacher1_Check();
  Teacher2_Check();
  Teacher3_Check();
  Teacher4_Check();
  Teacher5_Check();
  function Teacher1_Check(){	  
	  if(document.getElementById("Teacher_Name").value!=""){
		   if(document.getElementById("Teacher_Name").value.indexOf("(")>=0 && document.getElementById("Teacher_Name").value.indexOf(")")>=0){	
		        
				var str = document.getElementById("Teacher_Name").value.split("(");  			
				var id=str[1].replace(")", "");
				var name=str[0];
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_teacher = new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp_subitems_teacher = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp_subitems_teacher.onreadystatechange = function() 
				{   
					if (xmlhttp_subitems_teacher.readyState==4 && xmlhttp_subitems_teacher.status==200){					
						document.getElementById("Teacher_Area").innerHTML = xmlhttp_subitems_teacher.responseText;				 					
						if(document.getElementById("Teacher_ID").value !=''){		
						     document.getElementById('Msg_Teacher').innerHTML='';		
							 document.getElementById('Msg_Teacher').style.display="none";
							 document.getElementById("Teacher_Name").style.backgroundColor='#D7FFD7';
							
						}
						else{
							 document.getElementById("Teacher_Name").style.backgroundColor='#ffe1e1';
							 document.getElementById('Msg_Teacher').innerHTML='查無此人！';
							 document.getElementById('Msg_Teacher').style.display="inline-block";							 
						}	
					}
				}			
				xmlhttp_subitems_teacher.open("get", "teacher_value.php?Teacher_Name=" + encodeURI(name)+"&Teacher_Identity="+ encodeURI(id), true);
				xmlhttp_subitems_teacher.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems_teacher.send();	  
		   }
		   else {		
			    document.getElementById("Teacher_Name").style.backgroundColor='#ffe1e1';			  
			    document.getElementById('Msg_Teacher').style.display="inline-block";
			    document.getElementById('Msg_Teacher').innerHTML='請輸入關鍵字，並選擇搜尋下的資料！';	
				document.getElementById("Teacher_ID").value='';					
	       }				   
	  }
	  else if(document.getElementById("Teacher_Value").value!="" && x==0){
		  //搜索原本資料庫的值	
		  var id = document.getElementById("Teacher_Value").value;
		  if (window.XMLHttpRequest) 
		  {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp_subitems_teacher = new XMLHttpRequest();
		  } 
		  else {  
		      // code for IE6, IE5
		  	  xmlhttp_subitems_teacher = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp_subitems_teacher.onreadystatechange = function() 
		  { 
		  	  if (xmlhttp_subitems_teacher.readyState==4 && xmlhttp_subitems_teacher.status==200){	
			  		document.getElementById("Teacher_Area").innerHTML = xmlhttp_subitems_teacher.responseText;
					if(document.getElementById("Teacher_Name").value==""){  	
						document.getElementById('Teacher_Name').value=document.getElementById('Teacher_NameValue').value;			    				}
					else{
						 document.getElementById('Teacher_Name').value= document.getElementById('Teacher_Name').value;
					}
   			  }					
									
		  }	
		  xmlhttp_subitems_teacher.open("get", "teacher_value.php?Teacher_ID="+ encodeURI(id), true);
		  xmlhttp_subitems_teacher.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		  xmlhttp_subitems_teacher.send();		  
	  }	 
	  else{
		   document.getElementById("Teacher_Name").style.backgroundColor="#FFFFFF";
		   document.getElementById('Msg_Teacher').style.display="none";
		   document.getElementById('Teacher_ID').value='';		   
	  }
	  x=x+1;
  }
  
  function Teacher2_Check(){	 
	  if(document.getElementById("Teacher_Name2").value!=""){		    
		   if(document.getElementById("Teacher_Name2").value.indexOf("(")>=0 && document.getElementById("Teacher_Name2").value.indexOf(")")>=0){	
		        
				var str = document.getElementById("Teacher_Name2").value.split("(");  			
				var id=str[1].replace(")", "");
				var name=str[0];
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_teacher2 = new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp_subitems_teacher2 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp_subitems_teacher2.onreadystatechange = function() 
				{   
					if (xmlhttp_subitems_teacher2.readyState==4 && xmlhttp_subitems_teacher2.status==200){					
						document.getElementById("Teacher_Area2").innerHTML = xmlhttp_subitems_teacher2.responseText;			
						if(document.getElementById("Teacher_ID2").value !=''){		
						     document.getElementById('Msg_Teacher2').innerHTML='';		
							 document.getElementById('Msg_Teacher2').style.display="none";
							 document.getElementById("Teacher_Name2").style.backgroundColor='#D7FFD7';
							
						}
						else{
							 document.getElementById("Teacher_Name2").style.backgroundColor='#ffe1e1';
							 document.getElementById('Msg_Teacher2').innerHTML='查無此人！';
							 document.getElementById('Msg_Teacher2').style.display="inline-block";							 
						}	
					}
				}			
				xmlhttp_subitems_teacher2.open("get", "teacher_value2.php?Teacher_Name=" + encodeURI(name)+"&Teacher_Identity="+ encodeURI(id), true);
				xmlhttp_subitems_teacher2.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems_teacher2.send();	  
		   }
		   else {		
		  
			    document.getElementById("Teacher_Name2").style.backgroundColor='#ffe1e1';			  
			    document.getElementById('Msg_Teacher2').style.display="inline-block";
			    document.getElementById('Msg_Teacher2').innerHTML='請輸入關鍵字，並選擇搜尋下的資料！';
				document.getElementById("Teacher_ID2").value='';				
				 
	       }			   
	  }
	  else if(document.getElementById("Teacher_Value2").value!="" && x2==0){
		  //搜索原本資料庫的值			  
		  var id = document.getElementById("Teacher_Value2").value;		 
		  if (window.XMLHttpRequest) 
		  {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp_subitems_teacher2 = new XMLHttpRequest();
		  } 
		  else {  
		      // code for IE6, IE5
		  	  xmlhttp_subitems_teacher2 = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp_subitems_teacher2.onreadystatechange = function() 
		  { 
		  	  if (xmlhttp_subitems_teacher2.readyState==4 && xmlhttp_subitems_teacher2.status==200){
			  		document.getElementById("Teacher_Area2").innerHTML = xmlhttp_subitems_teacher2.responseText;
					
					if(document.getElementById("Teacher_Name2").value==""){  	
						document.getElementById('Teacher_Name2').value=document.getElementById('Teacher_NameValue2').value;			    				}
					else{
						 document.getElementById('Teacher_Name2').value= document.getElementById('Teacher_Name2').value;
					}
   			  }						
		  }	
		  xmlhttp_subitems_teacher2.open("get", "teacher_value2.php?Teacher_ID="+ encodeURI(id), true);
		  xmlhttp_subitems_teacher2.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		  xmlhttp_subitems_teacher2.send();		  
	  }
	  else{
		   document.getElementById("Teacher_Name2").style.backgroundColor="#FFFFFF";
		   document.getElementById('Msg_Teacher2').style.display="none";
		   document.getElementById('Teacher_ID2').value='';		   
	  }
	  x2=x2+1;
  }
  function Teacher3_Check(){	  
	  if(document.getElementById("Teacher_Name3").value!=""){
		   if(document.getElementById("Teacher_Name3").value.indexOf("(")>=0 && document.getElementById("Teacher_Name3").value.indexOf(")")>=0){	
		        
				var str = document.getElementById("Teacher_Name3").value.split("(");  			
				var id=str[1].replace(")", "");
				var name=str[0];
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_teacher3 = new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp_subitems_teacher3 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp_subitems_teacher3.onreadystatechange = function() 
				{   
					if (xmlhttp_subitems_teacher3.readyState==4 && xmlhttp_subitems_teacher3.status==200){					
						document.getElementById("Teacher_Area3").innerHTML = xmlhttp_subitems_teacher3.responseText;				 					
						if(document.getElementById("Teacher_ID3").value !=''){		
						     document.getElementById('Msg_Teacher3').innerHTML='';		
							 document.getElementById('Msg_Teacher3').style.display="none";
							 document.getElementById("Teacher_Name3").style.backgroundColor='#D7FFD7';
							
						}
						else{
							 document.getElementById("Teacher_Name3").style.backgroundColor='#ffe1e1';
							 document.getElementById('Msg_Teacher3').innerHTML='查無此人！';
							 document.getElementById('Msg_Teacher3').style.display="inline-block";							 
						}	
					}
				}			
				xmlhttp_subitems_teacher3.open("get", "teacher_value3.php?Teacher_Name=" + encodeURI(name)+"&Teacher_Identity="+ encodeURI(id), true);
				xmlhttp_subitems_teacher3.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems_teacher3.send();	  
		   }
		   else {		
			    document.getElementById("Teacher_Name3").style.backgroundColor='#ffe1e1';			  
			    document.getElementById('Msg_Teacher3').style.display="inline-block";
			    document.getElementById('Msg_Teacher3').innerHTML='請輸入關鍵字，並選擇搜尋下的資料！';	
				document.getElementById('Teacher_ID3').value='';		  
	       }			   
	  }
	  else if(document.getElementById("Teacher_Value3").value!="" && x3==0){
		  //搜索原本資料庫的值	
		  var id = document.getElementById("Teacher_Value3").value;
		  if (window.XMLHttpRequest) 
		  {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp_subitems_teacher3 = new XMLHttpRequest();
		  } 
		  else {  
		      // code for IE6, IE5
		  	  xmlhttp_subitems_teacher3 = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp_subitems_teacher3.onreadystatechange = function() 
		  { 
		  	  if (xmlhttp_subitems_teacher3.readyState==4 && xmlhttp_subitems_teacher3.status==200){	
			  		document.getElementById("Teacher_Area3").innerHTML = xmlhttp_subitems_teacher3.responseText;
					if(document.getElementById("Teacher_Name3").value==""){  	
						document.getElementById('Teacher_Name3').value=document.getElementById('Teacher_NameValue3').value;			    				}
					else{
						 document.getElementById('Teacher_Name3').value= document.getElementById('Teacher_Name3').value;
					}
   			  }					
									
		  }	
		  xmlhttp_subitems_teacher3.open("get", "teacher_value3.php?Teacher_ID="+ encodeURI(id), true);
		  xmlhttp_subitems_teacher3.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		  xmlhttp_subitems_teacher3.send();		  
	  }
	  else{
		   document.getElementById("Teacher_Name3").style.backgroundColor="#FFFFFF";
		   document.getElementById('Msg_Teacher3').style.display="none";
		   document.getElementById('Teacher_ID3').value='';		   
	  }
	  x3=x3+1;
  }
  function Teacher4_Check(){	  
	  if(document.getElementById("Teacher_Name4").value!=""){
		   if(document.getElementById("Teacher_Name4").value.indexOf("(")>=0 && document.getElementById("Teacher_Name4").value.indexOf(")")>=0){	
		        
				var str = document.getElementById("Teacher_Name4").value.split("(");  			
				var id=str[1].replace(")", "");
				var name=str[0];
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_teacher4 = new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp_subitems_teacher4 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp_subitems_teacher4.onreadystatechange = function() 
				{   
					if (xmlhttp_subitems_teacher4.readyState==4 && xmlhttp_subitems_teacher4.status==200){					
						document.getElementById("Teacher_Area4").innerHTML = xmlhttp_subitems_teacher4.responseText;				 					
						if(document.getElementById("Teacher_ID4").value !=''){		
						     document.getElementById('Msg_Teacher4').innerHTML='';		
							 document.getElementById('Msg_Teacher4').style.display="none";
							 document.getElementById("Teacher_Name4").style.backgroundColor='#D7FFD7';
							
						}
						else{
							 document.getElementById("Teacher_Name4").style.backgroundColor='#ffe1e1';
							 document.getElementById('Msg_Teacher4').innerHTML='查無此人！';
							 document.getElementById('Msg_Teacher4').style.display="inline-block";							 
						}	
					}
				}			
				xmlhttp_subitems_teacher4.open("get", "teacher_value4.php?Teacher_Name=" + encodeURI(name)+"&Teacher_Identity="+ encodeURI(id), true);
				xmlhttp_subitems_teacher4.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems_teacher4.send();	  
		   }
		   else {		
			    document.getElementById("Teacher_Name4").style.backgroundColor='#ffe1e1';			  
			    document.getElementById('Msg_Teacher4').style.display="inline-block";
			    document.getElementById('Msg_Teacher4').innerHTML='請輸入關鍵字，並選擇搜尋下的資料！';
				document.getElementById('Teacher_ID4').value='';		  
	       }			   
	  }
	  else if(document.getElementById("Teacher_Value4").value!="" && x4==0){
		  //搜索原本資料庫的值	
		  var id = document.getElementById("Teacher_Value4").value;
		  if (window.XMLHttpRequest) 
		  {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp_subitems_teacher4 = new XMLHttpRequest();
		  } 
		  else {  
		      // code for IE6, IE5
		  	  xmlhttp_subitems_teacher4 = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp_subitems_teacher4.onreadystatechange = function() 
		  { 
		  	  if (xmlhttp_subitems_teacher4.readyState==4 && xmlhttp_subitems_teacher4.status==200){	
			  		document.getElementById("Teacher_Area4").innerHTML = xmlhttp_subitems_teacher4.responseText;
					if(document.getElementById("Teacher_Name4").value==""){  	
						document.getElementById('Teacher_Name4').value=document.getElementById('Teacher_NameValue4').value;			    				}
					else{
						 document.getElementById('Teacher_Name4').value= document.getElementById('Teacher_Name4').value;
					}
   			  }					
									
		  }	
		  xmlhttp_subitems_teacher4.open("get", "teacher_value4.php?Teacher_ID="+ encodeURI(id), true);
		  xmlhttp_subitems_teacher4.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		  xmlhttp_subitems_teacher4.send();		  
	  }
	  else{
		   document.getElementById("Teacher_Name4").style.backgroundColor="#FFFFFF";
		   document.getElementById('Msg_Teacher4').style.display="none";
		   document.getElementById('Teacher_ID4').value='';		   
	  }
	  x4=x4+1;
  }
  function Teacher5_Check(){	  
	  if(document.getElementById("Teacher_Name5").value!=""){
		   if(document.getElementById("Teacher_Name5").value.indexOf("(")>=0 && document.getElementById("Teacher_Name5").value.indexOf(")")>=0){	
		        
				var str = document.getElementById("Teacher_Name5").value.split("(");  			
				var id=str[1].replace(")", "");
				var name=str[0];
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_teacher5 = new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp_subitems_teacher5 = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp_subitems_teacher5.onreadystatechange = function() 
				{   
					if (xmlhttp_subitems_teacher5.readyState==4 && xmlhttp_subitems_teacher5.status==200){					
						document.getElementById("Teacher_Area5").innerHTML = xmlhttp_subitems_teacher5.responseText;				 					
						if(document.getElementById("Teacher_ID5").value !=''){		
						     document.getElementById('Msg_Teacher5').innerHTML='';		
							 document.getElementById('Msg_Teacher5').style.display="none";
							 document.getElementById("Teacher_Name5").style.backgroundColor='#D7FFD7';
							
						}
						else{
							 document.getElementById("Teacher_Name5").style.backgroundColor='#ffe1e1';
							 document.getElementById('Msg_Teacher5').innerHTML='查無此人！';
							 document.getElementById('Msg_Teacher5').style.display="inline-block";							 
						}	
					}
				}			
				xmlhttp_subitems_teacher5.open("get", "teacher_value5.php?Teacher_Name=" + encodeURI(name)+"&Teacher_Identity="+ encodeURI(id), true);
				xmlhttp_subitems_teacher5.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems_teacher5.send();	  
		   }
		   else {		
			    document.getElementById("Teacher_Name5").style.backgroundColor='#ffe1e1';			  
			    document.getElementById('Msg_Teacher5').style.display="inline-block";
			    document.getElementById('Msg_Teacher5').innerHTML='請輸入關鍵字，並選擇搜尋下的資料！';
				document.getElementById('Teacher_ID5').value='';		  
	       }			   
	  }
	  else if(document.getElementById("Teacher_Value5").value!="" && x5==0){
		  //搜索原本資料庫的值	
		  var id = document.getElementById("Teacher_Value5").value;
		  if (window.XMLHttpRequest) 
		  {
			  // code for IE7+, Firefox, Chrome, Opera, Safari
			  xmlhttp_subitems_teacher5 = new XMLHttpRequest();
		  } 
		  else {  
		      // code for IE6, IE5
		  	  xmlhttp_subitems_teacher5 = new ActiveXObject("Microsoft.XMLHTTP");
		  }
		  xmlhttp_subitems_teacher5.onreadystatechange = function() 
		  { 
		  	  if (xmlhttp_subitems_teacher5.readyState==4 && xmlhttp_subitems_teacher5.status==200){	
			  		document.getElementById("Teacher_Area5").innerHTML = xmlhttp_subitems_teacher5.responseText;
					if(document.getElementById("Teacher_Name5").value==""){  	
						document.getElementById('Teacher_Name5').value=document.getElementById('Teacher_NameValue5').value;			    				}
					else{
						 document.getElementById('Teacher_Name5').value= document.getElementById('Teacher_Name5').value;
					}
   			  }					
									
		  }	
		  xmlhttp_subitems_teacher5.open("get", "teacher_value5.php?Teacher_ID="+ encodeURI(id), true);
		  xmlhttp_subitems_teacher5.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		  xmlhttp_subitems_teacher5.send();		  
	  }
	  else{
		   document.getElementById("Teacher_Name5").style.backgroundColor="#FFFFFF";
		   document.getElementById('Msg_Teacher5').style.display="none";
		   document.getElementById('Teacher_ID5').value='';		   
	  }
	  x5=x5+1;
  }
		  
		  	
	  </script>