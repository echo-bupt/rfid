	$(function()
	{
		var oriText=$("#troop").html();
		var updateBtn=$("#updateBtn");
		$("#unit")[0].onchange=function()
		{
			var val=this.value;
			var setting={
			type:"POST",
			url:"/dril/outAjax",
			data:"uid="+val,
			success:function($data)
			{
				$data=JSON.parse($data);
				var optionText="";
				var btn=false;
				if($data)
				{
					for($key in $data)
					{
						optionText+="<option value='"+$data[$key]+"'"+">"+$data[$key]+"</option>";
						btn=true;
					}
					if(btn)
					{
						$("#troop").html(optionText);	
					}else{
						$("#troop").html(oriText);
					}			
				}
			}
		}
		$.ajax(setting);
		}
		$("#updateBtn").click(function()
		{
			//获取要发送的数据、
			var oid=$("#oid").val(),
				unit=$("#unit").val(),
				troop=$("#troop").val(),
				picker=$("#picker").val(),
				operator=$("#operator").val(),
				picktime=$("#picktime").val();
			var succnotice=$("#succnotice");
			//组合
			var sendData="oid="+oid+"&"+"unit="+unit+"&"+"troop="+troop+"&"+
			"picker="+picker+"&"+"operator="+operator+"&"+"picktime="+picktime;
			var setting={
			type:"POST",
			url:"/dril/outUpdateAjax",
			data:sendData,
			success:function($data)
			{
				$("#succnotice").show();
				setTimeout(function()
					{
						$("#succnotice").hide();
					},2000);
			}
		}
		$.ajax(setting);
		})
		var deleBtn=$(".deleteBtn")
		$.each(deleBtn,function()
		{
			//里面的this 是每一个元素、
			$(this).click(function()
			{
				var oid=$("#oid_del").val();
				var that=this;
				var setting={
				type:"GET",
				url:"/dril/outDeleteAjax/oid/"+oid,
				success:function($data)
				{
					$(that).next().next().show();
					setTimeout(function()
						{
							$(that).next().next().hide();
							$(that).parent().remove();
						},2000);
				}
			}
			$.ajax(setting);
			})
		})
	})
