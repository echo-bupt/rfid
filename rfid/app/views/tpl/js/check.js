$(function()
{
	$("#checkBtn").click(function(event)
	{
		var days=$("#days").val(); //30 将得到的是 字符串式的 30、、
		var pattern=/\d+/;
		//使用 > <比较时、、其中一个为数字 会将 另一个转换为 数字、
		if(pattern.test(days) && days>0)
		{
			window.location.href=window.location.href+"/check/"+days;
			//改变浏览器地址 同时阻止、a标签的默认行为、、
			return false;
		}else{
			alert("请输入正确数字!");
			return false;
		}
	})
})