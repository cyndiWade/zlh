$(function(){
	$(".detail").click(function(){
		$(".backdiv").show()
		$(".show").fadeIn()	
	})	
	
	$(".close").click(function(){
		$(this).parents(".show").hide()
		$(this).parents().siblings(".backdiv").fadeOut()
			
	})
})