       <table width="98%" border="0" cellpadding="5" cellspacing="2" style="max-width:800px;">
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>姓名：</td>
       <td width="30%" class="middle"><?php if(isset($row_Data['Member_UserName'])){echo $row_Data['Member_UserName'];}?></td>
       </tr>
      
       
       <tr>
		<td class="right FormTitle02" width="20%">國籍：</td>
		<td class="middle"><?php if(isset($row_Data['Foreigns']) && $row_Data['Foreigns']==1){echo '國外';}else{echo '臺灣';}?><input name="Foreigns" id="Foreigns" type="hidden" value="<?php echo $row_Data['Foreigns'];?>"><input name="user_location" id="user_location"  value="<?php if(isset($row_Data['Foreigns']) && $row_Data['Foreigns']==1){echo '2';}else{echo '1';}?>"  type="hidden"></td>
		
       </tr>
	
       <tr>
       <td class="right FormTitle03" width="20%" ><font color="red">*</font><span class="tw">身分證字號</span><span class="fg" style="display:none;">居留證編號</span>(帳號)：</td>
       <td class="middle"><?php echo $row_Data['Member_Identity'];?></div></td>
       </tr>
       
       
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02" ><font color="red">*</font>國別(country)：</td>
		<td><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Country'])){echo $row_Data['Member_Country'];}}else{?><input type="text" name="Member_Country" id="Member_Country" value="<?php if(isset($row_Data['Member_Country'])){echo $row_Data['Member_Country'];}?>"><?php }?></td>
	   </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02" ><font color="red">*</font>護照英文姓名：</td>
		<td><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Rename'])){echo $row_Data['Member_Rename'];}}else{?><input type="text" name="Member_Rename" id="Member_Rename" value="<?php if(isset($row_Data['Member_Rename'])){echo $row_Data['Member_Rename'];}?>"><?php }?></td>
	   </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02"><font color="red">*</font>是否為新住民：</td>
		<td>
			<?php if(isset($row_Data['Member_Isresident']) && $row_Data['Member_Isresident']==1){ echo '是';}else{echo '否';}?> 
		</td>
	</tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>密碼：</td>
       <td class="middle"><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Password'])){for($p=0;$p<mb_strlen($row_Data['Member_Password'],'utf-8');$p++){echo '*';}}}else{?><input type="hidden"  name="Ori_Member_Password" id="Ori_Member_Password" value="<?php if(isset($row_Data['Member_Password'])){echo $row_Data['Member_Password'];}?>" ><input type="password"   name="Member_Password" placeholder="請填入5碼以上密碼" id="Member_Password" value="<?php if(isset($row_Data['Member_Password'])){echo $row_Data['Member_Password'];}?>" required><span style="display:inline-block"></span><span class="Password_Format">請填入5碼以上密碼</span><?php }?></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>身分註記：</td>
       <td class="middle"><?php if(isset($add_self) && $add_self==1){echo '一般(若要更改請聯繫該社大管理員)';}else{if(isset($row_Data['Member_Type'])){ echo $row_Data['Member_Type'];}}?>
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>出身年月日：</td>
       <td class="middle"><?php if(isset($row_Data['Member_Birthday'])){echo $row_Data['Member_Birthday'];}?></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>是否為原住民：</td>
       <td class="middle">
	<?php if(isset($row_Data['Member_Isindigenous']) && $row_Data['Member_Isindigenous']==1){ echo '是';}else{echo '否';}?> 
       
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>性別：</td>
       <td class="middle">
       <?php if(isset($row_Data['Member_Sex']) && $row_Data['Member_Sex']=='男'){ echo $row_Data['Member_Sex'];}?></td>
       </tr>
       
     
   
	<tr>
	<td class="right FormTitle02 middle">通訊地址：</td>
        <td  class="middle">
	<?php if(isset($add_self) && $add_self==1){?>
		郵遞區號：<?php if(isset($row_Data['Postal_Code']) && $row_Data['Postal_Code']<>""){echo $row_Data['Postal_Code'];} echo '<br>';if(isset($row_Data['County_Cate'])){echo $row_Data['County_Cate']; }if(isset($row_Data['County_Name'])){echo $row_Data['County_Name']; }if(isset($row_Data['Member_Address'])){echo $row_Data['Member_Address'];}?>
	<?php }else{?>郵遞區號：<?php if(isset($row_Data['Postal_Code']) && $row_Data['Postal_Code']<>""){echo '<input type="hidden" id="Postal_CodeOrigin" name="Postal_CodeOrigin" style="size:5; width:50px;" value="'.$row_Data['Postal_Code'].'">';}?><span id="Postal"></span><input type="hidden" id="Postal_Code" name="Postal_Code" style="size:5; width:50px;" value="<?php echo $row_Data['Postal_Code'];?>"><br/>
             
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
             <input type="hidden" value="<?php if(isset($row_Data['County_Name'])){echo $row_Data['County_Name'];}?>" name="CountyNameData" id="CountyNameData">
	     <?php }//if($add_self==1) end?>	
		</td>
       </tr>

      
       <tr>
       <td class="right FormTitle02" width="20%">email：</td>
       <td  class="middle"><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Email'])){echo $row_Data['Member_Email'];}}else{?><input type="email" name="Member_Email" id="Member_Email" style="width:60%;" value="<?php if(isset($row_Data['Member_Email'])){echo $row_Data['Member_Email'];}?>" ><?php }?></td>
       </tr> 
       <tr>
       <td class="right FormTitle03" width="20%"><font color="red">*</font>電話：</td>
       <td class="middle">
           <table cellpadding="5" cellspacing="0">
            <tr>
            <td class="right">行動電話：</td>
		    <td><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Phone'])){echo $row_Data['Member_Phone'];}}else{?><input type="tel" name="Member_Phone" id="Member_Phone"  placeholder="請輸入行動電話" value="<?php if(isset($row_Data['Member_Phone'])){echo $row_Data['Member_Phone'];}?>"/><br/>(格式:0912123456)<?php }?>
            </td>
            </tr>
            <tr>
            <td class="right">
			室內電話：</td>
            <td><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Tel'])){echo $row_Data['Member_Tel'];}}else{?><input type="tel" name="Member_Tel" id="Member_Tel" placeholder="請輸入室內電話"   value="<?php if(isset($row_Data['Member_Tel'])){echo $row_Data['Member_Tel'];}?>"/><br/>(格式:031234567)<?php }?>
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
			<td><?php if(isset($add_self) && $add_self==1){if(isset($Member_CTel)){echo $Member_CTel;}echo '分機';if(isset($Member_CTel2)){echo $Member_CTel2;} }else{?><input type="tel" name="Member_CTel" id="Member_CTel"   placeholder="請輸入公司電話" value="<?php if(isset($Member_CTel)){echo $Member_CTel;}?>"/>
                分機
                <input type="text" name="Member_CTel2" id="Member_CTel2" style="width:70px" value="<?php if(isset($Member_CTel2)){echo $Member_CTel2;}?>" />
                <br/>(格式:031234567分機165) <?php }?>
			</td>
            </tr>
            </table>
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%">學歷：</td>
       <td class="middle">
	<?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Edu_Name'])){echo $row_Data['Edu_Name'];}}else{?>
	<select name="Edu_ID" id="Edu_ID" > 
                      <option value="">=請選擇=</option>
                      <?php do{?>
					  <option value="<?php echo $row_Cate2['Edu_ID']?>" <?php if(isset($row_Data['Edu_ID']) && $row_Data['Edu_ID']==$row_Cate2['Edu_ID']){echo 'selected';}?>><?php echo $row_Cate2['Edu_Name'];?></option>
		      <?php }while($row_Cate2=mysql_fetch_assoc($Cate2))?>
	</select>
	<?php }?>
	</td>
       </tr>
       <tr>
		<td class="right FormTitle02">學區：</td>
		<td><?php if(isset($add_self) && $add_self==1){$area_key = array_search($row_Data['Member_Area'], $learnlocation_id);if(isset($area_key)){echo $learnlocation_name[$area_key];}}else{?>
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
			<?php }?>
		</td>
	   </tr>
       <tr>
       <td class="right FormTitle02" width="20%">職業類別：</td>
       <td class="middle">
	<?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Job_Name'])){echo $row_Data['Job_Name'];}echo ' 職稱：';if(isset($row_Data['Job_Title'])){echo $row_Data['Job_Title'];}}else{?>
	<select name="Job_ID" id="Job_ID" >
                      <option value="">=請選擇=</option>
                      <?php do{?>
					  <option value="<?php echo $row_Cate3['Job_ID']?>" <?php if(isset($row_Data['Job_ID']) && $row_Data['Job_ID']==$row_Cate3['Job_ID']){echo 'selected';}?>><?php echo $row_Cate3['Job_Name'];?></option>
					  <?php }while($row_Cate3=mysql_fetch_assoc($Cate3))?></select>
                      職稱：<input type="text" name="Job_Title" id="Job_Title" value="<?php if(isset($row_Data['Job_Title'])){echo $row_Data['Job_Title'];}?>">
	<?php }?>
	</td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%">服務單位：</td>
       <td class="middle"><?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Unitname'])){echo $row_Data['Member_Unitname'];}}else{?><input type="text" name="Member_Unitname" id="Member_Unitname" value="<?php if(isset($row_Data['Member_Unitname'])){echo $row_Data['Member_Unitname'];}?>"><?php }?></td>
       </tr>
      
       <tr>
		<td class="right FormTitle02">電子票證資訊：</td>
		<td>
		<?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_Card'])){echo $row_Data['Member_Card'];} echo '卡號：';if(isset($row_Data['Member_CardNo'])){echo $row_Data['Member_CardNo'];}}else{?>
			<select name="Member_Card" id="Member_Card" onchange="chosecard(this);">
				<option value="">無</option>
				<option value="悠遊卡" <?php if(isset($row_Data['Member_Card']) && $row_Data['Member_Card']=='悠遊卡'){echo 'selected';}?>>悠遊卡</option>
				<option value="一卡通" <?php if(isset($row_Data['Member_Card']) && $row_Data['Member_Card']=='一卡通'){echo 'selected';}?>>一卡通</option>
			</select>
			<div style="display:none" id="user_cardnumber">
		    &nbsp;卡號：<input type="text" name="Member_CardNo" value="<?php if(isset($row_Data['Member_CardNo'])){echo $row_Data['Member_CardNo'];}?>">
		<?php }?>
			</div>
		</td>
	   </tr>
       <tr>
       <td class="right FormTitle03" width="20%"><font color="red">*</font>緊急聯絡人：</td>
       <td class="middle">
	<?php if(isset($add_self) && $add_self==1){echo '姓名：';if(isset($row_Data['Emergency_Person'])){echo $row_Data['Emergency_Person'];}echo '<br/>關係：';if(isset($row_Data['Emergency_Relation'])){echo $row_Data['Emergency_Relation'];}echo '<br/>聯絡人電話：';if(isset($row_Data['Emergency_Person_Tel'])){echo $row_Data['Emergency_Person_Tel'];}}else{?>
            姓名：<input type="text" name="Emergency_Person" id="Emergency_Person"  value="<?php if(isset($row_Data['Emergency_Person'])){echo $row_Data['Emergency_Person'];}?>" required><br/>
			關係：<input type="text" name="Emergency_Relation" id="Emergency_Relation"  value="<?php if(isset($row_Data['Emergency_Relation'])){echo $row_Data['Emergency_Relation'];}?>" required><br/>
			聯絡人電話：
			<input type="tel" name="Emergency_Person_Tel" id="Emergency_Person_Tel" pattern='\d{9-10}' placeholder="請輸入行動電話"  required  value="<?php if(isset($row_Data['Emergency_Person_Tel'])){echo $row_Data['Emergency_Person_Tel'];}?>"/><br/>
	<?php }?>
	</td>
       </tr>
       <tr>
	   <td class="right FormTitle03">經由何種管道得知招生訊息：</td>
	   <td>
	
		<?php 	if(isset($row_Data['Member_News'])){
				$Member_NewsArray=explode(";",$row_Data['Member_News']);
			}else{
				$Member_NewsArray=array();
			}?>
			<?php if(isset($add_self) && $add_self==1){if(isset($row_Data['Member_News'])){echo str_replace(";","、",$row_Data['Member_News']);}}else{?>
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
			<?php }?>
		</td>
	    </tr>	   
        </table>