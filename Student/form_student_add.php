       <table width="98%" border="0" cellpadding="5" cellspacing="2" style="max-width:800px;">
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>姓名：</td>
       <td width="30%" class="middle"><input type="text" name="Title" id="Title" required ></td>
       </tr>
       <tr>
		<td class="right FormTitle02"><font color="red">*</font>國籍：</td>
		<td>
			<select name="user_location" id="user_location" onload="twfgck();" onchange="twfgck(this);"  required>
			　	<option value="1" >臺灣</option>
				<option value="2" >國外</option>
			</select>
            <input type="hidden" name="Foreigns" id="Foreigns" value="0">
		</td>
	   </tr>
       <tr>
       <td class="right FormTitle03" width="20%" ><font color="red">*</font><span class="tw">身分證字號</span><span class="fg" style="display:none;">居留證編號</span>(帳號)：</td>
       <td class="middle"><input type="text" name="Member_Identity" id="Member_Identity" required onKeyUp="CheckAgain();" style="width:100%;max-width:150px;" autocomplete="off"> 
    <input type="button" value="檢查身分" class="Button_General" onClick="callByAJAX2();"><div id="RepeatAccount" style="display:inline-block; margin-top:7px;"></div></td>
       </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02" ><font color="red">*</font>國別(country)：</td>
		<td><input type="text" name="Member_Country" id="Member_Country"></td>
	   </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02" ><font color="red">*</font>護照英文姓名：</td>
		<td><input type="text" name="Member_Rename" id="Member_Rename"></td>
	   </tr>
       <tr class="fg1" style="display:none;">
		<td class="right FormTitle02"><font color="red">*</font>是否為新住民：</td>
		<td>
			<input type="radio" name="Member_Isresident"  value="1">是
			<input type="radio" name="Member_Isresident"  checked value="0">否
		</td>
	</tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>密碼：</td>
       <td class="middle"><input type="text"  name="Member_Password" placeholder="請填入5碼以上密碼" id="Member_Password" autocomplete="off" required><span style="display:inline-block"></span><span class="Password_Format">請填入5碼以上密碼</span></td>
       </tr>
       
      
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>出身年月日：</td>
       <td class="middle">
		<?php require_once("search_years.php");?>
		<div class="DateStyle">
        	    <div class='input-group date picker_date' >
            	<input name="Member_Birthday" type="text" id="Member_Birthday" required data-format="yyyy/MM/dd"  class="form-control"/>
            	<span class="input-group-addon">
            		<span class="glyphicon glyphicon-calendar"></span>
          		</span>
            	</div>
        	 </div><span style="display:inline-block">(格式：西元年/月/日)</span><span class="Msg_Date">請輸入正確日期格式</span></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>是否為原住民：</td>
       <td class="middle">
        <input type="radio" name="Member_Isindigenous"  value="1">是
	<input type="radio" name="Member_Isindigenous"  checked value="0">否
       </td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%"><font color="red">*</font>性別：</td>
       <td class="middle">
       <select name="Member_Sex" id="Member_Sex">
       <option value="男">男</option>
       <option value="女">女</option>
       <option value="其他">其他</option></select></td>
       </tr>
       <tr>      
      <td class="right FormTitle03 middle"><font color="red">*</font>通訊地址：</td>
      <td  class="middle">郵遞區號：<span id="Postal"></span><input type="hidden" id="Postal_Code" name="Postal_Code" style="size:5; width:50px;" ><br/><select name="County_ID" id="County_ID" onChange="callByAJAX();"><option value="">=請選擇縣市=</option>
      <?php if($totalRows_County>0){
		       do{
				   ?><option value="<?php echo $row_County['County_Cate'];?>"><?php echo $row_County['County_Cate'];?></option>
				   
				   
				   
				   
				   <?php }while($row_County = mysql_fetch_assoc($County));
	  
	  
	  
	  }?>
      
      
      </select><span class="Msg_County_ID">請選擇縣市</span>&nbsp;<select name="County_Name" id="County_Name" onChange="callPostal();">
      <option value="" >=請選擇區域=</option>
    </select><span class="Msg_County_Name">請選擇區域</span>
     
   
    <input name="Member_Address" type="text" id="Member_Address" style="width:60%;"  required></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%">email：</td>
       <td  class="middle"><input type="email" name="Member_Email" id="Member_Email" style="width:60%;" ></td>
       </tr>     
       
       
       <tr>
       <td class="right FormTitle03" width="20%"><font color="red">*</font>電話：</td>
       <td class="middle">
           <table cellpadding="5" cellspacing="0">
            <tr>
            <td class="right">行動電話：</td>
		    <td><input type="tel" name="Member_Phone" id="Member_Phone"  placeholder="請輸入行動電話" autocomplete="off"/><br/>(格式:0912123456)
            </td>
            </tr>
            <tr>
            <td class="right">
			室內電話：</td>
            <td><input type="tel" name="Member_Tel" id="Member_Tel"  placeholder="請輸入室內電話"  autocomplete="off" /><br/>(格式:031234567)
            </td>
            </tr>
            <tr>
            <td class="right">公司電話：</td>
			<td><input type="tel" name="Member_CTel" id="Member_CTel" autocomplete="off"  placeholder="請輸入公司電話" />
                分機
                <input type="text" name="Member_CTel2" id="Member_CTel2" style="width:70px"  />
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
					  <option value="<?php echo $row_Cate2['Edu_ID']?>"><?php echo $row_Cate2['Edu_Name'];?></option>
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
						<option value="<?php echo $learnlocation_id[$learnlocationcont];?>" ><?php echo $learnlocation_name[$learnlocationcont]; ?></option>
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
					  <option value="<?php echo $row_Cate3['Job_ID']?>"><?php echo $row_Cate3['Job_Name'];?></option>
					  <?php }while($row_Cate3=mysql_fetch_assoc($Cate3))?></select>
                      職稱：<input type="text" name="Job_Title" id="Job_Title"></td>
       </tr>
       <tr>
       <td class="right FormTitle02" width="20%">服務單位：</td>
       <td class="middle"><input type="text" name="Member_Unitname" id="Member_Unitname"></td>
       </tr>
      
       <tr>
		<td class="right FormTitle02">電子票證資訊：</td>
		<td>
			<select name="Member_Card" id="Member_Card" onchange="chosecard(this);">
				<option value="">無</option>
				<option value="悠遊卡">悠遊卡</option>
				<option value="一卡通">一卡通</option>
			</select>
			<div style="display:none" id="user_cardnumber">
		    &nbsp;卡號：<input type="text" name="Member_CardNo">
			</div>
		</td>
	   </tr>
       <tr>
       <td class="right FormTitle03" width="20%"><font color="red">*</font>緊急聯絡人：</td>
       <td class="middle">
            姓名：<input type="text" name="Emergency_Person" id="Emergency_Person" required><br/>
			關係：<input type="text" name="Emergency_Relation" id="Emergency_Relation" required><br/>
			聯絡人電話：
			<input type="tel" name="Emergency_Person_Tel" id="Emergency_Person_Tel" placeholder="請輸入行動電話"  required /><br/></td>
       </tr>
       
       <!--<tr>
       <td class="right FormTitle02" width="20%">是否寄送通知：</td>
       <td  class="middle"></td>
       </tr>-->
       <input type="hidden" name="Member_Inform" id="Member_Inform" value="1"  >
       <tr>
	   <td class="right FormTitle03">經由何種管道得知招生訊息：</td>
	   <td>
				<input type="checkbox" name="Member_News[]" value="夾報、派報" class="where_get_msg" id="from_clip"> 夾報、派報<br>
				<input type="checkbox" name="Member_News[]" value="社大臉書粉絲頁" class="where_get_msg" id="from_district"> 社大臉書粉絲頁<br>
				<input type="checkbox" name="Member_News[]" value="路過看到" class="where_get_msg" id="from_extra1"> 路過看到<br>
				<input type="checkbox" name="Member_News[]" value="其他" class="where_get_msg" id="from_extra2"> 其他<br>
				<input type="checkbox" name="Member_News[]" value="講師介紹" class="where_get_msg" id="from_extra3"> 講師介紹<br>
				<input type="checkbox" name="Member_News[]" value="成果展" class="where_get_msg" id="from_fair"> 成果展<br>
				<input type="checkbox" name="Member_News[]" value="店家索取宣傳單" class="where_get_msg" id="from_fly"> 店家索取宣傳單<br>
				<input type="checkbox" name="Member_News[]" value="舊生介紹" class="where_get_msg" id="from_friend"> 舊生介紹<br>
				<input type="checkbox" name="Member_News[]" value="縣府網站" class="where_get_msg" id="from_my_web"> 縣府網站<br>
				<input type="checkbox" name="Member_News[]" value="報紙廣告" class="where_get_msg" id="from_newspaper"> 報紙廣告<br>
				<input type="checkbox" name="Member_News[]" value="紅布條或帆布廣告" class="where_get_msg" id="from_red"> 紅布條或帆布廣告<br>
				<input type="checkbox" name="Member_News[]" value="公部門服務台" class="where_get_msg" id="from_service"> 公部門服務台<br>
				<input type="checkbox" name="Member_News[]" value="舊生" class="where_get_msg" id="from_student"> 舊生<br>
				<input type="checkbox" name="Member_News[]" value="社大官方網站" class="where_get_msg" id="from_tao_web"> 社大官方網站<br>
				<input type="checkbox" name="Member_News[]" value="電視廣告" class="where_get_msg" id="from_tv"> 電視廣告
		</td>
	    </tr>
        </table>