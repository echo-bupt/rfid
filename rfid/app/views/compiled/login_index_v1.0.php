<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<link rel="stylesheet" href="<?php echo $this->scope["TPL"];?>/css/login.css" />
	<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
	<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/login.js"></script>

	<title></title>
</head>
<body>
	<div id="divBox">
		<form action="/Login/login" method="POST" id="login">
			<input type="text" id="userName" name="userName"/>
			<input type="password" id="psd" name="psd"/>
			<input type="" value="" id="verify" name="verify"/>
			<input type="submit" id="sub" value=""/>
			<img src="/Login/code" id="verify_img" style="margin-top:4px;"width="80px" height="20px"/>
		</form>
		<div class="four_bj">
			
			<p class="f_lt"></p>
			<p class="f_rt"></p>
			<p class="f_lb"></p>
			<p class="f_rb"></p>
		</div>

		<ul id="peo">
			
		</ul>
		<ul id="psd">
			
		</ul>
		<ul id="ver">
			
		</ul>
	</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>