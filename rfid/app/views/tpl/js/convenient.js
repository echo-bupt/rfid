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
			}else{
				$("#epc").val("设备连接超时,正重新连接...,请稍候..");
				setTimeout(function()
					{
						$("#notice").hide("slow");
					},3000);
		}
	}
}
	//这里看一下  在check阶段 控制器内 是如何做出反应的、、
	//点击扫描 出来确认上传的按钮 还是 直接发送了ajax、、
	//也就是 通过点击扫描 发送的 ajax？？
	$("#single").click(function()
	{
		if(btn)
		{
			$("#epc").val("");
			btn=false;
			$(this).removeClass("green").addClass("geny");
			$.ajax(setting);
		}
	})
})