$(function()
{
	var oSelect=$('#options');
	oSelect.change(function()
	{
		$.post("/service/change",{options:$(this).val()},function($data)
		{
			$data=JSON.parse($data);
			$("#contents").val($data.contents);
		})
	})
})