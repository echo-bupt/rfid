$(function()
{
	var btn=true;
	var setting={
		type:"POST",
		url:"/dril/reader",
		data:{
			"num":1,
		},
		success:function($data)
		{
			var $data=JSON.parse($data);
			btn=true;
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
				$("#operate").show();
			}else{
				$("#epc").val("设备连接超时,正重新连接...,请稍候..");
				setTimeout(function()
					{
						$("#notice").hide("slow");
					},3000);
		}
	}
}
	var ajaxAdd={
		type:"POST",
		url:"/information/ajaxFind",
		data:{},
		success:function($data)
		{
			$("#find").html($data);		
		}
	};
	//这里看一下  在check阶段 控制器内 是如何做出反应的、、
	//点击扫描 出来确认上传的按钮 还是 直接发送了ajax、、
	//也就是 通过点击扫描 发送的 ajax？？
	$("#single").click(function()
	{
		if(btn)
		{
			var epc=$("#epc").val();
			var days=$('#days').val();
			$('#find').html('');
			//由于 通过 val 得到的均为 字符串、、故不能使用 typeof 进行判断、、
			if(isNaN(days) || days<=0)
			{
				alert("请输入大于零的合法数字!");
				return false;
			}
			if(epc)
			{
				$("#operate").show();
			}else{
				$("#epc").val("");
				btn=false;
				$(this).removeClass("green").addClass("geny");
				$.ajax(setting);
				}
		}
	})
	$("#ensure").click(function()
	{
		var days=$('#days').val();
		var epc=$("#epc").val();
		//发送ajax 去查询之、
		ajaxAdd['data']['days']=days;
		ajaxAdd['data']['epc']=epc;
		$('#operate').hide();
		$.ajax(ajaxAdd);
	})
	$("#error").click(function()
	{
		$('#epc').val('');
		$('#operate').hide();
	})
})