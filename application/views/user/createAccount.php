<?php
	extract($this->vars);

	// sets default values.
	if(!isset($userInfo)) {
		$userInfo['theme'] = 'default';
		$userInfo['backgroundPicture'] = NULL;
	}
	// changes background
	echo '<script> document.body.background = "' .$userInfo['backgroundPicture']. '"; </script>';
	// changes theme
	echo '<div class="size ' .$userInfo['theme']. ' centered">';

	//shows set profilepicture
	if(isset($userInfo['profilePicture']) && $userInfo['profilePicture'] != null) {
		echo '<div class="profilePicture">';
		echo HTML::fancyBoxImage($userInfo['profilePicture'], $userInfo['profilePictureThumb']);
		echo '</div>';
	}

	
	echo '<div id="form">' .$createAccount. '</div>';
	echo '<div class="rightAlign">';
	if(isset($userInfo['userName'])) {
	echo HTML::appLink('blog/view/' . $userInfo['userName'], 'Go to your blog...');
	}
	echo '</div>';
	echo '</div>';
?>