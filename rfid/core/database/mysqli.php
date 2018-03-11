<?php
/*****

mysqli 工具类

*****/
namespace core\database;
use core\lib\config;
class mysqli extends model{
	private $coon;
	private $result;
	public function __construct()
	{
		$config=config::all("database");
		extract($config);
		$this->coon=new \mysqli($host,$user,$password,$database);
		$this->coon->set_charset("UTF8");
		return $this->coon;
	}

	public function query($sql)
	{
		$query=$this->coon->query($sql);
		if($query==false)
		{
			echo "错误编号为:".$this->coon->errno."\r\n";
			echo  "错误信息为:".$this->coon->error;
			exit();
		}
		$this->result=$query;
		return $this;
	}

	public function getAllResults()
	{
		if($this->result)
		{
			$data=$this->result->fetch_all(MYSQLI_ASSOC);
			return $data;
		}else{
			die("没有结果集!");
		}
	}

	public function getOne()
	{
		if($this->result)
		{
			$data=$this->result->fetch_assoc();
			return $data;
		}else{
			die("没有结果集!");
		}
	}

	public function getLastId()
	{
		return $this->coon?$this->coon->insert_id:0;
	}
	public function getAffectedRows()
	{
		return $this->coon?$this->coon->affected_rows:0;
	}

	//结果集关于对字段属性的处理
	public function getAllFields()
	{
		$fields=array();
		if($this->result)
		{
			//结果对象数组、、
			$arrObjects=$this->result->fetch_fields();
			foreach ($arrObjects as $key => $value) {
				$fields[]=$value->name;
			}
			return $fields;
		}else{
			die("没有结果集!");
		}
	}

	public function __destruct()
	{
		//关于对 结果集对象 以及 数据库连接资源的 垃圾回收、、
	}

}


?>