$(document).ready(function() {
	$("#blogPost").submit(function() {
		tinyMCE.triggerSave();
		url = ($(this).attr("action"));
		$.ajax({
			type: 'POST',
		  	url: url,
			data: $(this).serialize(),
			success: function(html) {
				result = $.parseJSON(html);
				if(result.status == "error") {
					errorDiv = $('.message.red');
					if(errorDiv.length == 0) {
						$('#navigation').after('<div class="message red"></div>');
					}
					$('.message.red').html(result.error);
				}
				else if(result.status == "ok") {
					window.location.replace(result.url);
				}	
			},
			error: function(html) {
				alert("Sorry, an error occured");
			} 
		});

		return false;
	});

	$(".fancybox").fancybox();

	(function(d, s, id) {
  		var js, fjs = d.getElementsByTagName(s)[0];
  		if (d.getElementById(id)) return;
    	js = d.createElement(s); js.id = id;
    	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=288792191246167";
	   fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

});
