<?php
namespace app\model;
use core\database\model;
class drilModel{
	function add($data=array())
	{
		return model::getInstance()->add("dril",$data);
	}
	//获取一个表的所有数据或者多条数据、、二维数组
	 function all($fields="*",$limit="",$where="")
	 {
	 	return model::getInstance()->select("dril",$fields,$where,"add_time desc",$limit)->getAllResults();
	 }
	 //根据条件得到某一条数据、、一维数组
	 function getOne($id,$fields="*")
	 {
	 	//select之后会得到结果集 然后 getOne 对结果集进行二次处理、、
	 	return model::getInstance()->select("dril",$fields,"did=$id")->getOne();
	 }
	 function getOneBy($fields,$where)
	 {
	 	return model::getInstance()->select("dril",$fields,$where)->getOne();
	 }
	 //根据条件更新一条数据、
	 function update($data,$where="")
	 {
	 	return model::getInstance()->update("dril",$data,$where);
	 }
	 //根据条件删除一条数据
	 function delete($where)
	 {
	 	return model::getInstance()->delete("dril",$where);
	 }
	 //使用join进行多表查询、
	 function getJoinOne($rtable,$did,$num=0)
	 {
	 	
	 	//select * from zhu t1 left join you t2 where t1
	 	//return 
	 	$join=array(
	 		"on"=>"cid",
	 		"jtype"=>"LEFT JOIN",
	 		);
	 	 $allresults=model::getInstance()->join("dril",$rtable,$join,array("",""),"did=$did","","")->getAllResults();
	 	 return $allresults[$num];
	 }
	 function join($rtable,$limit="")
	 {
	 	$join=array(
	 		"on"=>"cid",
	 		"jtype"=>"LEFT JOIN",
	 		);
	 	return model::getInstance()->join("dril",$rtable,$join,array("",""),"","add_time desc",$limit)->getAllResults();
	 }
	 function count()
	 {
	 	return count(model::getInstance()->select("dril","*")->getAllResults());
	 }
	 function where($fields="*",$where="")
	 {
	 	return model::getInstance()->select("dril",$fields,$where,"add_time desc")->getAllResults();
	 }
	 function joinWhere($rtable,$where,$limit="")
	 {
	 	$join=array(
	 		"on"=>"cid",
	 		"jtype"=>"LEFT JOIN",
	 		);
	 	return model::getInstance()->join("dril",$rtable,$join,array("",""),$where,"add_time desc",$limit)->getAllResults();
	 }
	 function joinCount($rtable,$where)
	 {
	 	return count($this->joinWhere($rtable,$where));
	 }
	 function joincheck($rtable,$where,$limit="")
	 {
	 	$join=array(
	 		"on"=>"did",
	 		"jtype"=>"LEFT JOIN",
	 		);
	 	$fields=array(
	 		"a"=>"epc,cid,pro_factory,pro_time,add_time,mat,length,number",
	 		"b"=>"next",
	 		);
	 	return model::getInstance()->join("dril",$rtable,$join,$fields,$where,"add_time desc",$limit)->getAllResults();
	 }
}



?>