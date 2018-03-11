<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title></title>
<link href="<?php echo $this->scope["TPL"];?>/css/public.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/out.js"></script>
<script type="text/javascript" src='/third/DatePicker/WdatePicker.js'></script>
<body>
<div id="map" style='vertical-align:center;'>
	<span class='title'>钻具归还</span>
</div>
<div id="content">
	<form id="tool" action="/dril/outBack" method="post">
	<table class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th width="20%">名称</th>
				<th>值</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					编号(钢印号)
				</td>
				<td>
					<input type="text" name="epc" class='dril write'id='epc' style='float:left;'/>
					<span class='notices' style='color:red;display:none;float:left;'>></span>
					<span id='notice' style='float:left;color:red;display:none;font-size:12px;line-height:30px;'>成功扫描n个</span>
					<input type='button' id='single' value="扫描" class='green' style="width:10%;height:35px;"/>
				</td>
			</tr>
			<tr>
				<td>归还人员</td>
				<td>
					<input type="text" name="backer" class='write' style='height:30px;width:160px;'/>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>				
			<tr>
				<td width="30%">归还日期</td>
				<td>
					<input id="d11" type="text" style='height:30px;width:160px;' class='write' name="backtime" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
					<span class='notices' style='color:red;display:none;'>></span>
				</td>
			</tr>		
			<tr>
				<td><input type="submit" class='btn' value='确定上传' id='transport'/></td>
				<td><input type="reset" class='btn' value='重新填写'/></td>
			</tr>
		</tbody>
	</table>
	</form>
	
	
	
</div>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>