<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<body>
<div id="map">
	<span class='title'>管理员添加</span>
</div>
<div id="content">
	<form id="tool" action="/system/add" method="post">
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
					<input type="text" name="username" style='width:150px;height:30px;'/>
				</td>
			</tr>
			<tr>
				<td>密码</td>
				<td>
					<input type="password" name="password" style='width:150px;height:30px;'/>
				</td>
			</tr>
			<tr>
				<td>请再输入一次密码</td>
				<td>
					<input type="password" name="password" style='width:150px;height:30px;'/>
				</td>
			</tr>
			<tr>
				<td>用户角色</td>
				<td>
					<select name='role' style='width:150px;height:30px;text-align:center;'>
						<option value='1'>超级管理员</option>
						<option value='2'>物资管理员</option>
						<option value='1'>现场管理员</option>
						<option value='1'>维修管理员</option>
					</select>
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