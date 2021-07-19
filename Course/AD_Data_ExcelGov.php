<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
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
<?php require_once('../../Include/Permission.php'); ?>
<?php
//搜班季
$colname_DataID='-1';
if(isset($_GET['ID']))
{
	$colname_DataID=$_GET['ID'];
	
}
$colname_Data2='-1';
$Season_Code='%';
if(isset($_GET['Year']) && $_GET['Year']<>"" && isset($_GET['Code']) && $_GET['Code']<>""){$Season_Code=$_GET['Year'].$_GET['Code'];}
if(isset($_GET['Season_Code']))
{
	$colname_Data2=$_GET['Season_Code'];
	
}
$colname_Data3='一般性';
if(isset($_GET['Cate']))
{
	$colname_Data3=$_GET['Cate'];
	
}
if ((isset($_GET['Com_ID'])) && ($_GET['Com_ID'] != "")) {
	      if($colname03_Unit=="%"||$_GET['Com_ID']==$row_AdminMember['Com_ID']){
               $colname03_Unit = "".$_GET['Com_ID']."";
		  }else{
			   $colname03_Unit ="-1";
		  }
    }
	
elseif($row_AdminMember['Unit_Range']<3){
	 $colname03_Unit = $row_AdminMember['Com_ID'];
	 $_GET['Com_ID']=$row_AdminMember['Com_ID'];
}
elseif($row_AdminMember['Unit_Range']>=3){
	$colname03_Unit ="%";
}
else{
	 $colname03_Unit ="-1";
	}
if ((isset($_GET['Unit_ID'])) && ($_GET['Unit_ID'] != "")) {
	      if($colname02_Unit=="%"||$_GET['Unit_ID']==$row_AdminMember['Unit_ID']){
               $colname02_Unit = "".$_GET['Unit_ID']."";
		  }else{
			   $colname02_Unit ="-1";
		  }
    }
elseif($row_AdminMember['Unit_Range']<2){
	 $colname02_Unit = "".$row_AdminMember['Unit_ID']."";
	 $_GET['Unit_ID']=$row_AdminMember['Unit_ID'];
}

//社區
if($row_AdminMember['Unit_Range']>=3){
$query_Community = "SELECT * FROM community where Com_Enable=1 and Com_ID <> 4 and Com_IsPrivate <> 1 ORDER BY Com_ID ASC";
}
else{
$query_Community = "SELECT * FROM community where Com_Enable=1 and Com_ID =".$row_AdminMember['Com_ID']." and Com_ID <> 4 and Com_IsPrivate <> 1 ORDER BY Com_ID ASC";	
	}
$Community = mysql_query($query_Community, $dbline) or die(mysql_error());
$row_Community = mysql_fetch_assoc($Community);
$totalRows_Community = mysql_num_rows($Community);
	

if(isset($_GET['Course_State']) && $_GET['Course_State']=='新課程'){
	//當為新課程
	$Str=" and ifnull(Course_Special,'') like '%新課程%'";
	//$query_Data= sprintf("SELECT *  FROM course where Course_Cate like %s and Season_Code Like %s and Com_ID like %s and Unit_ID Like %s and Course_Enable=1 and Course_Pass=1 and ifnull(Course_Special,'') like '%新課程%'",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($Season_Code, "text"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
}elseif(isset($_GET['Course_State']) && $_GET['Course_State']=='舊課程'){
	//當為舊課程
	$Str=" and ifnull(CourseRepeat_Name,'') like '%續開%'";
	//$query_Data= sprintf("SELECT *  FROM course where Course_Cate like %s and Season_Code Like %s and Com_ID like %s and Unit_ID Like %s and Course_Enable=1 and Course_Pass=1 and ifnull(Course_Repeat,'') like '%續開%'",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($Season_Code, "text"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
}else{
	$Str="";
	//未篩選
	//$query_Data= sprintf("SELECT *  FROM course where Course_Cate like %s and Season_Code Like %s and Com_ID like %s and Unit_ID Like %s and Course_Enable=1 and Course_Pass=1 ",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($Season_Code, "text"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
}
$query_Data= sprintf("SELECT *  FROM course where Course_Cate like %s and Season_Code Like %s and Com_ID like %s and Unit_ID Like %s and Course_Enable=1 and Course_Pass=1",GetSQLValueString($colname_Data3, "text"),GetSQLValueString($Season_Code, "text"),GetSQLValueString($colname03_Unit, "text"),GetSQLValueString($colname02_Unit, "text"));
$query_Data.=$Str;
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);


