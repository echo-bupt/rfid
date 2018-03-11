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
	<span class='title'>作业记录</span>
	<a class='btn btn-small-f edit' href="/work/exportWork/wid/<?php echo $this->scope["wid"];?>" style='float:right;margin-top:10px;'>导出本次作业的作业记录</a>
</div>
<div id="content" style="text-align:center;">
	<a class="btn-small-f btn-mini btn-info" style="float:right;" href="/unit/add">+</a>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>序号</th>
				<th>下入长度</th>
				<th>钻具编号</th>
				<th>下入时间(最近一次)</th>
				<th>起出时间(最近一次)</th>
				<th>本次下入累计时间</th>
				<th>累计长度</th>
				<th style='width:25%;'>操作</th>
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
							<?php echo $this->scope["child"]["index"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["len"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["epc"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["intime"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["outtime"];?>

						</td>
						<td>
							<?php echo $this->scope["child"]["time"];?>

						</td>
						<td>
							<?php echo $this->readVar("addLen.".(isset($this->scope["child"]["index"]) ? $this->scope["child"]["index"]:null));?>

						</td>
					<td>
					<a class='btn btn-small-f edit' href="/work/detail/epc/<?php echo $this->scope["child"]["epc"];?>">钻具详情查询</a>
					<a class='btn btn-small-f edit' href="/work/export/rid/<?php echo $this->scope["child"]["rid"];?>/epc/<?php echo $this->scope["child"]["epc"];?>/wid/<?php echo $this->scope["child"]["wid"];?>">导出</a>
					<a class='btn btn-small-f del'  href="/work/record_delete/rid/<?php echo $this->scope["child"]["rid"];?>">删除</a>
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
			<h2>暂时没有任何作业记录</h2>
			<?php 
}?>

		</tbody>
	</table>
</div>
<div class='page' style='text-align:center;'>
	<?php echo $this->scope["page"];?>

	</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>