<?php
namespace app\controller;
use app\model\userModel;
class LoginController extends Controller{
	function __construct()
	{
		//echo TPL;
	}
	function index()
	{
		//echo TPL;
		$this->display("login/index.html");
	}
	function code()
	{
		include 'third/common/image.php';
		 \Image::ImageVerify();
	}
	function login()
	{
		//找一下 error 页面、、仿照实现 报错跳转页面的实现、、
		$post=trims($_POST);
		$uname=$post['userName'];
		$verycode=$post['verify'];
		if(strtoupper($verycode)!=strtoupper($_SESSION['verify']))
		{
			$this->error("验证码不正确!");
		}
		if($uname)
		{
			$smodel=new userModel();
			$user=$smodel->getOne("*","username=$uname");
			if($user && $user['password']==$post['psd'])
			{
				$_SESSION['username']=$user['username'];
				$_SESSION['pwd']=$user['password'];
				$_SESSION['role']=$user['role'];
				$_SESSION['super']=$user['super'];
				$this->success("登陆成功!","/index/index");
			}else{
				$this->error("用户名或者密码错误");
			}
		}else{
			$this->error("用户名或者密码错误!");
		}
	}
}
