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
<body>
<div id="map">
	<span class='title'>钻具详情</span>
</div>
<div id="content" style="text-align:center;">
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>设备编号</th>
				<th>钻具类型</th>
				<th>生产厂家</th>
				<th>生产日期</th>
				<th>钢级</th>
				<th>长度(m)</th>
				<th>首次使用日期(年/月/日)</th>
				<th>送检二级单位</th>
				<th>二级单位下属井队编号</th>
			</tr>
		</thead>
		<tbody>
			<!-- 有信息的展示 没信息的增加一个 添加功能栏 -->
			<?php if ((isset($this->scope["data"]) ? $this->scope["data"] : null)) {
?>
					<tr>
						<td>
							<?php echo $this->scope["data"]["epc"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["cname"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["pro_factory"];?>

						</td>
						<td>
							<?php echo date("Y-m-d H:i:s", (isset($this->scope["data"]["pro_time"]) ? $this->scope["data"]["pro_time"]:null));?>

						</td>
						<td>
							<?php echo $this->scope["data"]["mat"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["length"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["firstuse"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["unit"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["troop"];?>

						</td>
					</tr>
			<?php 
}
else {
?>
			<h2>暂时没有钻具的相关信息</h2>
			<?php 
}?>

		</tbody>
	</table>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>