<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>后台管理首页</title>
	<link rel="stylesheet" href="<?php echo $this->scope["TPL"];?>/css/admin.css" />
	<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/jquery-2.0.3.js"></script>
	<script type="text/javascript" src="<?php echo $this->scope["TPL"];?>/js/admin.js"></script>
<!-- 默认打开目标 -->
<!-- 让所有的超链接在 iframe中显示 本来超链接就是请求控制器一个方法 返回一个页面 来进行显示、 -->
<!--所有的连接都在本页面显示的、、target指明让页面在哪个frame下显示、-->
<base target="iframe"/>
</head>
<body>
<!-- 头部 -->
	<div id="top_box">
		<div id="top">
			<p id="top_font">后台管理首页 （V1.0）</p>
			<ul id="menu2" class="menu">
				<li><a href="/" target="_bank">后台首页</a></li>
				<li><a href="/index/copy">系统配置</a></li> 
				<li><a href="/index/connect">断开连接</a></li> 			
			</ul>
		</div>
		<div class="top_bar">
			<p class="adm">
				<?php if($_SESSION['super']==1){?>
					<span>超级</span>
					<?php }?>
				管理员：
				<span class="adm_pic">&nbsp&nbsp&nbsp&nbsp
					<?php echo $_SESSION['username']?>
					</span>
				<span class="adm_people"><?php echo $_SESSION['username']?></span>
			</p>
			<p class="now_time">
				今天是：<?php echo date("Y-m-d",time());?>
					 | 
				您的当前位置是：
				<span>首页</span>
			</p>
			<p class="out">
				<span class="out_bg">&nbsp&nbsp&nbsp&nbsp</span>&nbsp
				<a href="/index/out" target="_self">退出</a>
			</p>
		</div>
	</div>
<!-- 左侧菜单 -->
		<div id="left_box">
			<p class="use">常用菜单</p>

			 <div class="menu_box">
				<h2>钻具管理</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/Tool/index" class="pos">钻具种类</a>				        	
				        </li> 
				    </ul>
				    <ul class="con">
				        <li class="nav_u">
				        	<a href="/Property/index" class="pos">钻具属性</a>				        	
				        </li> 
				    </ul>
				</div>
			</div>
			 <div class="menu_box">
				<h2>单位管理</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/unit/index" class="pos">二级单位</a>				        	
				        </li> 
				    </ul>
				    <ul class="con">
				        <li class="nav_u">
				        	<a href="/troop/index" class="pos">井队管理</a>				        	
				        </li> 
				    </ul>
				</div>
			</div>
		<div class="menu_box">
				<h2>物资管理</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/Dril/add" class="pos">钻具入库</a>				       
				        </li> 
				    </ul>
				    <ul class="con">
				        <li class="nav_u">
				        	<a href="/Dril/index" class="pos">库存钻具</a>				       	
				        </li> 
				    </ul>
				    <ul class="con">
				        <li class="nav_u">
				        	<a href="/Dril/out" class="pos">出库管理</a>				       	
				        </li> 
				    </ul>
				</div>
			</div>
			 <div class="menu_box">
				<h2>现场管理</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/work/index" class="pos">作业设置</a>				        	
				        </li> 
				    </ul>
				    <ul class="con">
				        <li class="nav_u">
				        	<a href="/work/out" class="pos">起出作业</a>				        	
				        </li> 
				    </ul>
				</div>
			</div>	
			 <div class="menu_box">
				<h2>维修管理</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/service/index" class="pos">维修信息</a>				        	
				        </li> 
				    </ul>
				    <?php if($_SESSION['super']==1){?>
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/service/content" class="pos">内容设置</a>				        	
				        </li> 
				    </ul>
				    <?php }?>
				</div>
			</div>	
			 <div class="menu_box">
				<h2>信息普查</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/information/detail" class="pos">钻具详情</a>				        	
				        </li> 
				    </ul>
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/information/convenient" class="pos">便捷查询</a>				        	
				        </li> 
				    </ul>
				</div>
			</div>	
			 <div class="menu_box">
				<h2>系统管理</h2>
				<div class="text">
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/user/index" class="pos">用户管理</a>				       
				        </li> 
				    </ul>
					<ul class="con">
				        <li class="nav_u">
				        	<a href="/role/index" class="pos">角色管理</a>				       
				        </li> 
				    </ul>
				</div>
			</div>	
		</div>
		<!-- 右侧 -->
		<div id="right">
			<iframe  frameboder="0" border="0" 	scrolling="yes" name="iframe" src="/index/copy"></iframe>
		</div>
	<!-- 底部 -->
	<div id="foot_box">
		<div class="foot">
			<p>@Copyright © 2017-2020 rfid.com All Rights Reserved. 京ICP备0000000号</p>
		</div>
	</div>
<!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/js/iepng.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('.adm_pic, #left_box .pos, .span_server, .span_people', 'background');
    </script>
<![endif]-->
</body>
</html><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>