<?php
namespace app\controller;
use app\model\catModel;
class ToolController extends Controller{
	function index()
	{
		$model=new catModel();
		$data=$model->all();
		$this->assign("data",$data);
		$this->display("tool/index.html");
	}
	function add()
	{
		if(ISPOST())
		{
			$model=new catModel();
			$_POST['time']=strtotime($_POST['time']);
			$id=$model->add($_POST);
			if($id)
			{
				$this->postSuccess("添加成功!");
			}else{
				$this->postError("添加失败!");
			}
		}else{
			$this->display("tool/add.html");
		}
	}
	function edit()
	{
		$model=new catModel();
		if(ISPOST())
		{	
			//unset 是一条语句 不是函数 没有返回值、、
			$_POST['time']=strtotime($_POST['time']);
			$num=$model->update($_POST,$where="cid={$_POST['cid']}");
			if($num)
			{
				$this->postSuccess("修改成功!");
			}else{
				$this->postError("修改失败!");
			}
		}else{
			$cid=$_GET['cid'];
			$data=$model->getOne("cid,cname","cid=$cid");
			$this->assign("data",$data);
			$this->display("tool/edit.html");
		}
	}
	function delete()
	{
		$cid=$_GET['cid'];
		$model=new catModel();
		$num=$model->delete("cid=$cid");
		if($num)
		{
			$this->success("删除成功!");
		}else{
			$this->error("删除失败!");
		}
	}

}



?>