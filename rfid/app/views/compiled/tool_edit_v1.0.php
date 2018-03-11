<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/tool.js"></script>
<body>
<div id="map">
	<span class='title'>分类列表</span>
</div>
<div id="content">
	<form id="goodsForm" action="/tool/edit" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="40%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>钻具名称</td>
				<td>
					<input type="text" name="cname" value="<?php echo $this->scope["data"]["cname"];?>" style='height:30px;'/>
					<input type='hidden' name='cid' value="<?php echo $this->scope["data"]["cid"];?>"/>
					<span class='notice' style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>修改时间</td>
				<td>
					<input type="text" name='time' value="<?php echo date('Y-m-d H:i:s', time());?>" style='height:30px;'/>
				</td>
			</tr>	
			<tr>
				<td><input type="submit" class='btn' value='确定上传' /></td>
				<td><input type="reset" class='btn' value='重新填写' /></td>
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