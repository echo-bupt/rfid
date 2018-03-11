<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/property.js"></script>
<body>
<div id="map">
	<span class='title'>属性添加</span>
</div>
<div id="content">
	<form id="tool" action="/property/add" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="40%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>钻具属性</td>
				<td>
					<input type="text" name="pname" style='height:30px;' class='write'/>
					<span class='notice'>(*数据库存储的字段名,请使用英文填写,比如:number)</span>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>
			<tr>
				<td>属性别名</td>
				<td>
					<input type="text" name="nickname" style='height:30px;' class='write'/>
					<span class='notice'>(*钻具属性的显示提示名,建议使用中文填写,比如:编号)</span>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>
			<tr>
				<td>添加时间</td>
				<td>
					<input type="text" name='time' style='height:30px;' value="<?php echo date('Y-m-d H:i:s') ?>"/>
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