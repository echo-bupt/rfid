<?php
/*****

pdo 工具类

*****/
namespace core\database;
use core\lib\config;

class PDO extends model{
	private $coon;
	private $statement;
	private $lastSql;//得到上一条语句便于打印调试、、
	public function __construct()
	{
		$config=config::all("database");
		extract($config);
		try{
			$dsn="nysql:host=$host;dbname=$database";
			$this->coon=new \PDO($dsn,$user,$password);
		}catch(\Exception ex)
		{
			echo ex.getMessage();
		}
	}
	//就是执行 sql 语句 以及 对结果集的处理不是 一样的、、所以要进行重写、、

	public function query($sql)
	{
		$this->lastSql=$sql;
		$this->statement=$this->coon->prepare();//得到 PDOSTATEMENT 对象 便于统一处理、、
		$ret=$this->statement->execute();
		if($ret==false)
		{
			$error		   = $this->statement->errorInfo();
			$this->error   = $error[2];
			$this->errno   = $this->statement->errorCode();
			if($this->errno) {
				cerror("mysql error:".$this->errno."  ".$this->error);
			}
		}
		return $this;
	}
	//首先是针对有结果集的处理 
	public function getAll()
	{	
		$data=$this->statement->fetchAll();
		return $data;
	}
	//针对 insert 语句 以及 delete和update 语句的处理
	public function getInsertId(){

	}
	public function getAffectedRows()
	{

	}
}




?>