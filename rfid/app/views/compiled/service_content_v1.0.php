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
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/content.js"></script>
<body>
<div id="map">
	<span class='title'>内容设置</span>
</div>
<div id="content" style="text-align:center;">
	<a class="btn-small-f btn-mini btn-info" style="float:right;" href="/service/add">+</a>
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>选项</th>
				<th>内容</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					示例:
						<select style='width:160px;height:30px;text-align:center;'>
							<option value='维修内容' selected='selected'>维修内容</option>
							<option value='评级'>评级</option>
							<option value='状态评估'>状态评估</option>
							<option value='使用建议'>使用建议</option>
						</select>
				</td>
				<td>
					<input type='text' value='修复丝扣#更换公扣#更换母扣' style='width:300px;height:30px;float:left;'/>
					<span style='color:red;float:left;'>
						(*不同项之间使用#进行分割)
					</span>
				</td>
			</tr>
			<tr>
				<form action='/service/content' method='POST'>
				<td>
					设置:
						<select id='options' style='width:160px;height:30px;text-align:center;' name='options'>
							<option value='维修内容' selected='selected'>维修内容</option>
							<option value='评级'>评级</option>
							<option value='状态评估'>状态评估</option>
							<option value='使用建议'>使用建议</option>
						</select>
				</td>
				<td>
					<input type='text' id='contents' style='float:left;width:300px;height:30px;' name='contents' value='<?php echo $this->scope["contents"];?>'/>
				</td>
			<tr>
				<?php if($_SESSION['super']==1){?>
				<td colspan='2'>
					<input type="submit" class='btn' value='确定上传'/>
					<input type="reset" class='btn' value='重新设置'/>
				</td>
				<?php } ?>
			</tr>
			</form>
			</tr>
		</tbody>
	</table>
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>