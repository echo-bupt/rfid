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
	<span class='title'>维检记录</span>
	<a class='btn btn-small-f edit' href="/service/exportAll/epc/<?php echo $this->scope["epc"];?>" style='float:right;margin-top:10px;'>导出全部维修记录</a>
</div>
<div id="content" style="text-align:center;">
	<a class="btn-small-f btn-mini btn-info" style="float:right;" href="/service/add">+</a>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>设备编号</th>
				<th>检修日期</th>
				<th>检修地点</th>
				<th>检测人员</th>
				<th>维修人员</th>
				<th>维修内容</th>
				<th>评级</th>
				<th>状态评估</th>
				<th>使用建议</th>
				<th>下一次检修日期</th>
				<th style='width:20%;'>操作</th>
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
						<td>
							<?php echo $this->scope["child"]["epc"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["time"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["addr"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["checker"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["fixer"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["content"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["class"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["state"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["suggest"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["next"];?>

						</td>
						<td>
							<a class='btn btn-small-f' href="/service/export/sid/<?php echo $this->scope["child"]["sid"];?>/epc/<?php echo $this->scope["child"]["epc"];?>">导出</a>
							<a class='del btn btn-small-f' href="/service/delete/sid/<?php echo $this->scope["child"]["sid"];?>">删除</a>
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
			<h2>暂时没有任何历史维修记录,本钻具是第一次维修。</h2>
			<?php 
}?>

		</tbody>
	</table>
	<dic class='page'>
		<?php echo $this->scope["page"];?>

	</div>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>