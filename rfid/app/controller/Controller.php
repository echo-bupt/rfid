<?php
namespace app\controller;
use core\lib\config;
use app\model\userModel;
use app\model\roleModel;
class Controller{
	protected $assign=array();
	function __construct()
	{
		$uname=isset($_SESSION['username'])?$_SESSION['username']:'';
		if($uname)
		{
			$controller=isset($_GET['c'])?$_GET['c']:"index";
			if(strtolower($controller)!="index")
			{
				//非 index 控制器 进行 拦截、
				$auth=$this->beforeAction($uname);
				if(!$auth)
				{
					$this->error("您没有相关权限！请联系超级管理员开通此权限!");
				}
			}
		}else{
			go('login/index');
		}
	}
	public function assign($key,$val)
	{
		$this->assign[$key]=$val;
	}
	public function display($file,$data=array())
	{
		$assignData=$this->assign;
		if(is_array($data) && !empty($data))
		{
			$assignData=array_merge($this->assign,$data);
		}
		$assignData["TPL"]="/app/views/tpl";
		$_GET['file']=$file;
		//引入模板文件、理论上编译 然后使编译后的PHP文件 得以执行、、使PHP文件能够访问到 数据变量
		//执行 PHP 文件后 不让其 直接输出 使用 OB缓存得到 缓存文件、、
		// $filepath=APP."/views/$file";
		if(strpos($file, "/")===false)
		{
			die("载入模板文件格式输入错误!");
		}
		$temp=explode("/", $file);
		$dir=APP."/views/tpl/".$temp[0];
		$file=$temp[1];
		if(!is_dir($dir))
		{
			mkdir($dir,0777,true);
			die("模板文件不在指定位置!");
		}else{
			$filename=$dir."/".$temp[1];

			if(file_exists($filename))
			{
				//不使用命名空间 那么 就相当于 在 index.php 直接 include 某文件 使用全局命名空间进行 
				//访问即可、、
				$compieldDir=config::get("compiled","view");
				$cacheDir=config::get("cache","view");
				$cachetime=config::get("cacheTime","view");
				$dwoo	  = new \Dwoo($compieldDir,$cacheDir);
				$compiler =\Dwoo_Compiler::compilerFactory();
				$compiler->setDelimiters("{{","}}");
				$dwoo->setCacheTime($cachetime);
				$dwoo->output($filename,$assignData,$compiler);
				exit();
			}else{
				die("模板文件不存在!");
			}
		}
		//已载入模板文件、

	}
	public function error($error="",$filename="public/error.html")
	{
		$error=$error?$error:"出错了";
		$data=array("msg"=>$error);
		$data['url']="window.history.go(-1)";
		$data['time']=2;
		$this->display($filename,$data);
	}
	function success($success="",$url='',$filename="public/success.html")
	{
		$success=$success?$success:"成功了";
		$data=array("msg"=>$success);
		if($url)
		{
			$data['url']="window.location.href='$url'";
		}else{
			$data['url']="window.history.go(-1)";
		}
		$data['time']=2;
		$this->display($filename,$data);
	}
	function postSuccess($success="",$url='',$filename="public/success.html")
	{
		$success=$success?$success:"成功了";
		$data=array("msg"=>$success);
		if($url)
		{
			$data['url']="window.location.href='$url'";
		}else{
			$data['url']="window.history.go(-2)";
		}
		$data['time']=2;
		$this->display($filename,$data);	
	}
	function postError($success="",$url='',$filename="public/error.html")
	{
		$success=$success?$success:"失败了";
		$data=array("msg"=>$success);
		if($url)
		{
			$data['url']="window.location.href='$url'";
		}else{
			$data['url']="window.history.go(-2)";
		}
		$data['time']=2;
		$this->display($filename,$data);	
	}
	function postErrorLong($success="",$url='',$filename="public/error.html")
	{
		$success=$success?$success:"失败了";
		$data=array("msg"=>$success);
		if($url)
		{
			$data['url']="window.location.href='$url'";
		}else{
			$data['url']="window.history.go(-2)";
		}
		$data['time']=3.5;
		$this->display($filename,$data);	
	}
	function beforeAction($uname)
	{
		$umodel=new userModel();
		$rmodel=new roleModel();
		$udata=$umodel->getOne("role,super","username=$uname");
		$controller=isset($_GET['c'])?$_GET['c']:"index";
		if($udata)
		{
			$role=$udata['role'];
			$super=$udata['super'];
			if($super==1)
			{
				$_SESSION['super']=1;
			}else{
				$_SESSION['super']=false;
			}
			//得到该用户所有权限
			$rights=$rmodel->getOne("right","rid=$role")['right'];
			$rightList=unserialize($rights);
			if(in_array(strtolower($controller), $rightList))
			{
				return true;
			}
			return false;
		}else{
			$this->error("用户不存在!");
		}
	}
}



?>