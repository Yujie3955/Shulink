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
require_once('../../include/Permission.php');
if(isset($_SESSION['inalready'])&&$_SESSION['inalready']>0){
	$_SESSION['inalready'] = 0;
	echo '<script type=\'text/javascript\'>window.location=\'AD_Data_Index.php?Msg=AddOK\';</script>';
	}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$query_SeasonCodes = sprintf("SELECT * FROM season_new where Com_ID like %s ORDER BY Com_ID asc",GetSQLValueString($colname03_Unit, "text"));
$SeasonCodes = mysql_query($query_SeasonCodes, $dbline) or die(mysql_error());
$row_SeasonCodes = mysql_fetch_assoc($SeasonCodes);
$totalRows_SeasonCodes = mysql_num_rows($SeasonCodes);

?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>
<?php require_once('../../Tools/JQFileUpLoad/JQFileUpLoad.php'); ?>
<?php

//導入Excel文件
function uploadFile($file,$filetempname,$season,$season_codes) {

//自己設置的上傳文件存放路徑
$filePath = '../../UpLoad/Course/';
$str = ""; 
//123456
require('../../Connections/dbline.php'); 

require_once("../../Tools/PHPExcel_1.8.0_doc/Classes/PHPExcel/IOFactory.php");
$Add_Time=date('Y-m-d H:i:s');
$filename=explode(".",$file);//把上傳的文件名以「.」好為準做一個數組。
$time=date("y-m-d");//去當前上傳的時間
//取文件名t替換
$name=implode(".",$filename); //上傳後的文件名
$uploadfile=$filePath.$name;//上傳後的文件名地址

//move_uploaded_file() 函數將上傳的文件移動到新位置。若成功，則返回 true，否則返回 false。
$result=move_uploaded_file($filetempname,$uploadfile);//假如上傳到當前目錄下

if($result) { //如果上傳文件成功，就執行導入excel操作

$objPHPExcel = PHPExcel_IOFactory::load($uploadfile);
$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();
$highestRow = $sheet->getHighestRow(); // 取得總行數
$highestColumn = $sheet->getHighestColumn(); // 取得總列數
$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

	 //foreach($sheetData as $key => $col)
   // {
       // echo "行{$key}: ";
       // foreach ($col as $colkey => $colvalue) {
          //  echo $colkey[$key];
			//echo "{$colvalue}, ";
			
        //} echo $colvalue;
        //echo "<br/>";
    //}
    //echo "<hr />";
	/*for($j=2;$j<=$highestRow and 21>$j;$j++){
	@$str0 = $str0.$objPHPExcel->getActiveSheet()->getCell("A$j")->getValue().';'; 
	
	}

$strs1 = explode(";",$str0);
array_pop($strs1);*/

	//if(in_array("",$strs1)){echo '第'.$strs1.'有空值';}


	//foreach ($strs1 as $value) {
    //echo "Value: $value
	//\n";
	//}

//循環讀取excel文件,讀取一條,插入一條
	for($j=2;$j<=$highestRow and 22>$j;$j++){
		
	   mysql_select_db($database_dbline, $dbline);	
	   $query_CateP = sprintf("SELECT max(Course_NOCount) as Max_Course FROM course where Com_ID=%s and Unit_ID=%s and Season_Code=%s",GetSQLValueString($_POST['Com_ID'], "int"),GetSQLValueString($_POST['Unit_ID'], "int"),GetSQLValueString($season_codes, "int"));
	   $CateP = mysql_query($query_CateP, $dbline) or die(mysql_error());
	   $row_CateP = mysql_fetch_assoc($CateP);
	   $totalRows_CateP = mysql_num_rows($CateP);
       if($totalRows_CateP>0){
	   	  $id=str_pad($row_CateP['Max_Course']+1,2,"0",STR_PAD_LEFT);
	   }
	   else{$id=str_pad("0",2,"0",STR_PAD_LEFT); }	
	   
	   //算人數OP
	    
	   @$strpeople = $strpeople.$objPHPExcel->getActiveSheet()->getCell("X$j")->getValue(); //是否保留身障名額
	   @$strmax = $strmax.$objPHPExcel->getActiveSheet()->getCell("Q$j")->getValue(); //招生人數
	  
	   
		
       $query_CateP2 = sprintf("SELECT * from season_rule where Season_Code=%s and Com_ID=%s",GetSQLValueString($season_codes, "int"),GetSQLValueString($_POST['Com_ID'], "int"));
	   $CateP2 = mysql_query($query_CateP2, $dbline) or die(mysql_error());
	   $row_CateP2 = mysql_fetch_assoc($CateP2);
	   $totalRows_CateP2 = mysql_num_rows($CateP2);
	   if(mb_strlen($row_CateP2['Rule_Online'], "utf-8")<3){
		    	$online_percent=(float)"0".".".str_pad($row_CateP2['Rule_Online'],2,"0",STR_PAD_LEFT);
	   }
	   else{
				$online_percent=(float)$row_CateP2['Rule_Online']/100;
	   }
	   if(isset($strpeople)&&$strpeople==1){
	  		$Course_Reserve=round((int)($strmax) * (float)("0".".".str_pad($row_CateP2['Rule_Reserve'],2,"0",STR_PAD_LEFT)),0);
	   		$Course_Online=round(((int)($strmax) - $Course_Reserve)*($online_percent),0);
       		$Course_OnSite=((int)($strmax) - $Course_Online-$Course_Reserve);			
	   }
	   else{
		  	$Course_Reserve=0;
	   		$Course_Online=round(((int)($strmax) - $Course_Reserve)*($online_percent),0);
       		$Course_OnSite=((int)($strmax) - $Course_Online-$Course_Reserve);
		   
	   }
	   //算人數END
	   //課程NAME OP
	   @$strcoursekind = $strcoursekind.$objPHPExcel->getActiveSheet()->getCell("B$j")->getValue();   
	   $query_CourseKind = sprintf("SELECT * FROM course_kind where CourseKind_ID=%s",GetSQLValueString($strcoursekind, "int"));
	   $CourseKind = mysql_query($query_CourseKind, $dbline) or die(mysql_error());
	   $row_CourseKind = mysql_fetch_assoc($CourseKind);
	   $totalRows_CourseKind= mysql_num_rows($CourseKind);
	   if($totalRows_CourseKind>0){$CourseKind_Name=$row_CourseKind['CourseKind_Name'];}
	   else{$CourseKind_Name='';}
	   //課程NAME END
	   //地點NAME OP
	   @$strarea = $strarea.$objPHPExcel->getActiveSheet()->getCell("F$j")->getValue();   
	   $query_LocArea = sprintf("SELECT * FROM location where Loc_ID=%s and Com_ID=%s and Unit_ID =%s",GetSQLValueString($strarea, "int"),GetSQLValueString($_POST['Com_ID'],'int'),GetSQLValueString($_POST['Unit_ID'],'int'));
	   $LocArea = mysql_query($query_LocArea, $dbline) or die(mysql_error());
	   $row_LocArea = mysql_fetch_assoc($LocArea);
	   $totalRows_LocArea= mysql_num_rows($LocArea);
	   if($totalRows_LocArea>0){$Loc_Name=$row_LocArea['Loc_Name'];}
	   else{$Loc_Name='';}
	   //地點NAME END
	  
	   //必填項目檢查OP
	    
		@$strmust2 = $strmust2.$objPHPExcel->getActiveSheet()->getCell("C$j")->getValue();//課程名稱
		@$strmust3 = $strmust3.$objPHPExcel->getActiveSheet()->getCell("D$j")->getValue();//學分
		@$strmust4 = $strmust4.$objPHPExcel->getActiveSheet()->getCell("E$j")->getValue();//課程類型
		@$strmust5 = $strmust5.$objPHPExcel->getActiveSheet()->getCell("G$j")->getValue();//週數
		@$strmust_starttime = $strmust_starttime.$objPHPExcel->getActiveSheet()->getCell("I$j")->getValue();//開始時間
		@$strmust_endtime = $strmust_endtime.$objPHPExcel->getActiveSheet()->getCell("J$j")->getValue();//結束時間
		@$strmust_isctime = $strmust_isctime.$objPHPExcel->getActiveSheet()->getCell("K$j")->getValue();//區段
		@$strmust_iscweek = $strmust_iscweek.$objPHPExcel->getActiveSheet()->getCell("L$j")->getValue();//是否有公民素養週
		@$strmust6 = $strmust6.$objPHPExcel->getActiveSheet()->getCell("P$j")->getValue();//開班人數
		@$strmust7 = $strmust7.$objPHPExcel->getActiveSheet()->getCell("Q$j")->getValue();//招生人數
		@$strmust8 = $strmust8.$objPHPExcel->getActiveSheet()->getCell("R$j")->getValue();//是否公開課程
		@$strmust9 = $strmust9.$objPHPExcel->getActiveSheet()->getCell("X$j")->getValue();//是否保留身障名額
		@$strmust10 = $strmust10.$objPHPExcel->getActiveSheet()->getCell("Y$j")->getValue();//講師ID1
		@$strmust11 = $strmust11.$objPHPExcel->getActiveSheet()->getCell("Z$j")->getValue();//講師ID2
		@$strmust12 = $strmust12.$objPHPExcel->getActiveSheet()->getCell("AA$j")->getValue();//講師ID3
		@$strmust13 = $strmust13.$objPHPExcel->getActiveSheet()->getCell("AB$j")->getValue();//講師ID4
		@$strmust14 = $strmust14.$objPHPExcel->getActiveSheet()->getCell("AC$j")->getValue();//講師ID5
		
		@$strarea = str_replace(" ","",$strarea);   
		$strmust2=str_replace(" ","",$strmust2);
		$strmust3=str_replace(" ","",$strmust3);
		$strmust4=str_replace(" ","",$strmust4);
		$strmust5=str_replace(" ","",$strmust5);
		$strmust_starttime=str_replace(" ","",$strmust_starttime);
		$strmust_endtime=str_replace(" ","",$strmust_endtime);
		$strmust_iscweek=str_replace(" ","",$strmust_iscweek);
		$strmust_isctime=str_replace(" ","",$strmust_isctime);
		$strmust6=str_replace(" ","",$strmust6);
		$strmust7=str_replace(" ","",$strmust7);
		$strmust8=str_replace(" ","",$strmust8);
		$strmust9=str_replace(" ","",$strmust9);
		$strmust10=str_replace(" ","",$strmust10);
		$strmust11=str_replace(" ","",$strmust11);
		$strmust12=str_replace(" ","",$strmust12);
		$strmust13=str_replace(" ","",$strmust13);
		$strmust14=str_replace(" ","",$strmust14);
		$strmust_timedetail='';
		if(preg_match("/上/i",$strmust_isctime)){
			$strmust_timedetail='上午';
		}
		elseif(preg_match("/下/i",$strmust_isctime)){
			$strmust_timedetail='下午';
		}
		elseif(preg_match("/晚/i",$strmust_isctime)){
			$strmust_timedetail='晚上';
		}
		$start_time=date('Y-m-d H:i:s',strtotime('2017-06-02'.' '.$strmust_starttime.':00'));//計算起始時間
		$end_time=date('Y-m-d H:i:s',strtotime('2017-06-02'.' '.$strmust_endtime.':00'));//計算結束時間
		
		if($strmust2=="" || $strmust3=="" || $strmust4=="" || $strmust5=="" || $strmust6=="" || $strmust7=="" || $strmust8=="" || $strmust9=="" || $strmust10=="" || $strmust_starttime=="" || $strmust_endtime=="" || $strmust_iscweek=="" || $strarea=="" || $strmust_isctime==""){
			
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			
			echo '<script type=\'text/javascript\'>alert(\'您的匯入課程筆數總共:'.($highestRow-1).'，(列：'.($j.'至'.$highestRow).' )'.'有漏填必填項目(課程名稱、學分、課程類型、上課地點、週數、授課星期、開始時間、結束時間、區段、是否有公民素養週、開班人數(最低)、招生人數(最高)、是否公開、是否保留身障名額、講師一ID)，請檢查！\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';	
			return false;
		}
		elseif($strmust_timedetail==''){
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			echo '<script type=\'text/javascript\'>alert(\'您的課程區段有錯誤情形(筆數：'.($j-1).' )，請檢查！\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';	
			return false;
		}		
		elseif($totalRows_LocArea<1)
		{
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			
			echo '<script type=\'text/javascript\'>alert(\'您的課程地點有錯誤情形(筆數：'.($j-1).' )，請檢查！\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';	
		
			return false;
		}
		elseif((is_numeric($strmust10)==false)||(is_numeric($strmust11)==false && $strmust11<>"" )||(is_numeric($strmust12)==false&&$strmust12<>"")||(is_numeric($strmust13)==false&&$strmust13<>"")||(is_numeric($strmust14)==false&&$strmust14<>""))
		{
			
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			
			echo '<script type=\'text/javascript\'>alert(\'您的EXCEL講師部分有格式錯誤情形(筆數：'.($j-1).' )，請檢查！\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';	
			
			return false;
		}			
		elseif((strtotime($end_time) - strtotime($start_time))/ (60*50)<1){
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			
			echo '<script type=\'text/javascript\'>alert(\'您的EXCEL授課時間部分有錯誤情形，休息時間需算進去，請檢查是否有把休息時間算進去，或者是起始時間大於結束時間！(筆數：'.($j-1).' )\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';
			return false;
		}
		elseif($strmust7<$strmust6){
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			
			echo '<script type=\'text/javascript\'>alert(\'您的EXCEL開班人數不可高於招生人數，請檢查！(筆數：'.($j-1).' )\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';
			return false;
		}
			
		if($season<1 || $season_codes=="")
		{
			echo '<script type=\'text/javascript\'>alert(\'目前班季還未能執行匯入！\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';	
		    return false;
			}
		if((strtotime($strmust_endtime) - strtotime($strmust_starttime))/ (60*50)>0){  
		    
			$sum_hour=floor((strtotime($end_time)-strtotime($start_time))/(60*50));
			if($strmust_iscweek==1){
				$Course_Hour=($sum_hour*$row_CateP2['Season_Week'])-$sum_hour;
			}
			else{
				$Course_Hour=($sum_hour*$row_CateP2['Season_Week']);
			}
			
		}
	   //必填項目ED
		for($k='A';$k!=$highestColumn;$k++){
      	
		$str = $str.$objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue().';'; 
		$strs = explode(";",$str);
		
		
	    
		//讀取單元格
		//explode:函數把字符串分割為數組。
	    
		@$sql = "INSERT INTO course(Com_ID, Com_Name, Unit_ID, Unit_Name, Season_Year, SeasonCate_Name, Course_Audit,Add_Time,Add_Account,Add_Unitname,Add_Username,Course_IsImport, Course_ImportFile,Course_NO,Course_NOCount,Season_Code,CourseKind_ID,CourseKind_Name,Course_Name,Season_Credit,Course_Free,Loc_ID,Loc_Name,Season_Week,Course_Day,Course_Start,Course_End,Course_Hour, Course_Time, Course_IsCWeek,Course_Aims,Course_Summary, Course_Evaluation, Course_Min, Course_Max, Course_Online, Course_OnSite, Course_Reserve, Course_OnlineRemaining, Course_OnSiteRemaining, Course_ReserveRemaining,Course_Private, Course_Require, Course_Book, Course_Item, Course_Benefit, Course_Pay,  Course_IsReserve, Course_Area,Course_Schedule)VALUES(".$_POST['Com_ID'].",'".$_POST['Com_Name']."',".$_POST['Unit_ID'].",'".$_POST['Unit_Name']."',".$_POST['Season_Year'].",'".$_POST['SeasonCate_Name']."',"."1,'".$Add_Time."','".$_POST['Add_Account']."','".$_POST['Add_Unitname']."','".$_POST['Add_Username']."',".'1'.",'".$file."','".$season_codes.$_POST['Unit_Code'].$id."','".$id."','".$season_codes."','$strs[1]','".$CourseKind_Name."','$strs[2]','$strs[3]','$strs[4]','$strs[5]','".$Loc_Name."','$strs[6]','$strs[7]','$strs[8]".":00','$strs[9]".":00',".$Course_Hour.",'$strs[10]','$strs[11]','$strs[12]','$strs[13]','$strs[14]','$strs[15]','$strs[16]','".$Course_Online."','".$Course_OnSite."','".$Course_Reserve."','".$Course_Online."','".$Course_OnSite."','".$Course_Reserve."','$strs[17]','$strs[18]','$strs[19]','$strs[20]','$strs[21]','$strs[22]','$strs[23]','$strs[29]','第1週,;,$strs[30],;,$strs[31],;,第2週,;,$strs[32],;,$strs[33],;,第3週,;,$strs[34],;,$strs[35],;,第4週,;,$strs[36],;,$strs[37],;,第5週,;,$strs[38],;,$strs[39],;,第6週,;,$strs[40],;,$strs[41],;,第7週,;,$strs[42],;,$strs[43],;,第8週,;,$strs[44],;,$strs[45],;,第9週,;,$strs[46],;,$strs[47],;,第10週,;,$strs[48],;,$strs[49],;,第11週,;,$strs[50],;,$strs[51],;,第12週,;,$strs[52],;,$strs[53],;,第13週,;,$strs[54],;,$strs[55],;,第14週,;,$strs[56],;,$strs[57],;,第15週,;,$strs[58],;,$strs[59],;,第16週,;,$strs[60],;,$strs[61],;,第17週,;,$strs[62],;,$strs[63],;,第18週,;,$strs[64],;,$strs[65],;,第19週,;,$strs[66],;,$strs[67],;,第20週,;,$strs[68],;,$strs[69],;,')";
		
		}
		
		
		if(!mysql_query($sql)){
		
			$sql2 ="delete from course where Course_ImportFile ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2, $dbline) or die(mysql_error());
			
			$sql3 ="ALTER TABLE course AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3, $dbline) or die(mysql_error());
			
			$sql2_teacher ="delete from teacher_record where TeacherRecord_File ='".$file."'";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql2_teacher, $dbline) or die(mysql_error());
			
			$sql3_teacher ="ALTER TABLE teacher_record AUTO_INCREMENT=1";		
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($sql3_teacher, $dbline) or die(mysql_error());
			
			echo '<script type=\'text/javascript\'>alert(\'您的EXCEL有格式錯誤情形(筆數：'.($j-1).' )，請檢查！\');window.location=\'AD_Data_Index.php?Msg=AddError\';</script>';	
			return false;
		}
		
		/*查詢課程自動編*/
		  $query_CourseID = "SELECT LAST_INSERT_ID();";
		  $CourseID = mysql_query($query_CourseID, $dbline) or die(mysql_error());
		  $row_CourseID= mysql_fetch_assoc($CourseID);
		  $totalRows_CourseID = mysql_num_rows($CourseID);
		/*查詢課程 END*/
		
		//新增老師OP
		    $Total_ID="";//放置多個講師ID
			$Total_Name="";//放置多個講師Name
			if(isset($strmust10)&&$strmust10<>""){
				 $query_Teacher1 = "SELECT * from teacher where Teacher_ID =".$strmust10;
				 $Teacher1 = mysql_query($query_Teacher1, $dbline) or die(mysql_error());
				 $row_Teacher1= mysql_fetch_assoc($Teacher1);
				 $totalRows_Teacher1 = mysql_num_rows($Teacher1);
				 if($totalRows_Teacher1>0){
					$Total_ID.=$row_Teacher1['Teacher_ID'].",";
					$Total_Name.=$row_Teacher1['Teacher_UserName'].",";
					$insertSQL_teacher1 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName,TeacherRecord_IsImport,TeacherRecord_File) VALUES (%s, %s, %s, %s, %s)",
								   GetSQLValueString($row_Teacher1['Teacher_ID'], "int"),
								   GetSQLValueString($row_CourseID['LAST_INSERT_ID()'], "int"),
								   GetSQLValueString($row_Teacher1['Teacher_UserName'], "text"),
								   GetSQLValueString(1, "int"),
								   GetSQLValueString($file, "text"));
					$Result2 = mysql_query($insertSQL_teacher1, $dbline) or die(mysql_error());	
				}
				mysql_free_result($Teacher1);
			 }
			 if(isset($strmust11)&&$strmust11<>""){
				 $query_Teacher2 = "SELECT * from teacher where Teacher_ID =".$strmust11;
				 $Teacher2 = mysql_query($query_Teacher2, $dbline) or die(mysql_error());
				 $row_Teacher2= mysql_fetch_assoc($Teacher2);
				 $totalRows_Teacher2 = mysql_num_rows($Teacher2);
				 if($totalRows_Teacher2>0){
					$Total_ID.=$row_Teacher2['Teacher_ID'].",";
					$Total_Name.=$row_Teacher2['Teacher_UserName'].",";
					$insertSQL_teacher2 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName,TeacherRecord_IsImport,TeacherRecord_File) VALUES (%s, %s, %s, %s, %s)",
								   GetSQLValueString($row_Teacher2['Teacher_ID'], "int"),
								   GetSQLValueString($row_CourseID['LAST_INSERT_ID()'], "int"),
								   GetSQLValueString($row_Teacher2['Teacher_UserName'], "text"),
								   GetSQLValueString(1, "int"),
								   GetSQLValueString($file, "text"));
					
					$Result2 = mysql_query($insertSQL_teacher2, $dbline) or die(mysql_error());	
				}
				mysql_free_result($Teacher2);
			 }
			 if(isset($strmust12)&&$strmust12<>""){
				 $query_Teacher3 = "SELECT * from teacher where Teacher_ID =".$strmust12;
				 $Teacher3 = mysql_query($query_Teacher3, $dbline) or die(mysql_error());
				 $row_Teacher3= mysql_fetch_assoc($Teacher3);
				 $totalRows_Teacher3 = mysql_num_rows($Teacher3);
				 if($totalRows_Teacher3>0){
					$Total_ID.=$row_Teacher3['Teacher_ID'].",";
					$Total_Name.=$row_Teacher3['Teacher_UserName'].",";
					$insertSQL_teacher3 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName,TeacherRecord_IsImport,TeacherRecord_File) VALUES (%s, %s, %s, %s, %s)",
								   GetSQLValueString($row_Teacher3['Teacher_ID'], "int"),
								   GetSQLValueString($row_CourseID['LAST_INSERT_ID()'], "int"),
								   GetSQLValueString($row_Teacher3['Teacher_UserName'], "text"),
								   GetSQLValueString(1, "int"),
								   GetSQLValueString($file, "text"));
					
					$Result2 = mysql_query($insertSQL_teacher3, $dbline) or die(mysql_error());	
				}
				mysql_free_result($Teacher3);
			 }
			 if(isset($strmust13)&&$strmust13<>""){
				 $query_Teacher4 = "SELECT * from teacher where Teacher_ID =".$strmust13;
				 $Teacher4 = mysql_query($query_Teacher4, $dbline) or die(mysql_error());
				 $row_Teacher4= mysql_fetch_assoc($Teacher4);
				 $totalRows_Teacher4 = mysql_num_rows($Teacher4);
				 if($totalRows_Teacher4>0){
					$Total_ID.=$row_Teacher4['Teacher_ID'].",";
					$Total_Name.=$row_Teacher4['Teacher_UserName'].",";
					$insertSQL_teacher4 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName,TeacherRecord_IsImport,TeacherRecord_File) VALUES (%s, %s, %s, %s, %s)",
								   GetSQLValueString($row_Teacher4['Teacher_ID'], "int"),
								   GetSQLValueString($row_CourseID['LAST_INSERT_ID()'], "int"),
								   GetSQLValueString($row_Teacher4['Teacher_UserName'], "text"),
								   GetSQLValueString(1, "int"),
								   GetSQLValueString($file, "text"));
					
					$Result2 = mysql_query($insertSQL_teacher4, $dbline) or die(mysql_error());	
				}
				mysql_free_result($Teacher4);
			 }
			 if(isset($strmust14)&&$strmust14<>""){
				 $query_Teacher5 = "SELECT * from teacher where Teacher_ID =".$strmust14;
				 $Teacher5 = mysql_query($query_Teacher5, $dbline) or die(mysql_error());
				 $row_Teacher5= mysql_fetch_assoc($Teacher5);
				 $totalRows_Teacher5 = mysql_num_rows($Teacher5);
				 if($totalRows_Teacher5>0){
					$Total_ID.=$row_Teacher5['Teacher_ID'].",";
					$Total_Name.=$row_Teacher5['Teacher_UserName'].",";
					$insertSQL_teacher5 = sprintf("INSERT INTO teacher_record (Teacher_ID, Course_ID, Teacher_UserName,TeacherRecord_IsImport,TeacherRecord_File) VALUES (%s, %s, %s, %s, %s)",
								   GetSQLValueString($row_Teacher5['Teacher_ID'], "int"),
								   GetSQLValueString($row_CourseID['LAST_INSERT_ID()'], "int"),
								   GetSQLValueString($row_Teacher5['Teacher_UserName'], "text"),
								   GetSQLValueString(1, "int"),
								   GetSQLValueString($file, "text"));
					
					$Result2 = mysql_query($insertSQL_teacher5, $dbline) or die(mysql_error());	
				}
				mysql_free_result($Teacher5);
			 }
			 if($Total_ID<>""){
				$updateSQL = sprintf("update course set Teacher_ID=%s ,Teacher_UserName=%s where Course_ID=%s",
                       GetSQLValueString(substr($Total_ID,0,-1), "text"),					  
					   GetSQLValueString(substr($Total_Name,0,-1), "text"),
					   GetSQLValueString($row_CourseID['LAST_INSERT_ID()'], "int"));
	   
			    
			    $Result3 = mysql_query($updateSQL, $dbline) or die(mysql_error());	 
			 }
			 
		//新增老師ED 
		mysql_free_result($CateP);
		mysql_free_result($CateP2);
		mysql_free_result($CourseKind);
		mysql_free_result($LocArea);
		mysql_free_result($CourseID);
        $strmax='';
		$strrule='';
		$strpeople='';
		$strcoursekind='';
		$strarea='';
		$str = "";
		$strmust1="";
		$strmust2="";
		$strmust3="";
		$strmust4="";
		$strmust5="";
		$strmust_starttime="";
		$strmust_endtime="";
		$strmust_iscweek="";
		$strmust_isctime="";
		
		$strmust6="";
		$strmust7="";
		$strmust8="";
		$strmust9="";
		$strmust10="";
		$strmust11="";
		$strmust12="";
		$strmust13="";
		$strmust14="";
		$start_time="";
		$end_time="";
		$Total_ID="";
		$Total_Name="";
       
	}
       $_SESSION['inalready'] += 1;  
	//unlink($uploadfile); //刪除上傳的excel文件
	/*echo '<script>window.location=\'AD_Data_Index.php?Msg=AddOK\';</script>';*/
	
	}
	else{
	echo '<script type=\'text/javascript\'>alert(\'匯入失敗！\');//window.location=\'AD_Data_CourseImport.php\';</script>';
    
	}

}
?> 
 
