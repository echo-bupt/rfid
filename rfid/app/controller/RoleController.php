<?php
namespace app\controller;
use app\model\roleModel;
use core\lib\config;
class RoleController extends Controller{
	function index()
	{
		$smodel=new roleModel();
		$data=$smodel->all();
		$this->assign("data",$data);
		$this->display("role/index.html");
	}
	function add()
	{
		$smodel=new roleModel();
		if(ISPOST())
		{
			$post=trims($_POST);
			$rname=$post['rname'];
			$rdata=$smodel->getOne("rid","rname=$rname");
			if($rdata)
			{
				$this->postError("该角色已存在!");
			}else{
				if(isset($post['right']) && $post['right'])
				{
					//通过 checkbox 提交的数据已经是一个数组
					$right=$post['right'];
					$rights=array();
					$roles=array();
					$previges=config::all("role");
					//从配置文件中读取权限、
					foreach ($right as $k => $v) {
						if(array_key_exists($v, $previges))
						{
							$rights=array_merge($rights,$previges[$v]);
							$roles[]=$v;
						}
					}
					$right=serialize($rights);
					$post['right']=$right;
					$roles=serialize($roles);
					$post['role']=$roles;
					$post['time']=date("Y-m-d H:i:s",time());
					$sid=$smodel->add($post);
					if($sid)
					{
						$this->postSuccess("添加成功!");
					}			
				}else{
						$this->postError("您没有为角色设置任何权限!");
				}
			}
		}else{
			$this->display("role/add.html");
		}
	}
	function out()
	{
		unset($_SESSION['rolename'],$_SESSION['password'],$_SESSION['role']);
		session_destroy();
		$this->postSuccess("退出成功!");
	}
	function edit()
	{
		$rmodel=new roleModel();
		if(ISPOST())
		{
			$post=trims($_POST);
			$rid=$post['rid'];
			if(isset($post['right']) && $post['right'])
			  {
				$right=$post['right'];
				$rights=array();
				$roles=array();
				$previges=config::all("role");
					//从配置文件中读取权限、
				foreach ($right as $k => $v) {
					if(array_key_exists($v, $previges))
					{
						//array_merge需要一个变量 来接受 返回结果、、
						$rights=array_merge($rights,$previges[$v]);
						$roles[]=$v;
					}
				}
				$right=serialize($rights);
				$roles=serialize($roles);
				$post['role']=$roles;
				$post['right']=$right;
				$post['time']=date("Y-m-d H:i:s",time());
				$sid=$rmodel->update($post,"rid=$rid");
				if($sid)
				{
					$this->postSuccess("更新成功!");
				}			
				}else{
						$this->postError("您没有为角色设置任何权限!");
				}
		}else{
			$rid=$_GET['rid'];
			$data=$rmodel->getOne("right,rname,rid,role","rid=$rid");
			$data['right']=unserialize($data['right']);
			$data['role']=unserialize($data['role']);
			$this->assign("data",$data);
			$this->display("role/edit.html");
		}
	}
	function delete()
	{
		$rid=$_GET['rid'];
		$umodel=new roleModel();
		$rid=$umodel->delete("rid=$rid");
		if($rid)
		{
			$this->success("删除成功!");
		}
	}
}



?>