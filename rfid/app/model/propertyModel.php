<?php
namespace app\model;
use core\database\model;
class propertyModel{
	function add($data=array())
	{
		//先修改dril表的信息 判断列是否已存在、
		$has=model::getInstance()->issetField("dril",$data['pname']);
		if(!$has)
		{
			$num=model::getInstance()->addField("dril",$data['pname']);
		}
		return model::getInstance()->add("property",$data);
	}
	//该模型是针对于字段的操作
	 function all($fields="*")
	 {
	 	return model::getInstance()->select("property",$fields,"","time desc")->getAllResults();
	 }
	 function allByTime($fields="*")
	 {
	 	return model::getInstance()->select("property",$fields,"","time desc")->getAllResults();
	 }
	 //根据条件得到某一条数据、、一维数组
	 function getOne($id)
	 {
	 	//select之后会得到结果集 然后 getOne 对结果集进行二次处理、、
	 	return model::getInstance()->select("property","pid,pname,nickname,sys","pid=$id")->getOne();
	 }
	 //根据条件更新一条数据、
	 function update($data,$where="")
	 {
	 	if($data['pyuan']!=$data['pname'])
	 	{
	 		//先判断列名是否存在、
	 		$has=model::getInstance()->issetField("dril",$data['pyuan']);
	 		if($has)
	 		{
	 			model::getInstance()->updateField("dril",$data['pyuan'],$data['pname']);	
	 		}else{
	 			model::getInstance()->addField("dril",$data['pname']);
	 		}
	 	}
	 	unset($data['pyuan']);
	 	return model::getInstance()->update("property",$data,$where);
	 }
	 //根据条件删除一条数据
	 function delete($where,$name)
	 {
	 	$has=model::getInstance()->issetField("dril",$name);
	 	if($has)
	 	{
	 		model::getInstance()->deleteField("dril",$name);
	 	}
	 	return model::getInstance()->delete("property",$where);
	 }
}



?>