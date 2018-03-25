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
		include '/vendor/autoload.php';
		include 'third/common/dwoo.php';
		//\core\lib\log::init();
		//\core\lib\log::write("jiazai");
		//通过路由类注册得到控制器与方法信息 再加以调用方法、
		$paths=\core\lib\route::register();
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
	//自动载入方法、
	public static function load($className)
	{
		//命名空间最前面的\不会有的、
		$className=ezPHP."/".str_replace("\\", "/", $className).".php";
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
