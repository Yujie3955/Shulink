<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin.php'); ?>
<?php 
$modulename=explode("_",basename(__FILE__, ".php"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);
/*權限*/
mysql_select_db($database_dbline, $dbline);
$query_Permission = sprintf("SELECT * FROM permissions_detail WHERE Account_ID =%s and ModuleSetting_Code = %s and ModuleSetting_Name= %s",GetSQLValueString($row_AdminMember['Account_ID'], "text"), GetSQLValueString($Code, "text"), GetSQLValueString($modulename[1], "text"));
$Permission = mysql_query($query_Permission, $dbline) or die(mysql_error());
$row_Permission = mysql_fetch_assoc($Permission);
$totalRows_Permission= mysql_num_rows($Permission);
?>
<?php require_once('module_setting.php'); ?>

<?php
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
<?php require_once('../../Include/Permission.php'); ?>
<?php
$query_Cate = sprintf("SELECT * FROM offers_item where OffersItem_Reason = %s ",GetSQLValueString("助教","text"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

$query_Data = sprintf("SELECT * FROM course_offers where Com_ID Like %s Group by course_offers.Season_Code,Com_ID ORDER BY course_offers.Season_Code ASC ,Com_ID ASC ",GetSQLValueString($colname03_Unit,"text"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);



if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "Form_Add") ) {
	if((isset($_POST['del'])&&$_POST['del']=="刪除") && (isset($_POST['Member_Identity'])&&$_POST['Member_Identity']<>'')){
	 	$Other="刪除".$row_Permission['ModuleSetting_Title'];
		//檢查刪除
		mysql_select_db($database_dbline, $dbline);
	    $query_CateP = sprintf("SELECT * FROM offers WHERE Offers_ID=%s and Course_ID is null and SignupItem_ID is null and SignupRecord_ID is null", GetSQLValueString($_POST['ID'], "int"));
	    $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
	    $row_CateP = mysql_fetch_assoc($CateP);
	    $totalRows_CateP = mysql_num_rows($CateP);
	    if($totalRows_CateP>0){  
			require_once('../../Include/Data_BrowseDel.php');	
			
			$deleteSQL = sprintf("DELETE FROM offers WHERE Offers_ID=%s and (Course_ID is null and SignupItem_ID is null and SignupRecord_ID is null)",
							   GetSQLValueString($_POST['ID'], "int"));
		
			mysql_select_db($database_dbline, $dbline);
			$Result1 = mysql_query($deleteSQL, $dbline) or die(mysql_error());  
			//回歸自動ID;
			$selectSQL = "ALTER TABLE offers AUTO_INCREMENT=1";
				
			mysql_select_db($database_dbline, $dbline);
			$Result1_2 = mysql_query($selectSQL, $dbline) or die(mysql_error()); 
		   
			$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelOK&CID=".$_POST['Course_ID']."&SID=".$_POST['Season_Area']."&UID=".$_POST['Unit_ID'];	  
		}
		else{
			$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=DelError&CID=".$_POST['Course_ID']."&SID=".$_POST['Season_Area']."&UID=".$_POST['Unit_ID'];				
		} 
		mysql_free_result($CateP);
			   
	}
	else{
		$Other="新增".$row_Permission['ModuleSetting_Title'];		
		$AddTime=date("Y-m-d H:i:s");
		if(isset($_POST['Member_Identity'])&&$_POST['Member_Identity']<>''){
			//判斷是否有新增過OP
			$query_CateP = sprintf("SELECT * FROM offers inner join member on member.Member_ID=offers.Member_ID where offers.CourseID_Assistant = %s and member.Member_Identity=%s and Com_ID=%s and (offers.Course_ID is null and offers.SignupItem_ID is null and offers.SignupRecord_ID is null)",GetSQLValueString($_POST['Course_ID'],"int"),GetSQLValueString($_POST['Member_Identity'],"text"),GetSQLValueString($_POST['Com_ID'],"text"));
			$CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
			$row_CateP = mysql_fetch_assoc($CateP);
			$totalRows_CateP = mysql_num_rows($CateP);
			
			$query_CateP2 = sprintf("SELECT * FROM member where member.Member_Identity=%s and Com_ID=%s ",GetSQLValueString($_POST['Member_Identity'],"text"),GetSQLValueString($_POST['Com_ID'],"text"));
			$CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
			$row_CateP2 = mysql_fetch_assoc($CateP2);
			$totalRows_CateP2 = mysql_num_rows($CateP2);
			$Member_ID=$row_CateP2['Member_ID'];
			mysql_free_result($CateP2);
			if($totalRows_CateP<1){
				/*先刪除原本的助教OP*/
				$Other="刪除".$row_Permission['ModuleSetting_Title'];
				require_once('../../Include/Data_BrowseDel.php');	
				$deleteSQL = sprintf("DELETE FROM offers WHERE Offers_ID=%s",
								   GetSQLValueString($_POST['ID'], "int"));
			
				mysql_select_db($database_dbline, $dbline);
				$Result1_1 = mysql_query($deleteSQL, $dbline) or die(mysql_error()); 
				//回歸自動ID;
				$selectSQL = "ALTER TABLE offers AUTO_INCREMENT=1";
			
				mysql_select_db($database_dbline, $dbline);
				$Result1_2 = mysql_query($selectSQL, $dbline) or die(mysql_error()); 
				/*先刪除原本的助教ED*/
				if(isset($_POST['Offers_Reason2'])&&$_POST['Offers_Reason2']<>''){
					$Offers_Reason2='-'.$_POST['Offers_Reason2'].'';
				}
				else{
					$Offers_Reason2='';
				}
				$insertSQL = sprintf("INSERT INTO Offers (Member_ID, Offers_Money, Offers_Reason, Season_Code, Add_Time, Add_Account, Add_Unitname, Add_Username,CourseID_Assistant) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
							   GetSQLValueString($Member_ID, "int"),
							   GetSQLValueString($_POST['Offers_Money'], "int"),
							   GetSQLValueString($_POST['Offers_Reason'].$Offers_Reason2, "text"),
							   GetSQLValueString($_POST['Season_Code'],"int"),
							  
							   GetSQLValueString($AddTime, "date"),
							   GetSQLValueString($_POST['Add_Account'], "text"),
							   GetSQLValueString($_POST['Add_Unitname'], "text"),
							   GetSQLValueString($_POST['Add_Username'], "text"),
							   GetSQLValueString($_POST['Course_ID'], "int"));
							   
				$NewContent=$Member_ID."/".$_POST['Offers_Money']."/".$_POST['Offers_Reason'].$Offers_Reason2."/".$_POST['Season_Code']."/".$AddTime."/".$_POST['Add_Account']."/".$_POST['Add_Unitname']."/".$_POST['Add_Username'];
		
				mysql_select_db($database_dbline, $dbline);
				$Result1 = mysql_query($insertSQL, $dbline) or die(mysql_error());
		   
				require_once('../../Include/Data_BrowseInsert.php'); 
					 
			 
			}//判斷是否有新增過END
			mysql_free_result($CateP);
			$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddOK&CID=".$_POST['Course_ID']."&SID=".$_POST['Season_Area']."&UID=".$_POST['Unit_ID'];
		}
		else{	
			$insertGoTo = @$_SERVER["PHP_SELF"]."?Msg=AddError&CID=".$_POST['Course_ID']."&SID=".$_POST['Season_Area']."&UID=".$_POST['Unit_ID'];	  
		}
	}
    header(sprintf("Location: %s", $insertGoTo));
}


?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Include/spry_style.php'); ?>
<!--Autocomplete JQUERY OP-->
<link href="../../Tools/selectjs/select2.css" rel="stylesheet" />
<script src="../../Tools/selectjs/select2.min.js"></script>
<!--Autocomplete JQUERY ED-->
<!--[if lte IE 9]>
<script type='text/javascript' src='../../JS/jquery.xdomainrequest.min.js'></script>
<![endif]-->
</head>
<body>
<!-- Body Top Start -->
<?php require_once('../../Include/Admin_Body_Top.php'); ?>
<!-- Body Top End -->
<!--Body menu top Start-->
<?php //require_once('../../Include/Admin_menu_upon.php'); ?>
<!--Body menu top End-->
<!--Body Layout up Start-->
<?php //require_once('../../Include/Admin_Body_Layout_up.php'); ?>
<!--Body Layout up End-->
<div>   
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="19%"><?php require_once('../../Include/Menu_AdminLeft.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> <?php echo $row_ModuleSet['ModuleSetting_Title']; ?> - <?php echo $row_ModuleSet['ModuleSetting_SubName']; ?>管理</div>
<?php if(@$_GET['Msg'] == "AddOK"){ ?>
	<script language="javascript">
	function AddOK(){
		$('.Success_Add').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddOK,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "AddError"){ ?>
	<script language="javascript">
	function AddError(){
		$('.Error_Add').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(AddError,0);
    </script>
<?php } ?>
<?php if(@$_GET["Msg"] == "DelOK"){ ?>
	<script language="javascript">
	function Success_Del(){
		$('.Success_Del').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(Success_Del,0);
    </script>
<?php } ?>
<?php if(@$_GET["Msg"] == "DelError"){ ?>
	<script language="javascript">
	function Error_Del(){
		$('.Error_Del').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(Error_Del,0);
    </script>
<?php } ?>
<?php if(@$_GET['Msg'] == "UpdateOK"){ ?>
	<script language="javascript">
	function UpdateOK(){
		$('.UpdateOK').fadeIn(1000).delay(2000).fadeOut(1000); 			
	}
	setTimeout(UpdateOK,0);
    </script>
<?php } ?>

        
      
        <div align="center">   
          <div class="Success_Msg Success_Del" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料刪除成功</div>
          <div class="Error_Msg Error_Del" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料刪除失敗，此優惠已使用。</div>
          <div class="Success_Msg Success_Add" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料登錄成功</div>
          <div class="Error_Msg Error_Add" style="display:none;"><img src="../../Icon/delete.gif" alt="失敗訊息" class="middle"> 資料登錄失敗</div>
          <div class="Success_Msg UpdateOK" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 資料更新成功</div>
       
        <div align="center">
        <table width="95%" border="0" cellpadding="5" cellspacing="2">
          <tr>
          <td>                    
          <input name="" type="button" value="回上頁" onClick="location.href='AD_Offers_Index.php'" class="Button_Add" />
                
          </td>
          <td class="right"></td>
          </tr>
        </table>
        </div>
        
      <!--新增OP-->
      
      <?php if($row_Permission['Per_Add'] == 1){ ?>
            <?php if($totalRows_Cate>0){?>
      <form ACTION="<?php echo $editFormAction; ?>" name="Form_Add" id="Form_Add" method="POST">
        <div align="center">       
        <fieldset>
              <legend> 新增<?php echo $row_ModuleSet['ModuleSetting_SubName'];?>-助教</legend>
              <div align="center">
              <div align="left" style="max-width:900px;">	
              <span class="FormTitle02 display-inline">班季:
              <select id="Season_Area" name="Season_Area" onChange="callByAJAX();">
              <option>請選擇班季...</option>
              <?php 
			 
			  do{?>
			  <option value="<?php echo $row_Data['Season_Code'].'/'.$row_Data['Com_ID'];?>"><?php echo $row_Data['Season_Year'].'年'.$row_Data['SeasonCate_Name'].'-'.$row_Data['Com_Name'];?></option>
              <?php }while($row_Data=mysql_fetch_assoc($Data));?>
              </select>	
                       
              
              </span> 
              <span class="FormTitle02 display-inline">校區:
              <select id="Unit_ID" name="Unit_ID" onChange="callByAJAX2();">
              <option>請選擇校區...</option>
            
              </select>	
                    
              
              </span>  
              <span class="FormTitle02 display-inline">課程:
              <select id="Course_ID" name="Course_ID" onChange="callByAJAX3()">   
              <option>請選擇課程...</option>           
              </select>	  
              
                     
              
              </span>  
              
              <span class="FormTitle02 display-inline"><!--優惠額度:200-->
              <input id="Offers_Money" name="Offers_Money"  type="hidden" value="">
              </span>
             
              <span class="FormTitle02 display-inline" id="Offers_MoneyArea">
              </span>
              </div>
              <br/>   
                         
              <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
              <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
              <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
              <input name="Origin_Season" id="Origin_Season" type="hidden" value="<?php if (isset($_GET['SID'])&&$_GET['SID']<>""){echo $_GET['SID'];}?>" >
              <input name="Origin_Unit" id="Origin_Unit" type="hidden" value="<?php if (isset($_GET['UID'])&&$_GET['UID']<>""){echo $_GET['UID'];}?>" >
              <input name="Origin_Course" id="Origin_Course" type="hidden" value="<?php if (isset($_GET['CID'])&&$_GET['CID']<>""){echo $_GET['CID'];}?>" >
              
              <input name="Season_Code" id="Season_Code" type="hidden" >
              <input name="Title" id="Title" type="hidden">
              <input name="Offers_Reason" id="Offers_Reason" type="hidden" value="<?php echo $row_Cate['OffersItem_Reason'];?>">
              <input name="Offers_Reason2" id="Offers_Reason2" type="hidden" >
              <input name="Unit_Range" id="Unit_Range" type="hidden" value="<?php echo $colname02_Unit;?>">
              <div id="Member_Lists"></div>
              </div>
              <script type="text/javascript">
			  callByAJAX();
			 
			  
			  function callByAJAX(){//選擇社區	
			 		      
			        if(document.getElementById("Season_Area").selectedIndex=="0"){
						var string=document.getElementById("Origin_Season").value;					
						$("#Season_Area").children().each(function(){						
							if ($(this).val()==string){
								//jQuery給法
								$(this).attr("selected", "true"); //或是給"selected"也可									
							}
						});					
					}
					else{
						var string=document.getElementById("Season_Area").value;						
					}
					
					if(document.getElementById("Season_Area").value!=document.getElementById("Origin_Season").value){
					    document.getElementById("Origin_Unit").value="";
						document.getElementById("Origin_Course").value="";	
						
					}
					var Season_Code= new Array();
					Season_Code = string.split("/");					
					var mainItemValue=Season_Code[1];							
					document.getElementById("Season_Code").value=Season_Code[0];	
					var mainItemValue3 = document.getElementById("Unit_Range").value	
					if (window.XMLHttpRequest){
						// code for IE7+, Firefox, Chrome, Opera, Safari
						xmlhttp_subitems_unit = new XMLHttpRequest();
					} 
					else{  
						// code for IE6, IE5
						xmlhttp_subitems_unit = new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp_subitems_unit.onreadystatechange = function(){ 
						if (xmlhttp_subitems_unit.readyState==4 && xmlhttp_subitems_unit.status==200){
							$("#Unit_ID").html(xmlhttp_subitems_unit.responseText);
							$("#Member_Lists").html('');
							callByAJAX2();
						}
					}
					xmlhttp_subitems_unit.open("get", "unit_value.php?Com_ID="+ encodeURI(mainItemValue)+"&Unit_Range="+encodeURI(mainItemValue3), true);
					
					xmlhttp_subitems_unit.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
					xmlhttp_subitems_unit.send();
					
			  }
			  function callByAJAX2(){//選擇社區
			        if(document.getElementById("Season_Area").value!=""){	
					    var string=document.getElementById("Season_Area").value;
						var Season_Code= new Array();
						Season_Code = string.split("/");
						var mainItemValue=Season_Code[0];
						var mainItemValue2=Season_Code[1];
						
						if(document.getElementById("Unit_ID").value=="" && document.getElementById("Season_Area").value==document.getElementById("Origin_Season").value){
													
							var mainItemValue3=document.getElementById("Origin_Unit").value;
							$("#Unit_ID").children().each(function(){													
								if ($(this).val()==mainItemValue3){
									//jQuery給法
									$(this).attr("selected", "true"); //或是給"selected"也可									
								}
							});	
						}
						else{
							var mainItemValue3=document.getElementById("Unit_ID").value;	
						}
						if(document.getElementById("Unit_ID").value!=document.getElementById("Origin_Unit").value){					    
						document.getElementById("Origin_Course").value="";	
					    }
						
						if (window.XMLHttpRequest){
							// code for IE7+, Firefox, Chrome, Opera, Safari
							xmlhttp_subitems_season = new XMLHttpRequest();
						} 
						else{  
							// code for IE6, IE5
							xmlhttp_subitems_season = new ActiveXObject("Microsoft.XMLHTTP");
						}
						xmlhttp_subitems_season.onreadystatechange = function(){ 
							if (xmlhttp_subitems_season.readyState==4 && xmlhttp_subitems_season.status==200){
								$("#Member_Lists").html("");
								$("#Course_ID").html(xmlhttp_subitems_season.responseText);
								
								callByAJAX3();
							}
							
						}
						xmlhttp_subitems_season.open("get", "course_value.php?Season_Code="+ encodeURI(mainItemValue)+"&Com_ID="+encodeURI(mainItemValue2)+"&Unit_ID="+encodeURI(mainItemValue3), true);
						
						xmlhttp_subitems_season.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
						xmlhttp_subitems_season.send();
					}
			  }
			  function callByAJAX3(){//上課學員
			  
			            if(document.getElementById("Course_ID").value=="" && document.getElementById("Unit_ID").value==document.getElementById("Origin_Unit").value){
												
							var mainItemValue=document.getElementById("Origin_Course").value;
							$("#Course_ID").children().each(function(){						
								if ($(this).val()==mainItemValue){
									//jQuery給法
									$(this).attr("selected", "true"); //或是給"selected"也可									
								}
							});
						}
						else{
							var mainItemValue=document.getElementById("Course_ID").value;
						}
						if (window.XMLHttpRequest){
							// code for IE7+, Firefox, Chrome, Opera, Safari
							xmlhttp_subitems_member = new XMLHttpRequest();
						} 
						else{  
							// code for IE6, IE5
							xmlhttp_subitems_member = new ActiveXObject("Microsoft.XMLHTTP");
						}
						xmlhttp_subitems_member.onreadystatechange = function(){ 
						   if(document.getElementById("Course_ID").value!=""){
							   //alert(xmlhttp_subitems_member.responseText);
							   if (xmlhttp_subitems_member.readyState==4 && xmlhttp_subitems_member.status==200){	
								  $("#Member_Lists").html(xmlhttp_subitems_member.responseText);
								 
								  $( "#Member_ID" ).select2({
									  placeholder: "請選擇助教人員",
									  allowClear: true
								  });	
								  
								  
								  
								
									if((document.getElementById("Check_Item")&&document.getElementById("Check_Item").value=="OK")||(document.getElementById("Check_Item2")&&document.getElementById("Check_Item2").value=="OK")){							
									document.getElementById("submit1").innerHTML='<input type="submit" value="確定更新" class="Button_Submit" id="Check_OK"  />';	
									}
									else{
										document.getElementById("submit1").innerHTML='';
									}							
									document.getElementById("Offers_Reason2").value=document.getElementById("Course_ID").options[document.getElementById("Course_ID").selectedIndex].text;
									
							    }								
						   }
						   else{document.getElementById("submit1").innerHTML='';}
							
						}
						xmlhttp_subitems_member.open("get", "assistant_value.php?Course_ID="+ encodeURI(mainItemValue)+"&date="+new Date().getTime(), true);
						
						xmlhttp_subitems_member.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
						xmlhttp_subitems_member.send();
				   
			  }
              </script>                           
         
        </fieldset>
        
        <div id="submit1"></div> 
        
        <input type="hidden" name="MM_insert" value="Form_Add" />
          
        </div>
      </form>  
             <?php }else{ ?><br><br><br>
                      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 優惠項目無"助教"，請先新增！</div>    
                      <?php } 
			 ?> 
      <?php }else{ ?><br><br><br>
      <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
      <?php } ?>
      <!--新增END-->
      <!--修改刪除OP-->
      <?php if($row_Permission['Per_View'] == 1){ ?>
      
      
       
       
        <?php }else{ ?><br><br><br>
        <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
        <?php } ?>
      <!--修改刪除END-->
          <br>
      </div>

        </td>
      </tr>
    </table>
    <br><br><br>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<!--Body Layout down End-->
</body>
</html>
<?php
mysql_free_result($Data);
mysql_free_result($Cate);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
