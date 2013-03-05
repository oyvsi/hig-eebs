<?php
	extract($this->vars);

// The following html code is for the wrapper for userdefined background:
//echo ' <div style="background-image:url(' . $variable . ')>';

	echo '<div class="createAccount ' .$userInfo['theme']. '">';

	//shows set profilepicture
	if(isset($userInfo['profilePicture']) && $userInfo['profilePicture'] != null) {
		echo '<div id="profilePicture">';
		echo HTML::fancyBoxImage($userInfo['profilePicture'], $userInfo['profilePictureThumb']);
		echo '</div>';
	}

	echo '<div id="form">' .$createAccount. '	</div></div>';

?>