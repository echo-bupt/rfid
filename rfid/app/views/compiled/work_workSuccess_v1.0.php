<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<body>
<div id="map">
	<span class='title'>下井作业</span>
</div>
<div>
			<h2>本次作业编号:<b style='color:red'><?php echo $this->scope["data"]["w_number"];?></b>,设置于<?php echo $this->scope["data"]["time"];?></h2>
			<input type='hidden' value='<?php echo $this->scope["data"]["wid"];?>' id='wid'/>
			<table class='table table-striped table-bordered' style='text-align:center;'>
				<thead>
					<tr>
						<th>序号</th>
						<th>钻具类型</th>
						<th>钢级</th>
						<th>型号(英寸)</th>
						<th>历史下井累计时间</th>
						<th>数量(根)</th>
					</tr>
				</thead>
				<tbody id='tbody'>
					<?php 
$_fh0_data = (isset($this->scope["contents"]) ? $this->scope["contents"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['content'])
	{
/* -- foreach start output */
?>
					<tr>
						<td>
							<?php echo $this->scope["content"]["index"];?>

						</td>
						<td>
							<?php echo $this->scope["content"]["cat"];?>

						</td>
						<td>
							<?php echo $this->scope["content"]["mat"];?>

						</td>
						<td>
							<?php echo $this->scope["content"]["size"];?>

						</td>
						<td>
							<?php echo $this->scope["content"]["total"];?>

						</td>	
						<td>
							<?php echo $this->scope["content"]["len"];?>

							</b>
						</td>				
					</tr>
					<?php 
/* -- foreach end output */
	}
}?>

				</tbody>
			</table>
			<h2 style='margin:50px 0 20px 0;text-align:center;'>作业状态:
					<b style='color:red;'>本次下井作业已完成。</b>
			</h2>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>