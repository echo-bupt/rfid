<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title></title>
	<link rel="stylesheet" href="<?php echo $this->scope["TPL"];?>/css/public.css" />
	<link rel="stylesheet" href="<?php echo $this->scope["TPL"];?>/css/copy.css" />
</head>
<body>
	<table class="table">
		<tr>
			<td colspan='2' class="th"><span class="span_server" style="float:left">&nbsp</span>服务器信息</td>
		</tr>

		<tr>
			<td>服务器环境</td>
            <td><?php echo $_SERVER['SERVER_SOFTWARE']?></td>
		</tr>
		<tr>
			<td>服务器IP</td>
			<td><?php echo $_SERVER['SERVER_ADDR']?></td>
		</tr>
		<tr>
			<td>数据库信息</td>
			<td><?php echo @mysql_get_server_info()?></td>
		</tr>
		<tr>
			<td colspan='2' class="th"><span class="span_people">&nbsp</span>管理员信息</td>
		</tr>
		<tr>
			<td>用户名</td>
			<td><?php echo $_SESSION['username']?></td>
		</tr>
		<tr>
			<td>登录时间</td>
			<td><?php echo date('Y-m-d H:i:s')?></td>
		</tr>
		<tr>
			<td>登陆IP</td>
			<td>127.0.0.1</td>
		</tr>
		
</table>
</body>
</html><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>