<?php
namespace app\model;
use core\database\model;
class stateModel{
	function add($data=array())
	{
		return model::getInstance()->add("state",$data);
	}
	//获取一个表的所有数据或者多条数据、、二维数组
	 function all($fields="*",$where="")
	 {
	 	return model::getInstance()->select("state",$fields,$where,"UNIX_TIMESTAMP(picktime) desc")->getAllResults();
	 }
	 //根据条件得到某一条数据、、一维数组
	 function getOne($fields="*",$where="",$order="")
	 {
	 	//select之后会得到结果集 然后 getOne 对结果集进行二次处理、、
	 	return model::getInstance()->select("state",$fields,$where,$order)->getOne();
	 }
	 //根据条件更新一条数据、
	 function update($data,$where="")
	 {
	 	return model::getInstance()->update("state",$data,$where);
	 }
	 //根据条件删除一条数据
	 function delete($where)
	 {
	 	return model::getInstance()->delete("state",$where);
	 }
	 function join($rtable)
	 {
	 	$join=array(
	 		"on"=>"uid",
	 		"jtype"=>"LEFT JOIN",
	 		);
	 	$fields=array(
	 		'a'=>"time as atime,tid,tname",
	 		'b'=>"uname,uid",
	 		);
	 	return model::getInstance()->join("state",$rtable,$join,$fields,"","a.time desc")->getAllResults();
	 }
}



?>