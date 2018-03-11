<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/public.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/outFind.js"></script>
<body>
<div id="map">
	<span class='title'>查询出库记录</span>
</div>
<div id="content" style="text-align:center;">
	<div id='notice'>
		<h2>
			快捷查询钻具的设备编号:
		</h2>
	</div>
	<div style='width:800px;text-align:center;margin:0 auto;'>
		<input type="text" name="epc" class='dril' id='epc'/>
		<input type='button' id='single' value="扫描" class='green' style="width:10%;height:35px;"/>
	</div>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>