$(function()
{
	var btn=true;
	var btn2=true;
	var setting={
		type:"POST",
		url:"/dril/reader",
		data:"num=1",
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
			}else if($data.status=="success")
			{
				var info=$data['epc'];
				var count=$data['count'];
				$("#notice").show();
				$("#notice").text("成功扫描"+count+"个");
				setTimeout(function()
					{
						$("#notice").hide("slow");
					},2000)
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
	$('form').submit(function()
	{
		var aWrite=$('.write'),check=true;
		$(aWrite).each(function()
		{
			if($(this).val()=='')
			{
				$(this).next().html('('+'填写项不得为空!'+')').show();
				check=false;
			}else{
				$(this).next().hide();
			}
		})
		if(!check) return false;
		return true;
	})
		var settingForData={
			type:"GET",
			url:'/dril/personalData',
			success:function($data)
			{
				var $data=JSON.parse($data);
				if($data)
				{
					if($data.status=="error")
					{
						if($data.msg)
						{
							$("#epc").val($data.msg);
						}else{

						}	
					}else if($data.status=="success")
					{
						var info=$data['epc'];
						var count=$data['count'];
						$("#notice").show();
						$("#notice").text("成功扫描"+count+"个");
						setTimeout(function()
							{
								$("#notice").hide("slow");
							},2000)
						$("#epc").val("");
						$("#epc").val(info);			
					}
				//AJAX轮询的方式、、、
			}
			setTimeout(function()
				{
					$.ajax(settingForData);
				},200);
		}
	}	
	
	$.ajax(settingForData);

})