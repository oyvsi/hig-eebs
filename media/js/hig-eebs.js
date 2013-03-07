// Functions to load with pages
$(document).ready(function() {

	/* 
	 * Ajax post of blogPost. Could be made generic with class of form insted of specific ID.
	 * Gets the url to post to from the action field of the form. 
	 * Expects a json formatted object to be printed on resulting page.
	 * Looks for field "status", which should be either "ok" or "error".
	 * In case of ok a field called url should contain the url the user should be redirected to
	 * In case of error the error field should contain a string with error message.
	 */
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
					window.scrollTo(0, 0); // Scroll up so the user can see her errors
				}
				else if(result.status == "ok") {
					window.location.replace(result.url); // All good. Send the user off
				}	
			},
			error: function(html) {
				alert("Sorry, an error occured");
			} 
		});

		return false; // Don't submit the form "normally"
	});

	// Initialize fancybox (for displaying pictures)
	$(".fancybox").fancybox();

	// Facebook sdk function
	(function(d, s, id) {
  		var js, fjs = d.getElementsByTagName(s)[0];
  		if (d.getElementById(id)) return;
    	js = d.createElement(s); js.id = id;
    	js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=288792191246167";
	   fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

});
