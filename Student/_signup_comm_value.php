<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php require_once('../../Include/function_array_keys.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>學員報名條件</title>
</head>

<?php
if (isset($_GET['Member_ID'])) {
  $colname_Member = $_GET['Member_ID'];
}

if (isset($_GET['Season_Code'])) {
  $colname_SeasonCode = $_GET['Season_Code'];
}

if (isset($_GET['Com_ID'])) {
  $colname_Com= $_GET['Com_ID'];
}
if (isset($_GET['Unit_ID'])) {
	if(isset($_GET['Unit_ID']) && $_GET['Unit_ID']=="all"){
		$colname_Unit= "%";
	}
	else{
		$colname_Unit= $_GET['Unit_ID'];
	}
}
else{
  $colname_Unit= "%";
}

   
$query_Sign = sprintf("SELECT signup_item.Course_ID, course.Course_Commid FROM signup_item INNER JOIN signup ON signup.Signup_ID = signup_item.Signup_ID INNER JOIN course on course.Course_ID = signup_item.Course_ID where signup.Member_ID=%s and signup.Season_Code=%s ",GetSQLValueString($colname_Member,"int"),GetSQLValueString($colname_SeasonCode,"int"));
$Sign = mysql_query($query_Sign, $dbline) or die(mysql_error());
$row_Sign = mysql_fetch_assoc($Sign);
$totalRows_Sign = mysql_num_rows($Sign);

$Course_List='';
$Course_Array=array();
/*判斷是否加選過OP*/
if($totalRows_Sign>0){
	do{
		$Course_List=explode(",",$row_Sign['Course_Commid']);
		for($i=0;$i<count($Course_List);$i++){
			if($Course_List[$i]<>""){
				array_push($Course_Array,$Course_List[$i]);
			}
		}
	}while($row_Sign= mysql_fetch_assoc($Sign));
	$Course_Array=array_unique($Course_Array);
}
mysql_free_result($Sign);	
	?>
<table width="98%" cellpadding="5" cellspacing="0" border="0" class="stripe">
                 <tr class="TableBlock_shadow_Head_Back">

                  <!--<td width="5%" class="middle center">編號</td>-->
                 <td width="8%" class="middle center">校區</td>
                 <td width="10%" class="middle">名稱</td>
                 <td width="10%" class="center middle">類型</td>
		 <td width="10%" class="center middle">繳費</td>
                 <td width="20%" class="center middle">上課時間</td>
                 <td width="20%" class="middle">地點</td>
                 <td width="15%" class="middle">講師</td>              
                 <td width="15%" class="middle">操作</td>                  
                 </tr>
<?php $a=0;
      foreach($Course_Array as $course_key=>$course_value){
	$query_Data = sprintf("SELECT * FROM signup_onlinelist WHERE Com_ID = %s and ifnull(Unit_ID,'') like %s and  Course_ID = %s  ORDER BY Com_ID asc,Unit_ID asc,Add_Time asc,Course_ID asc",GetSQLValueString($colname_Com,"int"),GetSQLValueString($colname_Unit,"text"),GetSQLValueString($course_value,"int"));
	$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
	$row_Data = mysql_fetch_assoc($Data);
	$totalRows_Data = mysql_num_rows($Data);
	
	//計算是否滿人
	$query_CountOnline = sprintf("SELECT Course_ID,OnlineNum FROM signup_countchoose where Com_ID=%s and Unit_ID Like %s",GetSQLValueString($colname_Com,"int"),GetSQLValueString($colname_Unit,"text"));
	$CountOnline = mysql_query($query_CountOnline, $dbline) or die(mysql_error());
	$row_CountOnline = mysql_fetch_assoc($CountOnline);
	$totalRows_CountOnline = mysql_num_rows($CountOnline);
	$CountCourseId=array();
	$CountCourseNum=array();	
	if($totalRows_CountOnline>0){
		
		do{
			array_push($CountCourseId,$row_CountOnline['Course_ID']);
			array_push($CountCourseNum,$row_CountOnline['OnlineNum']);
		}while($row_CountOnline = mysql_fetch_assoc($CountOnline));
		$CountData=array_fill_keys2($CountCourseId,$CountCourseNum);
	}
	mysql_free_result($CountOnline);

	//判斷加選有哪些課程
	$query_Sign = sprintf("SELECT Course_ID FROM signup_course where Member_ID=%s and Season_Code=%s",GetSQLValueString($colname_Member,"int"),GetSQLValueString($colname_SeasonCode,"int"));
	$Sign = mysql_query($query_Sign, $dbline) or die(mysql_error());
	$row_Sign = mysql_fetch_assoc($Sign);
	$totalRows_Sign = mysql_num_rows($Sign);
	$CourseRepeat=",".$row_Sign['Course_ID'].",";
	mysql_free_result($Sign);

	//搜尋現場報名課程
	$query_Sign2 = sprintf("SELECT group_concat(Course_ID) as Course_ID FROM signup_record where SignupRecord_Returns=0 and Member_ID=%s and Season_Code = %s and SignupRecord_Identity <> '線上報名' group by Season_Code, Member_ID",GetSQLValueString($colname_Member,"int"),GetSQLValueString($colname_SeasonCode,"int"));
	$Sign2 = mysql_query($query_Sign2, $dbline) or die(mysql_error());
	$row_Sign2 = mysql_fetch_assoc($Sign2);
	$totalRows_Sign2 = mysql_num_rows($Sign2);
	$CourseRepeat2=",".$row_Sign2['Course_ID'].",";
	mysql_free_result($Sign2);
?>

        <?php 	 
		        do { $a++;
			if($a%2==1){$str_bg='bgcolor="#EEEEEE"';}else{$str_bg='';}?>
                 <tr>
                  <!--<td class="middle center" <?php echo $str_bg;?>><?php echo substr(str_replace($row_Data['Season_Code'],"",$row_Data['Course_NO']),0);?></td>-->
                  <td class="middle center" <?php echo $str_bg;?>><?php echo mb_substr($row_Data['Unit_Name'],0,2,'utf-8');?></td>
                  <td class="middle" <?php echo $str_bg;?>><a href="javascript:newin(900,700,'AD_Course_Detail.php?ID=<?php echo $row_Data['Course_ID'];?>')" ><?php echo mb_substr($row_Data['Course_Name'],0,10,'utf-8');if(mb_strlen($row_Data['Course_Name'],'utf-8')>10){echo '...';}?></a></td>
                  <td class="middle center" <?php echo $str_bg;?>><?php if($row_Data['Course_Free']==1){echo '推廣';}elseif($row_Data['Course_Free']==2){echo '非正規教育認證課程';}else{echo '一般';}?></td>
		  <td class="middle center" <?php echo $str_bg;?>><?php echo "<font color='#d5ad0d' style='font-weight:bold;'>"."$"."</font><font color='#db2400' style='font-weight:bold;'>".$row_Data['Course_Money']."</font>";?></td>
                  <td class="center middle" <?php echo $str_bg;?>><?php $weekname=explode(",","一,二,三,四,五,六,日");
               if($row_Data['Course_Day']<>""){echo "星期".$weekname[$row_Data['Course_Day']-1];}if($row_Data['Course_Time']<>""){echo $row_Data['Course_Time'];}echo " ".date("H:i",strtotime($row_Data['Course_Start']))."~".date("H:i",strtotime($row_Data['Course_End']));?></td>
               
                  <td class="middle" <?php echo $str_bg;?>><?php echo mb_substr($row_Data['Loc_Name'],0,10,"utf-8");if(mb_strlen($row_Data['Loc_Name'],'utf-8')>10){echo '...';}?></td>
                  <td class="middle" <?php echo $str_bg;?>><?php echo $row_Data['Teacher_UserName'];?></td>                 
                  <td class="middle" <?php echo $str_bg;?>><input value="<?php echo $row_Data['Course_ID'];?>" type="hidden"  class="Course_ID<?php echo $a;?>" id="Course_ID<?php echo $a;?>"><input value="<?php if($row_Data['CO_Sale']<>0){echo (int)($row_Data['Rule_Credit']) * (int)($row_Data['Season_Credit']) * (int)($row_Data['CO_Sale']);}else{echo '0';}?>" type="hidden"  class="SignupItem_Money<?php echo $a;?>" id="SignupItem_Money<?php echo $a;?>"><input value="<?php echo $row_AdminMember['Member_Identity'];?>" type="hidden"  class="Add_Account" id="Add_Account"><input value="<?php echo $row_AdminMember['Member_UserName'];?>" type="hidden"  class="Add_Username" id="Add_Username"><input value="<?php echo $row_Data['Season_ID'];?>" type="hidden"  class="Season_ID<?php echo $a;?>" id="Season_ID<?php echo $a;?>" ><input value="<?php echo $row_Data['Season_Code'];?>" type="hidden"  class="Season_Code<?php echo $a;?>" id="Season_Code<?php echo $a;?>" ><input value="<?php echo $row_Data['Unit_ID'];?>" type="hidden"  class="Unit_ID<?php echo $a;?>" id="Unit_ID<?php echo $a;?>" ><input value="<?php echo $row_Data['Com_ID'];?>" type="hidden"  class="Com_ID<?php echo $a;?>" id="Com_ID<?php echo $a;?>" >
             
                  <?php 
			if (preg_match("/".",".$row_Data['Course_ID'].","."/i", $CourseRepeat)) {echo '<font color="#009933" style="font-weight:bold;">已加選</font>';}elseif(preg_match("/".",".$row_Data['Course_ID'].","."/i", $CourseRepeat2)){echo '<font color="#009933" style="font-weight:bold;">現場/團報已報名</font>';}
                        else{?>
        <?php 					if(isset($CountData[$row_Data['Course_ID']])&&$CountData[$row_Data['Course_ID']]<>"") { 
								if($row_Data['Season_IsAll']==1){
			                    	$OnlineTotalNoNum=($row_Data['Course_OnSite']+$row_Data['Course_OnSiteAdd'])-$CountData[$row_Data['Course_ID']];
								}
								else{
									$OnlineTotalNoNum=($row_Data['Course_Online']+$row_Data['Course_OnlineAdd'])-$CountData[$row_Data['Course_ID']];
								}
                                    if($OnlineTotalNoNum<=0){echo '<font color="#db2400" style="font-weight:bold;">已滿額</font>';}
                                    else{?><input value="加選" type="button"  class="Button_Submit" onClick="callbyAJAX<?php //echo $a;?>('<?php echo $row_Data['Course_ID'];?>','<?php echo $row_Data['Course_Money']?>','<?php echo $row_AdminMember['Member_Identity']?>','<?php echo $row_AdminMember['Member_UserName']?>','<?php echo $row_Data['Season_ID']?>','<?php echo $row_Data['Com_ID'];?>','<?php echo $row_Data['Unit_ID'];?>','<?php echo $row_Data['Season_Code'];?>')" id="button_<?php echo $row_Data['Course_ID']?>"><font color="#009933" style="font-weight:bold;display:none;" id="Already_Add<?php echo $row_Data['Course_ID'];?>">已加選</font><?php 
                                    }
                                }
                                else{
									$OnlineTotalNoNum=($row_Data['Course_Online']+$row_Data['Course_OnlineAdd']);
                                   if($OnlineTotalNoNum<=0){echo '<font color="#db2400" style="font-weight:bold;">已滿額</font>';}
                                    else{?><input value="加選" type="button"  class="Button_Submit" onClick="callbyAJAX<?php //echo $a;?>('<?php echo $row_Data['Course_ID'];?>','<?php echo $row_Data['Course_Money']?>','<?php echo $row_AdminMember['Member_Identity']?>','<?php echo $row_AdminMember['Member_UserName']?>','<?php echo $row_Data['Season_ID']?>','<?php echo $row_Data['Com_ID'];?>','<?php echo $row_Data['Unit_ID'];?>','<?php echo $row_Data['Season_Code'];?>')" id="button_<?php echo $row_Data['Course_ID']?>"><font color="#009933" style="font-weight:bold;display:none;" id="Already_Add<?php echo $row_Data['Course_ID'];?>">已加選</font><?php 
                                    }
                                }
								
                        }?>
                   
                  </td>
                 </tr>
                 <script type="text/javascript">
				   function callbyAJAX<?php echo $a;?>(objButton){
						// mainItemValue 代表 option value, 其值對應到 printing p_id
						var mainItemValue = document.getElementById("Course_ID<?php echo $a;?>").value;
						var mainItemValue2 = document.getElementById("SignupItem_Money<?php echo $a;?>").value;
						var mainItemValue3 = document.getElementById("Add_Account").value;
						var mainItemValue4 = document.getElementById("Add_Username").value;
						var mainItemValue5 = document.getElementById("Season_ID<?php echo $a;?>").value;
						var mainItemValue6 = document.getElementById("Com_ID<?php echo $a;?>").value;
						var mainItemValue7 = document.getElementById("Unit_ID<?php echo $a;?>").value;
						var mainItemValue8 = document.getElementById("Season_Code<?php echo $a;?>").value;
					    
						if (window.XMLHttpRequest) 
						{
					// code for IE7+, Firefox, Chrome, Opera, Safari
							xmlhttp_subitems = new XMLHttpRequest()
						} 
						else 
						{  
					// code for IE6, IE5
							xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");
						}
						xmlhttp_subitems.onreadystatechange = function() 
						{ 
							if (xmlhttp_subitems.readyState==4 && xmlhttp_subitems.status==200){
								document.getElementById("AddOK").innerHTML = xmlhttp_subitems.responseText;
								if(document.getElementById('Ins_OK'+objButton)&&document.getElementById('Ins_OK'+objButton).value=="true"){
									$('#button_'+objButton).hide();
									$('#Already_Add'+objButton).show();
									comm_area_ajax();
								}
								
								
							}
						}
				
						xmlhttp_subitems.open("get", "signup_value.php?Course_ID=" + encodeURI(mainItemValue)+"&SignupItem_Money="+encodeURI(mainItemValue2)+"&Add_Account="+encodeURI(mainItemValue3)+"&Add_Username="+encodeURI(mainItemValue4)+"&Season_ID="+encodeURI(mainItemValue5)+"&Season_Code="+encodeURI(mainItemValue8)+"&Com_ID="+encodeURI(mainItemValue6)+"&Unit_ID="+encodeURI(mainItemValue7), true);
						xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
						xmlhttp_subitems.send();
				
				   }
                  </script>  
          <?php  } while ($row_Data = mysql_fetch_assoc($Data)); 
		  mysql_free_result($Data);?>
<?php }//foreach() end?>
                 </table>


