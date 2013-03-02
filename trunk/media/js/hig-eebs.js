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
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=288792191246167";
	   fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

});
