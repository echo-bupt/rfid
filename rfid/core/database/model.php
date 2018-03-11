<?php
/*****

数据库操作类基类

*****/
namespace core\database;
use core\lib\config;
class model{
	public static $instance;
	public static function getInstance()
	{
		$driver=strtolower(config::get("driver","database"));
		if(self::$instance)
		{
			return self::$instance;
		}else{
			if($driver=="mysqli")
			{
				self::$instance=new mysqli();
			}elseif($driver=="pdo")
			{
				self::$instance=new pdo();
			}
			return self::$instance;
		}
	}
	public function select($tableName,$fields="",$where="",$order="",$limit="",$distinct=false)
	{
		if($fields=="*")
		{
			$fieldstr="*";
		}else if($distinct){
			$fieldstr="DISTINCT $fields";
		}else{
			$fieldstr=implode(array_map(array($this,"escapekey"),explode(",", $fields)),",");
		}
		$tableName=$this->escapekey($tableName);
		$sql="SELECT $fieldstr FROM $tableName"
		.$this->buildWhere($where)
		.$this->buildOrderBy($order)
		.$this->buildLimit($limit)
		;
		$this->query($sql);
		return $this;
	}
	public function escapekey($key)
	{
		//对于 单引号包裹的PHP变量 '"'.$bian.'"'  '`'$bian'`' 
		return '`'.$key.'`';
	}
	public function escapeval($val)
	{
		return is_array($val)?array_map(array($this,"escapeval"), $val):"'$val'";
	}
	public function buildWhere($where)
	{
		if(!$where) return "";
		$constr=isset($where['constr'])?" ".$where['constr']." ":" AND ";
		$wherestr=" WHERE 1=1 ";//因为总是要拼接 .= 可以默认先设置一个 where条件、、以后可以使用.=了
		if(is_array($where))
		{
			foreach ($where as $k => $v) {
				// 每一次循环拼接 AND 关键字 实现 每一个键值对的拼接、
				$wherestr.=$constr;
				//i不区分大小写、 id in(1,2,3) name like '%..%'
				if(preg_match('/\bin\b|\blike\b/i', $v))
				{
					$wherestr.=$this->escapekey($k)."".$v;
				}else{
					$wherestr.=$this->escapekey($k)."=".$this->escapeval($v);
				}

			}
		}else{
			//"name=haha,age=1" 这种、
			$wherearr=explode(",", $where); //只要字符串存在即可 至少会转换为 array(0=>"xuzenghui")
			foreach ($wherearr as $k => $v) {
				$wherestr.=$constr;
				//排除掉 >=!!
				if(strpos($v, "=")!==false && !(strpos($v, ">=")||strpos($v, "<=")))
				{
					$temp=explode("=", $v);
					$wherestr.=$this->escapekey($temp[0])."=".$this->escapeval($temp[1]);
				}elseif (preg_match("/\bin\b|\blike\b/i", $v)) {
					$wherestr.=$v;
				}else{
					$wherestr.=$v;
				}
			}
		}
		return $wherestr;
	}

	public function buildLimit($limit)
	{
		//limit 可以是 array(0,3) 或者是 1,3 limit 0,3变成这个样子
		//在$limit存在的条件下 !limit判断的是 '' 或者是 array() 
		if(!$limit) return '';
		$limitstr=" LIMIT ";
		$limitstr.=is_array($limit)?$limit[0].",".$limit[1]:$limit;
		return $limitstr;
	}

	public function buildOrderBy($order)
	{
		//输入形式 array("name"=>"asc",) name asc，age desc 这种、、可能输入 name 要手动加上 asc
		//输出形式`name` ASC,`age` DESC 第二个要大写、
		if(!$order) return "";
		$orderstr=" ORDER BY ";
		if(is_array($order))
		{
			foreach ($order as $k => $v) {
				$orderstr.=$k." ".strtoupper($v).",";
			}
		}else{
			$orderarr=explode(",", trim($order,","));
			foreach ($orderarr as $k => $v) {
				//要判断有无空格 来决定 ASC、DESC是否要使用默认值、
				if(strpos($v," " )!==false)
				{
					$temp=explode(" ", $v);
					$orderstr.=$temp[0]." ".strtoupper($temp[1]).",";
				}else{
					$orderstr.=$v." ASC".",";
				}
			}
		}
		return trim($orderstr,",");
	}
	//增加数据到数据库
	public function add($table,$data=array())
	{
		if(is_array($data) && !empty($data))
		{
			//insert into table(name1,name2) values(val,val2)
			$sql="INSERT INTO ".$this->escapekey($table)
			."(".$this->getKeyValues($data)['key'].")"
			. " VALUES(".$this->getKeyValues($data)['val'].")";
			$this->query($sql);
			return $this->getLastId();
		}
	}
	//组织 add 语句所需 SQL
	public function getKeyValues($data)
	{
		$ret=array("key"=>"","val"=>"");
		foreach ($data as $k => $v) {
			$ret['key'].=$this->escapekey($k).",";
			$ret['val'].=$this->escapeval($v).",";
		}
		$ret['key']=trim($ret['key'],",");
		$ret['val']=trim($ret['val'],",");
		return $ret;
	}
	public function update($table,$data,$where)
	{
		//update cat set name='',age='' where cid='1';
		$sql="UPDATE ".$this->escapekey($table)
		." SET "
		.$this->buildArr($data)
		.$this->buildWhere($where)
		;
		$this->query($sql);
		return $this->getAffectedRows();
	}
	//组织 update 语句所需 SQL
	public function buildArr($data)
	{
		$buildstr="";
		if(is_array($data))
		{
			foreach ($data as $k => $v) {
				if(preg_match("/[a-z]+[+-]/", $v))
				{
					$buildstr.=$this->escapekey($k)."=".$v.",";
				}else{
					$buildstr.=$this->escapekey($k)."=".$this->escapeval($v).",";
					}
			}
		}
		//注意 trim 操作后结果 返回了出来 本身 对原操作目标不会有影响、、
		//要么使用 return trim 来返回 要么 使用变量接受 trim的结果、、
		return trim($buildstr,",");	
	}
	function delete($table,$where)
	{
		$delete="DELETE FROM ".$this->escapekey($table)
		.$this->buildWhere($where)
		;
		$this->query($delete);
		return $this->getAffectedRows();
	}

