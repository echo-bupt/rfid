<?php
namespace app\controller;
use app\model\drilModel;
use app\model\propertyModel;
use app\model\catModel;
use app\model\unitModel;
use app\model\troopModel;
use app\model\outModel;
use app\model\stateModel;
class DrilController extends Controller{
	function index()
	{
		require 'third/common/page.php';
		$model=new drilModel();
		$pmodel=new propertyModel();
		$smodel=new catModel();
		//待改进 将两个表进行join 查询、、
		$sort=$smodel->all();
		$sortres=$this->getsortres($sort);
		//排序 完全 取决于 property表内的顺序、、
		$property=$pmodel->allByTime();
		//keys的顺序与property相同、在模板里面 keys 的顺序保证了与 property相同、、
		$keys=$this->getKeys($property);
		//通过分页 显示一定数量的数据、、
		//第二个参数页码数 第三个参数每页显示条数、
		$count=$model->count();
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagenum=isset($_GET['page'])?$_GET['page']:1;
		$pagestr=$pageObj->showPages(1);
		$datares=$model->all("*",$limit);
		$data=$this->getAllRes($datares,$sortres);
		$this->assign("bili",100/count($property));
		$this->assign("keys",$keys);
		$this->assign("pagenum",$pagenum);
		$this->assign("page",$pagestr);
		$this->assign("property",$property);
		$this->assign("data",$data);
		$this->display("dril/index.html");
	}
	function add()
	{
		if(ISPOST())
		{
			$post=trims($_POST);
			$post['pro_time']=strtotime($post['pro_time']);
			$post['add_time']=strtotime($post['add_time']);
			$model=new drilModel();
			$epcarr=explode(",", $post['epc']);
			$epclen=count($epcarr);
			for($i=0;$i<$epclen;$i++)
			{
				$dril=$dmodel->getOneBy("did","epc=$v");
				if(!$dril)
				{
					$post['epc']=$epcarr[$i];
					$id=$model->add($post);
				}
			}
			if($id)
			{
				$this->postSuccess("添加成功!");
			}else{
				$this->postError("添加失败,钻具已存在!");
			}
		}else{
			$model=new propertyModel();
			$somodel=new catModel();
			$data=$model->allByTime();
			$sort=$somodel->all();
			$this->assign("sort",$sort);
			$this->assign("data",$data);
			$this->display("dril/add.html");
		}
	}
	function edit()
	{
		$model=new drilModel();
		if(ISPOST())
		{	
			//unset 是一条语句 不是函数 没有返回值、、
			//strtotime是将 真正日期转换为 时间戳的形式、、
			//date 是将 时间戳转换为 真正日期 time()是获取 当前时间的时间戳、、
			$post=trims($_POST);
			$post['pro_time']=strtotime($post['pro_time']);
			$post['add_time']=strtotime($post['add_time']);
			$num=$model->update($post,$where="did={$post['did']}");
			if($num)
			{
				$this->postSuccess("修改成功!");
			}else{
				$this->postError("修改失败!");
			}
		}else{
			$did=$_GET['did'];
			$pmodel=new propertyModel();
			$cmodel=new catModel();
			$cat=$cmodel->all();
			$pdata=$pmodel->all();
			$data=$model->getJoinOne("cat",$did);
			$data['pro_time']=date("Y-m-d H:i:s",time());
			$data['add_time']=date("Y-m-d H:i:s",time());
			$this->assign("sort",$cat);
			$this->assign("pdata",$pdata);
			$this->assign("data",$data);
			$this->display("dril/edit.html");
		}
	}
	function paste()
	{
		$model=new drilModel();
		$did=$_GET['did'];
		$pmodel=new propertyModel();
		$cmodel=new catModel();
		$cat=$cmodel->all();
		$pdata=$pmodel->all();
		$data=$model->getJoinOne("cat",$did);
		$data['pro_time']=date("Y-m-d H:i:s",time());
		$data['add_time']=date("Y-m-d H:i:s",time());
		$this->assign("sort",$cat);
		$this->assign("pdata",$pdata);
		$this->assign("data",$data);
		$this->display("dril/paste.html");
	}
	function delete()
	{
		$did=$_GET['did'];
		$model=new drilModel();
		$num=$model->delete("did=$did");
		if($num)
		{
			$this->success("删除成功!");
		}else{
			$this->postError("删除失败!");
		}
	}

//注意就是判断 $pec 是否存在 这里不要 先trim 掉 逗号了、、因为 默认$pec默认是""

//并且如果没有消息的话 this.epc 不会有值进入、、

