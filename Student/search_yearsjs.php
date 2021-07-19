function CMY_Check(){	
	if($("#MYear").length>0){
		if($("#MYear option:selected").val()!=""){
			var cmy=$("#MYear option:selected").val();
			$("#MYear_Msg").css('color','#0c4379');	
			$("#MYear_Msg").html('為西元'+(parseInt(cmy)+1911)+'年');
		}
		else{	
			$("#MYear_Msg").html('');
		}
	}
}// JavaScript Document