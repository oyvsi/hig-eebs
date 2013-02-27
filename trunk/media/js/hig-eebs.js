$(document).ready(function() {
	/*$("#userInfo").submit(function() {
		url = ($(this).attr("action"));
		$.ajax({
			type: 'POST',
		  	url: url,
			data: $(this).serialize(),
			success: function(html) {
				console.log(html);			
			},
			error: function(html) {
				console.log("error");
				console.log(html);
			} 
		});

		return false;
	});*/
	$(".fancybox").fancybox();
});
