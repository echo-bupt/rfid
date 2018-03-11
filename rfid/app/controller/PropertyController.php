<?php
namespace app\controller;
use app\model\PropertyModel;
class PropertyController extends Controller{
	function index()
	{
		$model=new PropertyModel();
		$data=$model->all();
		$this->assign("data",$data);
		$this->display("property/index.html");
	}
	function add()
	{
		if(ISPOST())
		{
			$model=new PropertyModel();
			$_POST['time']=strtotime($_POST['time']);
			$id=$model->add($_POST);
			if($id)
			{
				$this->postSuccess("添加成功!");
			}else{
				$this->postError("添加失败!");
			}
		}else{
			$this->display("property/add.html");
		}
	}
	function edit()
	{
		$model=new PropertyModel();
		if(ISPOST())
		{	
			//unset 是一条语句 不是函数 没有返回值、、
			$_POST['time']=strtotime($_POST['time']);
			$num=$model->update($_POST,$where="pid={$_POST['pid']}");
			if($num)
			{
				$this->postSuccess("修改成功!");
			}else{
				$this->postError("修改失败!");
			}
		}else{
			$pid=$_GET['pid'];
			$data=$model->getOne($pid);
			$this->assign("data",$data);
			$this->display("property/edit.html");
		}
	}
	function delete()
	{
		$pid=$_GET['pid'];
		$model=new PropertyModel();
		$data=$model->getOne($pid);
		if($data['sys']==1)
		{
			$this->postError("系统字段,不允许删除!");
		}else{
			$num=$model->delete("pid=$pid",$data["pname"]);
			$this->success("删除成功!");
		}
	}
}




?>