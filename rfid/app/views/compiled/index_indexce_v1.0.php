<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?> <html>
 <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
 <body>
 <!-- 加载编辑器的容器 -->
    <script id="container" name="content" type="text/plain">
        这里写你的初始化内容
    </script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="/third/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/third/ueditor/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        //var ue = UE.getEditor('container');
    </script>


<img src="http://localhost/superJs/1.jpg"/>

</body><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>