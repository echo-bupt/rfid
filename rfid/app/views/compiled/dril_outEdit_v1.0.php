<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/out.js"></script>
<script type="text/javascript" src='/third/DatePicker/WdatePicker.js'></script>
<body>
<div id="map" style='vertical-align:center;'>
	<span class='title'>出库编辑</span>
</div>
<div id="content">
	<form id="tool" action="/dril/outEdit" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="20%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<input type="hidden" name="oid" class='dril' value='<?php echo $this->scope["data"]["oid"];?>'/>
				<td>
					编号(钢印号)
				</td>
				<td>
					<input type="text" name="epc" class='dril write' <?php if ((isset($this->scope["epc"]) ? $this->scope["epc"] : null)) {
?>value="<?php echo $this->scope["epc"];?>"<?php 
}
else {
?>value=""<?php 
}?> id='epc'/>
					<span class='notices' style='color:red;display:none;'>></span>
					<input type='button' id='single' value="扫描" class='green' style="width:10%;height:35px;"/>
				</td>
			</tr>
			<tr>
				<td width="30%">下拨二级单位</td>
				<td>
					<select name='unit' style='height:30px;width:160px;text-align:center;' id='unit'>
						<?php 
$_fh0_data = (isset($this->scope["units"]) ? $this->scope["units"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['unit'])
	{
/* -- foreach start output */
?>
							<option value='<?php echo $this->scope["unit"]["uname"];?>' <?php if ((isset($this->scope["unit"]["uname"]) ? $this->scope["unit"]["uname"]:null) == (isset($this->scope["data"]["unit"]) ? $this->scope["data"]["unit"]:null)) {
?> selected='selected'<?php 
}?>><?php echo $this->scope["unit"]["uname"];?></option>
						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
			</tr>
			<tr>
				<td width="30%">下属井队编号</td>
				<td>
					<select name='troop' style='height:30px;width:160px;text-align:center;' id='troop'>
						<?php 
$_fh1_data = (isset($this->scope["troops"]) ? $this->scope["troops"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['troop'])
	{
/* -- foreach start output */
?>
							<option value='<?php echo $this->scope["troop"]["tname"];?>' <?php if ((isset($this->scope["troop"]["tname"]) ? $this->scope["troop"]["tname"]:null) == (isset($this->scope["data"]["troop"]) ? $this->scope["data"]["troop"]:null)) {
?> selected='selected'<?php 
}?>><?php echo $this->scope["troop"]["tname"];?></option>
						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
			</tr>
			<tr>
				<td>出库办理人员</td>
				<td>
					<input type="text" name="operator" class='write' style='height:30px;width:160px;' value='<?php echo $this->scope["data"]["operator"];?>'/>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>
			<tr>
				<td>领取人员</td>
				<td>
					<input type="text" name="picker" class='write' style='height:30px;width:160px;' value='<?php echo $this->scope["data"]["picker"];?>'/>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>	
			<tr>
				<td width="30%">领取日期</td>
				<td>
					<input id="d11" type="text" style='height:30px;width:160px;' class='write' name="picktime" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value='<?php echo $this->scope["data"]["time"];?>'/>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>	
			<tr>
				<td>归还日期</td>
				<td>
					<?php if ((isset($this->scope["data"]["isback"]) ? $this->scope["data"]["isback"]:null) == 1) {
?>
					  <input id="d11" type="text" style='height:30px;width:160px;' class='write' name="backtime" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value='<?php echo $this->scope["data"]["backtime"];?>'/>
					  <?php 
}
else {
?>
					  <input id="d11" type="text" style='height:30px;width:160px;' class='write' name="backtime" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
					<?php 
}?>

					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>
			<tr>
				<td>归还人员</td>
				<td>
					<?php if ((isset($this->scope["data"]["isback"]) ? $this->scope["data"]["isback"]:null) == 1) {
?>
					<input type="text" name="backer" style='height:30px;width:160px;' class='write' value='<?php echo $this->scope["data"]["backer"];?>'/>
					<?php 
}
else {
?>
					<input type="text" name="backer" style='height:30px;width:160px;' class='write' value='<?php echo $this->scope["data"]["backer"];?>'/>
					<?php 
}?>

					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>				
			<tr>
				<td><input type="submit" class='btn' value='确定上传' id='transport'/></td>
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