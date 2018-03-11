<?php
namespace app\controller;//同一个命名空间下 直接引用即可 找不到Controller 
//默认就会去 找 app\controller\Controller.php 也就是同一个命名空间下 不需要 use 来引入、、
use app\model\drilModel;
use app\model\serviceModel;
use app\model\catModel;
use app\model\recordModel;
use app\model\outModel;
class InformationController extends Controller{
	function detail()
	{
		$this->display("information/detail.html");
	}
	function ajaxFind()
	{
		$post=trims($_POST);
		$days=$post['days'];
		$epc=$post['epc'];
		$epc=explode(',', $epc)[0];
		// $epc=trim($_GET['epc']);
		$data=$this->getDetail($epc);
		if($data)
		{
			$data['pro_time']=date("Y-m-d H:i:s",$data['pro_time']);
			if(isset($data['next']) && $data['next'])
			{
				$next=strtotime($data['next']);
				if(time()+$days*24*3600>=$next)
				{

					$data['notice']=$next>time()?ceil((strtotime($data['next'])-time())/(3600*24)):
												 -ceil((time()-strtotime($data['next']))/(3600*24))
					;
				}
			}
		}
		//有下一次时间的话、
		$this->assign("data",$data);
		$this->display("information/find.html");
	}
	function convenient()
	{
		$this->display("information/convenient.html");
	}
	function getDetail($epc)
	{
		$dmodel=new drilModel();
		$smodel=new serviceModel();
		$cmodel=new catModel();
		$rmodel=new recordModel();
		$omodel=new outModel();
		$ddata=$dmodel->getOneBy("epc,did,cid,pro_factory,pro_time,mat,length,firstuse,work_time,work_len,service","epc=$epc");
		if(!$ddata)
		{
			return array();
		}
		$did=$ddata['did'];
		$sdata=$smodel->getOne("addr,checker,fixer,content,class,state,suggest,next,count,time","epc=$epc,is_new=1");
		$cdata=$cmodel->getOne("cid,cname","cid=".$ddata['cid']);
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
			$data['isservice']=true;
		}
		if(!strtotime($data['firstuse']))
		{
			//没有现场作业记录、
			$data['record']=true;
			$data['firstuse']='该钻具没有作业记录!';
		}
		return $data;
	}
}



?>