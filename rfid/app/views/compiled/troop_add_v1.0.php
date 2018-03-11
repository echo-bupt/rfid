<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/troop.js"></script>
<body>
<div id="map">
	<span class='title'>井队添加</span>
</div>
<div id="content">
	<form id="tool" action="/troop/add" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="40%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>所属二级单位</td>
				<td>
					<select name='uid' style='width:150px;text-align:center;height:30px;'>
						<?php 
$_fh0_data = (isset($this->scope["unit"]) ? $this->scope["unit"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['unitson'])
	{
/* -- foreach start output */
?>
						<option value='<?php echo $this->scope["unitson"]["uid"];?>'<?php if ((isset($this->scope["unitson"]["uid"]) ? $this->scope["unitson"]["uid"]:null) == (isset($this->scope["uid"]) ? $this->scope["uid"] : null)) {
?> selected="selected"<?php 
}?>><?php echo $this->scope["unitson"]["uname"];?></option>
						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
			</tr>
			<tr>
				<td>井队编号</td>
				<td>
					<input type="text" name="tname" style='width:150px;height:30px;' class='write'/>
					<span class='notice' style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>添加时间</td>
				<td>
					<input type="text" name='time' class='write' style='width:150px;text-align:center;height:30px;'value="<?php echo date('Y-m-d H:i:s') ?>"/>
					<span class='notice' style='color:red;display:none;'></span>
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