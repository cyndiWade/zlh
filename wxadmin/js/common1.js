$(function(){
	$(".detail").click(function(){
		$(".backdiv").show()
		$(".show").fadeIn()	
	})	
	
	$(".close").click(function(){
		$(this).parents(".show").hide()
		$(this).parents().siblings(".backdiv").fadeOut()
			
	})
	
	
	//日历选择
	$('#checkinday').Zebra_DatePicker({
	  direction: true,
	  pair: $('#checkoutday')
	});
	$('#checkoutday').Zebra_DatePicker({
	  direction: 1
	});


})