<?php
//include_once ('global.php');
if (isset($_POST["send"])) {
	$leadExcel=$_POST["leadExcel"];
    if(!isset($_SESSION['inalready'])){
		   $_SESSION['inalready'] = 0;
	    }
	
	 
	if($leadExcel == "true" && $_SESSION['inalready']<1 ){
		
        
		//獲取上傳的文件名
		$filename1 = $_FILES['inputExcel']['name'];
		$ext = pathinfo($filename1, PATHINFO_EXTENSION);
		$add_Time = substr(microtime(),2,6);
		$filename = time()."_".$add_Time."_".rand(10,99).".".$ext;
		//上傳到服務器上的臨時文件名
	    $tmp_name = $_FILES['inputExcel']['tmp_name'];
		//$tmp_name=time()."_".$add_Time."_".rand(10,99).".".$ext;
		$season=$totalRows_SeasonCodes;
		$season_codes=$_POST['Season_Code'];
		if(in_array($ext,array('xls','xlsx'))){
			$msg =uploadFile($filename,$tmp_name,$season,$season_codes);
			
			$Add_Time=date('Y-m-d H:i:s');	
			$Other = "新增匯入EXCEL課程";
			$insertSQL = sprintf("INSERT INTO course_import (Com_ID, Unit_ID, CourseImport_File, CourseImport_FileText, Add_Time, Add_Account, Add_Unitname, Add_Username) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
								   GetSQLValueString($_POST['Com_ID'], "int"),
								   GetSQLValueString($_POST['Unit_ID'], "int"),
								   GetSQLValueString('../../UpLoad/Course/'.$filename,"text"),
								   GetSQLValueString($filename1, "text"),
								   GetSQLValueString($Add_Time, "date"),
								   GetSQLValueString($_POST['Add_Account'], "text"),
								   GetSQLValueString($_POST['Add_Unitname'], "text"),
								   GetSQLValueString($_POST['Add_Username'], "text"));
			mysql_select_db($database_dbline, $dbline);
			$Result = mysql_query($insertSQL, $dbline) or die(mysql_error());		
			require_once('../../Include/Data_BrowseInsert.php');
			
			
			echo '<script type=\'text/javascript\'>window.location=\'AD_Data_CourseImport.php\';</script>';
		}
		else{
			echo '<script type=\'text/javascript\'>alert(\'檔案格式錯誤！僅可上傳xlsx與xls檔\');window.location=\'AD_Data_CourseImport.php\';</script>';
	    }
		
		
	}
	
}
if (isset($_POST["clear"])) {
	$sql = "TRUNCATE TABLE net_mailuser";
	if(!mysql_query($sql)){
		return false;
	}
    
	
}
?>

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
        <td width="15%"><?php require_once('../../Include/Menu_AdminLeft.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> 新增<?php echo $row_ModuleSet['ModuleSetting_Title']; ?></div>
<?php if($row_Permission['Per_Add']==1){?>
   <br/>
<form name="form2" method="post" action="<?php $_SERVER['PHP_SELF']?>" enctype="multipart/form-data">

<input type="hidden" name="leadExcel" value="true">
<div align="center" >
<fieldset style="max-width:800px;">
<legend><?php echo '匯入課程'; ?></legend>
    <div align="center" >
    <table width="45%" border="0">
    <tr>
    <td>
    <select name="Season_Area" id="Season_Area"  required  onChange="callbyAJAX()">
    <option value="">請選擇班季/社區大學...</option>
    <?php if($totalRows_SeasonCodes>0){
              do {?>
    <option value="<?php echo $row_SeasonCodes['Season_Code'].'/'.$row_SeasonCodes['Com_ID']?>"><?php echo $row_SeasonCodes['Season_Year'].'年'.$row_SeasonCodes['SeasonCate_Name'].'/'.$row_SeasonCodes['Com_Name'];?></option>
    <?php 	  }while($row_SeasonCodes = mysql_fetch_assoc($SeasonCodes));
          } ?>
    </select>
    
     <select name="Unit_ID" id="Unit_ID" required onChange="UnitCode()">      <option value="">請選擇...</option> 
          </select>
          <input name="Com_ID" type="hidden" id="Com_ID">
          <input name="Com_Name" type="hidden" id="Com_Name">
          <input name="Unit_Name" type="hidden" id="Unit_Name">
          <input name="Season_Year" type="hidden" id="Season_Year">
          <input name="SeasonCate_Name" type="hidden" id="SeasonCate_Name">
          <input name="Season_Code" type="hidden" id="Season_Code">
          <input name="Unit_Code" type="hidden" id="Unit_Code">
          <input name="inalready" type="hidden" id="inalready" value="<?php if(isset($_SESSION['inalready'])){echo $_SESSION['inalready'];} ?>">
          <input name="Unit_Range" id="Unit_Range" type="hidden" value="<?php echo $colname02_Unit;?>">
           <script type="text/javascript">
           function callbyAJAX(){
                // mainItemValue 代表 option value, 其值對應到 printing p_id
                var string=document.getElementById("Season_Area").value;
		  	    var Season_Area = new Array();
		 		Season_Area = string.split("/");
				document.getElementById("Com_ID").value=Season_Area[1];
				document.getElementById("Season_Code").value=Season_Area[0];
                var mainItemValue = Season_Area[1];
                var mainItemValue2 = document.getElementById("Unit_Range").value;
        
                if (window.XMLHttpRequest) 
                {
            // code for IE7+, Firefox, Chrome, Opera, Safari
                    xmlhttp_subitems = new XMLHttpRequest();
                    xmlhttp_subitems_area = new XMLHttpRequest();
                } 
                else 
                {  
            // code for IE6, IE5
                    xmlhttp_subitems = new ActiveXObject("Microsoft.XMLHTTP");
                    xmlhttp_subitems_area = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp_subitems.onreadystatechange = function() 
                {
					
                    document.getElementById("Unit_ID").innerHTML = xmlhttp_subitems.responseText;
					/*社區名稱、班季年、班季類別*/
					var com_name=($('#Season_Area :selected').text()).split("/");
				    document.getElementById("Com_Name").value=com_name[1];
					var season_all=com_name[0].split("年");
					document.getElementById("Season_Year").value=season_all[0];
					document.getElementById("SeasonCate_Name").value=season_all[1];
					
                }
                
        
                xmlhttp_subitems.open("get", "cate_value.php?Com_ID=" + encodeURI(mainItemValue)+"&Unit_Range="+encodeURI(mainItemValue2), true);
                xmlhttp_subitems.setRequestHeader("Content-type","application/x-www-form-urlencoded");	
                xmlhttp_subitems.send();
    
        
          }
           </script>
           <script type="text/javascript">
           function UnitCode(){
              
               document.getElementById("Unit_Code").value=document.getElementById("Unit_ID").options[document.getElementById("Unit_ID").selectedIndex].text;
               var x=document.getElementById("Unit_Code").value.indexOf("：");
                document.getElementById("Unit_Code").value= document.getElementById("Unit_Code").value.substr(0, x);
				
				/*分校名稱*/
					var unit_name=($('#Unit_ID :selected').text()).split("：");
				    document.getElementById("Unit_Name").value=unit_name[1];
               
               }
           </script>
    </td>
    </tr>
    <tr>
    <td class="center">
    <input type="file" name="inputExcel"><br/><input type="submit" value="確定上傳" name="send" class="Button_Submit"> <input type="button" value="返回" class="Button_General" onClick="location.href='AD_Data_Index.php'"/>
    </td>
    </tr>
    
    </table>
    </div>
</fieldset>
</div>
  <input name="Add_Account" type="hidden" id="Add_Account" value="<?php echo $row_AdminMember['Account_Account']; ?>">
    <input name="Add_Unitname" type="hidden" id="Add_Unitname" value="<?php echo $row_AdminMember['Account_JobName']; ?>">
    <input name="Add_Username" type="hidden" id="Add_Username" value="<?php echo $row_AdminMember['Account_UserName']; ?>">
   
     <input type="hidden" name="MM_insert" value="Form_Add" />
</form>

<div align="center">
<br/>
<table cellpadding="0" cellspacing="0" border="0" >
<tr>
<td class="FormTitle03 right" nowrap="nowrap"><font color="red">*</font> 範本檔：
</td>
<td class="middle"><a href="example_1070810.xlsx"><img src="../../Icon/attach.png">最新範本檔案_1070810更新</a>
</td>
</tr>
<tr>
<td class="FormTitle03 right" nowrap="nowrap"><font color="red">*</font> 重要注意事項：
</td>
<td class="middle" ><p style="text-indent:-26px;margin-left:26px;">1、請下載範本檔案並做修改，一次最多匯入20筆，請依照範例格式輸入資料，若擅自更改格式、刪除欄項目，將會匯入失敗。</p>
<p style="text-indent:-26px;margin-left:26px;">2、列之間不可以處於空白狀況，如下圖：</p><div align="center"><table cellpadding="5" cellspacing="0" border="0" style=" border:1px #dddddd solid"><tr class="TableBlock_shadow_Head_Back"><td class="center middle">錯誤方式</td></tr><tr><td class="center middle"><img src="wrong.JPG" width="80%" style="max-width:501px;"></td></tr><tr class="TableBlock_shadow_Head_Back"><td class="center middle">正確方式</td></tr><tr><td class="center middle"><img src="correct.JPG" style="max-width:501px;" width="80%"></td></tr></table></div>
<p style="text-indent:-26px;margin-left:26px;">3、若您的資料出現錯誤情況：您的資料僅8筆，錯誤停醒的匯入課程筆數筆您的資料多時，請框起整個多餘的列，使用滑鼠右鍵，並點選刪除，如下圖：</p><div align="center"><table cellpadding="5" cellspacing="0" border="0"><tr><td class="center middle"><img src="ex1.JPG" width="80%" style="max-width:501px;"></td></tr><tr><td class="center middle">↓</td></tr><tr><td class="center middle"><img src="ex2.JPG" style="max-width:501px;" width="80%"></td></tr><tr><td class="center middle">↓</td></tr><tr><td class="center middle"><img src="ex3.jpg" style="max-width:501px;" width="80%"></td></tr></table></div>
<p style="text-indent:-26px;margin-left:26px;">4、匯入成功後，請檢查課程資料是否匯入完整。</p>
</td>
</tr>
</table> 
</div>
 
     
    <?php } else{ ?><br><br><br>
    <div class="Error_Msg Error_Code center"><img src="../../Icon/delete.gif" alt="錯誤訊息" class="middle"> 您的帳號不具有此功能新增權限</div>    
    <?php } ?>
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
mysql_free_result($SeasonCodes);
?>
<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('zz_Admin_Permission.php'); ?>
<?php require_once('zz_module_setting.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>