    function reader()
    {
		$host='127.0.0.1:2345';
		$socket=@stream_socket_client($host,$error,$errorno,5);
		if($socket)
		{
			fputs($socket,"read");
		}else{
			echo json_encode(array("status"=>"error","msg"=>"请先使用桌面程序连接蓝牙设备!"));
			exit();
		}
		stream_set_blocking($socket, 0);
		$epc='';
		$time1=time();
		while(true)
		{
			//sleep 与 count++ 踩到的坑？？？？
			//sleep 一次 count++了很多次！！！时间 才是真实的，不出错！！
			$time2=time();
			$epc=@fgets($socket);
			if($epc!==false)
			{
				break;
			}
			if($time2-$time1>=3)
			{
				break;
			}
			sleep(0.1);			
		}
		if($epc)
		{
			$epc=trim($epc,",");
			if($epc=='fail')
			{
				$epc='操作超时，请稍后重新连接设备!';
				echo json_encode(array("status"=>"error","msg"=>$epc));
			}else{
				$count=count(explode(',', $epc));
				echo json_encode(array("status"=>"success","epc"=>$epc,"count"=>$count));				
		}
		}else{
			echo json_encode(array("status"=>"error"));
		}
		fclose($socket);
    } 

 	function reader_mysql()
	{
		$state = new stateModel();
		$state->update(array("task"=>"read"),"sid=1");
		$time1=time();
		while(true)
		{
			$epc=$state->getOne("epc","sid=1")['epc'];
			if($epc)
			{
				$epc=trim($epc,",");
				$res=$state->update(array("epc"=>""),"sid=1");
				$count=count(explode(',', $epc));
				if($res)
				{
					echo json_encode(array("status"=>"success","epc"=>$epc,"count"=>$count));	
				}
				return;
			}
			$time2=time();
			if(($time2-$time1)>2)
			{
				echo json_encode(array("status"=>"error"));
				return;
			}
			sleep(0.1);
		}
	}

	function reader_mem()
	{
		$mem = new \Memcache;
		$mem->connect("127.0.0.1", 11211);
		$mem->delete("epc");
		$mem->set("read","1",MEMCACHE_COMPRESSED,60);
		$time1=time();
		while(true)
		{
			$epc=$mem->get("epc");
			$info=$mem->get("info");
			if($epc)
			{
				$epc=trim($epc,",");
				$count=count(explode(',', $epc));
				$mem->set("epc","",MEMCACHE_COMPRESSED,5);
				$res=$mem->delete("epc");
				if($res)
				{
					echo json_encode(array("status"=>"success","epc"=>substr($epc, 1),"count"=>$count));
					return;
				}
			}
			if($info)
			{
				$res=$mem->delete("info");
				echo json_encode(array("status"=>"check"));
				return;
			}
			$time2=time();
			if(($time2-$time1)>3)
			{
				echo json_encode(array("status"=>"error"));
				return;
			}
			sleep(0.05);
		}
	}

	function reader_yuan()
	{

		//设置脚本无限时间长、等待桌面程序返回、、

		//set_time_limit(0);

		echo 1;

		$num=isset($_POST['num'])?$_POST['num']:"1";

		$r=exec(THIRD."/reader/AsciiProtocolInventory.exe $num 2>&1",$output,$r2);

		//客户端判断 status 来确定是否正确、、

		set_time_limit(60);

		exit();


		if($r2==0)
		{
			echo json_encode(array("length"=>0,"status"=>"error","msg"=>"connection error"));
		}else{
			foreach ($output as $k => $v){
				$ret[]=$v;
			}
			$len=count($output);
			//读到的info 是一个数组、
			echo json_encode(array("length"=>$len,"status"=>"success","info"=>$ret));
		}
	}
	function getKeys($data)
	{
		$ret=array();
		foreach ($data as $key => $v) {
			$ret[]=$v['pname'];
		}
		return $ret;
	}

