<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/send.js"></script>
<script type="text/javascript" src='/third/DatePicker/WdatePicker.js'></script>
<body>
<div id="content">
	<form id="tool" action="/dril/add" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="20%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<?php 
$_fh1_data = (isset($this->scope["pdata"]) ? $this->scope["pdata"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['v'])
	{
/* -- foreach start output */
?>
			<tr>
				<td width="30%"><?php echo $this->scope["v"]["nickname"];?></td>
				<td>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'cid') {
?>
						<select name='cid' style="width:155px;height:30px;text-align:center;">
						<?php 
$_fh0_data = (isset($this->scope["sort"]) ? $this->scope["sort"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['val'])
	{
/* -- foreach start output */
?>
							<?php if ((isset($this->scope["val"]["cid"]) ? $this->scope["val"]["cid"]:null) == (isset($this->scope["data"]["cid"]) ? $this->scope["data"]["cid"]:null)) {
?>
							<option value='<?php echo $this->scope["val"]["cid"];?>' selected='selected'><?php echo $this->scope["val"]["cname"];?></option>	
							<?php 
}
else {
?>
							<option value='<?php echo $this->scope["val"]["cid"];?>'><?php echo $this->scope["val"]["cname"];?></option>
							<?php 
}?>

						<?php 
/* -- foreach end output */
	}
}?>

						</select>
					<?php 
}
else {
?>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'pro_time' || (isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'add_time') {
?>
					<input id="d11" type="text" style="height:30px;" name="<?php echo $this->scope["v"]["pname"];?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value='<?php echo $this->readVar("data.".(isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null));?>'/>
					<?php 
}
else {
?>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == "number") {
?>
					<input type="text" name="<?php echo $this->scope["v"]["pname"];?>" class='dril' value="<?php echo date('Ymd');?>"/>
					<?php 
}
else {
?>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == "islive") {
?>
					<input type="text" name="<?php echo $this->scope["v"]["pname"];?>" class='dril' value="否"/>
					<?php 
}
else {
?>
					<input type="text" name="<?php echo $this->scope["v"]["pname"];?>" class='dril'<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'epc') {
?>id="epc" value="" <?php 
}
else {
?> value='<?php echo $this->readVar("data.".(isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null));?>'<?php 
}?>style="<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'epc') {
?>width:70%;<?php 
}
else {
?>width:80%<?php 
}?>"/>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'epc') {
?>
					<input type='button' id='single' value="扫描" class='green' style="width:10%;height:35px;"/>
					<?php 
}?>

					<?php 
}?>

					<?php 
}?>

					<?php 
}?>

					<?php 
}?>

				</td>
			</tr>
			<?php 
/* -- foreach end output */
	}
}?>

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