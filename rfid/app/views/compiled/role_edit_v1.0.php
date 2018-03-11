<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<style>
h2{
	float: left;
	line-height: 30px;
	width:100px;
	margin-left: 0px;
}
</style>
<body>
<div id="map">
	<p class='title'>更改权限</h2>
</div>
<div id="content">
	<form id="tool" action="/role/edit" method="post">
		<input name="rid" type='hidden' value='<?php echo $this->scope["data"]["rid"];?>'/>
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="40%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>用户角色</td>
				<td>
					<input type="text" name="rname" style='width:150px;height:30px;' value='<?php echo $this->scope["data"]["rname"];?>'/>
				</td>
			</tr>
			<tr>
				<td>所属权限</td>
				<td>
					<h2>
						<label>
							<span>钻具管理</span>
							<input type="checkbox" name="right[]" value='tool' <?php if (in_array("tool", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>/>
						</label>
					</h2>
					<h2>
						<label>
							<span>单位管理</span>
							<input type="checkbox" name="right[]" value='unit'<?php if (in_array("unit", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>//>			
						</label>
					</h2>
					<h2>
						<label>
							<span>物资管理</span>
							<input type="checkbox" name="right[]" value='dril' <?php if (in_array("dril", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>//>					
						</label>
					</h2>
					<h2>
						<label>
							<span>现场管理</span>
							<input type="checkbox" name="right[]" value='work'<?php if (in_array("work", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>//>				
						</label>
					</h2>
					<h2>
						<label>
							<span>维修管理</span>
							<input type="checkbox" name="right[]" value='service'<?php if (in_array("service", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>//>					
						</label>
					</h2>
					<h2>
						<label>
							<span>信息普查</span>
							<input type="checkbox" name="right[]" value='information'<?php if (in_array("information", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>//>				
						</label>
					</h2>
					<h2>
						<label>
							<span>系统管理</span>
							<input type="checkbox" name="right[]" value='system'<?php if (in_array("system", (isset($this->scope["data"]['role']) ? $this->scope["data"]['role']:null))) {
?> checked='checked'<?php 
}?>//>			
						</label>
					</h2>
				</td>
			</tr>
			<tr>
				<td><input type="submit" class='btn' value='确定更改'/></td>
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