	function getsortres($res)
	{
		$ret=array();
		foreach ($res as $k => $v) {
			$ret[$v['cid']]=$v['cname'];
		}
		return $ret;
	}

	//将 cid 替换成 类别名称、、以及 将时间转换一下、、

	function getAllRes($data,$sort)
	{
		foreach ($data as $k => $v) {
			foreach ($v as $k2 => $v2) {
				if($k2=="cid")
				{
					$data[$k][$k2]=$sort[$v2];
				}
				if($k2=='pro_time' || $k2=='add_time')
				{
					$data[$k][$k2]=date("Y-m-d H:i:s",$v2);
				}
			}
		}
		return $data;
	}

	function getAllResult($data)
	{
		foreach ($data as $k => $v) {
			foreach ($v as $k2 => $v2) {
				if($k2=='pro_time' || $k2=='add_time')
				{
					$data[$k][$k2]=date("Y-m-d H:i:s",$v2);
				}
			}
		}
		return $data;
	}

	function getOneRes($data,$sort)
	{
		foreach ($data as $k => $v) {
			if($k=='cid')
			{
				$data[$k]=$sort[$v];
			}
			if($k=="pro_time" || $k=="add_time")
			{
				$data[$k]=date("Y-m-d H:i:s",$v);
			}
		}
		return $data;
	}
	function getNames($data)
	{
		$ret=array();
		foreach ($data as $k => $v) {
			$ret[]=$v['nickname'];
		}
		return $ret;
	}
	function getResByColumn($data)
	{
		$ret=array();
		if($data)
		{
			foreach ($data as $k => $v) {
				$ret[$v['cid']]=$v['cname'];
			}
		}
		return $ret;
	}

	function export()
	{
		$did=$_GET['did'];
		$model=new drilModel();
		$data=$model->getOne($did);
		$data['title']="钻具属性详细说明表";
		unset($data['did']);
		//这里完全可以使用 join 关联查询实现的、、
		$smodel=new catModel();
		$sorts=$smodel->all();
		$sortres=$this->getsortres($sorts);
		$data=$this->getOneRes($data,$sortres);
		$this->excel($data);
		$this->success("导出成功!");
	}

	function cat($data)
	{
		foreach ($data as $k => $v) {
				$data[$k]['cid']=$v['cname'];
		}
		return $data;
	}

	function exportIndexPage()
	{
		require 'third/common/page.php';
		$model=new drilModel();
		$count=$model->count();
		$pageObj=new \Page($count,5,5);
		$limit=$pageObj->limit(); //0,5
		$data=$model->join("cat",$limit);
		if($data)
		{
			$data=$this->getAllResult($data);
			$data=$this->cat($data);
			$data['title']="钻具属性详细说明表";
			$this->excelPage($data);
			$this->success("导出成功!");
		}else{
			$this->error("本页没有数据!");
		}
	}

	function exportAll()
	{
		require 'third/common/page.php';
		$model=new drilModel();
		$data=$model->join("cat","");
		$data=$this->getAllResult($data);
		$data=$this->cat($data);
		$data['title']="钻具属性详细说明表";
		$this->excelPage($data);
		$this->success("导出成功!");
	}

