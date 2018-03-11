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
<div id="map" style='vertical-align:center;'>
	<span class='title'>库存钻具</span>
	<?php if ((isset($this->scope["data"]) ? $this->scope["data"] : null)) {
?>
	<a class='btn btn-small-f edit' href="/dril/check" style='float:right;margin-top:10px;'>检修状态查询</a>
	<a class='btn btn-small-f edit' href='/dril/exportIndexPage/page/<?php echo $this->scope["pagenum"];?>'style='float:right;margin-top:10px;'>导出本页钻具数据</a>
	<a class='btn btn-small-f edit' href="/dril/exportAll" style='float:right;margin-top:10px;'>导出全部钻具数据</a>
	<a class='btn btn-small-f edit' href="/dril/findDisplay" style='float:right;margin-top:10px;'>高级查询</a>
	<?php 
}?>

</div>
<div id="content" style="text-align:center;">
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<?php 
$_fh0_data = (isset($this->scope["property"]) ? $this->scope["property"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['v'])
	{
/* -- foreach start output */
?>
					<th><?php echo $this->scope["v"]["nickname"];?></th>
				<?php 
/* -- foreach end output */
	}
}?>

				<th style="width:500px;">操作</th>
			</tr>
		</thead>
		<tbody>
			<!-- 有信息的展示 没信息的增加一个 添加功能栏 -->
			<?php if ((isset($this->scope["data"]) ? $this->scope["data"] : null)) {
?>
				<?php 
$_fh2_data = (isset($this->scope["data"]) ? $this->scope["data"] : null);
if ($this->isArray($_fh2_data) === true)
{
	foreach ($_fh2_data as $this->scope['child'])
	{
/* -- foreach start output */
?>
					<tr>
						<?php 
$_fh1_data = (isset($this->scope["keys"]) ? $this->scope["keys"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['childkey'])
	{
/* -- foreach start output */
?>
							<td>
								<?php echo $this->readVar("child.".(isset($this->scope["childkey"]) ? $this->scope["childkey"] : null));?>

							</td>
						<?php 
/* -- foreach end output */
	}
}?>

					<td style="width:500px;">
					<?php if($_SESSION['super']==1){?>
					<a class=' btn-small-f edit' href="/dril/edit/did/<?php echo $this->scope["child"]["did"];?>">编辑</a>
					<a class=' btn-small-f del'  href="/dril/delete/did/<?php echo $this->scope["child"]["did"];?>">删除</a>
					<?php }?>
					<a class=' btn-small-f add'  href="/dril/paste/did/<?php echo $this->scope["child"]["did"];?>">复制</a>
					<a class=' btn-small-f add'  href="/dril/export/did/<?php echo $this->scope["child"]["did"];?>">导出</a>
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
			<h2>暂时没有任何钻具属性，请点击<a href="/dril/add">添加钻具</h2>
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