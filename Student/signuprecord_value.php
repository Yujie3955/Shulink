<?php require_once('../../Connections/dbline.php');?>
<?php require_once('../../Include/GetSQLValueString.php'); ?>

<?php
if (isset($_GET['Course_ID'])&&$_GET['Course_ID']<>'') {  
    $colname_Area = $_GET['Course_ID'];    
}
else{
	$colname_Area = '';
}
$today=date("Y-m-d");
//搜索是否有班代
mysql_select_db($database_dbline, $dbline);
$query_Cate = sprintf("SELECT *,Member_UserName,Member_Identity FROM offers inner join member on member.Member_ID=offers.Member_ID where CourseID_Leader =%s",GetSQLValueString($colname_Area,"int"));
$Cate = mysql_query($query_Cate, $dbline) or die(mysql_error());
$row_Cate = mysql_fetch_assoc($Cate);
$totalRows_Cate = mysql_num_rows($Cate);

//搜索此課程所有上課學員(無退選)
$query_Data = sprintf("SELECT * FROM signup_record where Course_ID =%s and SignupRecord_Returns=0 order by Member_ID",GetSQLValueString($colname_Area,"int"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);

if($totalRows_Data>0){
	
	echo '<input type="hidden" name="ID" id="ID" value="'.$row_Cate["Offers_ID"].'">';
	
	
	?>
    
	<fieldset class="fieldset_Area">
    <legend class="legend_Area">學員名單</legend>
    <?php if($totalRows_Cate>0){?>
    <div align="left" style="padding-left:8px;">
    <font class="FormTitle02">目前班代：</font><font><?php echo $row_Cate['Member_UserName']."(".$row_Cate['Member_Identity'].")";?>&nbsp;&nbsp;
    <?php if($row_Cate['Course_ID']==""&&$row_Cate['SignupItem_ID']==""&&$row_Cate['SignupRecord_ID']==""){?>
    <input name="del" type="submit" value="刪除" class="Button_Del" onClick="return(confirm('您即將刪除班代\n<?php echo $row_Cate['Member_UserName']."(".$row_Cate['Member_Identity'].")"; ?>\n確定要刪除嗎?'))"/><input name="Check_Item" value="OK" id="Check_Item" type="hidden">  <?php }?> </font>
    <hr>
    </div>
    
    
  
    <?php }else{?><input name="Check_Item" value="OK" id="Check_Item2" type="hidden"><?php }?>
    <?php do {?>
    <div class="display-inline">
    <table cellpadding="5" cellspacing="0" border="0" width="250">       
    <tr>
    <td><input name="Member_ID" type="radio" id="Member_ID<?php echo $row_Data['Member_ID'];?>" value="<?php echo $row_Data['Member_ID'];?>" <?php if($totalRows_Cate>0&&$row_Cate['Member_ID']==$row_Data['Member_ID']){echo 'checked';}?> />&nbsp;<?php echo $row_Data['Member_UserName'];?>(<?php echo $row_Data['Member_Identity'];?>)   
    </td>
    </tr>    
    </table>
    </div>
     <?php	}while($row_Data = mysql_fetch_assoc($Data))?>
   
		
	    	
	
    </fieldset>
	
	<?php
}
else{
    echo '此課程無學員名單';	
}
mysql_free_result($Cate);
mysql_free_result($Data);




?>