	function exportFindPage()
	{
		require 'third/common/page.php';
		$model=new drilModel();
		$where=$_SESSION['where']?$_SESSION['where']:"";
		$count=$model->joinCount("cat",$where);
		$pageObj=new \Page($count,5,5);
		$limit=$pageObj->limit(); //0,5
		$data=$model->joinWhere("cat",$where,$limit);
		if($data)
		{
			$data=$this->getAllResult($data);
			$data=$this->cat($data);
			$data['title']="钻具属性详细说明表";
			$this->excelPage($data);
			// ob_start();//要在输出内容前 将输出内容放到OB缓存、、
			$this->success("导出成功!");
		}else{
			$this->error("本页没有数据!");
		}
	}
	function check()
	{
		require 'third/common/page.php';
		$check=isset($_GET['check'])?$_GET['check']:0;
		$model=new drilModel();
		if($check)
		{
			//维修期 小于check 的、、即time+check<下一次维修时间
			$now=time()+$check*24*3600;
			$data=$model->joincheck("service","is_new=1,UNIX_TIMESTAMP(next)<=$now");
			$count=count($data);
			$pageObj=new \Page($count,5,5);
			// var_dump($pageObj->showPages(1));
			$limit=$pageObj->limit();
			$pagestr=$pageObj->showPages(1);
			$data=$model->joincheck("service","is_new=1,UNIX_TIMESTAMP(next)<=$now",$limit);
			$this->assign("check",$check);
		}else{
			$data=$model->joincheck("service","is_new=1");
			$count=count($data);
			$pageObj=new \Page($count,5,5);
			// var_dump($pageObj->showPages(1));
			$limit=$pageObj->limit();
			$pagestr=$pageObj->showPages(1);
			$data=$model->joincheck("service","is_new=1",$limit);
		}
		foreach ($data as $k => $v) {
			$data[$k]['next']=ceil((strtotime($v['next'])-time())/(3600*24));
		}
		$this->assign('page',$pagestr);
		$this->assign("data",$data);		
		$this->display("dril/check.html");
	}
	function out()
	{
		//办理出库处理、

		//钻具返还、
		require 'third/common/page.php';
		$model=new drilModel();
		$cmodel=new catModel();
		$count=$model->count();
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagestr=$pageObj->showPages(1);
		$data=$model->all("epc,did,cid,pro_factory,pro_time,add_time,mat,length,number,state",$limit);
		$cat=$cmodel->all();
		$cats=$this->getResByColumn($cat);
		$this->assign("cats",$cats);
		$this->assign("data",$data);
		$this->assign("page",$pagestr);
		$this->display("dril/out.html");
	}
	function outAdd()
	{
		if(ISPOST())
		{
			//添加出库操作、
			$umodel=new unitModel();
			$omodel=new outModel();
			$dmodel=new drilModel();
			$unit=$_POST['unit'];
			$epc=explode(',', $_POST['epc']);
			unset($_POST['epc']);
			$btn=true;
			$num=1;
			$errorMessage="";
			foreach ($epc as $k => $v) {
				$num++;
				$dril=$dmodel->getOneBy("did,state","epc=$v");
				if($dril)
				{
					 $did=$dril['did'];
					 //判断该钻具在库存中、
					if($dril['state']=='库存中')
					{
						$units=$umodel->getOne('uname',"uid=$unit");
						$uname=$units['uname'];
						$_POST['did']=$did;
						$_POST['unit']=$uname;
						$_POST['time']=date("Y-m-d H:i:s",time());
						$oid=$omodel->add($_POST);
						//修改状态出库钻具状态、
						$dmodel->update(array("state"=>"已出库"),"did=$did");
					}else{
						$errorMessage.="钻具编号:$v 已经出库,不可重复出库!";
					}
				}else{
					$errorMessage.="钻具编号:$v 出库失败,库存无此钻具!请先录入!";
				}			
			}
		if($errorMessage && $num>=3)
		{
			$this->postErrorLong("部分操作成功!".$errorMessage);
		}else if($errorMessage && $num==2)
		{
			$this->postErrorLong("操作失败!".$errorMessage);
		}else{
			$this->postSuccess('操作成功!');
		}				
		}else{
			$model=new drilModel();
			$umodel=new unitModel();
			$tmodel=new troopModel();
			$units=$umodel->all("uname,uid");
			$troops=$tmodel->all("tname,tid");
			$did=isset($_GET['did'])?$_GET['did']:0;
			$data=$model->getOne($did,'epc');
			$this->assign("troops",$troops);
			$this->assign("units",$units);
			$this->assign("epc",$data['epc']);
			$this->display("dril/outAdd.html");
		}
	}
	function outAjax()
	{
		$uid=$_POST['uid'];
		$tmodel=new troopModel();
		$data=$tmodel->all("tid,tname","uid=$uid");
		$result=array();
		foreach ($data as $k => $v) {
			$result[$v['tid']]=$v['tname'];
		}
		echo json_encode($result);
	}
	function outUpdateAjax()
	{
		$model=new outModel();
		$umodel=new unitModel();
		$unit=$_POST['unit'];
		$units=$umodel->getOne('uname',"uid=$unit");
		$uname=$units['uname'];
		$oid=$_POST['oid'];
		unset($_POST['oid']);
		$_POST['unit']=$uname;
		$model->update($_POST,"oid=$oid");
		echo json_encode(array('status'=>'success'));
	}
	function outDeleteAjax()
	{
		 $model=new outModel();
		 $oid=$_GET['oid'];
		 $model->delete("oid=$oid");
		 echo json_encode(array('status'=>'success'));
	}
	//查看出库操作以及编辑、、
	function outHistory()
	{
		require 'third/common/page.php';
		$did=isset($_GET['did'])?$_GET['did']:0;
		$omodel=new outModel();
		$model=new drilModel();
		$ddata=$model->getOne($did,'epc');
		$odata=$omodel->all('*',"did=$did","time desc");
		$count=count($odata);
		$pageObj=new \Page($count,5,5);
			// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagenum=isset($_GET['page'])?$_GET['page']:1;
		$pagestr=$pageObj->showPages(1);
		$data=$omodel->all("*","did=$did",$limit);
		$this->assign("data",$data);
		$this->assign("epc",$ddata['epc']);
		$this->assign("page",$pagestr);
		$this->display("dril/outHistory.html");	

	}

