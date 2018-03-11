<?php
namespace app\controller;
use app\model\troopModel;
use app\model\unitModel;
class TroopController extends Controller{
	function index()
	{
		$model=new troopModel();
		//注意 ORDER BY 语句、、如果没有 JOIN 关键字、、那么 order by `time` desc
		//order by time desc 以及 order by 'time' desc 
		//但是连接查询、、 order by 'a.time' 以及 a.time 都没有问题 但是 order by `a.time` 有问题、
		$data=$model->join('unit');
		$this->assign("data",$data);
		$this->display("troop/index.html");
	}
	function add()
	{
		if(ISPOST())
		{
			$model=new troopModel();
			$id=$model->add($_POST);
			if($id)
			{
				$this->postSuccess("添加成功!");
			}
		}else{
			$uid=isset($_GET['uid'])?$_GET['uid']:0;
			$model=new unitModel();
			$unit=$model->all();
			$this->assign("uid",$uid);
			$this->assign("unit",$unit);
			$this->display("troop/add.html");
		}
	}
	function edit()
	{
		if(ISPOST())
		{
			$model=new troopModel();
			$post=trims($_POST);
			$tid=$post['tid'];
			$id=$model->update($post,"tid=$tid");
			if($id)
			{
				$this->postSuccess("更新成功!");
			}
		}else{
			$tid=$_GET['tid'];
			$umodel=new troopModel();
			$model=new unitModel();
			$unit=$model->all();
			$udata=$umodel->getOne("tid,tname,uid","tid=$tid");
			$this->assign("uid",$udata['uid']);
			$this->assign("unit",$unit);
			$this->assign("data",$udata);
			$this->display("troop/edit.html");
		}
	}
	function delete()
	{
		$tid=$_GET['tid'];
		$tmodel=new troopModel();
		$tmodel->delete("tid=$tid");
		$this->success("删除成功!");
	}
}




?>