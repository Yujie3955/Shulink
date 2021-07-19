<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/DB_Admin_student.php'); ?>
<?php 
$modulename=explode("_",basename(__FILE__, ".php"));
$Code=strrchr(dirname(__FILE__),"\\");
$Code=substr($Code, 1);

?>
<?php require_once('module_setting.php'); ?>
<?php

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$MID="%";
$colname_ID ="-1";
if (isset($_GET['ID'])) {
  $colname_ID = $_GET['ID'];  
 } 


	


		
$query_Data = sprintf("SELECT season_last.Season_Year, season_last.Season_Code, season_last.Season_PayEnd,  season_last.Season_PayStart,season_last.SeasonCate_Name, season_last.Com_Name, signup.Signup_OrderNumber, signup.Signup_ID, signup.Member_UserName, signup.Member_ID, signup.Signup_Money, Signup_Status, signup.Season_Count, signup.Season_Bank, signup.Season_BankCode, signup.Season_Transaction, signup.Season_Fee, signup.Season_BankName, signup.Season_BankAccount, signup.Com_ID FROM signup inner join season_last on season_last.Season_ID=signup.Season_ID where Signup_ID=%s and Signup_Status<>'未結單'  and signup.Signup_Money > 0 order by signup.Season_ID asc",GetSQLValueString($colname_ID, "int"));
$Data = mysql_query($query_Data, $dbline) or die(mysql_error());
$row_Data = mysql_fetch_assoc($Data);
$totalRows_Data = mysql_num_rows($Data);
		

	
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
<link href="../../Css/Style.css" rel="stylesheet" type="text/css">
<script src="../../Theme/<?php echo $row_WebSetting['SkinMain_Code']; ?>/JS/jquery-latest.js"></script>
<!--[if IE]>
    <script src = "../../Js/fixIePlaceholder.js"></script>
<![endif]-->

<!--Autocomplete JQUERY OP-->

<script src="../../barcode/prototype.js" type="text/javascript"></script>
<script src="../../barcode/prototype-barcode.js" type="text/javascript"></script>
<!--Autocomplete JQUERY ED-->
<style type="text/css">
#JustPrint {display:none}
body{ font-size:10px; background-color:#FFFFFF;}
#smallsize{ font-size:10px;}
@media print {


#NoPrint,#NoPrint1 {display:none}
#JustPrint { display:block; font:8pt verdana; letter-spacing:2px;}
#smallsize{ font-size:10px;}
}
#Store_College #tr1,#Store_College #tr2,#Store_College #tr3{display:none;}
.hr-more {
    height: 20px;  
    width: 300px;    
    position: relative; 
    left: 0%;  
    top: -30px; 
	text-align: center;
	border-radius: 4px; 
    background-color: #ffffff; 
    
}
#tr2 td,#tr3 td{height:20px;}
table{ border-collapse:collapse;}
.Table_R td{ border:1px solid #000000;}
#config{
          overflow: auto;
          margin-bottom: 10px;
      }
      .config{
          float: left;
          width: 200px;
          height: 250px;
          border: 1px solid #000;
          margin-left: 10px;
      }
      .config .title{
          font-weight: bold;
          text-align: center;
      }
      #submit{
          clear: both;
      }
      .barcodeTarget{
        margin-top: 20px;
        margin-left:10px;
      } 
</style>

</head>
<body>

<div> 

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
       
        <td>
        
        
<?php 
$total_unit=0; 
?>

	  <div align="center" >
     <input name="print_button" id="NoPrint1" type="button" value="友善列印" onClick="generateBarcode('');window.print();" />
      <div id="Print1">
