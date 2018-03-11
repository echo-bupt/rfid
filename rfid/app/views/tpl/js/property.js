$(function()
{
	$('form').submit(function()
	{
		var input1=$('.write').eq(0).val(),input2=$('.write').eq(1).val(),input3=$('.write').eq(2).val();;
		if(!(/\w+/.test(input1)))
		{
			$(".notices").eq(0).html('('+'请输入合法钻具属性!'+')').show();
			return false;
		}else{
			$(".notices").eq(0).hide();
		}
		if(!input2)
		{
			$(".notices").eq(1).html('('+'属性别名不得为空!'+')').show();
			return false;
		}else{
			$(".notices").eq(1).hide();
		}
		if(!input3)
		{
			$(".notices").eq(2).html('('+'属性值不得为空!'+')').show();
			return false;
		}else{
			$(".notices").eq(2).hide();
		}
		return true;
	})
})