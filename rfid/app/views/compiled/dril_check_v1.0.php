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
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/check.js"></script>
<body>
<div id="content" style="text-align:center;">
	<div id='notice'>
		<h2>距下一次钻具检修时间:&nbsp;&nbsp;<input type='text' name='checkName' <?php if ((isset($this->scope["check"]) ? $this->scope["check"] : null)) {
?>value="<?php echo $this->scope["check"];?>"<?php 
}
else {
?>value="30"<?php 
}?> id='days'/>天
			<a href='/dril/check' id='checkBtn'>更新查询</a>
		</h2>
	</div>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>编号(钢印号)</th>
				<th>钻具类型</th>
				<th>生产日期</th>
				<th>生产厂家</th>
				<th>入库时间</th>
				<th>批次编号</th>
				<th>钢级</th>
				<th>长度</th>
				<th style="width:500px;">检修状态</th>
			</tr>
		</thead>
		<tbody>
			<!-- 有信息的展示 没信息的增加一个 添加功能栏 -->
			<?php if ((isset($this->scope["data"]) ? $this->scope["data"] : null)) {
?>
				<?php 
$_fh0_data = (isset($this->scope["data"]) ? $this->scope["data"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['child'])
	{
/* -- foreach start output */
?>
					<tr>
					<td><?php echo $this->scope["child"]["epc"];?></td>
					<td><?php echo $this->scope["child"]["cid"];?></td>
					<td><?php echo date('Y-m-d H:i:s', (isset($this->scope["child"]["pro_time"]) ? $this->scope["child"]["pro_time"]:null));?></td>
					<td><?php echo $this->scope["child"]["pro_factory"];?></td>
					<td><?php echo date('Y-m-d H:i:s', (isset($this->scope["child"]["add_time"]) ? $this->scope["child"]["add_time"]:null));?></td>
					<td><?php echo $this->scope["child"]["number"];?></td>
					<td><?php echo $this->scope["child"]["mat"];?></td>
					<td><?php echo $this->scope["child"]["length"];?></td>
					<td style="width:500px;text-align:center;">
						<?php if ((isset($this->scope["child"]["next"]) ? $this->scope["child"]["next"]:null) >= 0) {
?>
						距离下一次检修时间，还有<b style='color:red'><?php echo $this->scope["child"]["next"];?></b>天
						<?php 
}
else {
?>
						距离下一次检修时间，已经过去<b style='color:red'><?php echo abs((isset($this->scope["child"]["next"]) ? $this->scope["child"]["next"]:null));?></b>天
						<?php 
}?>

					</td>
					</tr>
				<?php 
/* -- foreach end output */
	}
}?>

			<?php 
}
else {
?>
			<h2>暂时没有任何符合检修条件的钻具，请重新设置或者钻具没有维修记录</a>
			<?php 
}?>

		</tbody>
	</table>
	<div class="page">
				<?php echo $this->scope["page"];?>

	</div>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>