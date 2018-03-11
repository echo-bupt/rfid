$(function()
{
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
})