<?php if($totalRows_Data>0){//total
		  $Total_Money=0;
		  $Total_CMoney=0;
  
?>
      <!--中間OP-->
    
    <?php //線上報名OP
		  $total_unit=1;
		 //校本部?>
      
      
      
      <div align="center" style="max-width:800px;">
      <?php if(strtotime($row_Data['Season_PayEnd'])>=strtotime(date("Y-m-d"))){?>
      <!--校本部留OP-->
        <div id="Store_College" align="center">
            <div align="center" style=" font-weight:bold; font-size:15px;line-height:30px;">新竹縣<?php echo $row_Data['Com_Name'].$row_Data['Season_Year']."年";?>&nbsp;<?php echo $row_Data['SeasonCate_Name'].'線上報名繳費單';?></div>
       
            <table width="98%" cellpadding="5" cellspacing="0" border="0" >
            <tr>
            <td colspan="2" class="left middle" style="border:0;">列印日期：<?php echo date("Y-m-d");?></td>
            <td colspan="3" class="right middle MM" style="border:0;" id="MM" >第一聯：學員收執聯
    </td>
            </tr>
            </table>
            
            <table width="98%" cellpadding="5" cellspacing="0" border="1" class="Table_R">
            <tr >
              <td width="15%" class="center middle"><?php if($row_Data['Com_ID']==3){echo '轉入帳號';}else{echo '虛擬帳號';}?></td>
              <td width="35%" class="center middle" ><?php echo $row_Data['Signup_OrderNumber'];?></td>
              <td width="10%" class="center middle"rowspan="5">代收<br/>收訖章</td>
              <td width="30%" class="right middle" rowspan="5"><span id="SS" class="SS">此<br/>聯<br/>請<br/>學<br/>員<br/>保<br/>存</span></td>         
            </tr>
            <tr >
              <td  class="center middle">繳款人</td>
              <td class="center middle" ><?php echo $row_Data['Member_UserName'];?></td>        
                       
            </tr>
            <tr >
              <td  class="center middle">收費項目</td>
              <td class="center middle" >課程報名費</td>        
                       
            </tr>
            <tr >
              <td  class="center middle">應繳金額</td>
              <td  class="center middle" >
			  <?php 
			       if($row_Data['Season_Fee']<>"" && $row_Data['Season_Fee']>0){
					   $Season_Fee=$row_Data['Season_Fee'];
					}
				   else{$Season_Fee=0;}
			       echo $row_Data['Signup_Money']+$Season_Fee;?></td>         
                       
            </tr>
            <tr>
              <td  class="center middle">繳費日期</td>
              <td  class="center middle" ><?php echo $row_Data['Season_PayStart'];?> 至 <?php echo $row_Data['Season_PayEnd'];?></td>         
                       
            </tr>
            <tr>
            <td colspan="5">※跨行繳費，需自付依各銀行跨行手續費。</td>
            </tr>
            <tr>
            <td colspan="5" ><div id="smallsize">繳費注意事項：<br/>
    <?php if($row_Data['Com_ID']==3){?>
    <!--
    1. 本繳費單請盡量以雷射印表機列印。&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    4. 繳費後請務必保留本繳費單，作為日後對帳之依據。<br/>
    2. 若代收單位無法讀取條碼，煩請學員以其他方式另行繳費。
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    5. 臨櫃繳費需自行負擔額外手續費用，費用以各銀行為標準。<br/>
    3. 請學員於繳費期限前完成繳費程序。-->
    <table width="100%" cellpadding="3" cellspacing="0" border="0">
    <tr>
    <td class="top" style=" border:0px;" width="50%">
    1. 本繳費單請盡量以雷射印表機列印。<br/>
    2. 若代收單位無法讀取條碼，煩請學員以其他方式另行繳費。<br/>
    3. 請學員於繳費期限前完成繳費程序。<br/>
    
    </td>
    <td class="top" style=" border:0px;">台中銀行臨櫃作業：查詢畫面11200，交易畫面12880。<br/>
         01轉入帳號：轉入帳號<br/>
         03條碼一：總金額</td>
    </tr>
    
    </table>
    <div style="margin:-3px 0px 0px 3px;">4. 繳費後請務必保留本繳費單，作為日後對帳之依據。<br/>5. 本繳費單僅限銀行臨櫃及ATM繳費，額外手續費須自行負擔，費用以各銀行為標準。</div>
    <?php }else{?>
	1. 本繳費單請盡量以雷射印表機列印。<br/>
    2. 若代收單位無法讀取條碼，煩請學員以其他方式另行繳費。<br/>
    3. 請學員於繳費期限前完成繳費程序。<br/>
    4. 繳費後請務必保留本繳費單，作為日後對帳之依據。<br/>
    5. 臨櫃繳費需自行負擔額外手續費用10元。
	
	<?php }?></div>
   </td>
            </tr>
            <!--<tr id="tr1">
            <td class="center" colspan="2">銀行臨櫃</td>
            <td class="center" colspan="2">ATM</td>
            </tr>
            <tr id="tr2">
            <td colspan="2"><font style="font-size:16px;">銀行名稱：<?php echo $row_Data['Season_BankName'];?><br/>帳戶名稱：<?php echo $row_Data['Season_BankAccount'];?><?php if($row_Data['Com_ID']<>3){?><br/>代收類別：<?php echo $row_Data['Season_BankCode'];?><br/>交易代號：<?php echo $row_Data['Season_Transaction'];?><?php }?></font>
            <div align="center">
			<?php if($row_Data['Com_ID']==3){?>
                <div align="left">
                	<div style="border:0px solid #db2400; font-size:16px;">轉入帳號：</div>
                </div>
            <?php }?>
            <div id="barcodeTarget" class="barcodeTarget" <?php if($row_Data['Com_ID']==3){?> style="margin-top:-16px;"<?php }?>></div>
			<?php if($row_Data['Com_ID']==3){?>
                <div align="left">
                	<div style="border:0px solid #db2400; font-size:16px;margin-top:8px;">總金額：</div>
                </div>
            	<div id="barcodeTarget2" class="barcodeTarget" style="margin-top:-16px;"></div>
			<?php }?></div></td>
            <td colspan="2" style=" padding-left:10px;font-size:16px;">
            銀行代號：<?php echo $row_Data['Season_Bank'];?><br/><?php if($row_Data['Com_ID']==3){echo '轉入帳號';}else{echo '虛擬帳號';}?>：<?php echo $row_Data['Signup_OrderNumber'];?><br/>總金額：<?php echo $row_Data['Signup_Money']+$Season_Fee;?><input type="hidden" id="Signup_No" value="<?php echo $row_Data['Signup_OrderNumber'];?>"><input type="hidden" id="Signup_Money" value="<?php echo $row_Data['Signup_Money'];?>"></td>
            </tr>-->
            <tr id="tr3">
            <td class="center" style="font-size:16px;">認證欄</td>
            <td colspan="3" style="padding:0px;">
			<?php if($row_Data['Com_ID']==3){?>
            <div align="right">
			<table width="100" cellpadding="5" cellspacing="0" border="0" style="border:0px;">
            <tr>
            <td class="center top" style="font-size:16px;border:0px; border-left:1px solid; padding:10px 3px 3px 3px; height:45px; " width="46.6%">經辦</td>
           
            </tr>
            <tr>
            <td class="center top" style="font-size:16px;border:0px; border-left:1px solid;  border-top:1px solid; padding:10px 3px 3px 3px; height:45px;" width="46.6%">主管</td>
            
            </tr>
            </table>
            </div>
			
			<?php }?>
            </td>
            </tr>
            
            
			
            
            </table>
            
         <div style="height:5px;">&nbsp;</div>
        </div>
       
        <div align="center" style="line-height:20px;">------------------------------------------------&nbsp;&nbsp;&nbsp;代收單位請蓋章後沿此虛線撕開&nbsp;&nbsp;&nbsp;------------------------------------------------</div>
        <div id="Copy_Unit"></div>
           
      
        
      <!--校本部留ED-->
      </div>
        
      
    
      
     
      
      
     <!--中間END-->
    
    
    
     <?php }//Season_PayEnd
	       else{ echo "<br/>繳費已截止";}
	       /*total*/}else{?><?php echo '<br/><div align="center">無資料</div>';
	 }//totalRows_Data>0
	 ?>
     </div>
     </div>
     
   
        </td>
      </tr>
    </table>
    <br/>
