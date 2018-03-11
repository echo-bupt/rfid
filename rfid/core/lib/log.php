<?php
namespace core\lib;
use core\database\mysqli;

class log{
	//初始化配置 比如选择将log 存到什么地方 选择存储引擎 
	public static $instance;
	public static $dir;
	public static function init()
	{
		$driver=config::get("driver","log");//自动去config下的 关于日志的配置文件 log文件去找、、
		// self::$driver=$driver;
		$log=config::all("log");
		if(isset($log[$driver]))//存在与driver相关的配置、
		{
			if($driver=="mysql")
			{
				//建立表、
				$mysqli=new mysqli();
				$sql="CREATE TABLE log(id int(8) primary key auto_increment,time varchar(32),maessage varchar(64))";
				$r=$mysqli->query($sql);
			}
			if($driver=="file")
			{
				$dir=$log[$driver]."/".date("YmdH");
				self::$dir=$dir;
				if(!is_dir($dir))
				{
					mkdir($dir,0777,true);
				}
			//为了避免重复 实例化 这里 可以使用 静态类的方法 self::$intance="\core\log"
			//但是应当能让该类执行、、但是 两次使用 :: 会报错、、
			self::$instance=new \core\log\file();
			}
		}else{
			throw new \Exception("请配置日志相关项!");
		}
	}
	public static function write($message,$filename='log.txt')
	{
		$message=date("Y-m-d H:i:s")."\t".$message."\r\n";
		self::$instance->write($message,self::$dir."/".$filename);
		// self::$driver::log($message,$filename);
	}
}


?>