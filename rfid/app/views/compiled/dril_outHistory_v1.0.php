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
	<span class='title'>钻具出库记录</span>
</div>
<div id="content" style="text-align:center;">
	<a class="btn-small-f btn-mini btn-info" style="float:right;" href="/dril/outAdd">+</a>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>编号</th>
				<th>下拨二级单位</th>
				<th>下属井队编号</th>
				<th>出库办理人员</th>
				<th>领取人员</th>
				<th>领取日期</th>
				<th>归还人员</th>
				<th>归还日期</th>
				<?php if($_SESSION['super']==1){?>
				<th>操作</th>
				<?php }?>
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
					<td><?php echo $this->scope["epc"];?></td>
					<td><?php echo $this->scope["child"]["unit"];?></td>
					<td><?php echo $this->scope["child"]["troop"];?></td>
					<td><?php echo $this->scope["child"]["operator"];?></td>
					<td><?php echo $this->scope["child"]["picker"];?></td>
					<td><?php echo $this->scope["child"]["picktime"];?></td>
					<?php if ((isset($this->scope["child"]["isback"]) ? $this->scope["child"]["isback"]:null) != 0) {
?>
					<td><?php echo $this->scope["child"]["backer"];?></td>
					<td><?php echo $this->scope["child"]["backtime"];?></td>
					<?php 
}
else {
?>
					<td colspan="2">该钻具未归还</td>
					<?php 
}?>

					<?php if($_SESSION['super']==1){?>
					<td>
						<a class='btn btn-small-f edit' href="/dril/outEdit/oid/<?php echo $this->scope["child"]["oid"];?>">编辑</a>
						<a class='btn btn-small-f del'  href="/dril/outDelete/did/<?php echo $this->scope["child"]["did"];?>">删除</a>
					</td>
					<?php }?>
				</tr>
			<?php 
/* -- foreach end output */
	}
}?>

			<?php 
}
else {
?>
			<h2>该钻具没有历史出库记录<a class='btn btn-small-f edit' href="/dril/out">返回</a></h2>
			<?php 
}?>

		</tbody>
	</table>
	
	<div id="page">
				<?php echo $this->scope["page"];?>

	</div>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>