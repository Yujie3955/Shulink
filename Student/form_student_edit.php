       <table width="98%" border="0" cellpadding="5" cellspacing="2" style="max-width:800px;">
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>姓名：</td>
       <td width="30%" class="middle"><input type="text" name="Title" id="Title" required value="<?php if(isset($row_Data['Member_UserName'])){echo $row_Data['Member_UserName'];}?>" ></td>
       </tr>
      
       <?php if(isset($forms_type) && $forms_type=='edit'){?>
       <tr>
		<td class="right FormTitle02" width="20%">國籍：</td>
		<td class="middle"><?php if(isset($row_Data['Foreigns']) && $row_Data['Foreigns']==1){echo '國外';}else{echo '臺灣';}?><input name="Foreigns" id="Foreigns" type="hidden" value="<?php echo $row_Data['Foreigns'];?>"><input name="user_location" id="user_location"  value="<?php if(isset($row_Data['Foreigns']) && $row_Data['Foreigns']==1){echo '2';}else{echo '1';}?>"  type="hidden"></td>
		
       </tr>
	
       <tr>
       <td class="right FormTitle03" width="20%" ><font color="red">*</font><span class="tw">身分證字號</span><span class="fg" style="display:none;">居留證編號</span>(帳號)：</td>
       <td class="middle"><input type="text" name="Member_Identity" id="Member_Identity" required onKeyUp="CheckAgain();" style="width:100%;max-width:150px;" value="<?php echo $row_Data['Member_Identity'];?>"> 
	 
       <input type="button" value="檢查身分" class="Button_General" onClick="callByAJAX_Identity();"><input type="hidden" name="Member_OIdentity" id="Member_OIdentity" value="<?php echo $row_Data['Member_Identity'];?>"><div id="RepeatAccount" style="display:inline-block; margin-top:7px;"></div></td>
       </tr>
       <?php }
       else{ if(isset($forms_type) && $forms_type!='add'){?>
       <tr>
       <td class="right FormTitle02" width="20%">原國籍：</td>
       <td class="middle"><?php if($row_Data['Foreigns']==1){echo '國外';}else{echo '臺灣';}?></td>
       </tr>
 <?php }}?>
       
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02" ><font color="red">*</font>國別(country)：</td>
		<td><input type="text" name="Member_Country" id="Member_Country" value="<?php if(isset($row_Data['Member_Country'])){echo $row_Data['Member_Country'];}?>"></td>
	   </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02" ><font color="red">*</font>護照英文姓名：</td>
		<td><input type="text" name="Member_Rename" id="Member_Rename" value="<?php if(isset($row_Data['Member_Rename'])){echo $row_Data['Member_Rename'];}?>"></td>
	   </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02"><font color="red">*</font>是否為新住民：</td>
		<td>
			<input type="radio" name="Member_Isresident"  <?php if(isset($row_Data['Member_Isresident']) && $row_Data['Member_Isresident']==1){ echo 'checked';}?> value="1">是
			<input type="radio" name="Member_Isresident"  <?php if(isset($row_Data['Member_Isresident']) && $row_Data['Member_Isresident']==0){ echo 'checked';}elseif(!isset($row_Data['Member_Isresident'])){echo 'checked';}?> value="0">否
		</td>
	</tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>密碼：</td>
       <td class="middle"><input type="hidden"  name="Ori_Member_Password" id="Ori_Member_Password" value="<?php if(isset($row_Data['Member_Password'])){echo $row_Data['Member_Password'];}?>" ><input type="password"  name="Member_Password" placeholder="請填入5碼以上密碼" id="Member_Password" value="<?php if(isset($row_Data['Member_Password'])){echo $row_Data['Member_Password'];}?>" required><span style="display:inline-block"></span><span class="Password_Format">請填入5碼以上密碼</span></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>身分註記：</td>
       <td class="middle"><select name="Member_Type" id="Member_Type" required>
       		<option value="">請選擇身分別</option>    
		<?php if(isset($totalRows_TypeData) && $totalRows_TypeData>0){
			do{?>   
		   	<option value="<?php echo $row_TypeData['MemberType_Name']?>" <?php if(isset($row_Data['Member_Type']) && $row_Data['Member_Type']==$row_TypeData['MemberType_Name']){echo 'selected';}?>><?php echo $row_TypeData['MemberType_Name']?></option>
		<?php 	}while($row_TypeData=mysql_fetch_assoc($TypeData));
		      }else{?>            
            	<option value="一般" selected>一般</option>
    	        <?php }
			if(isset($totalRows_TypeData)){mysql_free_result($TypeData);}?>  
                </select>
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>出身年月日：</td>
       <td class="middle">
		<?php require_once("search_years.php");?>
		
		<div class="DateStyle">
        	    <div class='input-group date picker_date' >
            	<input name="Member_Birthday" type="text" id="Member_Birthday" required data-format="yyyy/MM/dd" value="<?php if(isset($row_Data['Member_Birthday'])){echo $row_Data['Member_Birthday'];}?>"  class="form-control"/>
            	<span class="input-group-addon">
            		<span class="glyphicon glyphicon-calendar"></span>
          		</span>
            	</div>
        	 </div><span style="display:inline-block">(格式：西元年/月/日)</span><span class="Msg_Date">請輸入正確日期格式</span></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>是否為原住民：</td>
       <td class="middle">
        <input type="radio" name="Member_Isindigenous" <?php if(isset($row_Data['Member_Isindigenous']) && $row_Data['Member_Isindigenous']==1){ echo 'checked';}?> value="1">是
	<input type="radio" name="Member_Isindigenous" <?php if(isset($row_Data['Member_Isindigenous']) && $row_Data['Member_Isindigenous']==0){ echo 'checked';}elseif(!isset($row_Data['Member_Isindigenous'])){echo 'checked';}?>  value="0">否
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>性別：</td>
       <td class="middle">
		<?php //echo $row_Data['Member_Sex']; ?>
       <select name="Member_Sex" id="Member_Sex">
       <option value="男" <?php if(isset($row_Data['Member_Sex']) && $row_Data['Member_Sex']=='男'){ echo 'selected';}?>>男</option>
       <option value="女" <?php if(isset($row_Data['Member_Sex']) && $row_Data['Member_Sex']=='女'){ echo 'selected';}?>>女</option>
       <option value="其他" <?php if(isset($row_Data['Member_Sex']) && $row_Data['Member_Sex']=='其他'){ echo 'selected';}?>>其他</option></select></td>
       </tr>
	<tr>
	<td class="right FormTitle02 middle">通訊地址：</td>
        <td  class="middle">郵遞區號：<?php if(isset($row_Data['Postal_Code']) && $row_Data['Postal_Code']<>""){echo '<input type="hidden" id="Postal_CodeOrigin" name="Postal_CodeOrigin" style="size:5; width:50px;" value="'.$row_Data['Postal_Code'].'">';}?><span id="Postal"></span><input type="hidden" id="Postal_Code" name="Postal_Code" style="size:5; width:50px;" value="<?php echo $row_Data['Postal_Code'];?>"><?php ?><br/>
             
             <select name="County_ID" id="County_ID" onChange="<?php if(isset($forms_type) && $forms_type=='edit'){?> callByAJAX3();<?php }else{?> callByAJAX();<?php }?>">
             <option value="">請選擇縣市...</option>
     		 <?php if($totalRows_County>0){
		       			do{?>
                        	<option value="<?php echo $row_County['County_Cate'];?>"  <?php if($row_Data['County_Cate']==$row_County['County_Cate']){echo 'selected'; }?>><?php echo $row_County['County_Cate'];?></option>
			  			<?php }while($row_County = mysql_fetch_assoc($County));
	  
			 }?>
             </select>
			 <?php mysql_free_result($County);?>
             <span class="Msg_County_ID">請選擇縣市</span>&nbsp;
            
             <select name="County_Name" id="County_Name" onChange="callPostal();">
             <option value="" >:::請選擇區域:::</option>
             </select>
             <span class="Msg_County_Name">請選擇區域</span>
             <input name="Member_Address" type="text" id="Member_Address"  value="<?php if(isset($row_Data['Member_Address'])){echo $row_Data['Member_Address'];}?>" >
             <input type="hidden" value="<?php if(isset($row_Data['County_Name'])){echo $row_Data['County_Name'];}?>" name="CountyNameData" id="CountyNameData"></td>
       </tr>

      
       <tr>
       <td class="right FormTitle02" width="20%">email：</td>
       <td  class="middle"><input type="email" name="Member_Email" id="Member_Email" style="width:60%;" value="<?php if(isset($row_Data['Member_Email'])){echo $row_Data['Member_Email'];}?>" ></td>
       </tr>     
       
       
       <tr>
       <td class="right FormTitle03" width="20%"><font color="red">*</font>電話：</td>
       <td class="middle">
           <table cellpadding="5" cellspacing="0">
            <tr>
            <td class="right">行動電話：</td>
		    <td><input type="tel" name="Member_Phone" id="Member_Phone"  placeholder="請輸入行動電話" value="<?php if(isset($row_Data['Member_Phone'])){echo $row_Data['Member_Phone'];}?>"/><br/>(格式:0912123456)
            </td>
            </tr>
            <tr>
            <td class="right">
			室內電話：</td>
            <td><input type="tel" name="Member_Tel" id="Member_Tel"  placeholder="請輸入室內電話"   value="<?php if(isset($row_Data['Member_Tel'])){echo $row_Data['Member_Tel'];}?>"/><br/>(格式:031234567)
            </td>
            </tr>
            <tr>
           
            <td class="right">公司電話：</td>
