$(function()
{
	var changeBtn=$(".change"),
		clickBtn=true,
		wid=$("#wid").val();
	var ajaxPost={
		type:"POST",
		data:'wid='+wid,
		url:"/work/ajaxCheck",
		success:function($data)
		{
			//将反馈信息显示页面、
			$data=JSON.parse($data);
			var selfObj=ajaxPost.selfObj;
			if($data.status=='success')
			{
				selfObj.next().removeClass().addClass('green');
				selfObj.next().text('钻具符合任务序号'+$data['sort']+'的条件!');
				//显示是否确定使用、
				selfObj.parent().next().attr("sort",$data['sort']);
				selfObj.parent().next().show();
				var ensureBtn=$(".ensureBtn"),
					errorBtn=$(".errorBtn");
				$.each(ensureBtn,function()
				{
					$(this).click(function()
					{
						var epc=$(this).parent().prev().children("input").first().val(),
							sort=$(this).parent().attr("sort"),
							notice=$(this).parent().prev().children("span").first(),
							rid=$(this).prev().val();
							that=$(this);
							notice.removeClass().addClass('green');
							notice.text("您已确认使用本钻具!");
							ajaxUpdate['data']="&epc="+epc+"&sort="+sort+"&wid="+wid+"&rid="+rid;
							$.ajax(ajaxUpdate);
							setTimeout(function()
								{
									that.parent().parent().parent().remove();
								},1000)
					})

				})
				$.each(errorBtn,function()
				{
					$(this).click(function()
					{
						var that=$(this),
						    notice=$(this).parent().prev().children("span").first();
						notice.removeClass().addClass('error');
						notice.text("您已取消使用本钻具!");
						setTimeout(function()
							{
								that.parent().parent().hide();
							},1000)
					})

				})
			}else{
				selfObj.parent().next().hide();
				selfObj.next().removeClass().addClass('error');
				selfObj.next().text($data.info);
			}
		}
	};
	var setting={
		type:"POST",
		url:"/dril/reader",
		data:"num=1",
		success:function($data)
		{
			var $data=JSON.parse($data);
			btn=true;
			clickBtn=true;
			var selfObj=ajaxPost.selfObj;
			if($data.status=="error")
			{
				if($data.msg)
				{
					$("#epc").val($data.msg);
				}else{
					$("#epc").val("操作过于频繁,稍后再试!");
				}
				selfObj.removeClass("geny").addClass("green");
				setTimeout(function()
					{
						$("#epc").val("");
					},1000);
			}else if($data.status=="success")
			{
				var info=$data['epc'];
				selfObj.prev().val("");
				selfObj.prev().val(info);
				selfObj.removeClass("geny").addClass("green");
				//发送ajax判断当前epc是否符合条件、
				//加一个sort、、
				ajaxPost['data']="wid="+wid+"&epc="+info+"&update="+setting['sort'];
				$.ajax(ajaxPost);
			}else{
				$("#epc").val("设备连接超时,正重新连接...,请稍候..");
				setTimeout(function()
					{
						$("#notice").hide("slow");
					},3000);
		}
	}
}
	var ajaxUpdate={
		type:"POST",
		url:"/work/ajaxUpdate",
		success:function($data)
		{
			var $data=JSON.parse($data);
			console.log($data);
		}
	};
	var ajaxSubmit={
		type:"POST",
		url:"/work/ajaxSubmit",
		data:'wid='+wid,
		success:function($data)
		{
			var href=window.location.href;
			//成功刷新一次页面
			setTimeout(function()
			{
				window.location.href=href;
			},1000);
		}
	};
	$.each(changeBtn,function()
	{
		$(this).click(function()
		{
			var oDiv=$(this).parent().next().next();
			var single=oDiv.children("p").children("#single");
			oDiv.css("display","block");
			single.click(singleClick);
		})
	})
	$("#submit").click(function()
	{
		if(confirm('确定本次作业完成，并提交作业吗'))
		{
			$.ajax(ajaxSubmit);
		}
	})
	function singleClick()
	{
		var epc=$(this).prev().val();
		var sort=$(this).parent().parent().prev().val();
		ajaxPost['selfObj']=$(this);
		//判断此时 所有的钻具是否已经符合条件、或者隔一秒刷新 判断是否核对完成、
		if(epc)
		{
			ajaxPost['data']="wid="+wid+"&epc="+epc+"&update="+sort;
		    $.ajax(ajaxPost);
		}else{
			if(clickBtn)
			{
				$(this).prev().val("");
				clickBtn=false;
				$(this).removeClass("green").addClass("geny");
				setting['sort']=sort;
				setting['selfObj']=$(this);
				$.ajax(setting);		
			}

		}
	}
})