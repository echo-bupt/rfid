<?php
namespace app\controller;
use app\model\serviceModel;
use app\model\contentModel;
use app\model\drilModel;
use app\model\catModel;
use app\model\recordModel;
use app\model\outModel;
class ServiceController extends Controller{
	function index()
	{
		require 'third/common/page.php';
		$smodel=new serviceModel();
		$sdata=$smodel->all("*","is_new=1","UNIX_TIMESTAMP(time) desc");
		$count=count($sdata);
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagestr=$pageObj->showPages(1);
		$data=$smodel->all("*","is_new=1","UNIX_TIMESTAMP(time) desc",$limit);
		$this->assign("page",$pagestr);
		$this->assign("data",$data);
		$this->display("service/index.html");
	}
	function add()
	{
		if(ISPOST())
		{
			$smodel=new serviceModel();
			$dmodel=new drilModel();
			$post=trims($_POST);
			if(!isset($post['suggest']) || !$post['suggest'])
			{
				if(isset($post['suggests']) && $post['suggests'])
				{
					$post['suggest']=implode($post['suggests'], ',');
				}
			}
			if(!isset($post['content']) || !$post['content'])
			{
				if(isset($post['contents']) && $post['contents'])
				{
					$post['content']=implode($post['contents'], ',');
				}
			}
			$epc=$post['epc'];
			$epc=explode(',', $epc);
			foreach ($epc as $k => $v) {
				$sdata=$smodel->getOne("sid","epc=$v");
				if($sdata)
				{
					//更新该epc的is_new为0
					$smodel->update(array("is_new"=>0),"epc=$v,is_new=1");
				}
				$updateDril=array(
						"service"=>"service+1",
					);
				$dmodel->update($updateDril);
				//把did获取到添加、
				$ddata=$dmodel->getOneBy("did","epc=$v");
				$did=$ddata['did'];
				$post['did']=$did;
				unset($post['suggests'],$post['contents']);
				$id=$smodel->add($post);
			}
			$this->postSuccess('操作成功!');
		}else{
			$cmodel=new contentModel();
			$contents=$cmodel->all('options,contents');
			$contentData=array();
			foreach ($contents as $k => $v) {
				$contentData[$v['options']]=explode("#", trim($v['contents'],"#"));
			}
			$content1=$contentData['维修内容'];
			$content2=$contentData['评级'];
			$content3=$contentData['状态评估'];
			$content4=$contentData['使用建议'];
			// var_dump($content1,$content2,$content3,$content4);
			$this->assign('checkContent',$content1);
			$this->assign('class',$content2);
			$this->assign('state',$content3);
			$this->assign('suggest',$content4);
			$this->display("service/add.html");
		}
	}
	function edit()
	{
		if(ISPOST())
		{
			$post=trims($_POST);
			$sid=$post['sid'];
			if(!isset($post['suggest']) || !$post['suggest'])
			{
				if(isset($post['suggests']) && $post['suggests'])
				{
					$post['suggest']=implode($post['suggests'], ',');
				}
			}
			if(!isset($post['content']) || !$post['content'])
			{
				if(isset($post['contents']) && $post['contents'])
				{
					$post['content']=implode($post['contents'], ',');
				}
			}
			unset($post['suggests'],$post['contents']);
			$smodel=new serviceModel();
			$smodel->update($post,"sid=$sid");
			$this->postSuccess("更新成功!");
		}else{
			$sid=isset($_GET['sid'])?$_GET['sid']:1;
			$smodel=new serviceModel();
			$sdata=$smodel->getOne("*","sid=$sid");
			$cmodel=new contentModel();
			$contents=$cmodel->all('options,contents');
			$contentData=array();
			foreach ($contents as $k => $v) {
				$contentData[$v['options']]=explode("#", trim($v['contents'],"#"));
			}
			$content1=$contentData['维修内容'];
			$content2=$contentData['评级'];
			$content3=$contentData['状态评估'];
			$content4=$contentData['使用建议'];
				//var_dump($content1,$content2,$content3,$content4);
			$this->assign('checkContent',$content1);
			$this->assign('class',$content2);
			$this->assign('state',$content3);
			$this->assign('suggest',$content4);
			$this->assign("data",$sdata);
			$this->display("service/edit.html");
		}

	}
	function serviceFindG()
	{
		$this->display('service/serviceFind.html');
	}
	function serviceFindP()
	{
		require 'third/common/page.php';
		$epc=trim($_GET['epc']);
		$epc=explode(',', $epc)[0];
		$dmodel=new drilModel();
		$dril=$dmodel->getOneBy("did,epc","epc=$epc");
		if(!$dril)
		{
			$this->error("库存中没有此设备!");
			exit();
		}
		$smodel=new serviceModel();
		$sdata=$smodel->all("sid","epc=$epc","UNIX_TIMESTAMP(time) desc");
		$count=count($sdata);
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagestr=$pageObj->showPages(1);
		$data=$smodel->all("*","epc=$epc","UNIX_TIMESTAMP(time) desc",$limit);
		$this->assign("epc",$epc);
		$this->assign("page",$pagestr);
		$this->assign("data",$data);
		$this->display("service/history.html");
	}
	function content()
	{
		if(ISPOST())
		{
			$model=new contentModel();
			$post=trims($_POST);
			$options=$post['options'];
			$cdata=$model->getOne("cid","options=$options");
			if($cdata)
			{
				//更新、
				$model->update(array('contents'=>$post['contents']),"options=$options");
				$this->postSuccess("设置成功!");
			}else{
				//添加、
				$id=$model->add($post);
				if($id)
				{
				$this->postSuccess("设置成功!");
				}
			}
		}else{
			$model=new contentModel();
			$content=$model->getOne("*","cid=1");
			$this->assign("contents",$content['contents']);
			$this->display("service/content.html");	
		}
	}
	function change(){
		$model=new contentModel();
		$post=trims($_POST);
		$options=$post['options'];
		$contents=$model->getOne("contents","options=$options");
		echo json_encode(array("contents"=>$contents['contents']));
	}
	function history()
	{
		require 'third/common/page.php';
		$epc=isset($_GET['epc'])?$_GET['epc']:1;
		$smodel=new serviceModel();
		$sdata=$smodel->all("sid","epc=$epc","UNIX_TIMESTAMP(time) desc");
		$count=count($sdata);
		$pageObj=new \Page($count,5,5);
		// var_dump($pageObj->showPages(1));
		$limit=$pageObj->limit();
		$pagestr=$pageObj->showPages(1);
		$data=$smodel->all("*","epc=$epc","UNIX_TIMESTAMP(time) desc",$limit);
		$this->assign("epc",$epc);
		$this->assign("page",$pagestr);
		$this->assign("data",$data);
		$this->display("service/history.html");
	}
	function info()
	{
		$epc=isset($_GET['epc'])?$_GET['epc']:1;
		$dmodel=new drilModel();
		$cmodel=new catModel();
		$rmodel=new recordModel();
		$omodel=new outModel();
		$epc=$dmodel->getOneBy("*","epc=$epc");
		$epcnum=$epc['epc'];
		$did=$epc['did'];
		$cdata=$cmodel->getOne("cid,cname","cid=".$epc['cid']);
		$out=$omodel->getOne("unit,troop","did=$did");
		if($out)
		{
			$epc['unit']=$out['unit'];
			$epc['troop']=$out['troop'];
		}else{
			$epc['unit']="该钻具未出库!";
			$epc['troop']="该钻具未出库!";
		}
		$epc['cname']=$cdata['cname'];
		$this->assign("data",$epc);
		$this->display("service/info.html");
	}
	function export()
	{
		$smodel=new serviceModel();
		$sid=$_GET['sid'];
		$epc=$_GET['epc'];
		$data=$this->getDetail($sid,$epc);
		$data=$this->getNumberArr($data);
		$this->excel($data);
		$this->success("导出成功!");
	}
	function exportAll()
	{
		$epc=$_GET['epc'];
		$smodel=new serviceModel();
		$sdata=$smodel->all("sid,epc","epc=$epc");
		$sids=array();
		foreach ($sdata as $k => $v) {
			$sids[]=$v['sid'];
		}
		$allData=array();

		foreach ($sids as $k => $v) {
			$tmpdata=$this->getDetail($v,$epc);
			$allData[]=$this->getNumberArr($tmpdata);
			// $allData[]=$tmpdata;
		}
		$this->excelAllHis($allData);
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
	function getDetail($sid,$epc)
	{
		$smodel=new serviceModel();
		$dmodel=new drilModel();
		$cmodel=new catModel();
		$omodel=new outModel();
		$ddata=$dmodel->getOneBy("epc,did,cid,pro_factory,pro_time,mat,length,firstuse","epc=$epc");
		$data=$smodel->getOne("epc,time,addr,checker,fixer,content,class,state,suggest,next","sid=$sid");
		$cid=$ddata['cid'];
		$did=$ddata['did'];
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
		$data=array_merge($ddata,$data);
		if(isset($data['cid'])) $data['cid']=$cdata['cname'];
		$data['pro_time']=date("Y-m-d H:i:s",$data['pro_time']);
		unset($data['did']);
		return $data;
	}
	function delete()
	{
		$sid=$_GET['sid'];
		$smodel=new serviceModel();
		$smodel->delete("sid=$sid");
		$this->success("删除成功!");
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
        $propertynames=array("设备编号","钻具类型","生产厂家","生产日期","钢级","长度(m)","首次使用日期(年/月/日)","所属二级单位","二级单位下属井队编号","检修日期(年/月/日)","检修地点","检测人员","维修人员","检修内容","评级","状态评估","使用建议","下一次检修日期");
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
     $title='钻具维修记录表';
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
function excelAllHis($data)
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
        $propertynames=array("序号","设备编号","钻具类型","生产厂家","生产日期","钢级","长度(m)","首次使用日期(年/月/日)","所属二级单位","二级单位下属井队编号","检修日期(年/月/日)","检修地点","检测人员","维修人员","检修内容","评级","状态评估","使用建议","下一次检修日期");
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
     $title='钻具维修记录表';
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
}


?>