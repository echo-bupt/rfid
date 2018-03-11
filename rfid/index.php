<?php
/*****

ezPHP 框架单入口文件

*****/

define("ezPHP",realpath("./"));

define("C_RELEASE","v1.0");

define("CORE",ezPHP."/"."core");

define("LIB",ezPHP."/"."core/lib");

define("COMMON", ezPHP."/"."core/common");

define("APP",ezPHP."/app");

define("THIRD",ezPHP."/third");

define("LOG",false);

define("VIEWS",ezPHP."/app/views");

define("TPL",VIEWS."/tpl");

define("DEBUG",true);


if(defined("DEBUG") && DEBUG)
{
	ini_set("display_errors", "On");
}else{
	ini_set("display_errors", "Off");
}

require COMMON."/functions.php";

//加载框架核心文件 并启动框架、

require CORE."/ezPHP.php";


spl_autoload_register("\core\\ezPHP::load");


\core\ezPHP::run();





?>