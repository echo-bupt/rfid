$(function()
{
	var num=parseFloat($(".conline").last().text());
	$("#addLine").click(function()
	{
		var node=$(".conline").eq(0).clone(true),
			count=$("#count")[0];
			count.value=parseFloat(count.value)+1;
		num++;
		node.children("td").eq(0).text(num);
		node.children("td").eq(1).children("select").attr("name","cat"+num);
		node.children("td").eq(2).children("input").attr("name","len"+num);
		node.children("td").eq(3).children("input").attr("name","mat"+num);
		node.children("td").eq(4).children("input").attr("name","size"+num);
		node.children("td").eq(5).children("input").attr("name","total"+num);
		node.appendTo($("#tbody"));
		return false;
	})
	$("#reduceLine").click(function()
	{
		var count=$("#count")[0];
		if($(".conline").length>1)
		{
			count.value=parseFloat(count.value)-1;
			$(".conline").last().remove();
		}
		return false;
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
		$(aNum).each(function()
		{
			if(!(/\d+/.test($(this).val())))
			{
				$(this).next().html('('+'填写项必须为数字!'+')').show();
				check=false;
			}else{
				$(this).next().hide();
			}
		})
		if(!check) return false;
		return true;
	})
})