<?php
namespace app\controller;
use app\model\unitModel;
use app\model\troopModel;
class UnitController extends Controller{
	function index()
	{
		$model=new unitModel();
		$data=$model->all();
		$this->assign("data",$data);
		$this->display("unit/index.html");
	}
	function add()
	{
		if(ISPOST())
		{
			$model=new unitModel();
			$id=$model->add($_POST);
			if($id)
			{
				$this->postSuccess("添加成功!");
			}
		}else{
			$this->display("unit/add.html");
		}
	}
	//删除某个二级单位 以及下属井队、
	function delete()
	{
		$uid=$_GET['uid'];
		$umodel=new unitModel();
		$tmodel=new troopModel();
		$umodel->delete("uid=$uid");
		$tmodel->delete("uid=$uid");
		$this->success("删除成功!");
	}
	function edit()
	{
		if(ISPOST())
		{
			$model=new unitModel();
			$post=trims($_POST);
			$uid=$post['uid'];
			$id=$model->update($post,"uid=$uid");
			if($id)
			{
				$this->postSuccess("更新成功!");
			}
		}else{
			$uid=$_GET['uid'];
			$umodel=new unitModel();
			$udata=$umodel->getOne("uid,uname","uid=$uid");
			$this->assign("data",$udata);
			$this->display("unit/edit.html");
		}
	}
}




?>