$(function()
{
/*	var checks=$(".check");
	$.each(checks,function()
	{
		var check;
		var checkval='';
		$(this).click(function()
		{
			checkval='';
			check=$(this)[0];
			//查阅 select 有length 属性即 子option的数目、、
			//select有options属性 可以获取所有子 options、
			for(var i=0;i<check.length;i++)
			{
				if(check.options[i].selected)
				{
					var tmpval=check.options[i].value+",";
					checkval+=tmpval;
				}
			}
			//字符串.字符串方法!!
			$(this).next().val(checkval.substr(0,checkval.length-1));
		})
			check=$(this)[0];
			for(var i=0;i<check.length;i++)
			{
				if(check.options[i].selected)
				{
					checkval+=(check.options[i].value+",");
				}
			}
			//字符串.字符串方法!!
			$(this).next().val(checkval.substr(0,checkval.length-1));
			checkval="";
	})*/

$('.other').each(function()
{
	$(this).click(function()
	{
		$(this).prevAll().hide();
		$(this).prev().show();
		$(this).next().show();
		$(this).hide();
	})
})

$('.choose').each(function()
{
	$(this).click(function()
	{
		$(this).prevAll().show();
		$(this).prev().prev().hide();
		$(this).hide();
	})
})

	$('form').submit(function()
	{
		var aWrite=$('.write'),aNum=$('.num'),check=true;
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
				setTimeout(function()
					{
						$("#epc").val("");
					},500);
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
})

})