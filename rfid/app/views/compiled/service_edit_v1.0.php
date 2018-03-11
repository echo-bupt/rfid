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
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/service_add.js"></script>
<script type="text/javascript" src='/third/DatePicker/WdatePicker.js'></script>
<body>
<div id="map">
	<span class='title'>编辑检修记录</span>
</div>
<div id="content">
	<form action='/service/edit' method='POST'>
	<input type='hidden' name='sid' value='<?php echo $this->scope["data"]["sid"];?>'/>
	<table class='table table-striped table-bordered'>
		<tdead>
				<th style='width:30%;'>名称</th>
				<th>值</th>
		</tdead>
		<tbody id='tbody'>
			<tr class='conline'>
				<td>设备编号</td>
				<td>
					<input type="text" name="epc" class='dril write' id='epc' value='<?php echo $this->scope["data"]["epc"];?>'/>
					<span style='color:red;display:none;'></span>
					<input type='button' id='single' value="扫描" class='green' style="width:10%;height:35px;"/>
				</td>
			</tr>
			<tr>
				<td>检修日期</td>
				<td>
					<input id="d11" type="text" style="height:30px;" class='write' name="time" value='<?php echo $this->scope["data"]["time"];?>' onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>检修地点</td>
				<td>
					<input type='text' name='addr' class='dril write' value='<?php echo $this->scope["data"]["addr"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>检测人员</td>
				<td>
					<input type='text' name='checker' class='putCon_1 write' value='<?php echo $this->scope["data"]["checker"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>维修人员</td>
				<td>
					<input type='text' name='fixer' class='putCon_1 write' value='<?php echo $this->scope["data"]["fixer"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<tr>
				<td>检修内容</td>
				<td>
						<?php 
$_fh0_data = (isset($this->scope["checkContent"]) ? $this->scope["checkContent"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['content'])
	{
/* -- foreach start output */
?>
							<label id='content' style='margin-right:20px;'>
									<?php if (in_array((isset($this->scope["content"]) ? $this->scope["content"] : null), explode(',', (isset($this->scope["data"]['content']) ? $this->scope["data"]['content']:null)))) {
?>
								<input type='checkbox' value='<?php echo $this->scope["content"];?>' checked='checked' name='contents[]'/>
								<?php 
}
else {
?>
								<input type='checkbox' value='<?php echo $this->scope["content"];?>' name='contents[]'/>
								<?php 
}?>

									<?php echo $this->scope["content"];?>

							</label>
						<?php 
/* -- foreach end output */
	}
}?>

						<input type='text' name='content' class='dril' style='display:none;'/>
						<a href='javascript:void(0);' class='other'>其它</a>
						<a href='javascript:void(0);'  class='choose' style='display:none;'>返回选择</a>
				</td>
			</tr>
			<tr>
				<td>评级</td>
				<td>
					<select class='putCon' name='class'>
						<?php 
$_fh1_data = (isset($this->scope["class"]) ? $this->scope["class"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['content'])
	{
/* -- foreach start output */
?>
						<?php if ((isset($this->scope["content"]) ? $this->scope["content"] : null) == (isset($this->scope["data"]['class']) ? $this->scope["data"]['class']:null)) {
?>
							<option value='<?php echo $this->scope["content"];?>' selected='selected'><?php echo $this->scope["content"];?></option>
						<?php 
}
else {
?>
							<option value='<?php echo $this->scope["content"];?>'><?php echo $this->scope["content"];?></option>
						<?php 
}?>

						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
			</tr>
			<tr>
				<td>状态评估</td>
				<td>
					<select class='putCon' name='state'>
						<?php 
$_fh2_data = (isset($this->scope["state"]) ? $this->scope["state"] : null);
if ($this->isArray($_fh2_data) === true)
{
	foreach ($_fh2_data as $this->scope['content'])
	{
/* -- foreach start output */
?>
						<?php if ((isset($this->scope["content"]) ? $this->scope["content"] : null) == (isset($this->scope["data"]['state']) ? $this->scope["data"]['state']:null)) {
?>
							<option value='<?php echo $this->scope["content"];?>' selected='selected'><?php echo $this->scope["content"];?></option>
						<?php 
}
else {
?>
							<option value='<?php echo $this->scope["content"];?>'><?php echo $this->scope["content"];?></option>
						<?php 
}?>

						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
			</tr>
			<tr>
				<td>使用建议</td>
				<td>
						<?php 
$_fh3_data = (isset($this->scope["suggest"]) ? $this->scope["suggest"] : null);
if ($this->isArray($_fh3_data) === true)
{
	foreach ($_fh3_data as $this->scope['content'])
	{
/* -- foreach start output */
?>
							<label id='content' style='margin-right:20px;'>
							  <?php if (in_array((isset($this->scope["content"]) ? $this->scope["content"] : null), explode(',', (isset($this->scope["data"]['suggest']) ? $this->scope["data"]['suggest']:null)))) {
?>
								<input type='checkbox' value='<?php echo $this->scope["content"];?>' checked='checked' name='suggests[]'/>
								<?php 
}
else {
?>
								<input type='checkbox' value='<?php echo $this->scope["content"];?>' name='suggests[]'/>
								<?php 
}?>

								<?php echo $this->scope["content"];?>

							</label>
						<?php 
/* -- foreach end output */
	}
}?>

						<input type='text' name='suggest' class='dril' style='display:none;'/>
						<a href='javascript:void(0);' class='other'>其它</a>
						<a href='javascript:void(0);'  class='choose' style='display:none;'>返回选择</a>
				</td>
			</tr>
			<tr>
				<td>下一次检修日期</td>
				<td>
					<input id="d11" type="text" style="height:30px;" name="next" class='write' value='<?php echo $this->scope["data"]["next"];?>'onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
		</tbody>
	</table>
			<p style='margin-top:50px;'>
				<input type="submit" class='btn' value='确定上传'/></td>
				<input type="reset" class='btn' value='重新填写'/></td>
			</p>
	</form>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>