<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $this->scope["msg"];?></title>
    <style type="text/css">
        *{margin:0px; padding:0px;}
        body{background: #F3F3F3;}
        ._prompt{
            width:500px;margin:10px;
            background-color:#f9f9f9;
            height: auto;overflow:hidden;
        }
        ._prompt h2{  height: 28px; color:#333;background-color:#FDCE16;font-size:12px;font-weight: normal; text-indent: 10px; line-height: 30px;}
        ._prompt div{padding:10px; font-size:12px; color:#333;}
        ._prompt div a{color:royalblue;}
        ._prompt div p{ border-bottom: solid 1px #e9e9e9;padding-bottom: 10px; margin-bottom: 10px;}
        ._prompt div span {color:#999; font-weight: normal;}
        ._prompt div span a{color:#999;}
        #_time{color:#F57E1D;font-size:12px;padding:3px;}
    </style>
    <script type="text/javascript">
        window.setTimeout("<?php echo $this->scope["url"];?>",<?php echo $this->scope["time"];?>*1000);
    </script>
</head>
<body>
<div class="_prompt">
    <h2>操作成功</h2>
    <div>
        <p><?php echo $this->scope["msg"];?>&nbsp&nbsp&nbsp&nbsp</p>
                <span><span id="_time"><?php echo $this->scope["time"];?></span>秒钟后将进行
                    <a href="javascript:<?php echo $this->scope["url"];?>">跳转</a>
                    也可以<a href="/index/copy">返回首页</a></span>
    </div>
</div>
<script type="text/javascript">
    var time=document.getElementById("_time").innerHTML;
    function revTime(){
        time--;
        if(time>0){
            document.getElementById("_time").innerHTML=time;
        }
    }
    setInterval("revTime()",1000);
</script>
</body>
</html>
<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>