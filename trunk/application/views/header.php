<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title><?php echo $this->vars['title'] ?></title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<?php
			echo HTML::jsLink('tiny_mce/tiny_mce');
			echo HTML::jsLink('hig-eebs');
			echo HTML::jsLink('fancybox/jquery.fancybox.pack.js?v=2.1.4');
			echo HTML::jsLink('fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5');
			echo HTML::cssLink('jquery.fancybox.css?v=2.1.4');
			echo HTML::cssLink('jquery.fancybox-buttons.css?v=1.0.5');
			echo HTML::cssLink('blog'); ?>
	</head>
	<body>
	<div id="navigation">
<?php	
		if(Auth::checkAdmin()) {
			echo '<ul>';
			echo '<li>' . HTML::appLink('comments/getFlagged', 'Flagged comments') . '</li>';
			echo '<li>' . HTML::appLink('blogpost/getFlagged', 'Flagged posts') . '</li>';
			echo '</ul>';
		}
			
			echo '<ul><li>' . HTML::appLink('mostRead', 'Most read') . '</li>';
			echo '<li>' . HTML::appLink('mostCommented', 'Most commented') . '</li>';
			if(Auth::checkLogin()) { 
				echo '<li>' . HTML::appLink('blogpost/create', 'New post') . '</li>';
				echo '<li>' . HTML::appLink('user/profile', 'Profile') . '</li>';
				echo '<li>' . HTML::appLink('user/logOut', 'Log out') . '</li>';
			} else {
				echo '<li>' . HTML::appLink('user/login', 'Log in') . '</li>';
			}
?>
		</ul>
		</div>
	<div id="contents">
<?php
			if(isset($this->vars['message'])) {
				echo '<div id="message"><p>' . $this->vars['message'] . '</p></div>';
			}
