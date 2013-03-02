<div id="createAccount">
<?php
	extract($this->vars);
	if($userInfo['profilePicture'] != null) {
		echo '<div id="profilePicture">';
		echo HTML::fancyBoxImage($userInfo['profilePicture'], $userInfo['profilePictureThumb']);
		echo '</div>';
	}
	echo $this->vars['createAccount'];
?>
</div>
