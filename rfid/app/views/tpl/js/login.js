$(function(){
	//登录框自动显示(弹性效果)
	// $('form').animate({top : '190px'},500);
	// $('form').animate({top : '150px'},200);
	
	//后台登陆由下到上
	allP = $('.four_bj').find('p');
	allP.eq(0).animate({left : 0,top : 0},500);
	allP.eq(1).animate({left : 235,top : 0},600);
	allP.eq(2).animate({left : 0,top : 122},700);
	allP.eq(3).animate({left : 235,top : 122},800);
	//验证码自动显示
	$('#verify_img').fadeIn(900);
	//网页加载自动获得焦点
	$('#userName').focus();
	var url = $('#verify_img').attr('src');
        $('#verify_img').click(function()
    {
    	//src只要发生改变、、便会发送一次 HTTP请求 去请求资源、、
    	//所以点击图片时 改变 src 便会去请求新的图片资源、、
        $(this).attr("src",url+"/"+Math.random());
    })

    History.go(0);
	
})