	function outEdit()
	{
		if(ISPOST())
		{
			//添加出库操作、
			$umodel=new unitModel();
			$omodel=new outModel();
			$dmodel=new drilModel();
			$post=trims($_POST);
			$unit=$post['unit'];
			$epc=$post['epc'];
			$oid=$post['oid'];
			unset($post['epc']);			
			$dril=$dmodel->getOneBy("did","epc=$epc");
			$did=$dril['did'];
			$omodel->update($post,"oid=$oid");
			$this->postSuccess("更新成功!");
		}else{
			$model=new drilModel();
			$umodel=new unitModel();
			$tmodel=new troopModel();
			$omodel=new outModel();
			$units=$umodel->all("uname,uid");
			$troops=$tmodel->all("tname,tid");
			$oid=isset($_GET['oid'])?$_GET['oid']:0;
			$odata=$omodel->getOne('*',"oid=$oid");
			//找到epc编号
			if($odata)
			{
				$did=$odata['did'];
				$data=$model->getOneBy("epc","did=$did");
			}
			$this->assign("data",$odata);
			$this->assign("troops",$troops);
			$this->assign("units",$units);
			$this->assign("epc",$data['epc']);
			$this->display("dril/outEdit.html");
		}
	}

	function outFind()
	{
		$this->display("dril/outFind.html");
	}
	function outEditPost()
	{
		require 'third/common/page.php';
		$epc=trim($_GET['epc']);
		$epc=explode(',', $epc)[0];
		$model=new drilModel();
		$omodel=new outModel();
		$did=$model->getOneBy("did","epc=".$epc)['did'];
		if($did){
			$odata=$omodel->all('oid',"did=$did","time desc");
			$count=count($odata);
			$pageObj=new \Page($count,5,5);
			// var_dump($pageObj->showPages(1));
			$limit=$pageObj->limit();
			$pagenum=isset($_GET['page'])?$_GET['page']:1;
			$pagestr=$pageObj->showPages(1);
			$data=$omodel->all("*","did=$did",$limit);
			$this->assign("data",$data);
			$this->assign("epc",$epc);
			$this->assign("page",$pagestr);
			$this->display("dril/outHistory.html");
		}else{
			$this->postError("该钻具不存在,未入库!");
		}
	}
	function outBack()
	{
		if(ISPOST())
		{
			$dmodel=new drilModel();
			$omodel=new outModel();
			$post=trims($_POST);
			$epc=explode(',', $post['epc']);
			$errorMessage="";
			$num=1;
			foreach ($epc as $k => $v) {
			$num++;
			$dril=$dmodel->getOneBy("did,state","epc=$v");
			if($dril)
			{
				if($dril['state']=="库存中")
				{
					$errorMessage.="钻具编号: $v 已归还或者未出库,不符合归还条件!";
				}else{
					$did=$dril['did'];
					$out=$omodel->getOne("oid","did=$did,isback=0","UNIX_TIMESTAMP(picktime) desc");
					$oid=$out['oid'];
					//更新dril表
					$dmodel->update(array('state'=>"库存中"),"did=$did");
					//更新out表	
					$omodel->update(array('backer'=>$post['backer'],'isback'=>'1','backtime'=>$post['backtime']),"oid=$oid");
					// echo "<script>alert('归还成功!');</script>";
				}
			}else{
				$errorMessage.="钻具编号:$v 归还失败,库存无此钻具!请先录入!";
			}
		}
		if($errorMessage && $num>=3)
		{
			$this->postErrorLong("部分操作成功!".$errorMessage);
		}else if($errorMessage && $num==2)
		{
			$this->postErrorLong("操作失败!".$errorMessage);
		}else{
			$this->postSuccess('操作成功!');
		}
	}else{
			$this->display("dril/back.html");
		}
	}
	function findDisplay()
	{
		$model=new drilModel();
		$number=$model->all("number","");
		$data=array();
		foreach ($number as $k => $v) {
			$data[]=$v['number'];
		}
		$data=array_unique($data);
		$this->assign("number",$data);
		$this->display("dril/find.html");
	}

