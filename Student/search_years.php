
		民國查詢:<select id="MYear" name="MYear" onchange="CMY_Check();"><option value="">請選擇年度</option><?php for($m_y=0;$m_y<((date('Y')-1911)-9);$m_y++){?>
			<option value="<?php echo (date('Y')-1911)-$m_y;?>"><?php echo (date('Y')-1911)-$m_y;?>年</option>
			<?php }?>
			</select><span id="MYear_Msg" class="FormTitle02"></span>
		
		<br/>