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
<script type="text/javascript" src='/third/DatePicker/WdatePicker.js'></script>
<body>
<div id="map" style='vertical-align:center;'>
	<span class='title'>高级查询</span>
</div>
<div id="content" style="text-align:center;">
	<h2>请组合以下查询条件进行多条件查询:<span style='color:red;'>(*不需要的条件可以为空)</span></h2>
	<form action='/dril/find' method='post'>
	<p class='add_time'>
		入库时间:<input type="text" style="height:30px;" name='add1' id='add1' onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
		----
		<input type="text" style="height:30px;" id='add2' name='add2' onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
	</p>
	<p class='factory'>
		生产厂家:<input type="text" style="height:30px;" id='factory' name='factory'/>
	</p>
	<p class='pro_time'>
		生产日期:<input type="text" style="height:30px;" id='pro1' name='pro1' onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
		----
		<input type="text" style="height:30px;" id='pro2' name='pro2' onClick="WdatePicker({dateFmt:'yyyy-MM-dd'})"/>
	</p>
	<p class='mat'>
		钻具钢级:<input type="text" style="height:30px;" id='mat' name='mat' />
	</p>
	<p class='mat'>
		批次编号:<select name='number' style='height:30px;width:160px;text-align:center;'>
			<option value=''>全部编次</option>
			<?php 
$_fh0_data = (isset($this->scope["number"]) ? $this->scope["number"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['v'])
	{
/* -- foreach start output */
?>
			<option value='<?php echo $this->scope["v"];?>'><?php echo $this->scope["v"];?></option>
			<?php 
/* -- foreach end output */
	}
}?>

		</select>
	</p>
	<p>
		<input type='submit' class='btn' value='确认提交'/>
		<input type='reset' class='btn'  value='重新填写'/>
	</p>
</form>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>