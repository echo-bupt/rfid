<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/work_success.js"></script>
<body>
<div id="map">
	<span class='title'>下井作业</span>
</div>
<div>
	<ul>
		<li style='border-bottom:solid 1px #ccc;padding-bottom:30px;'>
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
		</li>
		<li style='border-bottom:solid 1px #ccc;padding-bottom:30px;'>
			<h2 style='margin:10px 0 20px 0'>作业状态:
					<b style='color:red;'>钻具核对已完成，下井作业中...</b>
			</h2>
			<ul>
				<?php 
$_fh1_data = (isset($this->scope["record"]) ? $this->scope["record"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['record'])
	{
/* -- foreach start output */
?>
		<li class='workState' style='margin-bottom:30px;text-align:center;'>
			<p style='clear:both;text-align:left;margin-left:100px;'>作业任务序号<b><?php echo $this->scope["record"]["sort"];?></b>，钻具编号:<b><?php echo $this->scope["record"]["epc"];?></b>，于<b><?php echo $this->scope["record"]["outtime"];?></b>出井，您可以<a href='#' class='change' style='font-weight:bold;'>更换钻具</a></p>
			<input type='hidden' value='<?php echo $this->scope["record"]["sort"];?>' class='sort'/>
			<div class='changeCon' style='display:none;margin-top:30px;'>
				<p style='clear:both;'>
					<input type="text" name="epc" value="" id='epc' style='width:400px;height:35px;'/>
					<input type='button' id='single' value="扫描" class='green' style="height:35px;width:100px;"/>
					<span id='notice' class='notice'>(*点击扫描或者直接输入钻具编号实现核对操作)</span>
				</p>
				<p style='height:30px;float:right;padding-top:10px;margin-right:20px;display:none;' id='operate'>
					<input type='hidden' value='<?php echo $this->scope["record"]["rid"];?>' class='sort'/>
					<input type='button' class='btn ensureBtn' value='确认替换'/>
					<input type='button' class='btn errorBtn' value='取消替换'/>
				</p>
			</div>
		</li>
		<?php 
/* -- foreach end output */
	}
}?>

			</ul>
		</li>
		<li>
			<h2 style='margin:10px 0 20px 0'>作业状态:
					<b style='color:red;'>下井作业中，若完成作业方可进行作业提交</b>
			</h2>
		</li>
		<li style='border-bottom:solid 1px #ccc;padding-bottom:30px;text-align:center;'>
					<input type='button' class='btn' id='submit' value='提交作业'/>
		</li>
	</ul>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>