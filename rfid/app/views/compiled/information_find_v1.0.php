<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><div id="content" style="text-align:center;">
	<table id="table" class='table table-striped table-bordered'>
		<thead>
			<tr>
				<th>设备编号</th>
				<th>钻具类型</th>
				<th>生产厂家</th>
				<th>生产日期</th>
				<th>钢级</th>
				<th>长度(m)</th>
				<th>首次使用日期(年/月/日)</th>
				<th>累计作业时间(小时)</th>
				<th>累计作业深度(m)</th>
				<th>送检二级单位</th>
				<th>二级单位下属井队编号</th>
				<th>上一次维修日期</th>
				<th>维修站点</th>
				<th>检测人员</th>
				<th>维修人员</th>
				<th>维修内容</th>
				<th>评级</th>
				<th>状态评估</th>
				<th>使用建议</th>
				<th>下一次维修时间</th>
				<th>累计维修次数</th>
			</tr>
		</thead>
		<tbody>
			<!-- 有信息的展示 没信息的增加一个 添加功能栏 -->
			<?php if ((isset($this->scope["data"]) ? $this->scope["data"] : null)) {
?>
					<tr>
						<td>
							<?php echo $this->scope["data"]["epc"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["cid"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["pro_factory"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["pro_time"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["mat"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["length"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["firstuse"];?>

						</td>
						<?php if ((isset($this->scope["data"]['record']) ? $this->scope["data"]['record']:null)) {
?>
							<td colspan='2'>作业信息:该钻具暂无作业记录!</td>
						<?php 
}
else {
?>
						<td>
							<?php echo $this->scope["data"]["work_time"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["work_len"];?>

						</td>
						<?php 
}?>

						<td>
							<?php echo $this->scope["data"]["unit"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["troop"];?>

						</td>
						<?php if ((isset($this->scope["data"]['isservice']) ? $this->scope["data"]['isservice']:null)) {
?>
							<td colspan='10'>维修信息:该钻具暂无维修记录!</td>
						<?php 
}
else {
?>
						<td>
							<?php echo $this->scope["data"]["time"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["addr"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["checker"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["fixer"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["content"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["class"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["state"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["suggest"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["next"];?>

						</td>
						<td>
							<?php echo $this->scope["data"]["service"];?>

						</td>
						<?php 
}?>

					</tr>
			<?php 
}
else {
?>
			<h2>暂时没有钻具的相关信息</h2>
			<?php 
}?>

		</tbody>
	</table>
	<?php if ((isset($this->scope["data"]['notice']) ? $this->scope["data"]['notice']:null) && (isset($this->scope["data"]['notice']) ? $this->scope["data"]['notice']:null) > 0) {
?>
	<p>本钻具距离下一次检修，还有<b style='color:red'><?php echo $this->scope["data"]["notice"];?>天</b></p>
	<?php 
}
else {
?>
	<?php if ((isset($this->scope["data"]['notice']) ? $this->scope["data"]['notice']:null) && (isset($this->scope["data"]['notice']) ? $this->scope["data"]['notice']:null) < 0) {
?>
	<p>本钻具距离下一次检修，已过去<b style='color:red'><?php echo abs((isset($this->scope["data"]["notice"]) ? $this->scope["data"]["notice"]:null));?>天</b></p>
	<?php 
}?>

</div>
<?php 
}
 /* end template body */
return $this->buffer . ob_get_clean();
?>