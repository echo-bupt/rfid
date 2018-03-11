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
	<span class='title'>钻具种类</span>
</div>
<div id="content" style="text-align:center;">
	<a class="btn-small-f btn-mini btn-info" style="float:right;" href="/tool/add">+</a>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="30%">钻具类型</th>
				<th width="30%">添加日期</th>
				<?php if($_SESSION['super']==1){?>
				<th width="30%">操作</th>
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
					<td><?php echo $this->scope["child"]["cname"];?></td>
					<td><?php echo date("Y-m-d H:i:s", (isset($this->scope["child"]["time"]) ? $this->scope["child"]["time"]:null));?></td>
					<?php if($_SESSION['super']==1){?>
					<td>
						<a class='btn btn-small-f edit' href="/tool/edit/cid/<?php echo $this->scope["child"]["cid"];?>">编辑</a>
						<a class='btn btn-small-f del'  href="/tool/delete/cid/<?php echo $this->scope["child"]["cid"];?>">删除</a>
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
			<h2>暂时没有任何钻具属性，请点击<a href="/property/add">添加</a></h2>
			<?php 
}?>

		</tbody>
	</table>
	
	<div id="page">
				
	</div>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>