$query_SeasonData = sprintf("SELECT Season_Code FROM season  Group by Season_Code ORDER BY Season_Code desc");	
$SeasonData = mysql_query($query_SeasonData, $dbline) or die(mysql_error());
$row_SeasonData = mysql_fetch_assoc($SeasonData);
$totalRows_SeasonData = mysql_num_rows($SeasonData);

$query_Cate2= sprintf("SELECT Season_Code, SeasonCate_Name, Season_Year, Contract_SNum, Season_Start, Season_End, Contract_SDate, Com_Name FROM season inner join community on community.Com_ID=season.Com_ID where Season_Code=%s and season.Com_ID =%s",GetSQLValueString($row_Data['Season_Code'], "int"),GetSQLValueString($row_Data['Com_ID'], "int"));
	
	$Cate2= mysql_query($query_Cate2, $dbline) or die(mysql_error());
	$row_Cate2 = mysql_fetch_assoc($Cate2);
	$totalRows_Cate2 = mysql_num_rows($Cate2);
	
	
	$numstr=array("Ｏ","一","二","三","四","五","六","七","八","九");
	$monthstr=array("","一;","二","三","四;","五","六","七","八","九","十","十一","十二");
	$daystr=array("","十","二十","三十");
	$years=date("Y",strtotime($row_Cate2['Season_Start']))-1911;
	$months=date("m",strtotime($row_Cate2['Season_Start']));
	$days=date("d",strtotime($row_Cate2['Season_Start']));
			if(substr($months,0,1)==0){$months=substr($months,1,1);}
			if(substr($days,0,1)==0){
				$days_2="&nbsp;".substr($days,1,1);
				$days=$monthstr[substr($days,1,1)];
			}
			else{
				$days_2=$days;
				$days=$daystr[substr($days,0,1)].$monthstr[substr($days,1,1)];
			}
			
			$years2=date("Y",strtotime($row_Cate2['Season_End']))-1911;
			$months2=date("m",strtotime($row_Cate2['Season_End']));
			$days2=date("d",strtotime($row_Cate2['Season_End']));
			
			if(substr($months2,0,1)==0){$months2=substr($months2,1,1);}
			if(substr($days2,0,1)==0){
				$days2_2="&nbsp;".substr($days2,1,1);
				$days2=$monthstr[substr($days2,1,1)]; 
			}
			else{
				$days2_2=$days2;
				$days2=$daystr[substr($days2,0,1)].$monthstr[substr($days2,1,1)]; 
			}
			

?>


<?php //require_once('../../Include/Html_Top_Common.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $row_WebSetting['WebSetting_Title']; ?><?php if(@$SystemName <> ""){echo ":::".$SystemName.":::";} ?></title>
<meta name="keywords" content="<?php echo $row_WebSetting['WebSetting_Keyword']; ?>">
<meta name="description" content="<?php echo $row_WebSetting['WebSetting_Description']; ?>">
<!--<link rel="icon" href="../../Theme/<?php echo $row_WebSetting['SkinMain_Code']; ?>/image/<?php echo $row_WebSetting['SkinMain_favicon_ico']; ?>" type="image/x-icon" />
<link rel="shortcut icon" href="../../Theme/<?php echo $row_WebSetting['SkinMain_favicon_ico']; ?>" type="image/x-icon" />-->

<?php //require_once('../../Include/Html_Top_head.php'); ?>
<script src="../../Theme/<?php echo $row_WebSetting['SkinMain_Code']; ?>/JS/jquery-latest.js"></script>
<style>
#JustPrint {display:none}
#NoPrint{display:inline-block;}
.PrintTitle{font-family:'標楷體';font-size:1.5em;}
@page
 {mso-page-border-surround-header:no;
 mso-page-border-surround-footer:no;}
@page Section2{
	
	size:841.9pt 595.3pt;
	mso-header-margin:0pt;
	mso-footer-margin:0pt;
	margin:1.5cm 1cm 1.5pt 1cm;
	mso-page-orientation:landscape;
	mso-paper-source:0;
	layout-grid:15.6pt;
}

div.Section2{page:Section2;}

p{padding:0px; margin:0px;}
.center{text-align:center;}
.middle{vertical-align:middle;}
.right{text-align:right;}
.top{vertical-align:top;}
.middle{vertical-align:middle;}
.bottom{vertical-align:bottom;}
html,body,div{margin:0px; padding:0px;}


