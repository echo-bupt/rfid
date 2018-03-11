<?php
/*****

框架核心文件

*****/
namespace core;
defined("ezPHP") or die("禁止访问!");

class ezPHP{
	static function run()
	{
		//启动日志初始化、
		include CORE."/common/init.php";
		//好奇怪 引入的类 使用 \类名  就是可以使用的吗 文件反正最终全引入到了 index.php
		include '/vendor/autoload.php';
		include 'third/common/dwoo.php';
		//\core\lib\log::init();
		//\core\lib\log::write("jiazai");
		//通过路由类注册得到控制器与方法信息 再加以调用方法、
		$paths=\core\lib\route::register();
		//抛出异常、、 throw 且注意使用 PHP内置类加上 \ 表示PHP内置的、、
		//PDO方式会存在异常、、
		$c=$paths['c'];
		$m=$paths['m'];

		$_GET['c']=$c;

		$_GET['m']=$m;

		$file=APP."/controller/$c"."Controller.php";

		if(is_file($file))
		{

			require $file;
			//引入了 类文件之后 需要再组织命名空间、、
			$class="\app\controller\\{$c}Controller";
			$control=new $class;
			$control->$m();
		}


	}
		//自动载入类、
	public static function load($className)
	{
		//命名空间最前面的\不会有的、
		$className=ezPHP."/".str_replace("\\", "/", $className).".php";
		//这里使用 require、require_once 以及 include 未存在的类要加载几次？？

		//autoload方法同一个不存在的类 只会加载一次、、但是在其他场合 比如加载配置文件 注意要只加载一次、、

		//但是多个 require的情况 一定注意得保证只加载一次 也就是不通过 auto_load 方法的时候、、
		//注意是在autoload里面！！！


		//凡是几套方案动态选择 一般都是靠类的属性注入来实现的、、将一个类的实例注入到 属性中 

		//文件写入 多次写入内容 会如何 文件内容会被清空吗、、
		if(!file_exists($className))
		{
		 if ((class_exists($className,FALSE)) || (strpos($className, 'PHPExcel') !== 0)) {
            //    Either already loaded, or not a PHPExcel class request
            	return FALSE;
        	}
       	 $pClassFilePath = PHPEXCEL_ROOT . str_replace('_',DIRECTORY_SEPARATOR,$className) .'.php';
       	 if ((file_exists($pClassFilePath) === FALSE) || (is_readable($pClassFilePath) === FALSE)) {
           	  return FALSE;
       		 }
      		  require($pClassFilePath);
		}else{
			  require $className;
		}
	}
}



?>