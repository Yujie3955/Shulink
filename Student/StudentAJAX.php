<script type="text/javascript">
callbyAJAX();
function callbyAJAX(){//找尋班季
	// mainItemValue 代表 option value, 其值對應到 printing p_id
	var unit_name=($('#Season_Area :selected').val());
	document.getElementById("Season_Area").value=unit_name;
	
	var str = document.getElementById("Season_Area").value.split("/");  
	var Season_Code=str[0];
	var Com_ID=str[1];
	if(document.getElementById("Title")){
	document.getElementById("Title").value=document.getElementById("Season_Area").options[document.getElementById("Season_Area").selectedIndex].text
	}

	document.getElementById('Season_Code').value=Season_Code;
	
	if (window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp_subitems = new XMLHttpRequest();
	} 
	else 
	{
		// code for IE6, IE5
		xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp_subitems.onreadystatechange = function() 
	{
		document.getElementById("Member_ListArea").innerHTML = xmlhttp_subitems.responseText; 
		VoidMember();
		if(document.getElementById('Member_Name').value!=""){
			Member_Check();	
		}
	}
	xmlhttp_subitems.open("get", "../Student/Student_DB.php?Com_ID=" + encodeURI(Com_ID), true);
	xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
	xmlhttp_subitems.send();
	}
</script>
<script type="text/javascript">
function VoidMember() {//關鍵字查詢
	var Member_Array=new Array();
	Member_Array=document.getElementById("Member_List").value.split(',');	
	$( "#Member_Name" ).autocomplete({
		  source: Member_Array,//使用陣列
		  scroll:true,
		  scrollHeight :180,
		  select: function(event, ui) {
			  document.getElementById('Member_Name').value=ui.item.value;
			  Member_Check();
		  }
	});
}
</script>
<script type="text/javascript">
  function Member_Check(){//學員檢查
    
	  if(document.getElementById("Member_Name").value!=""){
		  if(document.getElementById("MName")){
			  document.getElementById("MName").value=document.getElementById("Member_Name").value;
		   }
		  if(document.getElementById("Member_Name").value.indexOf("(")>=0 && document.getElementById("Member_Name").value.indexOf(")")>=0){		
				var str = document.getElementById("Member_Name").value.split("(");  	
				var id=str[1].replace(")", "");//身分證字號
				var name=str[0];//姓名	
				var str2 = document.getElementById("Season_Area").value.split("/");  
				var com=str2[1];
				
				
				if (window.XMLHttpRequest) 
				{
					// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp_subitems_Member = new XMLHttpRequest();
				} 
				else 
				{  
					// code for IE6, IE5
					xmlhttp_subitems_Member = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp_subitems_Member.onreadystatechange = function() 
				{
					if (xmlhttp_subitems_Member.readyState==4 && xmlhttp_subitems_Member.status==200){						
						document.getElementById("Member_Area").innerHTML = xmlhttp_subitems_Member.responseText;
						if(document.getElementById("Member_ID").value!=''){
							 if(document.getElementById("MID")){
								 document.getElementById("MID").value=document.getElementById("Member_ID").value;
							 }
							 document.getElementById('Msg_Member').innerHTML='';
							 document.getElementById('Msg_Member').style.display="none";
							 document.getElementById("Member_Name").style.backgroundColor='#D7FFD7';
							 
							 
						}
						else{
							 document.getElementById("Member_Name").style.backgroundColor='#ffe1e1';						 
							 document.getElementById('Msg_Member').style.display="inline-block";
							 document.getElementById('Msg_Member').innerHTML='查無此人！';
							 if(document.getElementById("MID")){
								 document.getElementById("MID").value='';
							 }
						}	
    
   					}
									
				}			
				xmlhttp_subitems_Member.open("get", "../Student/student_value.php?Member_Name=" + encodeURI(name)+"&Member_Identity="+ encodeURI(id)+"&Com_ID=" + encodeURI(com), true);
				xmlhttp_subitems_Member.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems_Member.send();	  
		  }
		  else{		
			  document.getElementById("Member_Name").style.backgroundColor='#ffe1e1';			  
			  document.getElementById('Msg_Member').style.display="inline-block";
			  document.getElementById('Msg_Member').innerHTML='請輸入關鍵字，並選擇搜尋下的資料！';		              if(document.getElementById("MID")){
				  document.getElementById("MID").value='';
			  }
		  }	
	  }
	  else{
		   document.getElementById("Member_Name").style.backgroundColor="transparent";
		   document.getElementById('Msg_Member').style.display="none";
	  }
  }
Offers_Check();
function Offers_Check(){
var offers=document.getElementById('Offers_Area').value.split('/');	
document.getElementById("Offers_Money").value=offers[0];
document.getElementById("Offers_Reason").value=offers[1];
document.getElementById("Offers_MoneyArea").innerHTML="優惠額度:"+offers[0];;
} 
  
 </script>