<?php
namespace app\controller;
use app\model\userModel;
use app\model\roleModel;
class UserController extends Controller{
	function index()
	{
		$smodel=new userModel();
		$data=$smodel->join("role");
		$this->assign("data",$data);
		$this->display("user/index.html");
	}
	function add()
	{
		$smodel=new userModel();
		$rmodel=new roleModel();
		if(ISPOST())
		{
			$post=trims($_POST);
			if($post['password']!=$post['password2'])
			{
				$this->postError("两次输入的密码不一致!");
				exit();
			}
			$uname=$post['username'];
			$sid=$smodel->getOne("sid","username=$uname");
			if($sid)
			{
				$this->postError("该用户名已存在!");
				exit();
			}
			unset($post['password2']);
			$post['time']=date("Y-m-d H:i:s",time());
			$sid=$smodel->add($post);
			if($sid)
			{
				$this->postSuccess("添加成功!");
			}
		}else{
			$data=$rmodel->all("rname,rid");
			$this->assign("data",$data);
			$this->display("user/add.html");
		}
	}
	function edit()
	{
		$smodel=new userModel();
		$rmodel=new roleModel();
		if(ISPOST())
		{
			$post=trims($_POST);
			if($post['password']!=$post['password2'])
			{
				$this->postError("两次输入的密码不一致!");
				exit();
			}
			//删掉多余的字段不然会导致更新不正确、、
			$uname=$post['username'];
			$sid=$smodel->getOne("sid","username=$uname");
			unset($post['password2']);
			$sid=$post['sid'];
			$post['time']=date("Y-m-d H:i:s",time());
			//必须 全部更新字段 都涉及到 才算更新成功! 
			$sid=$smodel->update($post,"sid=$sid");
			$_SESSION['super']=false;
			if($sid)
			{
				$this->postSuccess("更新成功!");
			}
		}else{
			$sid=$_GET['sid'];
			$sdata=$smodel->getOne("*","sid=$sid");
			$data=$rmodel->all("rname,rid");
			$this->assign("sdata",$sdata);
			$this->assign("data",$data);
			$this->display("user/edit.html");
		}
	}
	function delete()
	{
		$sid=$_GET['sid'];
		$umodel=new userModel();
		$sid=$umodel->delete("sid=$sid");
		if($sid)
		{
			$this->success("删除成功!");
		}
	}
}



?>