</div> 
<script type="text/javascript">    
			  function generateBarcode(){
				
				$("barcodeTarget").update();
				
				 
				var value = document.getElementById("Signup_No").value;					
				var btype = "code39";
			   
				var renderer = "css";
				var settings = {
				  output:renderer,
				  bgColor: "#FFFFFF",
				  color: "#000000",
				  barWidth: 1,
				  barHeight: 38,
				  moduleSize: 5,
				  posX: 0,
				  posY: 0,
				  addQuietZone: false
				};
			   
				  //$("canvasTarget").hide();
				  $("barcodeTarget").update().show().barcode(value, btype, settings);
				  
			  
			  }
			   function generateBarcode2(){
				
				$("barcodeTarget2").update();
				
				 
				var value = document.getElementById("Signup_Money").value;					
				var btype = "code39";
			   
				var renderer = "css";
				var settings = {
				  output:renderer,
				  bgColor: "#FFFFFF",
				  color: "#000000",
				  barWidth: 1,
				  barHeight: 38,
				  moduleSize: 5,
				  posX: 0,
				  posY: 0,
				  addQuietZone: false
				};
			   
				  //$("canvasTarget").hide();
				  $("barcodeTarget2").update().show().barcode(value, btype, settings);
				  
			  
			  }
			  
			  function clearCanvas(){
				var canvas = $('canvasTarget');
				var ctx = canvas.getContext('2d');
				ctx.lineWidth = 1;
				ctx.lineCap = 'butt';
				ctx.fillStyle = '#FFFFFF';
				ctx.strokeStyle  = '#000000';
				ctx.clearRect (0, 0, canvas.width, canvas.height);
				ctx.strokeRect (0, 0, canvas.width, canvas.height);
			  }
			  
		   
		
			  generateBarcode();
			  generateBarcode2();
			
			</script>     
<script type="text/javascript">
function copy1(){
					
  
		 if(document.getElementById("Store_College")){
			 
			 document.getElementById("Copy_Unit").innerHTML=document.getElementById("Store_College").innerHTML;
			 document.getElementsByClassName("SS")[1].innerHTML="此<br/>聯<br/>請<br/>代<br/>收<br/>保<br/>存";
			 document.getElementsByClassName("MM")[1].innerHTML="第二聯：代收留存聯";
				  //$('#Copy_Unit #SS').innerHTML="此<br/>聯<br/>請<br/>代<br/>收<br/>保<br/>存";
				  
				 
	     }
}

copy1();

</script>

<!-- MeadCo ScriptX -->


 
</body>
</html>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php
mysql_free_result($Data);
?>