<?php 	if(isset($row_Data['Member_CTel']) && $row_Data['Member_CTel']<>""){
		$Member_CTelL = explode("#",$row_Data['Member_CTel']);
		$Member_CTel=$Member_CTelL[0];
		if(isset($Member_CTelL[1])){
			$Member_CTel2=$Member_CTelL[1]; 
		}
		else{
			$Member_CTel2=$Member_CTelL[0]; 
		}
	}else{
		$Member_CTel='';
		$Member_CTel2='';
	}   ?>
			<td><input type="tel" name="Member_CTel" id="Member_CTel"   placeholder="請輸入公司電話" value="<?php if(isset($Member_CTel)){echo $Member_CTel;}?>"/>
                分機
                <input type="text" name="Member_CTel2" id="Member_CTel2" style="width:70px" value="<?php if(isset($Member_CTel2)){echo $Member_CTel2;}?>" />
                <br/>(格式:031234567分機165) 
			</td>
            </tr>
            </table>
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%">學歷：</td>
       <td class="middle"><select name="Edu_ID" id="Edu_ID" > 
                      <option value="">=請選擇=</option>
                      <?php do{?>
					  <option value="<?php echo $row_Cate2['Edu_ID']?>" <?php if(isset($row_Data['Edu_ID']) && $row_Data['Edu_ID']==$row_Cate2['Edu_ID']){echo 'selected';}?>><?php echo $row_Cate2['Edu_Name'];?></option>
					  <?php }while($row_Cate2=mysql_fetch_assoc($Cate2))?></select></td>
       </tr>
       <tr>
		<td class="right FormTitle02">學區：</td>
		<td>
			<select name="Member_Area" id="Member_Area" >
				<option value="" SELECTED>=請選擇=</option>
			　	<?php 
					$learnlocationcont=0;
					do{
				?>
						<option value="<?php echo $learnlocation_id[$learnlocationcont];?>" <?php if(isset($row_Data['Member_Area']) && $row_Data['Member_Area']==$learnlocation_id[$learnlocationcont]){echo 'selected';}?>><?php echo $learnlocation_name[$learnlocationcont]; ?></option>
				<?php				
					$learnlocationcont++;
				}while($learnlocationcont<$learnlocationnumber);
				?>
			</select>
		</td>
	   </tr>
       <tr>
       <td class="right FormTitle02" width="20%">職業類別：</td>
       <td class="middle"><select name="Job_ID" id="Job_ID" >
                      <option value="">=請選擇=</option>
                      <?php do{?>
					  <option value="<?php echo $row_Cate3['Job_ID']?>" <?php if(isset($row_Data['Job_ID']) && $row_Data['Job_ID']==$row_Cate3['Job_ID']){echo 'selected';}?>><?php echo $row_Cate3['Job_Name'];?></option>
					  <?php }while($row_Cate3=mysql_fetch_assoc($Cate3))?></select>
                      職稱：<input type="text" name="Job_Title" id="Job_Title" value="<?php if(isset($row_Data['Job_Title'])){echo $row_Data['Job_Title'];}?>"></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%">服務單位：</td>
       <td class="middle"><input type="text" name="Member_Unitname" id="Member_Unitname" value="<?php if(isset($row_Data['Member_Unitname'])){echo $row_Data['Member_Unitname'];}?>"></td>
       </tr>
      
       <tr>
		<td class="right FormTitle02">電子票證資訊：</td>
		<td>
			<select name="Member_Card" id="Member_Card" onchange="chosecard(this);">
				<option value="">無</option>
				<option value="悠遊卡" <?php if(isset($row_Data['Member_Card']) && $row_Data['Member_Card']=='悠遊卡'){echo 'selected';}?>>悠遊卡</option>
				<option value="一卡通" <?php if(isset($row_Data['Member_Card']) && $row_Data['Member_Card']=='一卡通'){echo 'selected';}?>>一卡通</option>
			</select>
			<div style="display:none" id="user_cardnumber">
		    &nbsp;卡號：<input type="text" name="Member_CardNo" value="<?php if(isset($row_Data['Member_CardNo'])){echo $row_Data['Member_CardNo'];}?>">

			</div>
		</td>
	   </tr>
       <tr>
       <td class="right FormTitle03" width="20%"><font color="red">*</font>緊急聯絡人：</td>
       <td class="middle">
            姓名：<input type="text" name="Emergency_Person" id="Emergency_Person"  value="<?php if(isset($row_Data['Emergency_Person'])){echo $row_Data['Emergency_Person'];}?>" required><br/>
			關係：<input type="text" name="Emergency_Relation" id="Emergency_Relation"  value="<?php if(isset($row_Data['Emergency_Relation'])){echo $row_Data['Emergency_Relation'];}?>" required><br/>
			聯絡人電話：
			<input type="tel" name="Emergency_Person_Tel" id="Emergency_Person_Tel"  placeholder="請輸入行動電話"  required  value="<?php if(isset($row_Data['Emergency_Person_Tel'])){echo $row_Data['Emergency_Person_Tel'];}?>"/><br/></td>
       </tr>
       <tr>
             <td class="right FormTitle02" width="20%">備註：</td>
             <td colspan="3" class="middle"><textarea name="Member_Remark" id="Member_Remark" style="max-width:573px; width:100%; " rows="5"><?php if(isset($row_Data['Member_Remark'])){ echo $row_Data['Member_Remark'];}?></textarea></td>
       </tr>     
       <input type="hidden" name="Member_Inform" id="Member_Inform" value="1"  >
       <tr>
	   <td class="right FormTitle03">經由何種管道得知招生訊息：</td>
	   <td><?php 	if(isset($row_Data['Member_News'])){
				$Member_NewsArray=explode(";",$row_Data['Member_News']);
			}else{
				$Member_NewsArray=array();
			}?>
				<input type="checkbox" name="Member_News[]" value="夾報、派報" class="where_get_msg" id="from_clip" <?php if(in_array("夾報、派報",$Member_NewsArray)){echo 'checked';}?>> 夾報、派報<br>
				<input type="checkbox" name="Member_News[]" value="社大臉書粉絲頁" class="where_get_msg" id="from_district" <?php if(in_array("社大臉書粉絲頁",$Member_NewsArray)){echo 'checked';}?>> 社大臉書粉絲頁<br>
				<input type="checkbox" name="Member_News[]" value="路過看到" class="where_get_msg" id="from_extra1" <?php if(in_array("路過看到",$Member_NewsArray)){echo 'checked';}?>> 路過看到<br>
				<input type="checkbox" name="Member_News[]" value="其他" class="where_get_msg" id="from_extra2" <?php if(in_array("其他",$Member_NewsArray)){echo 'checked';}?>> 其他<br>
				<input type="checkbox" name="Member_News[]" value="講師介紹" class="where_get_msg" id="from_extra3" <?php if(in_array("講師介紹",$Member_NewsArray)){echo 'checked';}?>> 講師介紹<br>
				<input type="checkbox" name="Member_News[]" value="成果展" class="where_get_msg" id="from_fair" <?php if(in_array("成果展",$Member_NewsArray)){echo 'checked';}?>> 成果展<br>
				<input type="checkbox" name="Member_News[]" value="店家索取宣傳單" class="where_get_msg" id="from_fly" <?php if(in_array("店家索取宣傳單",$Member_NewsArray)){echo 'checked';}?>> 店家索取宣傳單<br>
				<input type="checkbox" name="Member_News[]" value="舊生介紹" class="where_get_msg" id="from_friend"<?php if(in_array("舊生介紹",$Member_NewsArray)){echo 'checked';}?>> 舊生介紹<br>
				<input type="checkbox" name="Member_News[]" value="縣府網站" class="where_get_msg" id="from_my_web" <?php if(in_array("縣府網站",$Member_NewsArray)){echo 'checked';}?>> 縣府網站<br>
				<input type="checkbox" name="Member_News[]" value="報紙廣告" class="where_get_msg" id="from_newspaper" <?php if(in_array("報紙廣告",$Member_NewsArray)){echo 'checked';}?>> 報紙廣告<br>
				<input type="checkbox" name="Member_News[]" value="紅布條或帆布廣告" class="where_get_msg" id="from_red" <?php if(in_array("紅布條或帆布廣告",$Member_NewsArray)){echo 'checked';}?>> 紅布條或帆布廣告<br>
				<input type="checkbox" name="Member_News[]" value="公部門服務台" class="where_get_msg" id="from_service" <?php if(in_array("公部門服務台",$Member_NewsArray)){echo 'checked';}?>> 公部門服務台<br>
				<input type="checkbox" name="Member_News[]" value="舊生" class="where_get_msg" id="from_student" <?php if(in_array("舊生",$Member_NewsArray)){echo 'checked';}?>> 舊生<br>
				<input type="checkbox" name="Member_News[]" value="社大官方網站" class="where_get_msg" id="from_tao_web" <?php if(in_array("社大官方網站",$Member_NewsArray)){echo 'checked';}?>> 社大官方網站<br>
				<input type="checkbox" name="Member_News[]" value="電視廣告" class="where_get_msg" id="from_tv" <?php if(in_array("電視廣告",$Member_NewsArray)){echo 'checked';}?>> 電視廣告
		</td>
	    </tr>
	   <?php if(isset($forms_type) && $forms_type=='edit' && $row_Permission['Per_Pass']==1){?>

	   <tr>
		<td colspan="2" style="font-weight:bold;"><hr><center>學員資料繳交資訊</center></td>
	   </tr>
	   <tr>
		<td class="right FormTitle03">是否已繳交身分證影本：</td>
		<td>
			<input type="radio" name="Member_Isidentity" <?php echo $row_Data['Member_Isidentity']==1?'CHECKED':''; ?> value='1'>是
			<input type="radio" name="Member_Isidentity" <?php echo $row_Data['Member_Isidentity']==0?'CHECKED':''; ?> value='0'>否
		</td>
	   </tr>
	   <tr>
		<td class="right FormTitle03">是否已繳交照片：</td>
		<td>
			<input type="radio" name="Member_Ispic" <?php echo $row_Data['Member_Ispic']==1?'CHECKED':''; ?> value='1'>是
			<input type="radio" name="Member_Ispic" <?php echo $row_Data['Member_Ispic']==0?'CHECKED':''; ?> value='0'>否
		</td>
	   </tr>
	   <tr>
		<td class="right FormTitle03">是否已發放學員證：</td>
		<td>
			<input type="radio" name="Member_IsCard" <?php echo $row_Data['Member_IsCard']==1?'CHECKED':''; ?> value='1'>是
			<input type="radio" name="Member_IsCard" <?php echo $row_Data['Member_IsCard']==0?'CHECKED':''; ?> value='0'>否
		</td>
	   </tr>
	   <tr>
		<td class="right FormTitle03">是否要登錄公務人員研習名單：</td>
		<td>
			<input type="radio" name="Member_Isgov" <?php echo $row_Data['Member_Isgov']==1?'CHECKED':''; ?> value='1'>是
			<input type="radio" name="Member_Isgov" <?php echo $row_Data['Member_Isgov']==0?'CHECKED':''; ?> value='0'>否
		</td>
  	   </tr>
	   <tr>
		<td class="right FormTitle03">是否以手動查驗學員身分：</td>
		<td>
			<input type="radio" name="Member_Audit" <?php echo $row_Data['Member_Audit']==1?'CHECKED':''; ?> value='1'>是
			<input type="radio" name="Member_Audit" <?php echo $row_Data['Member_Audit']==0?'CHECKED':''; ?> value='0'>否
		</td>
	    </tr>
	    <tr>
		<td class="right FormTitle03">是否為課審委員：</td>
		<td>
			<input type="radio" name="classck" <?php if(in_array('課審委員',array_filter(explode(";",$row_Data['Member_Position'])))){echo 'CHECKED';} ?> value='課審委員'>是
			<input type="radio" name="classck" <?php if(!in_array('課審委員',array_filter(explode(";",$row_Data['Member_Position'])))){echo 'CHECKED';} ?> value=''>否
		</td>
	    </tr>
	    <tr>
		<td class="right FormTitle03">是否為志工：</td>
		<td>
			<input type="radio" name="vtck" <?php if(in_array('志工',array_filter(explode(";",$row_Data['Member_Position'])))){echo 'CHECKED';} ?> value='志工'>是
			<input type="radio" name="vtck" <?php if(!in_array('志工',array_filter(explode(";",$row_Data['Member_Position'])))){echo 'CHECKED';} ?> value=''>否
		</td>
   	    </tr>	    
	   <?php }else{?>
	   <input name="Member_Isidentity" type="hidden" value="<?php if(isset($row_Data['Member_Isidentity'])){echo $row_Data['Member_Isidentity'];}?>">
	   <input name="Member_Ispic" type="hidden" value="<?php if(isset($row_Data['Member_Ispic'])){echo $row_Data['Member_Ispic'];}?>">
	   <input name="Member_IsCard" type="hidden" value="<?php if(isset($row_Data['Member_IsCard'])){echo $row_Data['Member_IsCard'];}?>">
	   <input name="Member_Isgov" type="hidden" value="<?php if(isset($row_Data['Member_Isgov'])){echo $row_Data['Member_Isgov'];}?>">
	   <input name="Member_Audit" type="hidden" value="<?php if(isset($row_Data['Member_Audit'])){echo $row_Data['Member_Audit'];}?>">
	   <input name="Member_Position" type="hidden" value="<?php if(isset($row_Data['Member_Position'])){echo $row_Data['Member_Position'];}?>">
           <?php }?>
        </table>