	// $join提供三个条件 on 连接条件、on 左表的字段=右表的字段 jtype连接方式 on =>符串以逗号分割
	// ，分割两个表的、、jtype=>"连接方式"
	//fields a=> 左表的字段 b=>右表的字段
	//细节:匹配所有的字母 替换成其余字符、将 name as name2 只是将 `name` as `name2`  不动as
	//这样 以字母开头的正则匹配确实起到好效果、、能将as 左右的 字符串匹配到
	function join($ltable,$rtable,$join,$fields="",$where="",$order="",$limit="")
		{
			//join type、、
			$jtype=isset($join['jtype'])?$join['jtype']:"INNER JOIN";
			//on 条件
			//strpos
			$onarr=(strpos($join['on'],",")!==false)?explode(",", $join['on']):array($join['on'],$join['on']);
			//字段、
			//可以是空数组、、
			//空数组 相当于不循环、能进入后面的要么是正常字段 要么是* 、、
			$larr=isset($fields['a'])?explode(",", $fields['a']):array("*");
			$rarr=isset($fields['b'])?explode(",", $fields['b']):array("*");
			$lfieldstr="";
			foreach ($larr as $k => $v) {
				//匹配到重命名的关键字as、、name as name1 => `name` as `name1` 
				//为了不匹配到 as 使用以字母开头的匹配方式、、
				if(preg_match('/\bas\b/i', $v))
				{
					//每一个字段实际上 都加上了 a.字段1,a.字段2....
					$lfieldstr.="a.".preg_replace('/^([\w]+)/', $this->escapekey('$1'), $v).",";
				}else{
					$lfieldstr.="a.".($v=="*"?"*":$this->escapekey($v)).",";
				}
			}
			$lfieldstr=trim($lfieldstr,",");
			$rfieldstr="";
			foreach ($rarr as $k => $v) {
				//匹配到重命名的关键字as、、name as name1 => `name` as `name1` 
				//为了不匹配到 as 使用以字母开头的匹配方式、、
				if(preg_match('/\bas\b/i', $v))
				{
					$rfieldstr.="b.".preg_replace('/^([\w]+)/', $this->escapekey('$1'), $v).",";
				}else{
					$rfieldstr.="b.".($v=="*"?"*":$this->escapekey($v)).",";
				}
			}
			$rfieldstr=trim($rfieldstr,",");

			$joinstr="SELECT $lfieldstr,$rfieldstr FROM "
			.$this->escapekey($ltable)." a ".$jtype." ".$this->escapekey($rtable)." b "
			." ON a.".$this->escapekey($onarr[0])."=b.".$this->escapekey($onarr[1])
			.$this->buildWhere($where)
			.$this->buildOrderBy($order)
			.$this->buildLimit($limit);
			$this->query($joinstr);
			return $this;
		}


	//字段处理:

	//增加表字段:ALTER TABLE `dril` ADD `time` VARCHAR(32) NOT NULL ; 增加、

	//修改字段名:ALTER TABLE `dril` CHANGE `time2` `time` VARCHAR(32) CHARACTER SET utf8 NOT NULL DEFAULT ''

	//删除表字段:ALTER TABLE `dril` DROP `tim2`;

	//数据库一个是设置字段的字符集(存储规则) 特别是汉字以什么编码存储、 一个是设置字段的校对集(

	//排序规则)

	function addField($table,$name)
	{
		//添加 值为 默认空字符串 也需要使用 形如 '$变量' 这样 进行添加、、
		//添加默认 "" 需要 '\"\"'
		//当然添加空 可以是 '""' 
		$addstr="ALTER TABLE ".$this->escapekey($table)
		." ADD ".$this->escapekey($name)
		." VARCHAR(64) CHARACTER SET utf8 NOT NULL DEFAULT '\"\"'";
		$this->query($addstr);
		return true;
	}

	function updateField($table,$yuan,$name)
	{
		//注意更改字段名时 后面被更名的字段 也要加上 varchar(32) 等添加字段时的信息、、
		$updatestr="ALTER TABLE ".$this->escapekey($table)
		." CHANGE ".$this->escapekey($yuan)." ".$this->escapekey($name)
		." VARCHAR(64) CHARACTER SET utf8 NOT NULL DEFAULT '\"\"'"
		;
		$this->query($updatestr);
		return true;
	}

	function deleteField($table,$name)
	{
		$deletestr="ALTER TABLE ".$this->escapekey($table)
		." DROP ".$this->escapekey($name);
		$this->query($deletestr);
		return true;
	}

	//判断某个表里面是否已经存在某字段、

	function issetField($table,$name)
	{
		$fields=$this->select($table,"*")->getAllFields();
		return in_array($name, $fields);
	}
}




?>