<?php
/*****

函数工具库

*****/

defined("ezPHP") or die("forrbien!");

//输入形式 bool、null 一般的任何变量、、

function p($content)
{
	if(is_bool($content))
	{
		var_dump($content);
	}else if(is_null($content))
	{
		var_dump($content);
	}else{
		echo "<pre style='position:relative;padding:10px;border-radius:10px;background:#F5F5F5;border:solid 1xp #F5F5F5;line-height:60px;'>".
				print_r($content,true)
		."</pre>";
	}
}

function remkdir($dir)
{
	if(!is_dir($dir))
	{
		mkdir($dir,0777,true);
	}
}
function getRandString($length = 8,$strict = false) {
	$str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	if($strict) $str = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ3456789";
	$len = strlen($str)-1;
	$ret = "";
	for($i=1;$i<=$length;$i++){
		$ret .= substr($str,mt_rand(0,$len),1);
	}
	return $ret;
}

function go($url="/")
{
	$url=trim($url);
	if($url)
	{
		$urlarr=explode("/", $url);
		//location需要加上 / 
		header("Location:"."/".$urlarr[0]."/".$urlarr[1]);
	}else{
		header("Location:/index/copy");
	}
}

function ISPOST() {
	return isset($_SERVER["REQUEST_METHOD"]) && strtoupper($_SERVER["REQUEST_METHOD"]) == "POST";
}

function trims($content)
{
	return is_array($content)?array_map("trims", $content):trim($content);
}

?>