<?php
namespace core\lib;

//该类用于 动态获取用于配置、、

class config{
	//获取配置文件的某个配置信息、
	public static $confs=array();
	public static function get($name,$file){
		if(!isset(self::$confs[$file]))
		{
			$relfile=APP."/config/{$file}.php";
			if(is_file($relfile))
			{
				$data=require $relfile;
				self::$confs[$file]=$data;
				return $data[$name];
			}
		}else{
			return self::$confs[$file][$name];
		}

	}
	//获取配置文件的所有配置信息、
	public static function all($file)
	{
		if(!isset(self::$confs[$file]))
		{
			$relfile=APP."/config/{$file}.php";
			if(is_file($relfile))
			{
				$data=require $relfile;
				self::$confs[$file]=$data;
				return $data;
			}
		}else{
			return self::$confs[$file];
		}
	}
}




?>