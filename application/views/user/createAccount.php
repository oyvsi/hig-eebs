<div id="createAccount">
<?php
	extract($this->vars);
	if(isset($profilePicture) && isset($profilePictureThumb)) {
		echo '<div id="profilePicture">';
		echo HTML::fancyBoxImage($profilePicture, $profilePictureThumb);
		echo '</div>';
	}
	echo $this->vars['createAccount'];
?>
</div>
