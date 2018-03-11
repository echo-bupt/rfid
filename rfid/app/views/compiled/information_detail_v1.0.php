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
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/information.js"></script>
<body>
<div id="map" style='vertical-align:center;'>
	<span class='title'>钻具详情快捷查询</span>
</div>
<div id="content" style="text-align:center;">
	<div id='notice'>
		<h2>距下一次钻具检修时间:&nbsp;&nbsp;
			<input type='text' name='checkName' value="30" id='days' style='width:160px;height:30px;text-align:center;'/>&nbsp;&nbsp;天
		</h2>
	</div>
	<div style='width:800px;text-align:center;margin:0 auto;'>
		<input type="text" name="epc" class='dril' id='epc'/>
		<input type='button' id='single' value="扫描" class='green' style="width:10%;height:35px;"/>
	</div>
	<p style='height:30px;float:right;padding-top:10px;margin-right:20px;display:none;' id='operate'>
			<input type='button' id='ensure' class='btn' value='确认查询'/>
			<input type='button' id='error' class='btn' value='放弃查询'/>
	</p>
	<div id='find'>
		
	</div>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>