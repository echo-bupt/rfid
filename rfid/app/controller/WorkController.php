<?php
namespace app\controller;
use app\model\workModel;
use app\model\catModel;
use app\model\drilModel;
use app\model\recordModel;
use app\model\serviceModel;
use app\model\outModel;
class WorkController extends Controller{
	function index()
	{
		require 'third/common/page.php';
		$model=new workModel();
		$count=count($model->all());
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagenum=isset($_GET['page'])?$_GET['page']:1;
		$pagestr=$pageObj->showPages(1);
		$data=$model->all("*","",$limit);
		$this->assign("page",$pagestr);
		$this->assign("data",$data);
		$this->display("work/index.html");
	}
	function add()
	{
		$model=new workModel();
		if(ISPOST())
		{
			$count=$_POST['count'];
			unset($_POST['count']);
			$data=array();
			for($i=1;$i<=$count;$i++)
			{
				$data[$i]=array("cat"=>$_POST["cat".$i],"len"=>$_POST["len".$i],
								"mat"=>$_POST["mat".$i],"size"=>$_POST["size".$i],
								"total"=>$_POST["total".$i],"index"=>$i,
					);
			}
		$add=array(
				"content"=>serialize($data),
				"w_number"=>$_POST['w_number'],
				"time"=>date("Y-m-d H:i:s",time()),
						);
		$wid=$model->add($add);
		if($wid)
		{
			$this->postSuccess("添加成功!");
		}
		}else{
			$cmodel=new catModel();
			$cats=$cmodel->all();
			$this->assign("cats",$cats);
			$this->display("work/add.html");
		}
	}
	function edit()
	{
		if(ISPOST())
		{
			$wid=isset($_POST['wid'])?$_POST['wid']:0;
			$count=$_POST['count'];
			unset($_POST['count']);
			$data=array();
			for($i=1;$i<=$count;$i++)
			{
				$data[$i]=array("cat"=>$_POST["cat".$i],"len"=>$_POST["len".$i],
								"mat"=>$_POST["mat".$i],"size"=>$_POST["size".$i],
								"total"=>$_POST["total".$i],"index"=>$i,
					);
			}
			$update=array(
				"content"=>serialize($data),
				"w_number"=>$_POST['w_number'],
						);
			$model=new workModel();
			$model->update($update,"wid=$wid");
			$this->postSuccess("更新成功!");
		}else{
			$wid=isset($_GET['wid'])?$_GET['wid']:0;
			$model=new workModel();
			$cmodel=new catModel();
			if($wid)
			{
				$mdata=$model->getOne("*","wid=$wid");
				$cats=$cmodel->all();
				$content=unserialize($mdata['content']);
				$this->assign("count",count($content));
				$this->assign("cats",$cats);
				$this->assign("contents",$content);
				$this->assign("data",$mdata);
				$this->display("work/edit.html");
			}
		}
	}
	//核对作业计划、、只是核对状态会进入此页面、、
	//作业0 编辑状态、1核对状态 2核对完成作业状态 3作业完成状态
	function check()
	{
		$wid=isset($_GET['wid'])?$_GET['wid']:0;
		if($wid)
		{
			//先得到作业计划、
			$model=new workModel();
			$rmodel=new recordModel();
			$rdata=$rmodel->getsort($wid);
			$record=array();
			$mdata=$model->getOne("*","wid=$wid");
			$content=unserialize($mdata['content']);
			if($mdata['state']==0 || $mdata['state']==1)
			{
				foreach ($rdata as $k => $v) {
					$record[$v['sort']]=$v['len'];
				}
				$this->assign("record",$record);
				$this->assign("rdata",$rdata);
				$this->assign("count",count($content));
				$this->assign("contents",$content);
				$this->assign("data",$mdata);
				//刷新时 判断各个任务序号的完成情况、、
				//只是在核对状态有这种核查、、在更换钻具只显示作业任务 不显示核对状态
				//即分两个页面显示、、
				$this->display("work/check.html");
			}else if($mdata['state']==2)
			{
				$model=new workModel();
				$rmodel=new recordModel();
				$record=$rmodel->all("rid,wid,sort,epc,outtime","isuse=0,wid=$wid,isreplace=0","UNIX_TIMESTAMP(outtime) desc");
				$mdata=$model->getOne("wid,content,w_number,state,time","wid=$wid");
				$this->assign("record",$record);
				$this->assign("data",$mdata);
				$this->assign("contents",$content);
				$this->display("work/checkSuccess.html");
			}else{
				//整个作业计划完成、
				$model=new workModel();
				$mdata=$model->getOne("wid,content,w_number,state,time","wid=$wid");
				$this->assign("data",$mdata);
				$this->assign("contents",$content);
				$this->display("work/workSuccess.html");
			}

		}
	}
	function record_delete()
	{
		$rid=$_GET['rid'];
		$rmodel=new recordModel();
		$rmodel->delete("rid=$rid");
		$this->postSuccess("删除成功!");
	}
	function delete()
	{
		$wid=$_GET['wid'];
		$wmodel=new workModel();
		$rmodel=new recordModel();
		$rmodel->delete("wid=$wid");
		$wmodel->delete("wid=$wid");
		//再去record表删除record记录、
		$this->success("删除成功!");
	}
	function out()
	{
		$this->display("work/out.html");
	}
	function ajaxCheck()
	{
		//判断提交上来的epc是否符合作业条件、
		// echo json_encode(array("status"=>"success","sort"=>'1'));
		$post=trims($_POST);
		$epc=trim($post['epc']);
		$wid=trim($post['wid']);
		$epc=explode(',', $epc)[0];
/*		header("Content-type:text/html;charset=utf-8");
		$post=trims($_GET);
		$epc=trim($post['epc']);
		$wid=trim($post['wid']);*/
		$addorUpdate=isset($post['update'])?$post['update']:false;
		$dmodel=new drilModel();
		$model=new workModel();
		$cmodel=new catModel();
		$rmodel=new recordModel();
		$ddata=$dmodel->getOneBy("cid,mat,size,work_time","epc=$epc");
		if(!$ddata)
		{
			echo json_encode(array("status"=>"error","info"=>"库存中没有此设备!"));
			exit();
		}
		$record=$rmodel->getOne("isuse,wid,isreplace","epc=$epc,isuse=1");
		if($record && $record['isuse']==1)
		{
			echo json_encode(array("status"=>"error","info"=>"该设备正在下井作业中!"));
			exit();	
		}
		$cid=$ddata['cid'];
		$cat=$cmodel->getOne("cid,cname","cid=$cid");
		$ddata['cid']=$cat['cname'];
		//目前的钻具信息在 ddata中、
		$mdata=$model->getOne("*","wid=$wid");
		//ddata数据与之比对、
		//content保留了、原来的任务需求、
		$content=unserialize($mdata['content']);
		$test=array("cat"=>$ddata['cid'],"mat"=>$ddata['mat'],"size"=>$ddata['size'],"total"=>$ddata['work_time']);
		$task=$content;

		//去掉数组的空格
		$test=trims($test);
		foreach ($task as $k => $v) {
			$task[$k]=trims($v);
		}
		/*
		if(in_array($test, $task))
		{
			//取出键名
			$index=array_search($test, $task);
			//判读累计时间是否小于规定、
			if($ddata['work_time']<=$content[$index]['total'])
			{
			//表明符合条件、
			//判断是替换检查还是插入检查、
				if($addorUpdate)
				{
					//这是做的再细一点、如果是此替换钻具的任务sort与原钻具不同也不让通过、
					if($addorUpdate==$index)
					{
						echo json_encode(array("status"=>"success","sort"=>$index));
					}else{
						//变量不是位于字符串最后 要使用{{}} 进行包裹!
						$info="钻具符合任务序号{$index}，但不符合本次任务序号{$addorUpdate}的要求";
						echo json_encode(array("status"=>"error","info"=>$info));
					}
				}else{
					//这里判断 record中 符合要求的
					$slen=$content[$index]['len'];
					$testLendata=$rmodel->all("wid","wid=$wid,isuse=1,sort=$index");
					$testlen=count($testLendata);
					if($testlen>=$slen)
					{
						$info="任务序号{$index}的需求量已经满足，请核对其它任务序号!";
						echo json_encode(array("status"=>"error","info"=>$info));
					}else{
						echo json_encode(array("status"=>"success","sort"=>$index));
					}
				}
			}else{
			echo json_encode(array("status"=>"error","info"=>"累计时间超出了规定时间"));
			}
		}else{
			//info用于以后提示哪个条件不满足、
			echo json_encode(array("status"=>"error","info"=>"钻具不符合本次作业需求,请重试!"));
		}
		*/
		$notice="";

		$noticeArr=array(
				'cat'=>'钻具类型不符合',
				'mat'=>'钢级不符合',
				'size'=>'型号不符合',
				'total'=>'历史下井累计时间大于',
			);

		$auth=array();

		$Btn=true;

		$keys=array_keys($task);

		foreach ($keys as $k=>$v) {
			$auth[$v]=true;
		}

		foreach ($test as $k => $v) {
			foreach ($task as $k2 => $v2) {
				$index=$v2['index'];
				if($k!='total')
				{
					if(strtolower($v)!=strtolower($v2[$k]))
					{
						$notice.=("该钻具的".$noticeArr[$k]."序号".$index."的作业需求!"."\r\n");
						$auth[$index]=false;
					}				
				}else{
					if($v>$v2[$k])	
					{
						$notice.=("该钻具的".$noticeArr[$k]."序号".$index."的作业需求!"."\r\n");
						$auth[$index]=false;
					}
				}
			}
		}


	//	var_dump($notice,$auth);


		foreach ($auth as $k => $v) {
			if($v)
			{
				if($addorUpdate)
				{
					//这是做的再细一点、如果是此替换钻具的任务sort与原钻具不同也不让通过、
					if($addorUpdate==$k)
					{
						$Btn=false;
						$info="该钻具符合".$k."作业需求!";
						echo json_encode(array("status"=>"success","info"=>$info,"sort"=>$k));
					}else{
						$Btn=false;
						//变量不是位于字符串最后 要使用{{}} 进行包裹!
						$info="钻具符合任务序号{$k}，但不符合本次任务序号{$addorUpdate}的要求";
						echo json_encode(array("status"=>"error","info"=>$info));
					}
				}else{
					//多次 echo 会导致 两次{}、、
					$slen=$task[$k]['len'];
					$testLendata=$rmodel->all("wid","wid=$wid,isuse=1,sort=$k");
					$testlen=count($testLendata);
					if($testlen>=$slen)
					  {
						$info="任务序号{$k}的需求量已经满足，请核对其它任务序号!";
						echo json_encode(array("status"=>"error","info"=>$info));
						$Btn=false;
						break;
					  }else{
					  	if($notice)
					  	{
							$info="该钻具符合序号".$k."的作业需求,但是".$notice;				  		
					  	}else{
							$info="该钻具符合序号".$k."的作业需求";
					  	}
						echo json_encode(array("status"=>"success","info"=>$info,"sort"=>$k));
						$Btn=false;
						break;
					  }			
				    }
			    }
			}
			if($Btn && $notice)
			{
				echo json_encode(array("status"=>"error","info"=>$notice));
			}
	}
	function outCheck()
	{
		$epc=trim($_POST['epc']);
		$epc=explode(',', $epc)[0];
		$rmodel=new recordModel();
		$wmodel=new workModel();
		$dmodel=new drilModel();
		$ddata=$rmodel->getOne("isuse,wid","epc=$epc");
		$dril=$dmodel->getOneBy("did,epc","epc=$epc");
		if(!$dril)
		{
			echo json_encode(array("status"=>"error","info"=>"库存中没有此设备!"));
			exit();
		}
		if($ddata)
		{
			$rdata=$rmodel->getOne("isuse,wid","epc=$epc,isuse=1");
			if($rdata['isuse']==1)
			{
				//这里默认是 不核对完成不允许出库、也可以出库者需要在检查页面、加上条件isuse=1、
				//再判断该任务是否已核对完成、未核对完成者不允许出库、
				$wid=$rdata['wid'];
				$wdata=$wmodel->getOne('state',"wid=$wid");
				if($wdata && ($wdata['state']==2 || $wdata['state']==3))
				{
			echo json_encode(array("status"=>"success","info"=>"该设备符合出井条件"));
				}else{
			echo json_encode(array("status"=>"error","info"=>"该设备的下井作业任务未核对完成!"));
				}
			}else{
			echo json_encode(array("status"=>"error","info"=>"该设备已出井!"));
			}
		}else{
			echo json_encode(array("status"=>"error","info"=>"该设备未参加作业!"));
		}
	}
	function ajaxAdd()
	{
		//点击确认使用时 符合条件的插入现场记录表、
		//同时修改作业表状态为1核对状态、注意已经添加进来的 就不要再添加、修改wid即可、、
		$rmodel=new recordModel();
		$wmodel=new workModel();
		$dmodel=new drilModel();
		$_POST=trims($_POST);
		$wid=$_POST['wid'];
		$epc=$_POST['epc'];
		$epc=explode(',', $epc)[0];
		$_POST['intime']=date("Y-m-d H:i:s",time());
		//判断dril里面是否有first_use 没有的话更新、
		$firstuse=$dmodel->getOneBy("firstuse","epc=$epc");
		if(!strtotime($firstuse['firstuse']))
		{
			$dmodel->update(array("firstuse"=>date("Y-m-d H:i:s",time())),"epc=$epc");
		}
		$rmodel->add($_POST);
		$wmodel->update(array("state"=>'1'),"wid=$wid");
		//查询核对工作是否已完成、
		$mdata=$wmodel->getOne("*","wid=$wid");
		$content=unserialize($mdata['content']);
		$rdata=$rmodel->getsort($wid);
		$record=array();
		foreach ($rdata as $k => $v) {
				$record[$v['sort']]=$v['len'];
			}	
		$btn=true;
		foreach ($content as $k => $v) {
			$recordlen=isset($record[$k])?$record[$k]:0;
			if($v['len']>$recordlen)
			{
				$btn=false;
			}
		}
		if($btn)
		{
			//修改状态为1表示已核对完成、
			$wmodel->update(array("state"=>"2"),"wid=$wid");
		}

	}
	function outAjaxUpdate()
	{
		$epc=trim($_POST['epc']);
		$epc=explode(',', $epc)[0];
		$len=trim($_POST['len']);
		$rmodel=new recordModel();
		$dmodel=new drilModel();
		//这种 多个括号一起很容易会导致语法错误、、
		//计算本次下入时间、更新总的下入时间、
		$epcData=$rmodel->getOne("intime","epc=$epc,isuse=1");
		$intime=strtotime($epcData['intime']);
		$time=ceil((time()-$intime)/3600);
		//本次加入长度、
		$updateData=array(
			"isuse"=>0,
			"time"=>$time,
			"outtime"=>date("Y-m-d H:i:s",time()),
			"len"=>$len,
			);
		$updateDril=array(
			"work_time"=>"work_time+$time",
			"work_len"=>"work_len+$len",
			);
		$dmodel->update($updateDril,"epc=$epc");
		$rmodel->update($updateData,"epc=$epc,isuse=1");
	}
	function ajaxUpdate()
	{
		//如果同一个设备 (epc对应的wid)不让其入库 只是改变其状态 isuse即可、、
		//更换钻具的接口、
		$post=trims($_POST);
		$epc=$post['epc'];
		$rid=$post['rid'];
		$wid=$post['wid'];
		$epc=explode(',', $epc)[0];
		$rmodel=new recordModel();
		$dmodel=new drilModel();
		$rdata=$rmodel->getOne("rid,wid","epc=$epc,wid=$wid");
		if($rdata)
		{
			$rid=$rdata['rid'];
			//同一件设备
			$update=array(
				"isuse"=>"1",
				"intime"=>date("Y-m-d H:i:s",time()),
				);
			$rmodel->update($update,"rid=$rid");
		}else{
			//新设备、进行添加、并且将原来的isuse设置为0、以及替换标志、
			$add=array("epc"=>$post['epc'],"wid"=>$post['wid'],"sort"=>$post['sort'],"intime"=>date("Y-m-d H:i:s",time()));
			$rmodel->update(array("isuse"=>"0","isreplace"=>1),"rid=$rid");
			$firstuse=$dmodel->getOneBy("firstuse","epc=$epc");
			if(!strtotime($firstuse['firstuse']))
			{
				$dmodel->update(array("firstuse"=>date("Y-m-d H:i:s",time())),"epc=$epc");
			}
			$rmodel->add($add);
		}
		echo json_encode(array("state"=>"success"));
	}
	function ajaxSubmit()
	{
		$wid=trim($_POST['wid']);
		$wmodel=new workModel();
		$rmodel=new recordModel();
		$rmodel->update(array("isuse"=>"0"),"wid=$wid");
		$wmodel->update(array("state"=>3),"wid=$wid");
		echo json_encode(array("status"=>"success"));
	}
	//注意当查询现场记录时、、如果outtime<intime说明 该钻具被重新替换使用了 此时显示未出井、
	function nowDisplay()
	{
		require 'third/common/page.php';
		$wid=trim($_GET['wid']);
		$wmodel=new recordModel();
		$all=$wmodel->all("*","wid=$wid","UNIX_TIMESTAMP(intime) desc");
		$count=count($all);
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagestr=$pageObj->showPages(1);
		$data=$wmodel->all("*","wid=$wid","UNIX_TIMESTAMP(intime) desc",$limit);
		$num=1;
		//下入累计时间 以天、小时共同表示计算、
		//累计长度、
		$addLen=array();

		//$lenAll=0;//由于+=要先访问$lenAll 所以先声明、、 php 的for循环与局部作用域有何不同、
		foreach ($data as $k => $v) {
			$addLen[$num]=$this->getTime($num,$data);
			$data[$k]['index']=$num;
			$num++;
			if(!(strtotime($v['outtime']))||strtotime($v['outtime']<strtotime($v['intime'])))
			{
				$data[$k]['outtime']="钻具未起出";
			}
			if($v['time']>24)
			{
				$data[$k]['time']=floor($v['time']/24)."天".($v['time']%24)."小时";
			}else{
				$data[$k]['time']=$v['time']."小时";
			}
		}
		$this->assign('wid',$wid);
		$this->assign("addLen",$addLen);
		$this->assign("data",$data);
		$this->assign("page",$pagestr);
		$this->display("work/nowDisplay.html");
	}
	//递归写法、、每一次递归改变循环次数
	function getTime($num,$data)
	{
		$retLen=0;
		for ($i=0; $i <$num ; $i++) { 
			$retLen+=$data[$i]['len'];
		}
		return $retLen;
	}
	function detail()
	{
		$epc=trim($_GET['epc']);
		$dmodel=new drilModel();
		$smodel=new serviceModel();
		$cmodel=new catModel();
		$rmodel=new recordModel();
		$omodel=new outModel();
		$ddata=$dmodel->getOneBy("epc,did,cid,pro_factory,pro_time,mat,length,firstuse,work_len,work_time","epc=$epc");
		if(!$ddata)
		{
			return array();
		}
		$did=$ddata['did'];
		$sdata=$smodel->getOne("addr,checker,fixer,content,class,state,suggest,next,count,time","epc=$epc,is_new=1");
		$cdata=$cmodel->getOne("cid,cname","cid={$ddata['cid']}");
		$out=$omodel->getOne("unit,troop","did=$did");
		if($out)
		{
			$ddata['unit']=$out['unit'];
			$ddata['troop']=$out['troop'];
		}else{
			$ddata['unit']="该钻具未出库!";
			$ddata['troop']="该钻具未出库!";
		}
		$ddata['cid']=$cdata['cname'];
		unset($ddata['did']);
		//$sdata 不存在的话 lasttime 就直接不给于 赋值、、
		if($sdata)
		{
			$data=array_merge($ddata,$sdata);//if无作用域、
		}else{
			$data=$ddata;
			$data['record']=true;
		}
		if(!strtotime($data['firstuse']))
		{
			$data['firstuse']="该钻具未参加过作业!";
		}
		$this->assign("data",$data);
		$this->display("work/detail.html");
	}
	//导出作业、
	function exportZuoye()
	{
		$wmodel=new workModel();
		$wid=$_GET['wid'];
		$wcontent=$wmodel->getOne("w_number,time,content");
		$content=unserialize($wcontent['content']);
		unset($wcontent['content']);
		$title=$wcontent;
		foreach ($content as $k => $v) {
			$data[]=$this->getNumberArr($v);
		}
		$title="作业编号".$wcontent['w_number']."的作业计划";
		$this->excelZuoye($title,$data);
		$this->success("导出成功!");
	}
	function export()
	{
		$epc=$_GET['epc'];
		$wid=$_GET['wid'];
		$data=$this->getDetail($epc,$wid);
		$data['pro_time']=date("Y-m-d H:i:s",$data['pro_time']);
		$data=$this->getNumberArr($data);
		$this->excel($data);
		$this->success("导出成功!");
	}
	function exportWork()
	{
		$wid=$_GET['wid'];
		$rmodel=new recordModel();
		$rdata=$rmodel->all("rid,epc","wid=$wid");
		$rids=array();
		foreach ($rdata as $k => $v) {
			$rids[]=$v['epc'];
		}
		$allData=array();

		foreach ($rids as $k => $v) {
			$tmpdata=$this->getDetail($v,$wid);
			$tmpdata['pro_time']=date("Y-m-d H:i:s",$tmpdata['pro_time']);
			$allData[]=$this->getNumberArr($tmpdata);
			// $allData[]=$tmpdata;
		}
		$this->excelWork($allData);
		$this->success("导出成功!");
	}
	function getNumberArr($arr)
	{
		$final=array();
		foreach ($arr as $k => $v) {
			$final[]=$v;
		}
		return $final;
	}
	//****必须是对应该次work的钻具、、钻具可被使用到多种work、
	function getDetail($epc,$wid)
	{
		$dmodel=new drilModel();
		$smodel=new serviceModel();
		$cmodel=new catModel();
		$rmodel=new recordModel();
		$omodel=new outModel();
		$ddata=$dmodel->getOneBy("epc,did,cid,pro_factory,pro_time,mat,length,firstuse","epc=$epc");
		$record=$rmodel->getOne("len,intime,outtime,time","epc=$epc,wid=$wid");
		if(!$ddata)
		{
			return array();
		}
		$did=$ddata['did'];
		$cid=$ddata['cid'];
		$cdata=$cmodel->getOne("cid,cname","cid=$cid");
		$out=$omodel->getOne("unit,troop","did=$did");
		if($out)
		{
			$ddata['unit']=$out['unit'];
			$ddata['troop']=$out['troop'];
		}else{
			$ddata['unit']="该钻具未出库!";
			$ddata['troop']="该钻具未出库!";
		}
			$data=array_merge($ddata,$record);
			unset($data['did']);
			//钻具类型 以及 累计时间、
			if(isset($data['cid'])) $data['cid']=$cdata['cname'];
			if($data['time']>24)
			{
				$data['time']=floor($data['time']/24)."天".($data['time']%24)."小时";
			}else{
				$data['time']=$data['time']."小时";
			}
		return $data;
	}
function excelZuoye($title,$data)
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
        $propertynames=array("序号","钻具类型","数量(根)","钢级","型号(英寸)","历史累计时间(小时)");
        $cols=count(array_keys($propertynames));
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
    $count=1;
    foreach ($data as $k => $v) {
    	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$row,$count++);
    	    for($i=66;$i<$end;$i++)
    	{
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).$row,$v[$j++]);
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
        $propertynames=array("设备编号","钻具类型","生产厂家","生产日期","钢级","长度(m)","首次使用日期(年/月/日)","所属二级单位","二级单位下属井队编号","下入长度(m)","下入时间(最近一次)","起出时间(最近一次)","本次下入累计时间");
        $cols=count(array_keys($propertynames));
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
     $title='作业详情现场记录表';
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
      	 if($data[$j]===true)
    		    	{
    		    		//合并
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i)."3","本钻具暂无作业记录");
    		$objPHPExcel->getActiveSheet()->mergeCells(chr($i)."3".":".chr($end-1)."3");  
    		break;
    		    	}
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i)."3",$data[$j++]);
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
	function excelWork($data)
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
        $propertynames=array("序号","设备编号","钻具类型","生产厂家","生产日期","钢级","长度(m)","首次使用日期(年/月/日)","所属二级单位","二级单位下属井队编号","下入长度(m)","下入时间(最近一次)","起出时间(最近一次)","本次下入累计时间");
        $cols=count(array_keys($propertynames));
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
     $title='作业详情现场记录表';
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
    $count=1;
    foreach ($data as $k => $v) {
    	$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A".$row,$count++);
    	    for($i=66;$i<$end;$i++)
    	{
    		   if($v[$j]===true)
    		    	{
    		    		//合并
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).$row,"本钻具暂无维修记录!");
    		$objPHPExcel->getActiveSheet()->mergeCells(chr($i).$row.":".chr($end-1).$row);  
    		break;
    		    	}
    		$objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($i).$row,$v[$j++]);
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
}



?>