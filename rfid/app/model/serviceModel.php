<?php
namespace app\model;
use core\database\model;
class serviceModel{
	function add($data=array())
	{
		return model::getInstance()->add("service",$data);
	}
	//获取一个表的所有数据或者多条数据、、二维数组
	 function all($fields="*",$where="",$order="",$limit="")
	 {
	 	return model::getInstance()->select("service",$fields,$where,$order,$limit)->getAllResults();
	 }
	 //根据条件得到某一条数据、、一维数组
	 function getOne($fields="*",$where="")
	 {
	 	//select之后会得到结果集 然后 getOne 对结果集进行二次处理、、
	 	return model::getInstance()->select("service",$fields,$where)->getOne();
	 }
	 function getsort()
	 {
	 	return model::getInstance()->query("SELECT sort,count(sort) as 'len' FROM service GROUP BY sort")->getAllResults();
	 }
	 //根据条件更新一条数据、
	 function update($data,$where="")
	 {
	 	return model::getInstance()->update("service",$data,$where);
	 }
	 //根据条件删除一条数据
	 function delete($where)
	 {
	 	return model::getInstance()->delete("service",$where);
	 }
}



?>