	function find()
	{
		//高级查询、
		require 'third/common/page.php';
		$pmodel=new propertyModel();
		$model=new drilModel();
		$property=$pmodel->allByTime();
		$keys=$this->getKeys($property);
		$where="";
		if(ISPOST()){
		//PHP代码块 也是产生局部作用域的、、
			//	POST  数据如果 不填写 将会是 "" 但是会是 isset
			if(isset($_SESSION['where']))
			{
				unset($_SESSION['where']);
			}
			$add1=$_POST['add1']?strtotime($_POST['add1']):"";
			$add2=$_POST['add2']?strtotime($_POST['add2']):"";
			$pro1=$_POST['pro1']?strtotime($_POST['pro1']):"";
			$pro2=$_POST['pro2']?strtotime($_POST['pro2']):"";
			$factory=$_POST['factory']?$_POST['factory']:"";
			$mat=$_POST['mat']?$_POST['mat']:"";
			$number=$_POST['number']?$_POST['number']:"";
			//数字加引号吗
			if($add1 && !$add2)
			{
				$where.="`add_time`>'$add1' AND `add_time`<".time().",";
			}elseif (!$add1 && $add2) {
				$where.="`add_time`<'$add2',";
			}elseif($add1 && $add2)
			{
				$where.="`add_time`>'$add1' AND `add_time`<'$add2',";
			}
			if($factory)
			{
				$where.="`pro_factory` like '%{$factory}%',";
			}
			//数字加引号吗
			if($pro1 && !$pro2)
			{
				$where.="`pro_time`>'$pro1' AND `pro_time`<".time().",";
			}elseif (!$pro1 && $pro2) {
				$where.="`pro_time`<'$pro2',";
			}elseif($pro1 && $pro2)
			{
				$where.="`pro_time`>'$pro1' AND `pro_time`<'$pro2',";
			}
			if($mat)
			{
				//对于 like 和 in 没有处理、、对于下面的=还是处理了的 不需要加 ``与''
				$where.="`mat` like '%{$mat}%,'";
			}
			if($number)
			{
				$where.="number=$number,";
			}
			//将最后一个 AND 去掉、、
			$where=trim($where,",");
			//keys的顺序与property相同、在模板里面 keys 的顺序保证了与 property相同、、
			//通过分页 显示一定数量的数据、、
			//第二个参数页码数 第三个参数每页显示条数、
			$_SESSION['where']=$where;
		}
			$where=$where?$where:$_SESSION['where'];
			$count=$model->joinCount("cat",$where);
			$pageObj=new \Page($count,5,5);
			// var_dump($pageObj->showPages(1));
			$limit=$pageObj->limit();
			$data=$model->joinWhere("cat",$where,$limit);
			$data=$this->getAllResult($data);
			$pagenum=isset($_GET['page'])?$_GET['page']:1;
			$pagestr=$pageObj->showPages(1);
			$this->assign("pagenum",$pagenum);
			$this->assign("page",$pagestr);
			$this->assign("keys",$keys);
			$this->assign("property",$property);
			$this->assign("data",$data);
			$this->display("dril/fresult.html");
	}