@media print {
#JustPrint { display:block; font:9pt verdana; letter-spacing:2px;}
#NoPrint {display:none}


}
table{border-collapse:collapse;}
table td{ border:1px solid #333;}
</style>
<?php   
	$filename='';
	if(isset($_GET['Cate']) && $_GET['Cate']<>""){$CateTitle=$_GET['Cate'];}else{$CateTitle='一般性';}
	if($totalRows_Data>0){	//新竹縣109年度社區大學春季班一般性課程審查意見表
	$filename.="新竹縣".$row_Cate2['Season_Year']."年度".$row_Data['Com_Name'].$row_Cate2['SeasonCate_Name'].$CateTitle."課程審查意見表.doc";
	}	
	
	$Title=str_replace(".doc","",$filename);
	$agent = $_SERVER['HTTP_USER_AGENT']; 
	if(strpos($agent,"Firefox")) { 
		$filename=$filename;//轉碼 解決火狐亂碼問題
	}else{
		$filename=urlencode($filename);//其他瀏覽器轉碼 解決IE亂碼問題
	}
	if(isset($_GET['download']) && $_GET['download']=='word'){
	
	//header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
	header('Content-type:application/vnd.ms-word');  //宣告網頁格式	
	header('Content-Disposition: attachment; filename='.$filename);  //設定檔案名稱	
	}
	
?>
</head>
<body>

<div align="center" class="Section2">

<?php if($row_Permission['Per_View'] == 1){ ?>
		<?php if((isset($_GET['download']) && $_GET['download']<>'word') || !isset($_GET['download'])){?>
		<form name="search_form" id='NoPrint' method='GET'>
		<select name="Season_Code" id="Season_Code" >
        
        <?php if($totalRows_SeasonData>0){
				  do { ?>
			<option value="<?php echo $row_SeasonData['Season_Code']; ?>" <?php if (isset($_GET['Season_Code'])&&$_GET['Season_Code'] == $row_SeasonData['Season_Code']) { echo "selected='selected'"; } ?>><?php if(substr($row_SeasonData['Season_Code'],-1,1)=="1"){echo substr_replace($row_SeasonData['Season_Code'],'春季班',-1);}if(substr($row_SeasonData['Season_Code'],-1,1)=="2"){echo substr_replace($row_SeasonData['Season_Code'],'夏季班',-1);}if(substr($row_SeasonData['Season_Code'],-1,1)=="3"){echo substr_replace($row_SeasonData['Season_Code'],'秋季班',-1);}if(substr($row_SeasonData['Season_Code'],-1,1)=="4"){echo substr_replace($row_SeasonData['Season_Code'],'冬季班',-1);}  ?></option>
			<?php } while ($row_SeasonData = mysql_fetch_assoc($SeasonData));
		      }mysql_free_result($SeasonData); ?>
      </select>
		
	     
	     <select id="Com_ID" name="Com_ID" onChange="callbyAJAX2()">
	     <option value="">:::全部所屬社區大學:::</option>
	     <?php do{?>
	     <option value="<?php echo $row_Community['Com_ID'];?>" <?php if(isset($_GET['Com_ID'])&&$_GET['Com_ID']==$row_Community['Com_ID']){echo 'selected';}elseif($row_AdminMember['Com_ID']==$row_Community['Com_ID']){echo 'selected';}?>><?php echo $row_Community['Com_Name'];?></option>
	     
	     <?php }while($row_Community = mysql_fetch_assoc($Community));
		       mysql_free_result($Community); ?>
	     </select>
		課程性質：<select name="Cate" 	required>
		<option value='一般性' <?php if($_GET['Cate']=='一般性'){ ?> selected <?php } ?>>一般性</option>
		<option value='計畫性' <?php if($_GET['Cate']=='計畫性'){ ?> selected <?php } ?>>計畫性</option>
		</select>
		課程新舊:
		<select name='Course_State'> 
			<option>全部</option>
			<option value="新課程" <?php if($_GET['Course_State']=='新課程'){ ?> SELECTED <?php } ?>>新課程</option>
			<option value="舊課程" <?php if($_GET['Course_State']=='舊課程'){ ?> SELECTED <?php } ?>>舊課程</option>
		 </select>
		<input type="submit" value="查詢" class="Button_General">
		<input type="button" value="匯出Word" name="Search" class="Button_General"  onclick="window.open('<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."&download=word";?>');" >
		</form>
	<?php }?>
   	    <?php if($totalRows_Data>0){
			      $a=0; ?>
                  <div class="PrintTitle"><?php echo $Title;?></div>
                  <table cellpadding="5" cellspacing="0" border="0" width="98%">
                  
                  <tr>
                  <td style="text-align:center;" nowrap>編號</td>
                 
		<td style="text-align:center; " nowrap>課程名稱</td>
		
		<td style="text-align:center; " nowrap>分類</td>
		<td style="text-align:center; " nowrap>授課教師</td>
		<td style="text-align:center; " nowrap>上課地點</td>
		<td style="text-align:center; " nowrap><?php if(isset($_GET['Cate']) && $_GET['Cate']=='一般'){echo '校內';}?>審查意見</td>
		<td style="text-align:center; width:300px;" nowrap><?php if(isset($_GET['Cate']) && $_GET['Cate']=='一般'){echo '其他';}else{echo '改進';}?>建議</td
                  </tr>
			<?php do{$a++;?>
                  <tr>
                  <td style="text-align:center;"><?php echo $a;?></td>
		<td style="text-align:center; "><?php echo $row_Data['Course_Name'];?></td>
		<td style="text-align:center; "><?php echo $row_Data['CourseKind_Name'];?></td>
		<td style="text-align:center; "><?php echo $row_Data['Teacher_UserName'];?></td>
		<td style="text-align:center; "><?php echo $row_Data['Loc_Name'];?></td>
		<td style="text-align:left; "><font style="font-size:30px; font-family:Verdana, Geneva, sans-serif;">□</font>	通過 <br/>
						<font style="font-size:30px; font-family:Verdana, Geneva, sans-serif;">□</font>	不通過<br>
						<font style="font-size:30px; font-family:Verdana, Geneva, sans-serif;">□</font>	修正後通過<br/>
		</td>
		  <td style="text-align:center; "></td>
                  </tr>                   
            <?php }while($row_Data=mysql_fetch_assoc($Data));?>
                  </table>
 <?php        }//($totalRows_Data>0){
              else{
				  echo '<br/><br/><div align="center">無資料！</div>';
			  }
			  
			  ?>




<?php }else{ ?><br><br><br>
        <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能瀏覽權限</div>    
<?php } ?>
 
</div>      

</body>
</html>
<script type="text/javascript">
	  
	 
	   callbyAJAX2();
	   function callbyAJAX2(){
		   
		   
		    var mainItemValue = document.getElementById("Com_ID").options[document.getElementById("Com_ID").selectedIndex].value;	
			
			if(document.getElementById("Cate").options[document.getElementById("Cate").selectedIndex].value=='old' || document.getElementById("Cate").options[document.getElementById("Cate").selectedIndex].value=='identity'){//年齡與特殊身分要顯示區分課程
				document.getElementById("Course_Area").style.display='inline-block';	
				document.getElementById("Course_Area2").style.display='inline-block';	
			}
			else{
				document.getElementById("Course_Area").style.display='none';	
				document.getElementById("Course_Area2").style.display='none';	
			}
			var mainItemValue2 =0;
			if(document.getElementById("Cate").options[document.getElementById("Cate").selectedIndex].value!="result"){
				
				if(document.getElementById("Range")){
					document.getElementById("Range").style.display='inline-block';
					mainItemValue2 = document.getElementById("Range").value;	
				}
							
				
			}
			else{				
				
				if(document.getElementById("Range")){
					document.getElementById("Range").style.display='none';
				}
				
			}
			
			if(mainItemValue!="" && mainItemValue2!=1){  
			
				var mainItemValue2 = document.getElementById("Unit_Range").value;
				<?php if(isset($_GET['Unit_ID'])&&($_GET['Unit_ID'])<>""){?>
				var mainItemValue3=<?php echo $_GET['Unit_ID'];?>;
				
				<?php }?>
		
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
					if(xmlhttp_subitems.readyState==4 && xmlhttp_subitems.status==200){
						document.getElementById("Unit_ID").innerHTML = xmlhttp_subitems.responseText;           
											
					}
								
				}	
				<?php if(isset($_GET['Unit_ID'])&&($_GET['Unit_ID'])<>""){?>
				xmlhttp_subitems.open("get", "../Sign/unit_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Unit_Range="+mainItemValue2+"&Unit_ID="+mainItemValue3, true);
				<?php }
				else{?>		
				xmlhttp_subitems.open("get", "../Sign/unit_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Unit_Range="+mainItemValue2, true);
				<?php }?>
				
				xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
				xmlhttp_subitems.send();
			}
			else{
				document.getElementById("Unit_ID").innerHTML ='<option value="">:::全部所屬分校:::</option>';
				
			}
			
	
      }
	  
	  
	  
	  
	  function CateChange(){
		 
		  if(document.getElementById("Code").options[document.getElementById("Code").selectedIndex].value!=""){
			 document.getElementById("CateName").value=document.getElementById("Code").options[document.getElementById("Code").selectedIndex].text;
		  }
		  else{
			  document.getElementById("CateName").value='';	 
		  }
		  
		  
	  }
	 </script> 
<?php
mysql_free_result($Cate2);
mysql_free_result($Data);
?>

<?php require_once('../../JS/open_windows.php'); ?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
