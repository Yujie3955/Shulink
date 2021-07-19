<?php require_once('../../Connections/dbline.php'); ?>
<?php require_once('../../Include/web_set.php'); ?>
<?php require_once('../../Include/menu_upon_common.php'); ?>
<?php require_once('../../Include/DB_Admin_Student.php'); ?>
<?php require_once('../../Include/Html_Top_Common.php'); ?>
<?php require_once('../../Include/Html_Top_head.php'); ?>

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
        <td width="15%"><?php require_once('../../Include/Menu_AdminLeft_Student.php'); ?>
      </td>
        <td>
<div class="SubTitle"><img src="../../Icon/IconEdit.png" width="28" class="middle"> 網站管理系統</div>
	<?php if(@$_GET['Login'] == "success"){ ?>
	<script language="javascript">
	function Success_Login(){
		$('.Success_Login').fadeIn(1000).delay(2000).fadeOut(1000);
	}
	setTimeout(Success_Login,0);
    </script>
	<?php } ?>    
    <div align="center">   
        <div class="Success_Msg Success_Login" style="display:none;"><img src="../../Icon/accept.gif" alt="成功訊息" class="middle"> 您已成功登入</div></div>
        </td>
      </tr>
    </table>
    <br><br><br>
</div>      


<!--Body Layout down Start-->
<?php require_once('../../Include/Admin_Body_Layout_down.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<!--Body Layout down End-->
</body>
</html>

<?php require_once('../../Include/zz_Admin_WebSet.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>
<?php require_once('../../Include/zz_menu_upon.php'); ?>