<?php
namespace app\controller;
/*use core\database\PDO;
use core\database\mysqli;*/
class IndexController extends Controller{
	public function index()
	{
		$this->assign("name","xuzenghui");
		//加上文件夹 以及 后缀、、
		$this->display("index/index.html");

	}
	public function copy()
	{
		$this->display("index/copy.html");
	}
	public function out()
	{
		unset($_SESSION['username'],$_SESSION['password'],$_SESSION['role']);
		session_destroy();
		$this->success("退出成功!");
	}
/*	function easy()
	{
		echo "该模块用于联网状态与远程服务器对接，同步更新各设备的数据!";
	}*/

	function connect()
	{
		$host='127.0.0.1:2345';
		$socket=@stream_socket_client($host,$error,$errorno,5);
		if($socket)
		{
			fputs($socket,'exit');
		}
		$this->success("成功断开连接!");
	}
}




?>