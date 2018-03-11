$(function()
{
	var notice=$("#notice"),
		clickBtn=true,
		wid=$("#wid").val();
	var ajaxPost={
		type:"POST",
		data:'wid='+wid,
		url:"/work/ajaxCheck",
		success:function($data)
		{
			clickBtn=true;
			//将反馈信息显示页面、
			$data=JSON.parse($data);
			if($data.status=='success')
			{
				notice.removeClass().addClass('green');
				notice.text($data.info);
				//显示是否确定使用、
				$("#operate").attr("sort",$data['sort']);
				$("#operate").show();
			}else{
				setTimeout(function()
					{
						$("#epc").val('');
					},1000);
			    $("#operate").hide();
				notice.removeClass().addClass('error');
				notice.text($data.info);
			}
		}
	};
	var ajaxAdd={
		type:"POST",
		url:"/work/ajaxAdd",
		success:function($data)
		{
			var href=window.location.href;
			//成功刷新一次页面
			setTimeout(function()
			{
				window.location.href=href;
			},1500);
		}
	};
	var setting={
		type:"POST",
		url:"/dril/reader",
		data:"num=1",
		success:function($data)
		{
			var $data=JSON.parse($data);
			clickBtn=true;
			if($data.status=="error")
			{
				if($data.msg)
				{
					$("#epc").val($data.msg);
				}else{
					$("#epc").val("操作过于频繁,稍后再试!");
				}
				$("#single").removeClass("geny").addClass("green");
				setTimeout(function()
					{
						$("#epc").val("");
					},1000);
			}else if($data.status=="success")
			{
				var info=$data['epc'];
				$("#epc").val("");
				$("#epc").val(info);
				$("#single").removeClass("geny").addClass("green");
				//发送ajax判断当前epc是否符合条件、
				ajaxPost['data']="wid="+wid+"&epc="+info;
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
	//点击扫描先进行判断、
	$("#single").click(function()
	{
		if(clickBtn)
		{
			var epc=$("#epc").val();
			//判断此时 所有的钻具是否已经符合条件、或者隔一秒刷新 判断是否核对完成、
			if(epc)
			{
				clickBtn=false;
				ajaxPost['data']="wid="+wid+"&epc="+epc;
				$.ajax(ajaxPost);
			}else{
				$("#epc").val("");
				clickBtn=false;
				$(this).removeClass("green").addClass("geny");
				$.ajax(setting);
			}	
		}
	})
	$("#ensure").click(function()
	{
		var epc=$("#epc").val();
		var sort=$("#operate").attr("sort");
		notice.removeClass().addClass('green');
		notice.text("您已确认使用本钻具!");
		ajaxAdd['data']="&epc="+epc+"&sort="+sort+"&wid="+wid;
		$.ajax(ajaxAdd);
	})
	$("#error").click(function()
	{
		notice.removeClass().addClass('error');
		notice.text("您已放弃使用本钻具!");
		var href=window.location.href;
			//成功刷新一次页面
		setTimeout(function()
			{
				window.location.href=href;
			},1500);
	})
})