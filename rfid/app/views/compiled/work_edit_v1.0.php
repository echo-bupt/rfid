<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/work.js"></script>
<body>
<form id="tool" action="/work/edit" method="post">
<div id="map">
	<span class='title'>作业设置编辑</span>
	<span style='float:right;'>作业编号:<input type='text'class='putCon' name='w_number'value="<?php echo $this->scope["data"]["w_number"];?>"/></span>
	<input type='hidden' name='wid' value='<?php echo $this->scope["data"]["wid"];?>'>
</div>
<div id="content">
	<table class='table table-striped table-bordered' style='text-align:centerl'>
		<thead>
			<tr>
				<th>序号</th>
				<th>钻具类型</th>
				<th>数量(根)</th>
				<th>钢级</th>
				<th>型号(英寸)</th>
				<th>历史下井累计时间</th>
			</tr>
		</thead>
		<tbody id='tbody'>
			<?php 
$_fh1_data = (isset($this->scope["contents"]) ? $this->scope["contents"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['content'])
	{
/* -- foreach start output */
?>
			<tr class='conline'>
				<td><?php echo $this->scope["content"]["index"];?></td>
				<td>
					<select name='cat<?php echo $this->scope["content"]["index"];?>' class='putCon'>
						<?php 
$_fh0_data = (isset($this->scope["cats"]) ? $this->scope["cats"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['cat'])
	{
/* -- foreach start output */
?>
							<?php if ((isset($this->scope["cat"]["cname"]) ? $this->scope["cat"]["cname"]:null) == (isset($this->scope["content"]["cat"]) ? $this->scope["content"]["cat"]:null)) {
?>
							<option value='<?php echo $this->scope["cat"]["cname"];?>' selected='selected'><?php echo $this->scope["cat"]["cname"];?></option>
							<?php 
}
else {
?>
							<option value='<?php echo $this->scope["cat"]["cname"];?>'><?php echo $this->scope["cat"]["cname"];?></option>
							<?php 
}?>

						<?php 
/* -- foreach end output */
	}
}?>

					</select>
				</td>
				<td>
					<input type='text' name='len<?php echo $this->scope["content"]["index"];?>' class='putCon write' value='<?php echo $this->scope["content"]["len"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
				<td>
					<input type='text' name='mat<?php echo $this->scope["content"]["index"];?>' class='putCon write' value='<?php echo $this->scope["content"]["mat"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
				<td>
					<input type='text' name='size<?php echo $this->scope["content"]["index"];?>' class='putCon write' value='<?php echo $this->scope["content"]["size"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
				<td>
					<input type='text' name='total<?php echo $this->scope["content"]["index"];?>' class='putCon write' value='<?php echo $this->scope["content"]["total"];?>'/>
					<span style='color:red;display:none;'></span>
				</td>
			</tr>
			<?php 
/* -- foreach end output */
	}
}?>

		</tbody>
	</table>
	<input type='hidden' id='count' value='<?php echo $this->scope["count"];?>' name='count'/>
	<a class="btn-small-f btn-mini btn-info" style="float:right;margin-top:20px;margin-right:30px;" href="#" id='reduceLine'>-</a>
	<a class="btn-small-f btn-mini btn-info" style="float:right;margin-top:20px;margin-right:30px;" href="#" id='addLine'>+</a>
	<?php if($_SESSION['super']==1){?>
			<p style='margin-top:50px;'>
				<input type="submit" class='btn' value='确定上传'/></td>
				<input type="reset" class='btn' value='重新填写'/></td>
			</p>
	<?php }?>
	</form>
	
	
	
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>