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
<div id="map" style='vertical-align:center;'>
	<span class='title'>钻具入库</span>
</div>
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
$_fh1_data = (isset($this->scope["data"]) ? $this->scope["data"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['v'])
	{
/* -- foreach start output */
?>
			<tr <?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'islive' || (isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'state') {
?> style='display:none;' <?php 
}?>>
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
							<option value='<?php echo $this->scope["val"]["cid"];?>'><?php echo $this->scope["val"]["cname"];?></option>
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
					<input id="d11" type="text" style="height:30px;"name="<?php echo $this->scope["v"]["pname"];?>"onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" class='write'/>
					<span class='notices' style='color:red;display:none;'></span>
					<?php 
}
else {
?>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == "number") {
?>
					<input type="text" name="<?php echo $this->scope["v"]["pname"];?>" class='dril write' value="<?php echo date('Ymd');?>"/>
					<span class='notices' style='color:red;display:none;'></span>
					<?php 
}
else {
?>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == "islive") {
?>
					<input type="hidden" name="<?php echo $this->scope["v"]["pname"];?>" class='dril' value="否"/>
					<?php 
}
else {
?>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == "state") {
?>
					<input type="hidden" name="<?php echo $this->scope["v"]["pname"];?>" class='dril' value="库存中"/>
					<?php 
}
else {
?>
					<input type="text" name="<?php echo $this->scope["v"]["pname"];?>" class='dril write' <?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'epc') {
?>id="epc"<?php 
}?>style="float:left;<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'epc') {
?>width:75%;<?php 
}
else {
?>width:80%<?php 
}?>"/>
					<span class='notices' style='color:red;display:none;'></span>
					<?php if ((isset($this->scope["v"]["pname"]) ? $this->scope["v"]["pname"]:null) == 'epc') {
?>
					<span id='notice' style='float:left;color:red;display:none;font-size:12px;line-height:30px;'>成功扫描n个</span>
					<input type='button' id='single' value="扫描" class='green' style="width:10%;height:38px;float:;left;margin-left:15px;margin-top:0px;"/>
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
}

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