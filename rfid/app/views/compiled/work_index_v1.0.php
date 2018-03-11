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
	<span class='title'>分类列表</span>
</div>
<div id="content" style="text-align:center;">
	<a class="btn-small-f btn-mini btn-info" style="float:right;" href="/work/add">+</a>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>作业编号</th>
				<th>添加时间</th>
				<th>操作</th>
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
							<?php echo $this->scope["child"]["w_number"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["time"];?>

						</td>
					<td>
					<?php if ((isset($this->scope["child"]["state"]) ? $this->scope["child"]["state"]:null) == 0) {
?>
					<a class='btn btn-small-f edit' href="/work/edit/wid/<?php echo $this->scope["child"]["wid"];?>">查看详情</a>
					<a class='btn btn-small-f edit' href="/work/check/wid/<?php echo $this->scope["child"]["wid"];?>">下井作业</a>
					<?php 
}
else {
?>
					<a class='btn btn-small-f edit' href="/work/check/wid/<?php echo $this->scope["child"]["wid"];?>">下井作业</a>
					<a class='btn btn-small-f edit' href="/work/nowDisplay/wid/<?php echo $this->scope["child"]["wid"];?>">现场记录</a>
					<?php 
}?>

					<a class='btn btn-small-f'  href="/work/exportZuoye/wid/<?php echo $this->scope["child"]["wid"];?>">导出</a>
					<?php if($_SESSION['super']==1){?>
					<a class='btn btn-small-f del'  href="/work/delete/wid/<?php echo $this->scope["child"]["wid"];?>">删除</a>
					<?php }?>
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
			<h2>暂时没有任何作业计划，请点击<a href="/work/add">添加作业计划</a></h2>
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