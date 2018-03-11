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
<div id="map" style='vertical-align:center;'>
	<span class='title'>钻具出库</span>
	<?php if ((isset($this->scope["data"]) ? $this->scope["data"] : null)) {
?>
	<a class='btn btn-small-f edit' href="/dril/outFind" style='float:right;margin-top:10px;'>出库查询</a>
	<a class='btn btn-small-f edit' href="/dril/outBack" style='float:right;margin-top:10px;'>钻具归还</a>
	<a class='btn btn-small-f edit' href="/dril/outAdd" style='float:right;margin-top:10px;'>钻具出库</a>
	<?php 
}?>

</div>
<div id="content" style="text-align:center;">
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
				<th>状态</th>
				<th style="width:500px;">操作</th>
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
					<td><?php echo $this->readVar("cats.".(isset($this->scope["child"]["cid"]) ? $this->scope["child"]["cid"]:null));?></td>
					<td><?php echo date("Y-m-d H:i:s", (isset($this->scope["child"]["pro_time"]) ? $this->scope["child"]["pro_time"]:null));?></td>
					<td><?php echo $this->scope["child"]["pro_factory"];?></td>
					<td><?php echo date("Y-m-d H:i:s", (isset($this->scope["child"]["add_time"]) ? $this->scope["child"]["add_time"]:null));?></td>
					<td><?php echo $this->scope["child"]["number"];?></td>
					<td><?php echo $this->scope["child"]["mat"];?></td>
					<td><?php echo $this->scope["child"]["length"];?></td>
					<td><?php echo $this->scope["child"]["state"];?></td>
					<td>
						<?php if ((isset($this->scope["child"]["state"]) ? $this->scope["child"]["state"]:null) == "库存中") {
?>
						<a class='btn btn-small-f edit' href="/dril/outAdd/did/<?php echo $this->scope["child"]["did"];?>">出库</a>
						<a class='btn btn-small-f edit' href="/dril/outHistory/did/<?php echo $this->scope["child"]["did"];?>">历史出库记录</a>
						<?php 
}
else {
?>
						<a class='btn btn-small-f edit' href="/dril/outHistory/did/<?php echo $this->scope["child"]["did"];?>">出库记录</a>
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
			<h2>暂时没有任何符合出库条件的钻具</a>
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