	//测试增加属性 后 超过 Z 之后的 excel 表格导出问题、、

	

	function excel($data)
	{
		include 'third/excel/PHPExcel.php';
		$objPHPExcel = new \PHPExcel();  
    // Set properties    
    	$objPHPExcel->getProperties()->setCreator("ctos")  
            ->setLastModifiedBy("ctos")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
            ->setKeywords("office 2007 openxml php")  
            ->setCategory("Test result file");  
  
    // set width  
        $pmodel=new propertyModel();
		$property=$pmodel->allByTime();
		$propertynames=$this->getNames($property);
		$keys=$this->getKeys($property);
        $title=$data['title'];
        unset($data['title']);
        $cols=count(array_keys($keys));
        $end=65+$cols;
        for($i=65;$i<$end;$i++)
        {
        	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(25); 
        }
  
    // 设置行高度    
	    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);  
	  
	    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);  
  
    // 字体和样式  
	    //A1 行(后面合并了)与 A2行一整行 字体是 bold、、
	    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr($end-1)."2")->getFont()->setBold(true);  
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  //设置 A2 一行居中、
	    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr($end-1)."2")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr($end-1)."2")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);  
  
    // 设置水平居中    
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
	      for($i=65;$i<$end;$i++)
       	 {
        	$objPHPExcel->getActiveSheet()->getStyle(chr($i))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
         } 
	 $end=65+$cols;
    //  合并  
    	$objPHPExcel->getActiveSheet()->mergeCells("A1:".chr($end-1)."1");  
  
    // 表头 
    //题目
    	    // Rename sheet    
	 $objPHPExcel->getActiveSheet()->setTitle($title);  
     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title);

     $j=0;
    	for($i=65;$i<$end;$i++)
    	{
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i)."2",$propertynames[$j++]);
    	}
    //只有一行数据时、
    $j=0;
        for($i=65;$i<$end;$i++)
    	{
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i)."3",$data[$keys[$j++]]);
    	}	
    	//设置行高、
    	$objPHPExcel->getActiveSheet()->getStyle(chr($i)."3:".chr($end-1)."3")->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 $objPHPExcel->getActiveSheet()->getStyle(chr($i) .'3:'. chr($end-1)."3")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
  	     $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(30); 	  
	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
	    $objPHPExcel->setActiveSheetIndex(0);  
	  	//2007 excel
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
	    $filename=date("YmdHi",time());
	    $filedate=date("Ymd",time());
	    $dir="excel/".$filedate;
	    if(!is_dir($dir))
	    {
	    	mkdir($dir,0777,true);
	    }
	    $final=$dir."/".$filename;
	    if(file_exists($final))
	    {
	    	$final=$dir."/".date("YmdHis",time());
	    }
	    $objWriter->save($final.".xls");
	}

	function excelPage($data)
	{
		include 'third/excel/PHPExcel.php';
		$objPHPExcel = new \PHPExcel();  
    // Set properties    
    	$objPHPExcel->getProperties()->setCreator("ctos")  
            ->setLastModifiedBy("ctos")  
            ->setTitle("Office 2007 XLSX Test Document")  
            ->setSubject("Office 2007 XLSX Test Document")  
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")  
            ->setKeywords("office 2007 openxml php")  
            ->setCategory("Test result file");  
  
    // set width  
        $pmodel=new propertyModel();
		$property=$pmodel->allByTime();
		$propertynames=$this->getNames($property);
		$keys=$this->getKeys($property);
        $title=$data['title'];
        unset($data['title']);
        $cols=count(array_keys($keys));
        $end=65+$cols;
        for($i=65;$i<$end;$i++)
        {
        	$objPHPExcel->getActiveSheet()->getColumnDimension(chr($i))->setWidth(25); 
        }
  
    // 设置行高度    
	    $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);  
	  
	    $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(25);  
  
    // 字体和样式  
	    //A1 行(后面合并了)与 A2行一整行 字体是 bold、、
	    $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr($end-1)."2")->getFont()->setBold(true);  
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);  
  //设置 A2 一行居中、
	    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr($end-1)."2")->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);  
	    $objPHPExcel->getActiveSheet()->getStyle('A2:'.chr($end-1)."2")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);  
  
    // 设置水平居中    
	    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
	      for($i=65;$i<$end;$i++)
       	 {
        	$objPHPExcel->getActiveSheet()->getStyle(chr($i))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
         } 
	 $end=65+$cols;
    //  合并  
    	$objPHPExcel->getActiveSheet()->mergeCells("A1:".chr($end-1)."1");  
  
    // 表头 
    //题目
    	    // Rename sheet    
	 $objPHPExcel->getActiveSheet()->setTitle($title);  
     $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $title);

     $j=0;
    	for($i=65;$i<$end;$i++)
    	{
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i)."2",$propertynames[$j++]);
    	}
    //只有一行数据时、
    $j=0;
    $row=3;
    foreach ($data as $k => $v) {
    	    for($i=65;$i<$end;$i++)
    	{
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).$row,$v[$keys[$j++]]);
    		$objPHPExcel->getActiveSheet()->getStyle(chr($i).$row.":".chr($end-1).$row)->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
		 	$objPHPExcel->getActiveSheet()->getStyle(chr($i) .$row.':'. chr($end-1).$row)->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);
		 	//设置数据行的高度、、
  	     	$objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(30); 
    	}	
    	$row++;
    	$j=0;
    }	  
	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet    
	    $objPHPExcel->setActiveSheetIndex(0);  
	  	//2007 excel
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  
	    $filename=date("YmdHi",time());
	    $filedate=date("Ymd",time());
	    $dir="excel/".$filedate;
	    if(!is_dir($dir))
	    {
	    	mkdir($dir,0777,true);
	    }
	    $final=$dir."/".$filename;
	    if(file_exists($final))
	    {
	    	$final=$dir."/".date("YmdHis",time());
	    }
	    $objWriter->save($final.".xls");
	}

	function personalData()
	{
		$txt=RFID.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'data.txt';
		$fp=fopen($txt, 'r+');
		if(!$fp)
		{
			$fp=fopen($txt, 'w+');
		}
		$contents=file_get_contents($txt);
		if($contents)
		{
			$res=explode("\r\n", file_get_contents($txt));
			unset($res[count($res)-1]);
			$line=$res[count($res)-1];
		}
		file_put_contents($txt, '');
		if(!empty($line) && $line!="\r\n")
		{
			$line=trim($line,",");
			$count=count(explode(',', $line));
			echo json_encode(array("status"=>"success","epc"=>$line,"count"=>$count));
		}else{
			echo json_encode(array("status"=>"error"));
		}
	}

}



?>