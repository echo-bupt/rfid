<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/user.js"></script>
<body>
<div id="map">
	<span class='title'>管理员添加</span>
</div>
<div id="content">
	<form id="tool" action="/user/add" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="40%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>用户名</td>
				<td>
					<input type="text" name="username" class='write' style='width:150px;height:30px;'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>密码</td>
				<td>
					<input type="password" name="password" class='write' style='width:150px;height:30px;'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>请再输入一次密码</td>
				<td>
					<input type="password" name="password2" class='write' style='width:150px;height:30px;'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>用户角色</td>
				<td>
					<select name='role' style='width:150px;height:30px;text-align:center;'>
						<?php 
$_fh0_data = (isset($this->scope["data"]) ? $this->scope["data"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['role'])
	{
/* -- foreach start output */
?>
						<option value='<?php echo $this->scope["role"]["rid"];?>'><?php echo $this->scope["role"]["rname"];?></option>
						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
			</tr>	
			<tr>
				<td>本角色的超级管理员</td>
				<td>
					<input type="radio" name='super' value='0' checked='checked'/>否
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="radio" name='super' value='1'/>是
				</td>
			</tr>
			<tr>
				<td><input type="submit" class='btn' value='确定上传'/></td>
				<td><input type="reset" class='btn' value='重新填写'/></td>
			</tr>
		</tbody>
	</table>
	</form>
	
	
	
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>