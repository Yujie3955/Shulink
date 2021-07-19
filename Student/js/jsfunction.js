//顯示卡號

function chosecard(num1){
	if(num1.value!=''){
		$('#user_cardnumber').css("display","inline-block");
	}else{
		$('#user_cardnumber').css("display","none");
	}
}
//護照身分證轉換
function twfgck(num1){
	// 1為台灣
	// 2為國外
	if(num1.value.length==0){
		var st='臺灣';
		document.getElementById('Foreigns').value=0;
	}else{
		var st=num1.value;
		if(st==2){
			document.getElementById('Foreigns').value=1;
		}
		else{
			document.getElementById('Foreigns').value=0;
		}
	}
	twfgck_check();	
    
}
function twfgck_check(){
	
	if($('#Foreigns').val()=='1'){		
		
		if($('.tw').length>0){	
			$('.tw').css("display","none");	
		}
		if($('.fg').length>0){	
			$('.fg').css("display","");
		}
		if($('.fg1').length>0){	
			$('.fg1').css("display","");
		}
		if(document.getElementById('Member_Country')){
			document.getElementById('Member_Country').required = true; 
		}
		if(document.getElementById('Member_Rename')){
			document.getElementById('Member_Rename').required = true; 
		}
	
		
        }else{
		if($('.tw').length>0){	
			$('.tw').css("display","");
		}
		if($('.fg').length>0){	
			$('.fg').css("display","none");
		}
		if($('.fg1').length>0){	
			$('.fg1').css("display","none");
		}
		if(document.getElementById('Member_Country')){
			document.getElementById('Member_Country').required = false; 
		}
		if(document.getElementById('Member_Rename')){
			document.getElementById('Member_Rename').required = false; 
		}	
	}
}
	
function checkTwID(id){
	//建立字母分數陣列(A~Z)
	var city = new Array(1,10,19,28,37,46,55,64,39,73,82,2,11,20,48,29,38,47,56,65,74,83,21,3,12,30);
	id = id.toUpperCase();
	// 使用「正規表達式」檢驗格
	//將字串分割為陣列(IE必需這麼做才不會
	id = id.split('');
	//計算總分
	var total = city[id[0].charCodeAt(0)-65];
	for(var i=1; i<=8; i++){
		total += eval(id[i]) * (9 - i);
	}
	//補上檢查碼(最後一碼
	total += eval(id[9]);
	//檢查比對碼(餘數應為0
	return ((total%10 == 0 ));
}/*function end*/ 