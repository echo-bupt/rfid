<?php
namespace app\model;
use core\database\model;
class workModel{
	function add($data=array())
	{
		return model::getInstance()->add("work",$data);
	}
	//获取一个表的所有数据或者多条数据、、二维数组
	 function all($fields="*",$where="",$limit="")
	 {
	 	return model::getInstance()->select("work",$fields,$where,"UNIX_TIMESTAMP(time) desc",$limit)->getAllResults();
	 }
	 //根据条件得到某一条数据、、一维数组
	 function getOne($fields="*",$where="",$order="")
	 {
	 	//select之后会得到结果集 然后 getOne 对结果集进行二次处理、、
	 	return model::getInstance()->select("work",$fields,$where,$order)->getOne();
	 }
	 //根据条件更新一条数据、
	 function update($data,$where="")
	 {
	 	return model::getInstance()->update("work",$data,$where);
	 }
	 //根据条件删除一条数据
	 function delete($where)
	 {
	 	return model::getInstance()->delete("work",$where);
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
	 	return model::getInstance()->join("work",$rtable,$join,$fields,"","a.time desc")->getAllResults();
	 }
}



?>