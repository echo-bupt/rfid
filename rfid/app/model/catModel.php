<?php
namespace app\model;
use core\database\model;
class catModel{
	function add($data=array())
	{
		return model::getInstance()->add("cat",$data);
	}
	//获取一个表的所有数据或者多条数据、、二维数组
	 function all($fields="*")
	 {
	 	return model::getInstance()->select("cat",$fields)->getAllResults();
	 }
	 //根据条件得到某一条数据、、一维数组
	 function getOne($fields="*",$where="")
	 {
	 	//select之后会得到结果集 然后 getOne 对结果集进行二次处理、、
	 	return model::getInstance()->select("cat",$fields,$where)->getOne();
	 }
	 //根据条件更新一条数据、
	 function update($data,$where="")
	 {
	 	return model::getInstance()->update("cat",$data,$where);
	 }
	 //根据条件删除一条数据
	 function delete($where)
	 {
	 	return model::getInstance()->delete("cat",$where);
	 }
}



?>