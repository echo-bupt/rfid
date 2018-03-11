<?php

/*****

路由类

*****/

namespace core\lib;
use \core\lib\config;
//PHP路由类、

class route{
	private static $paths=array();
	static function register()
	{
		//PATH_INFO是有可能不存在的
		//$_SERVER['REQUEST_URI'] 得到的是域名之后的所有内容 并且会带上 /
		//即 不开启重写机制时 变为 /index.php/index/index 开启后 变为 /index/index 

		//对 index.php 不开启重写机制、、$_SERVER['REQUEST_URI'] 变为/index.php/index
		//但是他访问的确实是 index.php 只不过控制器与方法 出现了错误、、
		$pathI=$_SERVER['REQUEST_URI'];
		//注意拆分、、/name/cc 那么两个 / 会被拆分为三份的、、所以将左边的/去掉 拆分为两份、、
		$path=trim($pathI,"/");
		$paths=array();
		if($pathI!="/")
		{
			$path_tmp=explode("/", $path);
			if(isset($path_tmp[0]))
			{
				$paths['c']=ucfirst($path_tmp[0]);
			}else{
				$paths['c']=config::get("controller","controller");
			}
			if(isset($path_tmp[1]))
			{
				$paths['m']=ucfirst($path_tmp[1]);
			}else{
				$paths['m']=config::get("method","controller");
			}
			//以上是对控制器与方法的匹配、它俩是前两个 是确定的0与1 但是之后的可能参数有多个了、
			unset($path_tmp[0],$path_tmp[1]);
			//删掉 数组前两个后 索引并没有替换掉这两个删掉的索引、索引从2开始、
			$l=count($path_tmp)+2;
			for($i=2;$i<$l;$i++)
			{
				if(isset($path_tmp[$i+1]))
				{
					$_GET[$path_tmp[$i]]=$path_tmp[$i+1];
				}else{
					$_GET[$path_tmp[$i]]=1;
				}
				$i++;
			}
		}else{
			$paths['c']=config::get("controller","controller");
			$paths['m']=config::get("method","controller");
		}
		self::$paths=$paths;
		return $paths;
	}
}



?>