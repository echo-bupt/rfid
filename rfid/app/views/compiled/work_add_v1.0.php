<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/work.js"></script>
<body>
<form id="tool" action="/work/add" method="post">
<div id="map">
	<span class='title'>作业设置添加</span>
	<span style='float:right;'>作业编号:<input type='text'class='putCon' name='w_number'value="<?php echo date('Ymd')?>"/></span>
</div>
<div id="content">
	<table class='table table-striped table-bordered' style='text-align:center;'>
		<thead>
			<tr>
				<th>序号</th>
				<th>钻具类型</th>
				<th>数量(根)</th>
				<th>钢级</th>
				<th>型号(英寸)</th>
				<th>历史下井累计时间(小时)</th>
			</tr>
		</thead>
		<tbody id='tbody'>
			<tr class='conline'>
				<td>1</td>
				<td>
					<select name='cat1' class='putCon'>
						<?php 
$_fh0_data = (isset($this->scope["cats"]) ? $this->scope["cats"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['cat'])
	{
/* -- foreach start output */
?>
							<option value='<?php echo $this->scope["cat"]["cname"];?>'><?php echo $this->scope["cat"]["cname"];?></option>
						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
				<td>
					<input type='text' name='len1' class='putCon write num'/>
					<span style='color:red;display:none;'></span>
				</td>
				<td>
					<input type='text' name='mat1' class='putCon write'/>
					<span style='color:red;display:none;'></span>
				</td>
				<td>
					<input type='text' name='size1' class='putCon write num'/>
					<span style='color:red;display:none;'></span>
				</td>
				<td>
					<input type='text' name='total1' class='putCon write num'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
		</tbody>
	</table>
	<input type='hidden' id='count' value='1' name='count'/>
	<a class="btn-small-f btn-mini btn-info" style="float:right;margin-top:20px;margin-right:30px;" href="#" id='reduceLine'>-</a>
	<a class="btn-small-f btn-mini btn-info" style="float:right;margin-top:20px;margin-right:30px;" href="#" id='addLine'>+</a>
			<p style='margin-top:50px;'>
				<input type="submit" class='btn' value='确定上传'/></td>
				<input type="reset" class='btn' value='重新填写'/></td>
			</p>
	</form>
	
	
	
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>