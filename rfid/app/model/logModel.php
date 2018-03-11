<?php

//model 层负责解释用户意图 组装 sql 语句 基于 数据库操作类的再封装 专门提供给用户层使用、、
namespace app\model;
//只需要使用 model类得到对象实例即可、、model 类根据配置得到正确的操作数据库的实例对象、

//也就是你引入的类这个类又实例化了另一个类、、实例化的过程 在引入的类已经做好了、

//有些接口一定得从 这里开始 比如接受 用于输入的 ID、、由model再去调底层接口、、

use core\database\model;

class logModel{
	public function all()
	{
		// $data=model::getInstance()->select("log","*",$where=array("name"=>"xiaoming","id"=>1),"name","0,3")->getAllResults();
		$data=model::getInstance()->select("log","*")->getAllResults();
	}
}


?>